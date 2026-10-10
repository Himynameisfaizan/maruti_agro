<?php
include 'config/connect.php';

// ==========================================
// REVIEW SUBMIT LOGIC (DYNAMIC REVIEWS)
// ==========================================
$review_msg = "";
if(isset($_POST['submit_review'])) {
    $p_id = (int)$_POST['product_id'];
    $r_name = mysqli_real_escape_string($conn, $_POST['reviewer_name']);
    $r_email = mysqli_real_escape_string($conn, $_POST['reviewer_email']);
    $r_rating = (int)$_POST['rating'];
    $r_text = mysqli_real_escape_string($conn, $_POST['review_text']);
    
    // Status 0 means pending approval by admin
    $insert_rev = mysqli_query($conn, "INSERT INTO product_reviews (product_id, reviewer_name, reviewer_email, rating, review_text, status) VALUES ('$p_id', '$r_name', '$r_email', '$r_rating', '$r_text', 0)");
    
    if($insert_rev) {
        $review_msg = "<div class='alert alert-success small'>Review submitted successfully! It will appear after admin approval.</div>";
    }
}

// ==========================================
// FETCH PRODUCT DETAILS
// ==========================================
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$where = "status = 1";
if($slug) { $where .= " AND slug_url = '$slug'"; }
elseif($id) { $where .= " AND id = $id"; }
else { header("Location: products.php"); exit; }

$prod_res = mysqli_query($conn, "SELECT * FROM products WHERE $where LIMIT 1");
if(!$prod_res || mysqli_num_rows($prod_res) == 0) {
    header("Location: products.php"); 
    exit;
}
$product = mysqli_fetch_assoc($prod_res);
$p_id = $product['id'];
$c_id = $product['pro_cate'];

// ==========================================
// DYNAMIC SEO META & SCHEMA (FROM PRODUCTS TABLE)
// ==========================================
$pageTitle = !empty($product['meta_title']) ? $product['meta_title'] : $product['pro_name'];
$meta_keywords = !empty($product['meta_key']) ? $product['meta_key'] : $product['pro_name'].", export quality, buy bulk";
$meta_description = !empty($product['meta_desc']) ? $product['meta_desc'] : strip_tags($product['short_desc']);
$page_schema = !empty($product['schema_markup']) ? $product['schema_markup'] : "";

// Dynamic Phone & WhatsApp
$c_phone = "+910000000000";
$c_wp = "+910000000000";
$contact_query = mysqli_query($conn, "SELECT phone, wp_number FROM contacts ORDER BY id DESC LIMIT 1");
if ($contact_query && mysqli_num_rows($contact_query) > 0) {
    $c_info = mysqli_fetch_assoc($contact_query);
    $c_phone = !empty($c_info['phone']) ? preg_replace('/[^0-9+]/', '', $c_info['phone']) : $c_phone;
    $c_wp = !empty($c_info['wp_number']) ? preg_replace('/[^0-9]/', '', $c_info['wp_number']) : $c_wp;
}

// Review Statistics
$rev_stat_res = mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM product_reviews WHERE product_id = $p_id AND status = 1");
$rev_stat = mysqli_fetch_assoc($rev_stat_res);
$avg_rating = $rev_stat['total_reviews'] > 0 ? round($rev_stat['avg_rating'], 1) : 5.0;
$total_reviews = $rev_stat['total_reviews'];

include("includes/header.php");
include("includes/breadcrumb.php"); // Single H1 load hota hai yahan se
?>

