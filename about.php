<?php
include 'config/connect.php';

// Fetch Dynamic About Us Data
$about_query = mysqli_query($conn, "SELECT * FROM about_us ORDER BY id DESC LIMIT 1");
$about_data = [];
if ($about_query && mysqli_num_rows($about_query) > 0) {
    $about_data = mysqli_fetch_assoc($about_query);
}

// Fetch Dynamic Brands Data for Portfolio
$brands_array = [];
$brands_res = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
if ($brands_res && mysqli_num_rows($brands_res) > 0) {
    while ($brand = mysqli_fetch_assoc($brands_res)) {
        $brands_array[] = $brand;
    }
}

// ==========================================
// DYNAMIC SEO META TAGS HANDLING (SEO FIX)
// ==========================================
$currentPage = basename($_SERVER['PHP_SELF']);
$seo_query = mysqli_query($conn, "SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = '$currentPage'");

// Setup Fallbacks in case database is empty
$pageTitle = "About Maruti Agro Industries | Export Quality Spices & Dry Fruits";
$meta_keywords = "About Maruti Agro, premium agricultural exports, Indian spices supplier, bulk dry fruits exporter";
$meta_description = "Discover the legacy of Maruti Agro Industries. We are committed to exporting the finest quality agricultural products from India to global markets.";

if ($seo_query && mysqli_num_rows($seo_query) > 0) {
    $seo_data = mysqli_fetch_assoc($seo_query);
    $pageTitle = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : $pageTitle;
    $meta_keywords = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_keywords;
    $meta_description = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_description;
} elseif (!empty($about_data)) {
    // Fallback to about_us table meta if meta table is empty for this page
    $pageTitle = !empty($about_data['meta_title']) ? $about_data['meta_title'] : $pageTitle;
    $meta_keywords = !empty($about_data['meta_key']) ? $about_data['meta_key'] : $meta_keywords;
    $meta_description = !empty($about_data['meta_desc']) ? $about_data['meta_desc'] : $meta_description;
}

// Header and Breadcrumb Include (H1 is inside breadcrumb.php)
include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<!-- 1. DYNAMIC ABOUT US SECTION -->
<section class="section-padding bg-white overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Dynamic Image -->
            <div class="col-lg-6 mb-5 mb-lg-0 reveal-left">
                <div class="about-premium-img-box">
                    <?php 
                    $aboutImg = !empty($about_data['image_url']) ? 'admin/' . $about_data['image_url'] : 'assets/images/about.jpg';
                    ?>
                    <img src="<?= htmlspecialchars($aboutImg); ?>" alt="<?= htmlspecialchars($pageTitle); ?>" onerror="this.src='https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=800&auto=format&fit=crop'" class="img-fluid main-img rounded shadow-lg">
                    
                    <!-- Floating Badge -->
                    <div class="experience-card bg-gold">
                        <div class="mb-1 fw-bold text-dark"><i class="bi bi-globe-americas fs-4"></i></div>
                        <p class="mb-0 small fw-bold text-uppercase">Global Exporter</p>
                    </div>
                </div>
            </div>
            
            <!-- Right: Dynamic Content -->
            <div class="col-lg-6 ps-lg-5 reveal-right">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Our Story</span>
                
                <!-- SEO FIX: H2 tag used for section heading instead of H1 -->
                <h2 class="main-heading text-dark fw-bold mt-2 mb-4">
                    <?= !empty($about_data['title']) ? htmlspecialchars($about_data['title']) : 'A Legacy of Authenticity & Purity'; ?>
                </h2>
                
                <div class="about-content text-muted-custom" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php 
                    if (!empty($about_data['content'])) {
                        echo $about_data['content']; 
                    } else {
                        echo '<p>Maruti Agro Industries stands as a pillar of trust in the global agricultural export market. We are dedicated to bridging the gap between authentic Indian farms and international buyers, ensuring that every product we deliver meets the highest standards of quality and freshness.</p>';
                    }
                    ?>
                </div>
                
                <div class="d-flex align-items-center mt-5 gap-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-gold fs-3 me-2"></i>
                        <span class="fw-bold text-dark">Verified Quality</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-fill-check text-gold fs-3 me-2"></i>
                        <span class="fw-bold text-dark">Trusted Supply</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. STATIC MISSION & VISION SECTION -->
