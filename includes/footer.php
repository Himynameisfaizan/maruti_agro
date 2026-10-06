<?php
// 1. Fallback Text Updated for Maruti Agro Industries
$c_address = "RS NO 111P3/2/P PLOT NO 2, HADADAD-KANIYAD ROAD, NEAR CANAL, BOTAD, BHAVNAGAR, GUJARAT, 364710";
$c_phone = "+91-0000000000";
$c_email = "info@marutiagro.com";
$c_fb = "#";
$c_linkedin = "#";
$c_wp = "#";
$footer_about_text = "Maruti Agro Industries is a leading exporter of premium agricultural products, specializing in farm-fresh spices and dry fruits."; 

if (isset($conn)) {
    $contact_query = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
    if ($contact_query && mysqli_num_rows($contact_query) > 0) {
        $contact_info = mysqli_fetch_assoc($contact_query);

        $c_address = !empty($contact_info['address']) ? $contact_info['address'] : $c_address;
        $c_phone = !empty($contact_info['phone']) ? $contact_info['phone'] : $c_phone;
        $c_email = !empty($contact_info['contact_email']) ? $contact_info['contact_email'] : (!empty($contact_info['email']) ? $contact_info['email'] : $c_email);

        $c_fb = !empty($contact_info['facebook']) ? $contact_info['facebook'] : $c_fb;
        $c_linkedin = !empty($contact_info['linkdin']) ? $contact_info['linkdin'] : $c_linkedin;
        $c_instagram = !empty($contact_info['instagram']) ? $contact_info['instagram'] : '#';
        $c_wp = !empty($contact_info['wp_number']) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $contact_info['wp_number']) : $c_wp;
    }

    $about_query = mysqli_query($conn, "SELECT content FROM about_us ORDER BY id ASC LIMIT 1");
    if ($about_query && mysqli_num_rows($about_query) > 0) {
        $about_data = mysqli_fetch_assoc($about_query);
        if (!empty($about_data['content'])) {
            $clean_text = strip_tags($about_data['content']);
            $footer_about_text = strlen($clean_text) > 120 ? substr($clean_text, 0, 120) . '...' : $clean_text;
        }
    }

    $footer_products = mysqli_query($conn, "SELECT id, pro_name, slug_url FROM products WHERE status = 1 ORDER BY id DESC LIMIT 5");
}
?>

