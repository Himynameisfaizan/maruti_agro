<?php
include 'config/connect.php';

// ==========================================
// DYNAMIC CONTACT INFO FOR WHATSAPP/PHONE ICONS
// ==========================================
$c_phone = "+910000000000";
$c_wp = "+910000000000";

if (isset($conn)) {
    $contact_query = mysqli_query($conn, "SELECT phone, wp_number FROM contacts ORDER BY id DESC LIMIT 1");
    if ($contact_query && mysqli_num_rows($contact_query) > 0) {
        $c_info = mysqli_fetch_assoc($contact_query);
        $c_phone = !empty($c_info['phone']) ? preg_replace('/[^0-9+]/', '', $c_info['phone']) : $c_phone;
        $c_wp = !empty($c_info['wp_number']) ? preg_replace('/[^0-9]/', '', $c_info['wp_number']) : $c_wp;
    }
}

// ==========================================
// DYNAMIC SEO META TAGS & SCHEMA FIX
// ==========================================
$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch Meta Tags
$seo_query = mysqli_query($conn, "SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = '$currentPage'");
$pageTitle = "Our Products | Premium Export Quality Spices & Dry Fruits";
$meta_keywords = "Maruti Agro products, export quality spices, dry fruits, seeds exporter";
$meta_description = "Explore our wide range of premium export-quality products including whole spices, dry fruits, and seeds sourced directly from Indian farms.";

if ($seo_query && mysqli_num_rows($seo_query) > 0) {
    $seo_data = mysqli_fetch_assoc($seo_query);
    $pageTitle = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : $pageTitle;
    $meta_keywords = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_keywords;
    $meta_description = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_description;
}

// Fetch Schema Markup specifically from page_schemas table
$page_schema = "";
$schema_query = mysqli_query($conn, "SELECT schema_markup FROM page_schemas WHERE page_url = '$currentPage'");
if ($schema_query && mysqli_num_rows($schema_query) > 0) {
    $schema_data = mysqli_fetch_assoc($schema_query);
    $page_schema = !empty($schema_data['schema_markup']) ? $schema_data['schema_markup'] : "";
}

// Fetch all categories for sidebar
$categories_res = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY categories ASC");

// ==========================================
// PAGINATION & SEARCH LOGIC
// ==========================================
$limit = 9; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_query = "";
$where_clause = "WHERE status = 1";

// Search logic
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clause .= " AND pro_name LIKE '%$search_term%'";
    $search_query = "&search=" . urlencode($_GET['search']);
}

// Count total products for pagination
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products $where_clause");
$total_row = mysqli_fetch_assoc($total_query);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch Products for current page
$products_res = mysqli_query($conn, "SELECT * FROM products $where_clause ORDER BY id DESC LIMIT $offset, $limit");

