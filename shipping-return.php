<?php
include('config/connect.php');

$c_phone = "+910000000000";
if (isset($conn)) {
    $contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
    if ($contact_query && mysqli_num_rows($contact_query) > 0) {
        $c_info = mysqli_fetch_assoc($contact_query);
        $c_phone = !empty($c_info['phone']) ? $c_info['phone'] : $c_phone;
    }
}

$pageTitle = "Shipping and Delivery Policy | Maruti Agro Industries";
$meta_description = "Learn about domestic and international shipping options for premium commodities like Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat by Maruti Agro Industries.";
$meta_key = "shipping policy, delivery policy, maruti agro industries, export delivery";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-8 reveal-left">
                <div class="bg-white p-4 p-md-5 rounded shadow-sm border" style="border-color: #f0f0f0 !important;">
                    <h2 class="mb-4 h3" style="color: #0B4619; font-weight: 700;">Shipping and Delivery Policy</h2>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>
                    
                    <h3 class="mt-5 mb-3 h4" style="color: #D4AF37; font-weight: 700;">Shipping Policy</h3>
                    
                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">1. Order Processing & Dispatch</h4>
                    <p class="text-muted">Standard orders of Jeera, Dhaniya, Chana, Bajra, Rice, and Wheat are processed and dispatched within <strong>3 to 5 business days</strong>. Bulk wholesale and international container exports follow custom schedules based on documentation and phytosanitary processing.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">2. Domestic Delivery (Within India)</h4>
                    <p class="text-muted">Standard delivery across India takes approximately <strong>5 to 7 business days</strong> depending on the delivery location and freight partner.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">3. International Shipping & Exports</h4>
                    <p class="text-muted">International shipments are handled via air or sea freight. <strong>Note:</strong> The buyer is fully responsible for destination country customs duties, import clearance, and local taxes. Maruti Agro Industries provides all mandatory Indian export compliance documentation.</p>

                    <hr class="my-5" style="border-color: #e0e0e0;">

                    <h3 class="mb-3 h4" style="color: #D4AF37; font-weight: 700;">Returns Policy</h3>
                    
                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">1. Non-Returnable Goods</h4>
                    <p class="text-muted">Due to stringent food safety and agricultural standards, <strong>we do not accept standard returns</strong> on consumable items once shipped.</p>

                    <h4 class="mt-4 h6" style="color: #0B4619; font-weight: 700;">2. Damaged or Incorrect Shipments</h4>
                    <p class="text-muted">Exceptions apply strictly if you receive wrong or transit-damaged items. Claims must be filed within <strong>48 hours of receipt</strong> along with an unboxing video/photos.</p>
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
                            <p class="small mb-4 opacity-75">Get reliable global shipping for wholesale commodities.</p>
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