<footer class="custom-footer pt-5">
    <div class="container pb-5">
        <div class="row g-4">

            <!-- Column 1: About & Text Logo -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                
                <!-- Premium Text Logo -->
                <div class="footer-brand mb-4">
                    <a href="index.php" class="text-decoration-none d-inline-flex flex-column align-items-start text-logo-container">
                        <span style="font-size: 26px; font-weight: 800; color: #ffffff; line-height: 1; letter-spacing: 1px; font-family: 'Arial', sans-serif;">MARUTI AGRO</span>
                        <span style="font-size: 12px; font-weight: 600; color: #D4AF37; line-height: 1; letter-spacing: 4.5px; margin-top: 4px; text-transform: uppercase;">INDUSTRIES</span>
                    </a>
                </div>
                
                <p class="footer-text mb-4">
                    <?= htmlspecialchars($footer_about_text); ?>
                </p>

                <span class="badge" style="background-color: #0B4619; color: #D4AF37; border: 1px solid #D4AF37; font-weight: 600; font-size: 13px; padding: 8px 15px; letter-spacing: 0.5px;">
                    <i class="bi bi-shield-check me-2"></i> Verified Exporter
                </span>
            </div>

            <!-- Column 2: Information Links -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h4 class="footer-heading">Information</h4>
                <ul class="footer-links list-unstyled">
                    <li><a href="index.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Home</a></li>
                    <li><a href="about.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Company Profile</a></li>
                    <li><a href="contact.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Contact Us</a></li>
                    <li><a href="terms-condition.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Terms & Conditions</a></li>
                    <li><a href="privacy-policy.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Privacy Policy</a></li>
                    <li><a href="shipping-return.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Shipping & Returns</a></li>
                    <li><a href="refund-policy.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Refund & Cancellation</a></li>
                </ul>
            </div>

            <!-- Column 3: Dynamic Products -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h4 class="footer-heading">Our Products</h4>
                <ul class="footer-links list-unstyled">
                    <?php
                    if (isset($footer_products) && mysqli_num_rows($footer_products) > 0) {
                        while ($f_prod = mysqli_fetch_assoc($footer_products)) {
                            // Link ko id ya slug url ke through bhej sakte ho jaisa setup ho
                            $prod_slug = !empty($f_prod['slug_url']) ? $f_prod['slug_url'] : $f_prod['id'];
                    ?>
                            <li>
                                <a href="product-details.php?slug=<?= urlencode($prod_slug); ?>">
                                    <i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> <?= htmlspecialchars($f_prod['pro_name']); ?>
                                </a>
                            </li>
                        <?php
                        }
                    } else {
                        ?>
                        <li><a href="products.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Whole Spices</a></li>
                        <li><a href="products.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Dry Fruits</a></li>
                        <li><a href="products.php"><i class="bi bi-chevron-right small me-2" style="color: #D4AF37;"></i> Premium Nuts</a></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Column 4: Contact Details -->
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-heading">Contact Details</h4>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-start mb-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <span class="footer-text mt-1">
                            <?= htmlspecialchars($c_address); ?>
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <span>
                            <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $c_phone)); ?>" class="footer-text text-decoration-none">
                                <?= htmlspecialchars($c_phone); ?>
                            </a>
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-4">
                        <div class="footer-contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <span>
                            <a href="mailto:<?= htmlspecialchars($c_email); ?>" class="footer-text text-decoration-none" style="word-break: break-all;">
                                <?= htmlspecialchars($c_email); ?>
                            </a>
                        </span>
                    </li>
                </ul>

                <!-- Social Media Icons -->
                <div class="footer-social d-flex gap-3">
                    <?php if ($c_fb != '#'): ?>
                        <a href="<?= htmlspecialchars($c_fb); ?>" target="_blank" style="background-color: #3b5998;">
                            <i class="bi bi-facebook"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ($c_instagram != '#'): ?>
                        <a href="<?= htmlspecialchars($c_instagram); ?>" target="_blank" style="background-color: #e1306c;">
                            <i class="bi bi-instagram"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ($c_linkedin != '#'): ?>
                        <a href="<?= htmlspecialchars($c_linkedin); ?>" target="_blank" style="background-color: #007bb5;">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ($c_wp != '#'): ?>
                        <a href="<?= htmlspecialchars($c_wp); ?>" target="_blank" style="background-color: #25D366;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright & Developer Info -->
    <div class="py-4 mt-2" style="background-color: #050505; border-top: 1px solid #1a1a1a;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0" style="color: #888; font-size: 0.9rem;">
                    &copy; <?= date('Y'); ?> <strong class="text-white">Maruti Agro Industries</strong>. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-end" style="color: #888; font-size: 0.9rem;">
                    Powered by <a href="https://digitalwebtrackers.com" target="_blank" class="text-decoration-none" style="color: #D4AF37; font-weight: 600; transition: 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#D4AF37'">digitalwebtrackers.com</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Contact Buttons -->
<div class="floating-contact">

    <!-- Phone Call Floating Button -->
    <?php if (!empty($c_phone) && $c_phone != '#'): ?>
        <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $c_phone)); ?>" class="float-btn float-phone shadow-lg" title="Call Us">
            <i class="bi bi-telephone-fill"></i>
        </a>
    <?php endif; ?>
    
    <!-- WhatsApp Floating Button -->
    <?php if (!empty($c_wp) && $c_wp != '#'): ?>
        <a href="<?= htmlspecialchars($c_wp); ?>" target="_blank" class="float-btn float-whatsapp shadow-lg" title="Chat on WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>
    <?php endif; ?>

</div>