<section class="section-padding bg-light-green">
    <div class="container">
        <div class="row text-center mb-5 reveal-up">
            <div class="col-12">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Our Core Values</span>
                <!-- SEO FIX: H2 tag -->
                <h2 class="main-heading text-dark fw-bold mt-2">Mission & Vision</h2>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Mission Card -->
            <div class="col-lg-5 reveal-left">
                <div class="mv-card bg-white p-5 h-100 position-relative rounded shadow-sm border-top-gold">
                    <div class="mv-icon bg-green text-gold mb-4 shadow">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <!-- SEO FIX: H3 tag -->
                    <h3 class="text-dark fw-bold mb-3 h4">Our Mission</h3>
                    <p class="text-muted mb-0" style="line-height: 1.7;">
                        To deliver the finest, ethically sourced agricultural commodities from India to the world. We strive to maintain uncompromising quality standards, ensuring our partners receive fresh, authentic, and hygienic products every single time.
                    </p>
                </div>
            </div>
            
            <!-- Vision Card -->
            <div class="col-lg-5 reveal-right">
                <div class="mv-card bg-white p-5 h-100 position-relative rounded shadow-sm border-top-gold">
                    <div class="mv-icon bg-green text-gold mb-4 shadow">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <!-- SEO FIX: H3 tag -->
                    <h3 class="text-dark fw-bold mb-3 h4">Our Vision</h3>
                    <p class="text-muted mb-0" style="line-height: 1.7;">
                        To establish Maruti Agro Industries as the most reliable and leading global export partner. We envision a sustainable supply chain that empowers local farmers while meeting the dynamic demands of the international food industry.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. STATIC WHY CHOOSE US SECTION -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 reveal-left">
                <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Why Choose Us</span>
                <!-- SEO FIX: H2 tag -->
                <h2 class="main-heading text-dark fw-bold mt-2 mb-4">Excellence in Every Export Shipment</h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    With years of expertise in the agro-export sector, we understand the critical importance of quality, packaging, and timely delivery. Our dedicated team works round the clock to exceed client expectations.
                </p>
                <a href="contact.php" class="btn-gold-solid">Contact Our Experts</a>
            </div>
            
            <div class="col-lg-7 reveal-right">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="why-choose-box bg-light-gray p-4 rounded h-100 transition-up">
                            <i class="bi bi-award-fill text-gold fs-1 mb-3 d-block"></i>
                            <!-- SEO FIX: H3 tag -->
                            <h3 class="fw-bold text-dark h5">Premium Grading</h3>
                            <p class="text-muted small mb-0">Every product undergoes strict sorting and grading to meet international parameters.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="why-choose-box bg-light-gray p-4 rounded h-100 transition-up mt-sm-4">
                            <i class="bi bi-box-seam-fill text-gold fs-1 mb-3 d-block"></i>
                            <h3 class="fw-bold text-dark h5">Custom Packaging</h3>
                            <p class="text-muted small mb-0">We offer bulk and customized packaging solutions to ensure safe transit and long shelf life.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="why-choose-box bg-light-gray p-4 rounded h-100 transition-up">
                            <i class="bi bi-airplane-engines-fill text-gold fs-1 mb-3 d-block"></i>
                            <h3 class="fw-bold text-dark h5">Timely Logistics</h3>
                            <p class="text-muted small mb-0">Partnered with top freight forwarders for seamless and on-time global deliveries.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="why-choose-box bg-light-gray p-4 rounded h-100 transition-up mt-sm-4">
                            <i class="bi bi-headset text-gold fs-1 mb-3 d-block"></i>
                            <h3 class="fw-bold text-dark h5">24/7 Support</h3>
                            <p class="text-muted small mb-0">Dedicated B2B customer support to track your shipments and handle your inquiries.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. DYNAMIC CLIENT PORTFOLIO (BRANDS SLIDER) -->
<section class="section-padding bg-light-gray border-top border-bottom">
    <div class="container text-center mb-5 reveal-up">
        <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Client Portfolio</span>
        <!-- SEO FIX: H2 tag -->
        <h2 class="main-heading text-dark fw-bold mt-2">Trusted By Leading Organizations</h2>
    </div>
    
    <div class="container-fluid px-0 reveal-up">
        <div class="brand-slider-container">
            <div class="brand-slide-track">
                <?php if(!empty($brands_array)): ?>
                    <?php 
                    for($loop = 0; $loop < 2; $loop++):
                        foreach($brands_array as $brand):
                            $brandLogo = !empty($brand['logo_path']) ? $brand['logo_path'] : '';
                    ?>
                    <div class="brand-slide">
                        <div class="portfolio-logo-box bg-white shadow-sm rounded d-flex align-items-center justify-content-center p-3 w-100 h-100">
                            <?php if(!empty($brandLogo)): ?>
                                <img src="admin/<?= htmlspecialchars($brandLogo) ?>" alt="<?= htmlspecialchars($brand['brand_name']) ?>" title="<?= htmlspecialchars($brand['brand_name']) ?>">
                            <?php else: ?>
                                <span class="fw-bold text-dark text-uppercase small"><?= htmlspecialchars($brand['brand_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                        endforeach; 
                    endfor; 
                    ?>
                <?php else: ?>
                    <div class="brand-slide"><div class="portfolio-logo-box bg-white shadow-sm rounded d-flex align-items-center justify-content-center p-3 w-100 h-100"><span class="fw-bold text-muted small m-0">FSSAI</span></div></div>
                    <div class="brand-slide"><div class="portfolio-logo-box bg-white shadow-sm rounded d-flex align-items-center justify-content-center p-3 w-100 h-100"><span class="fw-bold text-muted small m-0">APEDA</span></div></div>
                    <div class="brand-slide"><div class="portfolio-logo-box bg-white shadow-sm rounded d-flex align-items-center justify-content-center p-3 w-100 h-100"><span class="fw-bold text-muted small m-0">SPICES BOARD</span></div></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Include global scroll animations script -->
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