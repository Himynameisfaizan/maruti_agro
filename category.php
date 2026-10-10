<?php
include 'config/connect.php';

// ==========================================
// GET CATEGORY SLUG & DETAILS
// ==========================================
$cat_slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

if (empty($cat_slug)) {
    header("Location: products.php");
    exit;
}

$cat_res = mysqli_query($conn, "SELECT * FROM categories WHERE slug_url = '$cat_slug' AND status = 1 LIMIT 1");
if (!$cat_res || mysqli_num_rows($cat_res) == 0) {
    header("Location: products.php");
    exit;
}

$category = mysqli_fetch_assoc($cat_res);
$c_id = $category['cate_id']; // For fetching products

// ==========================================
// DYNAMIC CONTACT INFO FOR ICONS
// ==========================================
$c_phone = "+910000000000";
$c_wp = "+910000000000";
$contact_query = mysqli_query($conn, "SELECT phone, wp_number FROM contacts ORDER BY id DESC LIMIT 1");
if ($contact_query && mysqli_num_rows($contact_query) > 0) {
    $c_info = mysqli_fetch_assoc($contact_query);
    $c_phone = !empty($c_info['phone']) ? preg_replace('/[^0-9+]/', '', $c_info['phone']) : $c_phone;
    $c_wp = !empty($c_info['wp_number']) ? preg_replace('/[^0-9]/', '', $c_info['wp_number']) : $c_wp;
}

// ==========================================
// SEO META TAGS HANDLING (SEO FIX)
// ==========================================
$pageTitle = !empty($category['meta_title']) ? $category['meta_title'] : $category['categories'] . " | Premium Products";
$meta_keywords = !empty($category['meta_key']) ? $category['meta_key'] : $category['categories'] . ", premium exports, wholesale";
$meta_description = !empty($category['meta_desc']) ? $category['meta_desc'] : "Explore our premium range of " . $category['categories'] . " sourced directly from the finest farms.";

// Schema logic (Fallback to category name if page schema not found)
$currentPage = basename($_SERVER['PHP_SELF']);
$page_schema = "";
$schema_query = mysqli_query($conn, "SELECT schema_markup FROM page_schemas WHERE page_url = '$currentPage'");
if ($schema_query && mysqli_num_rows($schema_query) > 0) {
    $schema_data = mysqli_fetch_assoc($schema_query);
    $page_schema = !empty($schema_data['schema_markup']) ? $schema_data['schema_markup'] : "";
}

// Fetch all categories for sidebar
$all_categories_res = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY categories ASC");

// ==========================================
// PAGINATION & SEARCH LOGIC
// ==========================================
$limit = 9; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_query = "";
$where_clause = "WHERE status = 1 AND pro_cate = '$c_id'";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clause .= " AND pro_name LIKE '%$search_term%'";
    $search_query = "&search=" . urlencode($_GET['search']);
}

// Count total products
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products $where_clause");
$total_row = mysqli_fetch_assoc($total_query);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch Products
$products_res = mysqli_query($conn, "SELECT * FROM products $where_clause ORDER BY id DESC LIMIT $offset, $limit");

