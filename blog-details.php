<?php
include 'config/connect.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

if (empty($slug)) {
    header("Location: blog.php");
    exit;
}

$blog_res = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug' AND status = 1 LIMIT 1");
if (!$blog_res || mysqli_num_rows($blog_res) == 0) {
    header("Location: blog.php");
    exit;
}
$blog = mysqli_fetch_assoc($blog_res);

// ==========================================
// SEO META TAGS HANDLING
// ==========================================
$pageTitle = !empty($blog['meta_title']) ? $blog['meta_title'] : $blog['title'];
$meta_keywords = !empty($blog['meta_key']) ? $blog['meta_key'] : "Maruti Agro Blog, " . $blog['title'];
$meta_description = !empty($blog['meta_desc']) ? $blog['meta_desc'] : strip_tags(substr($blog['description'], 0, 150));
$page_schema = !empty($blog['schema_markup']) ? $blog['schema_markup'] : "";

// Fetch Product Categories for Sidebar
$categories_res = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY categories ASC");

// Fetch Recent Posts for Sidebar
$recent_blogs_res = mysqli_query($conn, "SELECT * FROM blogs WHERE status = 1 AND slug != '$slug' ORDER BY blog_id DESC LIMIT 4");

$pageTitle = htmlspecialchars($blog['title']); // For Breadcrumb H1
include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<section class="section-padding bg-light-gray">
    <div class="container">
        <div class="row g-5">
            
            <!-- LEFT COLUMN: BLOG CONTENT -->
            <div class="col-lg-8 reveal-left">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border-top-gold h-100">
                    
                    <!-- Blog Meta -->
                    <div class="d-flex flex-wrap align-items-center mb-4 gap-3 text-muted fw-semibold small text-uppercase letter-spacing-1 border-bottom pb-3">
                        <span><i class="bi bi-calendar-event text-gold me-1"></i> <?= date('d M, Y', strtotime($blog['created_at'])) ?></span>
                        <span><i class="bi bi-person-circle text-gold me-1"></i> <?= htmlspecialchars($blog['author']) ?></span>
                        <span><i class="bi bi-folder2-open text-gold me-1"></i> Industry News</span>
                    </div>

                    <!-- Featured Image -->
                    <?php $bImg = !empty($blog['image']) ? 'admin/assets/img/uploads/blogs/' . $blog['image'] : 'assets/images/black.png'; ?>
                    <div class="blog-detail-img-wrapper mb-4 rounded overflow-hidden shadow-sm">
                        <img src="<?= htmlspecialchars($bImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="img-fluid w-100 object-fit-cover" onerror="this.src='assets/images/black.png'" style="max-height: 450px;">
                    </div>

                    <!-- SEO FIX: H2 used inside content area since H1 is in breadcrumb -->
                    <h2 class="fw-bold text-dark mb-4 h3"><?= htmlspecialchars($blog['title']) ?></h2>

                    <!-- CKEditor Description -->
                    <div class="ckeditor-content text-muted">
                        <?= !empty($blog['description']) ? $blog['description'] : '<p>Content is being updated.</p>' ?>
                    </div>

                    <!-- Share Section -->
                    <div class="mt-5 pt-4 border-top d-flex align-items-center gap-3">
                        <span class="fw-bold text-dark text-uppercase small">Share Article:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="social-share-icon facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>&text=<?= urlencode($blog['title']) ?>" target="_blank" class="social-share-icon twitter"><i class="bi bi-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($blog['title'] . " " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="social-share-icon whatsapp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div class="col-lg-4 reveal-right">
                
                <!-- 1. Search Bar -->
                <div class="sidebar-widget bg-white p-4 rounded shadow-sm mb-4 border-top-gold">
                    <h3 class="widget-title text-dark fw-bold h5 mb-3">Search Posts</h3>
                    <form action="blog.php" method="GET" class="d-flex position-relative">
                        <input type="text" name="search" class="form-control premium-search-input pe-5" placeholder="Search blog...">
                        <button type="submit" class="premium-search-btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-gold pe-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <!-- 2. Product Categories -->
                <div class="sidebar-widget bg-green text-white p-4 rounded shadow-sm mb-4">
                    <h3 class="widget-title text-gold fw-bold h5 mb-3 border-bottom border-secondary pb-2">Product Categories</h3>
                    <ul class="list-unstyled sidebar-cat-list-dark m-0">
                        <?php
                        if ($categories_res && mysqli_num_rows($categories_res) > 0) {
                            while ($cat = mysqli_fetch_assoc($categories_res)) {
                                $cSlug = !empty($cat['slug_url']) ? $cat['slug_url'] : $cat['cate_id'];
                                echo '<li><a href="category.php?slug=' . urlencode($cSlug) . '"><i class="bi bi-arrow-right-short text-gold me-1"></i> ' . htmlspecialchars($cat['categories']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- 3. Recent Posts -->
                <div class="sidebar-widget bg-white p-4 rounded shadow-sm mb-4 border-top-gold">
                    <h3 class="widget-title text-dark fw-bold h5 mb-4">Recent Posts</h3>
                    <div class="recent-posts-list">
                        <?php 
                        if ($recent_blogs_res && mysqli_num_rows($recent_blogs_res) > 0) {
                            while ($r_blog = mysqli_fetch_assoc($recent_blogs_res)) {
                                $rImg = !empty($r_blog['image']) ? 'admin/assets/img/uploads/blogs/' . $r_blog['image'] : 'assets/images/black.png';
                                $rSlug = !empty($r_blog['slug']) ? $r_blog['slug'] : $r_blog['blog_id'];
                        ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                                <div class="recent-post-img rounded overflow-hidden flex-shrink-0" style="width: 70px; height: 70px;">
                                    <a href="blog-details.php?slug=<?= urlencode($rSlug) ?>">
                                        <img src="<?= htmlspecialchars($rImg) ?>" alt="<?= htmlspecialchars($r_blog['title']) ?>" class="img-fluid w-100 h-100 object-fit-cover" onerror="this.src='assets/images/black.png'">
                                    </a>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold m-0" style="font-size: 0.95rem; line-height: 1.3;">
                                        <a href="blog-details.php?slug=<?= urlencode($rSlug) ?>" class="text-dark text-decoration-none cat-title-link">
                                            <?= htmlspecialchars($r_blog['title']) ?>
                                        </a>
                                    </h6>
                                    <span class="small text-muted"><i class="bi bi-calendar-event text-gold me-1"></i> <?= date('d M, Y', strtotime($r_blog['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php 
                            }
                        } else {
                            echo '<p class="text-muted small">No recent posts available.</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- 4. Request Quote CTA -->
                <div class="sidebar-widget bg-white text-center p-4 rounded shadow-sm position-relative overflow-hidden border-top-gold">
                    <div class="position-relative z-1">
                        <i class="bi bi-envelope-paper-fill text-gold fs-1 mb-2 d-block"></i>
                        <h4 class="text-dark fw-bold h5">Custom Order?</h4>
                        <p class="text-muted small mb-4">Require a specific grade or bulk container? Drop us an inquiry.</p>
                        <a href="contact.php" class="btn-green-solid w-100 py-2 text-uppercase fw-bold text-decoration-none d-block rounded">Contact Us</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- INQUIRY FORM (Placed exactly as requested after the columns end) -->
<?php include('includes/inquiry-form.php'); ?>

<!-- Scroll Animations -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".reveal-up, .reveal-left, .reveal-right");
        const revealOnScroll = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(reveal => revealOnScroll.observe(reveal));
    });
</script>

<?php include('includes/footer.php'); ?>