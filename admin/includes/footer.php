<?php if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] == "demo@internetprofis.at"): ?>
    <div class="fixed-bottom bg-danger text-white d-flex align-items-center justify-content-center shadow-lg" 
         style="height: 50px; z-index: 9999; font-size: 15px; font-weight: 500;">
        <div class="container text-center">
            <i class="fa fa-exclamation-triangle mr-2"></i> 
            <strong>Demo Mode:</strong> Saving, updating or deleting is disabled.
        </div>
    </div>
    
    <style>
        /* Schiebt den Inhalt hoch, damit nichts vom roten Balken verdeckt wird */
        body { padding-bottom: 50px !important; }
        /* Falls du einen "Back to Top" Button hast, schieben wir den auch hoch */
        .back-to-top { bottom: 60px !important; }
    </style>
<?php endif; ?>