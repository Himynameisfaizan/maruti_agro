<?php
include 'config/connect.php';

// ==========================================
// FETCH CONTACT DETAILS FROM DATABASE
// ==========================================
$c_address = "RS NO 111P3/2/P PLOT NO 2, HADADAD-KANIYAD ROAD, NEAR CANAL, BOTAD, BHAVNAGAR, GUJARAT, 364710";
$c_phone = "+910000000000";
$c_email = "info@marutiagro.com";
$c_wp = "+910000000000";
$c_map = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3671.000000000000!2d71.6666667!3d22.1666667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDEwJzAwLjAiTiA3McKwNDAnMDAuMCJF!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin";
$c_hours = "Mon-Sat: 9:00 AM to 6:00 PM";

if (isset($conn)) {
    $contact_query = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
    if ($contact_query && mysqli_num_rows($contact_query) > 0) {
        $contact_info = mysqli_fetch_assoc($contact_query);

        $c_address = !empty($contact_info['address']) ? $contact_info['address'] : $c_address;
        $c_phone = !empty($contact_info['phone']) ? $contact_info['phone'] : $c_phone;
        $c_email = !empty($contact_info['contact_email']) ? $contact_info['contact_email'] : (!empty($contact_info['email']) ? $contact_info['email'] : $c_email);
        $c_wp = !empty($contact_info['wp_number']) ? preg_replace('/[^0-9]/', '', $contact_info['wp_number']) : $c_wp;
        $c_map = !empty($contact_info['map']) ? $contact_info['map'] : $c_map;
        $c_hours = !empty($contact_info['working_hours']) ? $contact_info['working_hours'] : $c_hours;
    }
}

// ==========================================
// DYNAMIC SEO META TAGS
// ==========================================
$currentPage = basename($_SERVER['PHP_SELF']);
$seo_query = mysqli_query($conn, "SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = '$currentPage'");

$pageTitle = "Contact Us";
$meta_keywords = "Contact Maruti Agro, export inquiry, buy bulk jeera, dhaniya, chana, bajra, rice, wheat";
$meta_description = "Get in touch with Maruti Agro Industries for bulk supply and export inquiries of premium Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat.";

if ($seo_query && mysqli_num_rows($seo_query) > 0) {
    $seo_data = mysqli_fetch_assoc($seo_query);
    $pageTitle = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : $pageTitle;
    $meta_keywords = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_keywords;
    $meta_description = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_description;
}

$schema_query = mysqli_query($conn, "SELECT schema_markup FROM page_schemas WHERE page_url = '$currentPage'");
$page_schema = ($schema_query && mysqli_num_rows($schema_query) > 0) ? mysqli_fetch_assoc($schema_query)['schema_markup'] : "";

$pageTitle = "Contact Us"; // Breadcrumb Header H1
include("includes/header.php");
include("includes/breadcrumb.php"); 
?>

<!-- 1. CONTACT INFO CARDS -->
<section class="section-padding bg-light-green">
    <div class="container">
        <div class="row g-4 justify-content-center reveal-up">
            <!-- Address Card -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card bg-white p-4 p-md-5 text-center h-100 shadow-sm border-top-gold rounded">
                    <div class="contact-icon-box bg-light-green text-green mx-auto mb-4">
                        <i class="bi bi-geo-alt-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark h5 mb-3">Head Office</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;"><?= htmlspecialchars($c_address) ?></p>
                </div>
            </div>
            
            <!-- Contact Box -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card bg-white p-4 p-md-5 text-center h-100 shadow-sm border-top-gold rounded">
                    <div class="contact-icon-box bg-light-green text-green mx-auto mb-4">
                        <i class="bi bi-telephone-inbound-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark h5 mb-3">Direct Contact</h3>
                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $c_phone) ?>" class="d-block text-muted small text-decoration-none mb-2 hover-gold"><?= htmlspecialchars($c_phone) ?></a>
                    <a href="mailto:<?= htmlspecialchars($c_email) ?>" class="d-block text-muted small text-decoration-none hover-gold"><?= htmlspecialchars($c_email) ?></a>
                </div>
            </div>

            <!-- Business Hours Box -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card bg-white p-4 p-md-5 text-center h-100 shadow-sm border-top-gold rounded">
                    <div class="contact-icon-box bg-light-green text-green mx-auto mb-4">
                        <i class="bi bi-clock-history fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark h5 mb-3">Business Hours</h3>
                    <p class="text-muted small mb-0"><?= htmlspecialchars($c_hours) ?></p>
                    <p class="text-muted small mt-2">Support available 24/7 for export shipments.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. INQUIRY FORM & GOOGLE MAP SECTION -->
