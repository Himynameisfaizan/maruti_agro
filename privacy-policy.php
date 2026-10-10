<?php
include('config/connect.php');

// Dynamic Contact Fetch
$c_phone = "+910000000000";
$c_email = "info@marutiagro.com";
if (isset($conn)) {
    $contact_query = mysqli_query($conn, "SELECT phone, email, contact_email FROM contacts ORDER BY id DESC LIMIT 1");
    if ($contact_query && mysqli_num_rows($contact_query) > 0) {
        $c_info = mysqli_fetch_assoc($contact_query);
        $c_phone = !empty($c_info['phone']) ? $c_info['phone'] : $c_phone;
        $c_email = !empty($c_info['contact_email']) ? $c_info['contact_email'] : (!empty($c_info['email']) ? $c_info['email'] : $c_email);
    }
}

// SEO Meta Variables
$pageTitle = "Privacy Policy | Maruti Agro Industries";
$meta_description = "Read the Privacy Policy of Maruti Agro Industries regarding personal data protection for our premium export commodities like Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat.";
$meta_key = "privacy policy, maruti agro industries, agricultural export, jeera, dhaniya, chana, bajra, rice, wheat";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row g-5">
            
            <!-- LEFT COLUMN: Policy Content -->
            <div class="col-lg-8 reveal-left">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border" style="border-color: #f0f0f0 !important;">
                    <h2 class="mb-3 h3" style="color: #0B4619; font-weight: 700;">Privacy Policy</h2>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <p>Welcome to <strong>Maruti Agro Industries</strong>. We respect your privacy and are committed to protecting your personal data. This Privacy Policy outlines how we collect, use, process, and safeguard your information when you visit our website, purchase our premium agricultural commodities—including <strong>Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat</strong>—or use our bulk export services.</p>

                    <h3 class="mt-5 h5 fw-bold" style="color: #D4AF37;">1. Information We Collect</h3>
                    <p>To provide you with a seamless and secure shopping and wholesale inquiry experience, we collect the following types of information:</p>
                    <ul class="text-muted">
                        <li><strong>Personal Identification Information:</strong> Name, email address, phone number, and business/company details (if applicable).</li>
                        <li><strong>Shipping & Billing Information:</strong> Delivery address, billing address, postal code, and destination country for domestic or international freight.</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, time zone setting, operating system, and platform details when browsing our catalog.</li>
                        <li><strong>Trade & Bulk Order Compliance Data:</strong> For bulk and wholesale orders, we may collect business credentials, GST/VAT numbers, or import-export licenses as required by trade regulations.</li>
                    </ul>

                    <h3 class="mt-5 h5 fw-bold" style="color: #D4AF37;">2. How We Use Your Information</h3>
                    <p>We use the information we collect for the following purposes:</p>
                    <ul class="text-muted">
                        <li>To process, pack, and fulfill your orders for Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat, ensuring quality dispatch.</li>
                        <li>To communicate with you regarding order confirmations, shipping updates, tracking, and customer support.</li>
                        <li>To process online payments securely and prevent fraudulent activities.</li>
                        <li>To comply with food safety, agricultural export standards, and legal trade obligations.</li>
                    </ul>

                    <h3 class="mt-5 h5 fw-bold" style="color: #D4AF37;">3. Secure Payment Processing</h3>
                    <p>We do not store your credit card, debit card, UPI, or net banking details on our servers. All financial transactions are handled through secure, PCI-DSS compliant third-party payment gateways. Your payment data is fully encrypted.</p>

                    <h3 class="mt-5 h5 fw-bold" style="color: #D4AF37;">4. Data Security</h3>
                    <p>We implement robust administrative, technical, and physical security measures, including SSL encryption and secure firewalls, to protect your personal data against unauthorized access, disclosure, or misuse.</p>

                    <h3 class="mt-5 h5 fw-bold" style="color: #D4AF37;">5. Contact Us</h3>
                    <p>If you have any questions or concerns regarding this Privacy Policy, please feel free to contact us:</p>
                    <div class="p-4 mt-3 rounded" style="background-color: rgba(11, 70, 25, 0.05); border-left: 4px solid #0B4619;">
                        <p class="mb-1"><strong>Maruti Agro Industries</strong></p>
                        <p class="mb-1"><strong>Specialization:</strong> Exporters & Suppliers of Jeera, Dhaniya, Chana, Bajra, Rice & Wheat</p>
                        <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($c_phone) ?></p>
                        <p class="mb-0"><strong>Email:</strong> <?= htmlspecialchars($c_email) ?></p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar Widgets -->
            <div class="col-lg-4 reveal-right">
                <div class="sidebar-widgets position-sticky" style="top: 100px;">
                    
                    <div class="widget-box bg-white p-4 rounded shadow-sm border mb-4" style="border-top: 3px solid #D4AF37 !important;">
                        <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">Search Products</h5>
                        <form action="products.php" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2 bg-light" placeholder="Search..." required style="font-size: 0.9rem; border: 1px solid #e0e0e0;">
                            <button type="submit" class="btn text-white px-3" style="background: #0B4619;"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <div class="widget-box bg-white p-4 rounded shadow-sm border mb-4" style="border-top: 3px solid #D4AF37 !important;">
                        <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">Categories</h5>
                        <ul class="list-unstyled mb-0 category-list">
                            <?php
                            $catQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1");
                            if ($catQuery && mysqli_num_rows($catQuery) > 0) {
                                while($catRow = mysqli_fetch_assoc($catQuery)) {
                            ?>
                            <li class="mb-2 pb-2 border-bottom">
                                <a href="category.php?slug=<?= htmlspecialchars($catRow['slug_url']) ?>" class="text-decoration-none d-flex justify-content-between align-items-center text-muted" style="font-size: 0.95rem; transition: color 0.3s;">
                                    <span class="cat-hover-text"><i class="bi bi-chevron-right text-gold me-2" style="font-size: 0.75rem;"></i> <?= htmlspecialchars($catRow['categories']) ?></span>
                                </a>
                            </li>
                            <?php 
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="widget-box rounded shadow-sm text-center p-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0B4619 0%, #062b0f 100%);">
                        <div class="position-relative z-1">
                            <div class="icon-wrap mb-3"><i class="bi bi-headset fs-1 text-gold"></i></div>
                            <h4 class="fw-bold mb-2 text-white h5">Bulk Export Inquiry?</h4>
                            <p class="small mb-4 opacity-75">Get premium quality export-grade commodities at wholesale prices.</p>
                            <a href="contact.php" class="btn w-100 fw-bold shadow-sm mb-3 text-uppercase" style="background-color: #D4AF37; color: #1A1A1A;">Request Quote</a>
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $c_phone) ?>" class="text-white text-decoration-none small fw-bold"><i class="bi bi-telephone-fill me-1 text-gold"></i> <?= htmlspecialchars($c_phone) ?></a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Include global scroll animations script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".reveal-left, .reveal-right");
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

<?php 
include ('includes/inquiry-form.php');
include ('includes/footer.php'); 
?>