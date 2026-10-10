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

$pageTitle = "Terms & Conditions | Maruti Agro Industries";
$meta_description = "Read the terms and conditions of Maruti Agro Industries for purchasing our export-quality agricultural products.";
$meta_key = "terms and conditions, maruti agro industries, export terms, jeera, rice, wheat supplier";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-8 reveal-left">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border" style="border-color: #f0f0f0 !important;">
                    <h2 class="mb-4 h3" style="color: #0B4619; font-weight: 700;">Terms & Conditions</h2>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">1. Introduction</h4>
                    <p class="text-muted">Welcome to <strong>Maruti Agro Industries</strong>. By browsing our website and purchasing our agricultural commodities—including Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat—you agree to abide by these Terms & Conditions.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">2. Natural Variations in Products</h4>
                    <p class="text-muted">We deal in natural agricultural produce. Minor variations in color, size, aroma, or texture may occur between harvest batches, which are natural characteristics and not defects.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">3. Pricing and Import Duties</h4>
                    <p class="text-muted">Domestic prices include applicable taxes unless specified. For international shipments, the buyer is solely responsible for all destination customs duties, import taxes, and local levies.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">4. Culinary Disclaimer</h4>
                    <p class="text-muted">Information on our website regarding product benefits is for general culinary and informational purposes and does not substitute medical advice.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">5. Governing Law</h4>
                    <p class="text-muted">Any disputes arising from purchases or website usage shall be governed by the laws of India and subject to local jurisdiction.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">6. Contact Information</h4>
                    <div class="p-4 mt-3 rounded" style="background-color: rgba(11, 70, 25, 0.05); border-left: 4px solid #0B4619;">
                        <p class="mb-1"><strong>Maruti Agro Industries</strong></p>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($c_email) ?></p>
                        <p class="mb-0"><strong>Phone:</strong> <?= htmlspecialchars($c_phone) ?></p>
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
                            <h4 class="fw-bold mb-2 text-white h5">Wholesale Inquiries</h4>
                            <p class="small mb-4 opacity-75">Get in touch for bulk orders and export pricing.</p>
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
include 'includes/footer.php'; 
?>