<section class="section-padding bg-white border-top">
    <div class="container">
        <div class="row g-0 shadow-lg rounded overflow-hidden reveal-up">
            
            <!-- Map Section -->
            <div class="col-lg-6">
                <div class="contact-map-wrapper h-100" style="min-height: 500px;">
                    <iframe src="<?= htmlspecialchars($c_map) ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-6">
                <div class="contact-form-wrapper bg-green p-4 p-md-5 h-100 position-relative">
                    <!-- Abstract Background Graphic -->
                    <div class="position-absolute top-0 end-0 translate-middle-y rounded-circle bg-white opacity-10" style="width: 250px; height: 250px; right: -50px !important;"></div>
                    
                    <div class="position-relative z-1">
                        <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Get In Touch</span>
                        <h2 class="text-white fw-bold h3 mt-2 mb-4">Request An Export Quote</h2>
                        <p class="text-white-50 small mb-4">Share your bulk requirements for Jeera, Dhaniya, Chana, Bajra, Rice, or Wheat. Our team will get back to you shortly.</p>

                        <form action="inquiry-process.php" method="POST">
                            <div class="row g-3">
                                <!-- Pre-filled product name from other pages -->
                                <?php $product_focus = isset($_GET['product']) ? htmlspecialchars($_GET['product']) : ''; ?>
                                
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control premium-dark-input" placeholder="Your Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control premium-dark-input" placeholder="Your Email *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="phone" class="form-control premium-dark-input" placeholder="Phone Number *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="subject" class="form-control premium-dark-input" placeholder="Product / Subject *" value="<?= $product_focus ?>" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control premium-dark-input" placeholder="Please mention quantity and destination..." style="height: 120px; resize: none;" required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-gold-solid w-100 py-3 text-uppercase">
                                        Submit Inquiry <i class="bi bi-send-fill ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. CUSTOM FAQ FOR MARUTI AGRO INDUSTRIES -->
<section class="section-padding bg-light-green border-top">
    <div class="container">
        <div class="row justify-content-center reveal-up">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="sub-heading text-gold text-uppercase fw-bold letter-spacing-1">Help & Information</span>
                    <h2 class="main-heading text-dark fw-bold mt-2">Frequently Asked Questions</h2>
                </div>

                <div class="accordion premium-accordion" id="contactFaqAccordion">
                    
                    <!-- FAQ 1 -->
                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm rounded overflow-hidden">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What agricultural products do you export and supply?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body text-muted small" style="line-height: 1.6;">
                                Maruti Agro Industries specializes in the supply and global export of premium quality commodities, including <strong>Jeera (Cumin), Dhaniya (Coriander), Chana (Chickpeas), Bajra (Pearl Millet), Rice, and Wheat</strong>.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm rounded overflow-hidden">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Are your products certified for international markets?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body text-muted small" style="line-height: 1.6;">
                                Yes, all our agricultural products undergo rigorous grading, sorting, and quality checks. We adhere to global food safety and hygiene standards ensuring smooth customs clearance for export shipments.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm rounded overflow-hidden">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How can I place a bulk export order?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body text-muted small" style="line-height: 1.6;">
                                You can submit your bulk requirements via the inquiry form above, drop us an email at <strong><?= htmlspecialchars($c_email) ?></strong>, or connect instantly with our sales team via WhatsApp.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item mb-3 bg-white border-0 shadow-sm rounded overflow-hidden">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Do you provide customized bulk packaging?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body text-muted small" style="line-height: 1.6;">
                                Yes, we offer standard PP bags, jute bags, and customized bulk packaging options tailored to protect the natural aroma and quality of products during long transit periods.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

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