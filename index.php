<?php
include 'config/connect.php';

$banner_res = false;
if (isset($conn)) {
    $banner_res = mysqli_query($conn, "SELECT * FROM banners WHERE status = 0 ORDER BY display_order ASC, id DESC");
}

$categories_res = false;
if (isset($conn)) {
    $categories_res = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 3");
}

$products_res = false;
if (isset($conn)) {
    $products_res = mysqli_query($conn, "SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT 8");
}

// Fetch Testimonials
$test_res = false;
if (isset($conn)) {
    $test_res = mysqli_query($conn, "SELECT * FROM testimonials WHERE status = 1 ORDER BY test_id DESC LIMIT 3");
}

// Fetch About Section Data
$about_res = false;
$about_data = [];
if (isset($conn)) {
    $about_query = mysqli_query($conn, "SELECT * FROM about_sections ORDER BY section_order ASC LIMIT 1");
    if ($about_query && mysqli_num_rows($about_query) > 0) {
        $about_data = mysqli_fetch_assoc($about_query);
    }
}

$brands_array = [];
if (isset($conn)) {
    $brands_res = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
    if ($brands_res && mysqli_num_rows($brands_res) > 0) {
        while ($brand = mysqli_fetch_assoc($brands_res)) {
            $brands_array[] = $brand;
        }
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
$seo_query = mysqli_query($conn, "SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = '$currentPage'");

if ($seo_query && mysqli_num_rows($seo_query) > 0) {
    $seo_data = mysqli_fetch_assoc($seo_query);
    $pageTitle = $seo_data['meta_title'];
    $meta_keywords = $seo_data['meta_key'];
    $meta_description = $seo_data['meta_desc'];
}
include("includes/header.php");
?>
    
<!-- Premium Hero Slider Section -->
<div id="heroCarousel" class="carousel slide carousel-fade premium-hero-slider" data-bs-ride="carousel" data-bs-pause="false">
    <div class="carousel-indicators">
        <?php
        if ($banner_res && mysqli_num_rows($banner_res) > 0):
            $i = 0;
            mysqli_data_seek($banner_res, 0);
            while ($b_row = mysqli_fetch_assoc($banner_res)):
        ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= ($i == 0) ? 'active' : '' ?>" aria-current="<?= ($i == 0) ? 'true' : 'false' ?>"></button>
            <?php
                $i++;
            endwhile;
        else:
            ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <?php endif; ?>
    </div>

    <div class="carousel-inner">
        <?php
        if ($banner_res && mysqli_num_rows($banner_res) > 0):
            $j = 0;
            mysqli_data_seek($banner_res, 0);
            while ($banner = mysqli_fetch_assoc($banner_res)):
                $bannerImg = !empty($banner['banner_path']) ? $banner['banner_path'] : 'assets/images/black.png';
        ?>
                <div class="carousel-item <?= ($j == 0) ? 'active' : '' ?>" data-bs-interval="6000">
                    <div class="slide-bg" style="background-image: url('admin/<?= htmlspecialchars($bannerImg) ?>');"></div>
                    <div class="carousel-caption premium-caption container">
                        <div class="caption-content">
                            <span class="subtitle-badge reveal-fade">Maruti Agro Industries</span>
                            <h2 class="hero-title reveal-up"><?= htmlspecialchars($banner['title']) ?></h2>
                            <p class="reveal-up delay-1"><?= htmlspecialchars($banner['description']) ?></p>
                            <div class="btn-group-premium reveal-up delay-2">
                                <a href="<?= !empty($banner['link_url']) ? htmlspecialchars($banner['link_url']) : 'products.php' ?>" class="btn-gold-solid">Explore Products</a>
                                <a href="contact.php" class="btn-gold-outline">Contact an Expert</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $j++;
            endwhile;
        else:
            ?>
            <div class="carousel-item active" data-bs-interval="6000">
                <div class="slide-bg" style="background-image: url('assets/images/banner1.jpg');"></div>
                <div class="carousel-caption premium-caption container">
                    <div class="caption-content">
                        <span class="subtitle-badge reveal-fade">Maruti Agro Industries</span>
                        <h2 class="hero-title reveal-up">Premium Quality <br><span class="text-gold">Agricultural Exports</span></h2>
                        <p class="reveal-up delay-1">Delivering the finest authentic Indian spices and agricultural wealth to global markets with unmatched purity.</p>
                        <div class="btn-group-premium reveal-up delay-2">
                            <a href="products.php" class="btn-gold-solid">Explore Products</a>
                            <a href="contact.php" class="btn-gold-outline">Contact an Expert</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="nav-icon-box"><i class="bi bi-chevron-left"></i></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="nav-icon-box"><i class="bi bi-chevron-right"></i></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Modern About Us Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                <div class="about-premium-img-box reveal-left">
                    <?php 
                    $aboutImg = !empty($about_data['image_url']) ? 'admin/' . $about_data['image_url'] : 'assets/images/about.jpg';
                    ?>
                    <img src="<?= htmlspecialchars($aboutImg); ?>" alt="<?= !empty($about_data['title']) ? htmlspecialchars($about_data['title']) : 'Maruti Agro Industries Premium Quality'; ?>" onerror="this.src='https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=800&auto=format&fit=crop'" class="img-fluid main-img rounded">
                    <div class="experience-card bg-gold">
                        <h3 class="mb-0 fw-bold">100%</h3>
                        <p class="mb-0 small fw-semibold text-uppercase">Export Quality</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 reveal-right">
                <div class="section-heading mb-4">
                    <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Who We Are</span>
                    <h2 class="main-heading text-dark fw-bold mt-2">
                        <?= !empty($about_data['title']) ? htmlspecialchars($about_data['title']) : 'Delivering Authentic Indian Flavors to the World'; ?>
                    </h2>
                </div>
                
                <div class="about-content text-muted-custom mb-4" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php 
                    if (!empty($about_data['content'])) {
                        echo $about_data['content']; 
                    } else {
                        echo '<p>At <strong>Maruti Agro Industries</strong>, we specialize in processing and exporting premium quality whole spices, dry fruits, and authentic Indian agricultural products globally.</p>';
                    }
                    ?>
                </div>
                
                <a href="about.php" class="btn-gold-solid px-4 py-3 text-uppercase fw-bold mt-2 d-inline-block">Discover Our Journey</a>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges Section (Why Choose Us) -->
<section class="section-padding bg-light-green">
    <div class="container">
        <div class="row text-center mb-5 reveal-up">
            <div class="col-12">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Why Maruti Agro Industries</span>
                <h2 class="main-heading text-dark fw-bold mt-2">The Trusted Choice for Global Importers</h2>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-lg-3 col-md-6 reveal-up delay-1">
                <div class="premium-feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <i class="bi bi-shield-check text-gold"></i>
                    </div>
                    <h4 class="feature-title text-dark fw-bold mb-3">Certified Quality</h4>
                    <p class="text-muted small mb-0">Our products meet rigorous global food safety standards ensuring 100% purity and authenticity.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal-up delay-2">
                <div class="premium-feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <i class="bi bi-globe text-gold"></i>
                    </div>
                    <h4 class="feature-title text-dark fw-bold mb-3">Global Export</h4>
                    <p class="text-muted small mb-0">Seamless international logistics and timely delivery to our clients across the globe.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal-up delay-3">
                <div class="premium-feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <i class="bi bi-basket text-gold"></i>
                    </div>
                    <h4 class="feature-title text-dark fw-bold mb-3">Farm Fresh</h4>
                    <p class="text-muted small mb-0">Ethically sourced directly from the finest Indian farms to preserve natural aroma.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal-up delay-4">
                <div class="premium-feature-card text-center h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <i class="bi bi-graph-up-arrow text-gold"></i>
                    </div>
                    <h4 class="feature-title text-dark fw-bold mb-3">Best Pricing</h4>
                    <p class="text-muted small mb-0">Premium quality agricultural and food exports offered at highly competitive rates.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Premium Categories Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-end mb-5 reveal-up">
            <div class="col-md-8">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Our Offerings</span>
                <h2 class="main-heading text-dark fw-bold mt-2">Explore Premium Categories</h2>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <?php
            if ($categories_res && mysqli_num_rows($categories_res) > 0):
                while ($cat = mysqli_fetch_assoc($categories_res)):
                    $catImg = !empty($cat['image']) ? 'admin/uploads/category/' . $cat['image'] : 'assets/images/black.png';
                    $catSlug = !empty($cat['slug_url']) ? $cat['slug_url'] : $cat['cate_id'];
            ?>
                    <div class="col-lg-4 col-md-6 reveal-up">
                        <div class="premium-category-card">
                            <div class="cat-img-box">
                                <img src="<?= htmlspecialchars($catImg) ?>" alt="<?= htmlspecialchars($cat['categories']) ?>" onerror="this.src='assets/images/black.png'">
                                <div class="overlay"></div>
                                <div class="cat-content text-center">
                                    <h3 class="cat-title text-white fw-bold mb-2"><?= htmlspecialchars($cat['categories']) ?></h3>
                                    <a href="products.php?category=<?= urlencode($catSlug) ?>" class="btn-outline-white text-uppercase">View Category</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center text-muted">No categories available right now.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Elegant Products Showcase -->
<section class="section-padding bg-light-gray border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5 reveal-up">
            <div>
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Export Grade Produce</span>
                <h2 class="main-heading text-dark fw-bold mt-2">Featured Products</h2>
            </div>
            <div class="d-none d-md-block">
                <a href="products.php" class="btn-dark-outline text-uppercase">View All Catalog</a>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            if ($products_res && mysqli_num_rows($products_res) > 0):
                while ($prod = mysqli_fetch_assoc($products_res)):
                    $proImg = !empty($prod['pro_img']) ? 'admin/assets/img/uploads/' . $prod['pro_img'] : 'assets/images/black.png';
                    $productSlug = !empty($prod['slug_url']) ? $prod['slug_url'] : $prod['id'];
            ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 reveal-up">
                        <div class="premium-product-card bg-white h-100">
                            <div class="product-badge bg-gold text-dark fw-bold">Premium</div>
                            <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="product-img-box d-block">
                                <img src="<?= htmlspecialchars($proImg) ?>" alt="<?= htmlspecialchars($prod['pro_name']) ?>" onerror="this.src='assets/images/black.png'">
                            </a>
                            <div class="product-info text-center p-4">
                                <h4 class="product-title text-dark fw-bold mb-3">
                                    <a href="product-details.php?slug=<?php echo urlencode($productSlug); ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($prod['pro_name']) ?></a>
                                </h4>
                                <a href="contact.php?product=<?= urlencode($prod['pro_name']) ?>" class="btn-gold-solid w-100 py-2 d-block">Inquire Now</a>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center text-muted">No products found.</div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5 d-block d-md-none">
            <a href="products.php" class="btn-dark-outline text-uppercase">View All Catalog</a>
        </div>
    </div>
</section>

<!-- Minimalist Testimonials -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row justify-content-center text-center mb-5 reveal-up">
            <div class="col-lg-8">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Client Feedback</span>
                <h2 class="main-heading text-dark fw-bold mt-2">What Global Partners Say</h2>
            </div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            if ($test_res && mysqli_num_rows($test_res) > 0): 
                while($test = mysqli_fetch_assoc($test_res)):
                    $testImg = !empty($test['image']) ? 'admin/uploads/testimonials/' . $test['image'] : 'assets/images/clove.png';
            ?>
            <div class="col-lg-4 col-md-6 reveal-up">
                <div class="premium-testimonial-card h-100 bg-light-gray">
                    <div class="quote-icon text-gold mb-3"><i class="bi bi-quote"></i></div>
                    <p class="test-msg text-dark fst-italic mb-4">"<?= htmlspecialchars($test['message']) ?>"</p>
                    <div class="client-info d-flex align-items-center border-top pt-3 border-light">
                        <div class="client-img rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px; border: 2px solid #D4AF37;">
                            <img src="<?= htmlspecialchars($testImg) ?>" alt="<?= htmlspecialchars($test['name']) ?>" class="w-100 h-100 object-fit-cover" onerror="this.src='assets/images/section/default-avatar.png'">
                        </div>
                        <div>
                            <h5 class="client-name text-dark fw-bold mb-0" style="font-size: 1rem;"><?= htmlspecialchars($test['name']) ?></h5>
                            <span class="client-desig text-gold small fw-semibold"><?= htmlspecialchars($test['designation']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="col-12 text-center text-muted">Client reviews will be updated shortly.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Dynamic Brands Slider Section (Greyscale to Color) -->
<section class="brands-slider-section border-top border-bottom" style="background-color: #fafafa;">
    <div class="container py-5">
        <h5 class="text-center text-uppercase text-muted fw-bold letter-spacing-1 mb-4" style="font-size: 0.9rem;">Organizations & Partners Trusting Us</h5>
        
        <div class="brand-slider-container">
            <div class="brand-slide-track">
                <?php if(!empty($brands_array)): ?>
                    <?php 
                    for($loop = 0; $loop < 2; $loop++):
                        foreach($brands_array as $brand):
                            $brandLogo = !empty($brand['logo_path']) ? $brand['logo_path'] : '';
                    ?>
                    <div class="brand-slide">
                        <?php if(!empty($brandLogo)): ?>
                            <img src="admin/<?= htmlspecialchars($brandLogo) ?>" alt="<?= htmlspecialchars($brand['brand_name']) ?>" title="<?= htmlspecialchars($brand['brand_name']) ?>">
                        <?php else: ?>
                            <span class="fw-bold text-dark text-uppercase"><?= htmlspecialchars($brand['brand_name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php 
                        endforeach; 
                    endfor; 
                    ?>
                <?php else: ?>
                    <div class="brand-slide"><h4 class="fw-bold text-muted text-uppercase m-0">FSSAI</h4></div>
                    <div class="brand-slide"><h4 class="fw-bold text-muted text-uppercase m-0">APEDA</h4></div>
                    <div class="brand-slide"><h4 class="fw-bold text-muted text-uppercase m-0">SPICES BOARD</h4></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include ('includes/inquiry-form.php');?>

<!-- Clean Corporate FAQ Section -->
<section class="section-padding bg-light-green">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 reveal-up">
                <div class="text-center mb-5">
                    <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Clear Your Doubts</span>
                    <h2 class="main-heading text-dark fw-bold mt-2">Frequently Asked Questions</h2>
                </div>

                <div class="accordion premium-accordion" id="exportFaqAccordion">
                    <div class="accordion-item mb-3 bg-white">
                        <h3 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                Are your agricultural products certified for global export?
                            </button>
                        </h3>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#exportFaqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, absolutely. Maruti Agro Industries strictly complies with global food safety standards. Our exports are backed by necessary quality checks and certifications to clear customs smoothly in your destination country.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm">
                        <h3 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                Do you handle B2B bulk orders and container shipments?
                            </button>
                        </h3>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#exportFaqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, our core expertise lies in B2B wholesale and bulk container shipments (FCL/LCL). We supply high volumes of dry fruits, whole spices, and other commodities tailored to your commercial needs.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm">
                        <h3 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                What is your Minimum Order Quantity (MOQ)?
                            </button>
                        </h3>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#exportFaqAccordion">
                            <div class="accordion-body text-muted">
                                The Minimum Order Quantity (MOQ) varies depending on the specific product and the shipping method. Please reach out to our sales team at info@marutiagro.com for exact product-wise MOQs.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm">
                        <h3 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                Do you offer customized or private label packaging?
                            </button>
                        </h3>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#exportFaqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, we offer customized packaging solutions, including bulk PP bags, jute bags, vacuum packs, and private labeling for retail brands. Let us know your packaging requirements during the inquiry process.
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>

<!-- Scroll Animation Script -->
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