include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<section class="section-padding bg-light-gray">
    <div class="container">
        <div class="row g-5">
            
            <!-- LEFT SIDEBAR -->
            <div class="col-lg-3 reveal-left">
                
                <!-- 1. Search Bar -->
                <div class="sidebar-widget bg-white p-4 rounded shadow-sm mb-4 border-top-gold">
                    <h3 class="widget-title text-dark fw-bold h5 mb-3">Search Products</h3>
                    <form action="products.php" method="GET" class="d-flex position-relative">
                        <input type="text" name="search" class="form-control premium-search-input pe-5" placeholder="Search..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="premium-search-btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-gold pe-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <!-- 2. Categories -->
                <div class="sidebar-widget bg-white p-4 rounded shadow-sm mb-4 border-top-gold">
                    <h3 class="widget-title text-dark fw-bold h5 mb-3">All Categories</h3>
                    <ul class="list-unstyled sidebar-cat-list m-0">
                        <?php
                        if ($categories_res && mysqli_num_rows($categories_res) > 0) {
                            while ($cat = mysqli_fetch_assoc($categories_res)) {
                                $catSlug = !empty($cat['slug_url']) ? $cat['slug_url'] : $cat['cate_id'];
                                echo '<li><a href="category.php?slug=' . urlencode($catSlug) . '"><i class="bi bi-chevron-right text-gold me-2 small"></i> ' . htmlspecialchars($cat['categories']) . '</a></li>';
                            }
                        } else {
                            echo '<li class="text-muted">No categories found.</li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- 3. Request Quote CTA -->
                <div class="sidebar-widget bg-green text-center p-4 rounded shadow-sm position-relative overflow-hidden">
                    <div class="position-relative z-1">
                        <i class="bi bi-headset text-gold fs-1 mb-2 d-block"></i>
                        <h4 class="text-white fw-bold h5">Need Bulk Quantity?</h4>
                        <p class="text-white-50 small mb-4">Get a customized quotation based on your export requirements.</p>
                        <a href="contact.php" class="btn-gold-solid w-100 py-2">Request Quote</a>
                    </div>
                    <div class="position-absolute top-0 end-0 translate-middle-y rounded-circle bg-white opacity-10" style="width: 150px; height: 150px; right: -50px !important;"></div>
                </div>

            </div>

            <!-- RIGHT CONTENT AREA -->
            <div class="col-lg-9 reveal-right">
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <h2 class="h5 fw-bold text-dark m-0">
                        <?php 
                        if(isset($_GET['search']) && !empty($_GET['search'])){
                            echo 'Search Results for "' . htmlspecialchars($_GET['search']) . '"';
                        } else {
                            echo 'Our Products Collection';
                        }
                        ?>
                    </h2>
                    <span class="text-muted small">Showing <?= mysqli_num_rows($products_res); ?> of <?= $total_products; ?> products</span>
                </div>

                <div class="row g-4">
                    <?php
                    if ($products_res && mysqli_num_rows($products_res) > 0):
                        while ($prod = mysqli_fetch_assoc($products_res)):
                            $proImg = !empty($prod['pro_img']) ? 'admin/assets/img/uploads/' . $prod['pro_img'] : 'assets/images/black.png';
                            $productSlug = !empty($prod['slug_url']) ? $prod['slug_url'] : $prod['id'];
                            
                            $descText = !empty($prod['short_desc']) ? strip_tags($prod['short_desc']) : strip_tags($prod['meta_desc']);
                            if(empty(trim($descText))){
                                 $descText = "Premium quality export grade agricultural product sourced directly from the finest Indian farms, ensuring 100% natural aroma.";
                            }
                    ?>
                            <!-- NAYA MODERN PRODUCT CARD -->
                            <div class="col-md-6 col-lg-4 d-flex">
                                <div class="modern-product-card bg-white w-100 d-flex flex-column shadow-sm">
                                    
                                    <div class="modern-img-wrapper d-block position-relative">
                                        <span class="modern-badge bg-gold text-dark fw-bold">Export Quality</span>
                                        <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>">
                                            <img src="<?= htmlspecialchars($proImg) ?>" alt="<?= htmlspecialchars($prod['pro_name']) ?>" onerror="this.src='assets/images/black.png'" class="img-fluid w-100 object-fit-cover" style="height: 220px;">
                                        </a>
                                    </div>
                                    
                                    <div class="modern-card-body d-flex flex-column flex-grow-1 p-4">
                                        <h3 class="modern-title text-dark fw-bold mb-2 h5">
                                            <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($prod['pro_name']) ?>
                                            </a>
                                        </h3>
                                        
                                        <!-- Exact 3-line Description -->
                                        <div class="modern-desc text-muted mb-4 flex-grow-1">
                                            <?= htmlspecialchars($descText) ?>
                                        </div>

                                        <!-- Sleek Action Footer -->
                                        <div class="modern-card-footer mt-auto">
                                            
                                            <!-- Contact Strip -->
                                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                                                <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Quick Connect</span>
                                                <div class="d-flex gap-2">
                                                    <a href="tel:<?= $c_phone ?>" class="modern-icon-btn call-btn" title="Call Us"><i class="bi bi-telephone-fill"></i></a>
                                                    <a href="https://wa.me/<?= $c_wp ?>?text=Hello, I am interested in <?= urlencode($prod['pro_name']) ?>" target="_blank" class="modern-icon-btn wa-btn" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                                </div>
                                            </div>

                                            <!-- Split Buttons -->
                                            <div class="d-flex gap-2">
                                                <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="btn-modern-outline flex-fill text-center text-decoration-none px-1">
                                                    Details
                                                </a>
                                                <a href="contact.php?product=<?= urlencode($prod['pro_name']) ?>" class="btn-modern-solid flex-fill text-center text-decoration-none px-1">
                                                    Quote
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-dark fw-bold">No Products Found</h4>
                            <p class="text-muted">We couldn't find any products matching your criteria.</p>
                            <a href="products.php" class="btn-gold-solid mt-2">Clear Search</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($total_pages > 1): ?>
                <div class="mt-5 d-flex justify-content-center">
                    <ul class="pagination premium-pagination">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= ($page - 1) ?><?= $search_query ?>" aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for($p = 1; $p <= $total_pages; $p++): ?>
                            <li class="page-item <?= ($p == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $p ?><?= $search_query ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= ($page + 1) ?><?= $search_query ?>" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- Include global scroll animations script -->
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
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        reveals.forEach(reveal => revealOnScroll.observe(reveal));
    });
</script>

<?php include('includes/footer.php'); ?>