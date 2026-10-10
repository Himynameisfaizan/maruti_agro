<?php
include 'config/connect.php';

// ==========================================
// DYNAMIC SEO META TAGS HANDLING
// ==========================================
$currentPage = basename($_SERVER['PHP_SELF']);
$seo_query = mysqli_query($conn, "SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = '$currentPage'");

$pageTitle = "Latest News & Blogs | Maruti Agro Industries";
$meta_keywords = "Maruti Agro blogs, agricultural news, export updates, spice industry news";
$meta_description = "Stay updated with the latest news, export trends, and information about Indian agricultural products by Maruti Agro Industries.";
$page_schema = "";

if ($seo_query && mysqli_num_rows($seo_query) > 0) {
    $seo_data = mysqli_fetch_assoc($seo_query);
    $pageTitle = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : $pageTitle;
    $meta_keywords = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_keywords;
    $meta_description = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_description;
}

$schema_query = mysqli_query($conn, "SELECT schema_markup FROM page_schemas WHERE page_url = '$currentPage'");
if ($schema_query && mysqli_num_rows($schema_query) > 0) {
    $schema_data = mysqli_fetch_assoc($schema_query);
    $page_schema = !empty($schema_data['schema_markup']) ? $schema_data['schema_markup'] : "";
}

// ==========================================
// PAGINATION & SEARCH LOGIC
// ==========================================
$limit = 9; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_query = "";
$where_clause = "WHERE status = 1";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clause .= " AND title LIKE '%$search_term%'";
    $search_query = "&search=" . urlencode($_GET['search']);
}

// Count total blogs
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM blogs $where_clause");
$total_row = mysqli_fetch_assoc($total_query);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch Blogs
$blogs_res = mysqli_query($conn, "SELECT * FROM blogs $where_clause ORDER BY blog_id DESC LIMIT $offset, $limit");

include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<section class="section-padding bg-light-green">
    <div class="container">
        
        <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
            <div class="mb-4 pb-2 border-bottom border-gold d-flex justify-content-between align-items-center">
                <h2 class="h5 fw-bold text-dark m-0">Search Results for "<?= htmlspecialchars($_GET['search']) ?>"</h2>
                <a href="blog.php" class="btn-dark-outline btn-sm">Clear Search</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php
            if ($blogs_res && mysqli_num_rows($blogs_res) > 0):
                while ($blog = mysqli_fetch_assoc($blogs_res)):
                    $blogImg = !empty($blog['image']) ? 'admin/assets/img/uploads/blogs/' . $blog['image'] : 'assets/images/black.png';
                    $blogSlug = !empty($blog['slug']) ? $blog['slug'] : $blog['blog_id'];
                    $blogDate = date('d M, Y', strtotime($blog['created_at']));
                    $shortDesc = strip_tags($blog['description']);
                    $shortDesc = strlen($shortDesc) > 120 ? substr($shortDesc, 0, 120) . '...' : $shortDesc;
            ?>
                    <div class="col-lg-4 col-md-6 reveal-up">
                        <div class="premium-blog-card bg-white shadow-sm h-100 d-flex flex-column">
                            <div class="blog-img-wrapper position-relative">
                                <a href="blog-details.php?slug=<?= urlencode($blogSlug) ?>" class="d-block">
                                    <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" onerror="this.src='assets/images/black.png'" class="img-fluid w-100 object-fit-cover">
                                </a>
                                <div class="blog-date-badge bg-gold text-dark fw-bold text-center">
                                    <span class="d-block fs-5 lh-1"><?= date('d', strtotime($blog['created_at'])) ?></span>
                                    <span class="d-block small text-uppercase"><?= date('M', strtotime($blog['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="blog-card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="blog-meta mb-2 small fw-semibold text-muted text-uppercase letter-spacing-1">
                                    <i class="bi bi-person-circle text-gold me-1"></i> <?= htmlspecialchars($blog['author']) ?>
                                </div>
                                <h3 class="fw-bold text-dark h5 mb-3" style="line-height: 1.4;">
                                    <a href="blog-details.php?slug=<?= urlencode($blogSlug) ?>" class="text-decoration-none text-dark blog-title-link">
                                        <?= htmlspecialchars($blog['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-muted small mb-4 flex-grow-1"><?= htmlspecialchars($shortDesc) ?></p>
                                <div class="mt-auto border-top pt-3">
                                    <a href="blog-details.php?slug=<?= urlencode($blogSlug) ?>" class="btn-read-more text-green fw-bold text-uppercase text-decoration-none d-inline-flex align-items-center">
                                        Read Article <i class="bi bi-arrow-right ms-2 transition-icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center py-5 bg-white rounded shadow-sm border-top-gold">
                    <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-dark fw-bold">No Articles Found</h4>
                    <p class="text-muted">We will publish new updates soon.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($total_pages > 1): ?>
        <div class="mt-5 d-flex justify-content-center">
            <ul class="pagination premium-pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= ($page - 1) ?><?= $search_query ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for($p = 1; $p <= $total_pages; $p++): ?>
                    <li class="page-item <?= ($p == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $p ?><?= $search_query ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= ($page + 1) ?><?= $search_query ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Scroll Animations Fix -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".reveal-up, .reveal-fade, .reveal-left, .reveal-right");
        const revealOnScroll = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        reveals.forEach(reveal => revealOnScroll.observe(reveal));
    });
</script>

<?php include('includes/footer.php'); ?>

<?php include('includes/footer.php'); ?>