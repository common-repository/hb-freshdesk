<div class="main-wrap">
    <h1 class="page-heading"> </h1>
    <?php 
        include_once('admin-settings.php');
        $FreshDeskSettingsPage = new FreshDeskSettingsPage();
        
        $allFaqData = $FreshDeskSettingsPage->FaqPageFront();
//        echo "<pre>";
//        print_r($allFaqData);
//        echo "</pre>";
        die();
      
      ?>    
    
    
</div>