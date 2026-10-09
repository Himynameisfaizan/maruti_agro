<?php
// Agar kisi page par $pageTitle set na ho toh default title le lega
if (!isset($pageTitle)) {
    $pageTitle = "Maruti Agro Industries";
}
?>
<div class="premium-breadcrumb-wrapper">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <!-- IMPORTANT SEO FIX: Single H1 Tag for the entire page -->
                <h1 class="breadcrumb-title"><?= htmlspecialchars($pageTitle); ?></h1>
                
                <!-- Dynamic Navigation -->
                <ul class="custom-breadcrumb">
                    <li>
                        <a href="index.php"><i class="bi bi-house-door-fill text-gold"></i> Home</a>
                    </li>
                    <li class="active"><?= htmlspecialchars($pageTitle); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Premium Gold Bottom Line -->
    <div class="breadcrumb-bottom-line"></div>
</div>