// Title for breadcrumb
$pageTitle = htmlspecialchars($category['categories']); 
include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<section class="section-padding bg-light-green">
    <div class="container">
        <!-- Notice the Row Swap: Left is Products (col-lg-9), Right is Sidebar (col-lg-3) -->
        <div class="row g-5">
            
            <!-- LEFT COLUMN: PRODUCTS AREA -->
            <div class="col-lg-9 reveal-left">
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-gold">
                    <h2 class="h5 fw-bold text-dark m-0">
                        <?php 
                        if(isset($_GET['search']) && !empty($_GET['search'])){
                            echo 'Search Results in ' . htmlspecialchars($category['categories']);
                        } else {
                            echo htmlspecialchars($category['categories']) . ' Collection';
                        }
                        ?>
                    </h2>
                    <span class="badge bg-gold text-dark fs-6"><?= $total_products; ?> Items</span>
                </div>

                <div class="row g-4">
                    <?php
                    if ($products_res && mysqli_num_rows($products_res) > 0):
                        while ($prod = mysqli_fetch_assoc($products_res)):
                            $proImg = !empty($prod['pro_img']) ? 'admin/assets/img/uploads/' . $prod['pro_img'] : 'assets/images/black.png';
                            $productSlug = !empty($prod['slug_url']) ? $prod['slug_url'] : $prod['id'];
                            
                            $descText = !empty($prod['short_desc']) ? strip_tags($prod['short_desc']) : strip_tags($prod['meta_desc']);
                            if(empty(trim($descText))){
                                 $descText = "Premium export quality product, processed and packed with utmost hygiene.";
                            }
                    ?>
                            <!-- NAYA CATEGORY PRODUCT CARD DESIGN -->
                            <div class="col-md-6 col-lg-4 d-flex">
                                <div class="category-product-card w-100 d-flex flex-column shadow-sm bg-white">
                                    
                                    <!-- Image Block -->
                                    <div class="cat-img-wrapper position-relative">
                                        <div class="premium-tag">100% Pure</div>
                                        <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="d-block">
                                            <img src="<?= htmlspecialchars($proImg) ?>" alt="<?= htmlspecialchars($prod['pro_name']) ?>" onerror="this.src='assets/images/black.png'" class="img-fluid w-100 object-fit-cover">
                                        </a>
                                        <!-- Hover Action Icons -->
                                        <div class="cat-hover-actions d-flex gap-2">
                                            <a href="tel:<?= $c_phone ?>" class="hover-icon bg-white text-green shadow" title="Call Us"><i class="bi bi-telephone-fill"></i></a>
                                            <a href="https://wa.me/<?= $c_wp ?>?text=Hello, I am interested in <?= urlencode($prod['pro_name']) ?>" target="_blank" class="hover-icon bg-white text-green shadow" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                        </div>
                                    </div>
                                    
                                    <!-- Content Block -->
                                    <div class="cat-card-body d-flex flex-column flex-grow-1 p-4">
                                        <h3 class="fw-bold text-dark mb-2 h5" style="line-height: 1.3;">
                                            <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="text-decoration-none text-dark cat-title-link">
                                                <?= htmlspecialchars($prod['pro_name']) ?>
                                            </a>
                                        </h3>
                                        
                                        <!-- Exact 3-line Description -->
                                        <div class="cat-desc text-muted mb-4 flex-grow-1">
                                            <?= htmlspecialchars($descText) ?>
                                        </div>

                                        <!-- Bottom Buttons -->
                                        <div class="d-flex gap-2 mt-auto">
                                            <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="btn-cat-outline flex-fill text-center text-decoration-none">
                                                Details
                                            </a>
                                            <a href="contact.php?product=<?= urlencode($prod['pro_name']) ?>" class="btn-cat-solid flex-fill text-center text-decoration-none">
                                                Inquire
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
                            <i class="bi bi-basket-fill text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-dark fw-bold">No Products Found</h4>
                            <p class="text-muted">Currently, there are no items in this category.</p>
                            <a href="products.php" class="btn-gold-solid mt-2">View All Products</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($total_pages > 1): ?>
                <div class="mt-5 d-flex justify-content-center">
                    <ul class="pagination premium-pagination">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?slug=<?= $cat_slug ?>&page=<?= ($page - 1) ?><?= $search_query ?>"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <?php for($p = 1; $p <= $total_pages; $p++): ?>
                            <li class="page-item <?= ($p == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?slug=<?= $cat_slug ?>&page=<?= $p ?><?= $search_query ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?slug=<?= $cat_slug ?>&page=<?= ($page + 1) ?><?= $search_query ?>"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div class="col-lg-3 reveal-right">
                
                <!-- 1. Search Bar -->
                <div class="sidebar-widget bg-white p-4 rounded shadow-sm mb-4 border-top-gold">
                    <h3 class="widget-title text-dark fw-bold h5 mb-3">Search</h3>
                    <form action="category.php" method="GET" class="d-flex position-relative">
                        <input type="hidden" name="slug" value="<?= htmlspecialchars($cat_slug); ?>">
                        <input type="text" name="search" class="form-control premium-search-input pe-5" placeholder="Search here..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="premium-search-btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-gold pe-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <!-- 2. Categories (Dark Theme Widget for variation) -->
                <div class="sidebar-widget bg-green text-white p-4 rounded shadow-sm mb-4">
                    <h3 class="widget-title text-gold fw-bold h5 mb-3 border-bottom border-secondary pb-2">Categories</h3>
                    <ul class="list-unstyled sidebar-cat-list-dark m-0">
                        <?php
                        if ($all_categories_res && mysqli_num_rows($all_categories_res) > 0) {
                            while ($cat = mysqli_fetch_assoc($all_categories_res)) {
                                $cSlug = !empty($cat['slug_url']) ? $cat['slug_url'] : $cat['cate_id'];
                                $isActive = ($cSlug == $cat_slug) ? 'active-cat' : '';
                                echo '<li><a href="category.php?slug=' . urlencode($cSlug) . '" class="'.$isActive.'"><i class="bi bi-arrow-right-short text-gold me-1"></i> ' . htmlspecialchars($cat['categories']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- 3. Request Quote CTA -->
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
        }, { threshold: 0.1 });

        reveals.forEach(reveal => revealOnScroll.observe(reveal));
    });
</script>

<?php include('includes/footer.php'); ?>