<!-- 1. TOP SECTION: OVERVIEW (Image & Short Content) -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            
            <!-- Left Side: Product Image -->
            <div class="col-lg-5 reveal-left">
                <div class="product-detail-img-box bg-light-gray rounded shadow-sm position-relative overflow-hidden p-4 d-flex align-items-center justify-content-center" style="height: 500px;">
                    <span class="modern-badge bg-gold text-dark fw-bold position-absolute top-0 start-0 m-4">100% Authentic</span>
                    <?php $proImg = !empty($product['pro_img']) ? 'admin/assets/img/uploads/' . $product['pro_img'] : 'assets/images/black.png'; ?>
                    <img src="<?= htmlspecialchars($proImg) ?>" alt="<?= htmlspecialchars($product['pro_name']) ?>" class="img-fluid object-fit-contain w-100 h-100 detail-zoom-img">
                </div>
            </div>

            <!-- Right Side: Content & Actions -->
            <div class="col-lg-7 reveal-right">
                <!-- SEO FIX: H2 used here since H1 is in breadcrumb -->
                <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem;"><?= htmlspecialchars($product['pro_name']) ?></h2>
                
                <!-- Dynamic Review Stars -->
                <div class="d-flex align-items-center mb-4">
                    <div class="text-gold me-2 fs-5">
                        <?php 
                        for($i=1; $i<=5; $i++){
                            if($i <= round($avg_rating)) echo '<i class="bi bi-star-fill"></i>';
                            else echo '<i class="bi bi-star"></i>';
                        }
                        ?>
                    </div>
                    <span class="text-muted small fw-bold">(<?= $total_reviews ?> Customer Reviews)</span>
                </div>

                <!-- Short Description (CKEditor allowed) -->
                <div class="product-detail-short-desc text-muted mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                    <?= !empty($product['short_desc']) ? $product['short_desc'] : '<p>Premium quality agricultural product, sourced fresh from the best Indian farms and rigorously graded for global export standards.</p>' ?>
                </div>

                <!-- Trust Icons Row -->
                <div class="row g-3 mb-5 border-top border-bottom py-3">
                    <div class="col-4 text-center border-end">
                        <i class="bi bi-shield-check text-gold fs-3 mb-1"></i>
                        <h6 class="fw-bold text-dark m-0 small">Export Grade</h6>
                    </div>
                    <div class="col-4 text-center border-end">
                        <i class="bi bi-tree text-gold fs-3 mb-1"></i>
                        <h6 class="fw-bold text-dark m-0 small">Farm Fresh</h6>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi bi-airplane text-gold fs-3 mb-1"></i>
                        <h6 class="fw-bold text-dark m-0 small">Global Delivery</h6>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex flex-wrap gap-3">
                    <a href="contact.php?product=<?= urlencode($product['pro_name']) ?>" class="btn-gold-solid px-4 py-3 flex-grow-1 text-center d-flex align-items-center justify-content-center">
                        <i class="bi bi-envelope-paper-fill me-2"></i> Request A Quote
                    </a>
                    <a href="tel:<?= $c_phone ?>" class="btn-modern-outline px-4 py-3 text-center d-flex align-items-center justify-content-center" style="height: auto;">
                        <i class="bi bi-telephone-fill me-2 text-gold"></i> Call Inquiry
                    </a>
                    <a href="https://wa.me/<?= $c_wp ?>?text=Hello, I need details about <?= urlencode($product['pro_name']) ?>" target="_blank" class="btn-modern-outline px-4 py-3 text-center d-flex align-items-center justify-content-center" style="height: auto; border-color: #25d366; color: #25d366;">
                        <i class="bi bi-whatsapp fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. BOTTOM SECTION: LONG DESC & REVIEWS -->
<section class="section-padding bg-light-green">
    <div class="container">
        <div class="row g-5">
            
            <!-- Left: Long Description -->
            <div class="col-lg-7 reveal-up">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border-top-gold h-100">
                    <h3 class="fw-bold text-dark h4 mb-4">Product Overview</h3>
                    <div class="ckeditor-content text-muted">
                        <?= !empty($product['description']) ? $product['description'] : '<p>Detailed product description will be updated soon. Please request a quote for complete specifications.</p>' ?>
                    </div>
                </div>
            </div>

            <!-- Right: Dynamic Reviews Section -->
            <div class="col-lg-5 reveal-up">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border-top-gold h-100 d-flex flex-column">
                    <h3 class="fw-bold text-dark h4 mb-4">Customer Reviews</h3>
                    
                    <!-- Reviews List -->
                    <div class="reviews-list-container flex-grow-1 mb-4" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                        <?php
                        $reviews_res = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = $p_id AND status = 1 ORDER BY id DESC LIMIT 5");
                        if(mysqli_num_rows($reviews_res) > 0){
                            while($rev = mysqli_fetch_assoc($reviews_res)){
                        ?>
                            <div class="review-box border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold text-dark m-0"><?= htmlspecialchars($rev['reviewer_name']) ?></h6>
                                    <div class="text-gold small">
                                        <?php for($i=1; $i<=5; $i++){ echo ($i <= $rev['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>'; } ?>
                                    </div>
                                </div>
                                <p class="text-muted small m-0 fst-italic">"<?= htmlspecialchars($rev['review_text']) ?>"</p>
                                <span class="text-muted" style="font-size: 0.7rem;"><?= date('d M, Y', strtotime($rev['created_at'])) ?></span>
                            </div>
                        <?php 
                            }
                        } else {
                            echo '<p class="text-muted small">No reviews yet. Be the first to review this product!</p>';
                        }
                        ?>
                    </div>

                    <!-- Write a Review Form -->
                    <div class="review-form-box bg-light-gray p-3 rounded">
                        <h6 class="fw-bold text-dark mb-3">Write a Review</h6>
                        <?= $review_msg ?>
                        <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?= $p_id ?>">
                            <div class="mb-2">
                                <select name="rating" class="form-control form-control-sm border-0 shadow-none" required>
                                    <option value="">Select Rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                    <option value="2">⭐⭐ (2/5)</option>
                                    <option value="1">⭐ (1/5)</option>
                                </select>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6"><input type="text" name="reviewer_name" class="form-control form-control-sm border-0 shadow-none" placeholder="Your Name" required></div>
                                <div class="col-6"><input type="email" name="reviewer_email" class="form-control form-control-sm border-0 shadow-none" placeholder="Your Email" required></div>
                            </div>
                            <div class="mb-2">
                                <textarea name="review_text" class="form-control form-control-sm border-0 shadow-none" rows="2" placeholder="Your Review..." required></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="btn-gold-solid w-100 py-2" style="font-size: 0.8rem;">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. RELATED PRODUCTS SLIDER -->
<section class="section-padding bg-white border-top">
    <div class="container reveal-up">
        <h3 class="fw-bold text-dark h3 mb-5 text-center">Related Products</h3>
        
        <div class="related-products-wrapper">
            <div class="row flex-nowrap overflow-auto hide-scrollbar g-4 pb-4">
                <?php
                $rel_res = mysqli_query($conn, "SELECT * FROM products WHERE pro_cate = '$c_id' AND id != $p_id AND status = 1 ORDER BY id DESC LIMIT 5");
                if($rel_res && mysqli_num_rows($rel_res) > 0):
                    while ($rel = mysqli_fetch_assoc($rel_res)):
                        $relImg = !empty($rel['pro_img']) ? 'admin/assets/img/uploads/' . $rel['pro_img'] : 'assets/images/black.png';
                        $relSlug = !empty($rel['slug_url']) ? $rel['slug_url'] : $rel['id'];
                ?>
                    <div class="col-10 col-md-6 col-lg-3">
                        <div class="modern-product-card bg-white w-100 d-flex flex-column h-100 shadow-sm">
                            <div class="modern-img-wrapper d-block position-relative">
                                <a href="product-details.php?slug=<?php echo urlencode($relSlug); ?>">
                                    <img src="<?= htmlspecialchars($relImg) ?>" alt="<?= htmlspecialchars($rel['pro_name']) ?>" class="img-fluid w-100 object-fit-cover" style="height: 200px;">
                                </a>
                            </div>
                            <div class="modern-card-body p-3 text-center">
                                <h4 class="text-dark fw-bold h6 mb-3">
                                    <a href="product-details.php?slug=<?php echo urlencode($relSlug); ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($rel['pro_name']) ?></a>
                                </h4>
                                <a href="product-details.php?slug=<?php echo urlencode($relSlug); ?>" class="btn-modern-outline px-4 py-2 w-100">View Product</a>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile; 
                else: 
                    echo '<div class="col-12 text-center text-muted">No related products found.</div>';
                endif; 
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Inquiry Form Include -->
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