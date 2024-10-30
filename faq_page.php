<div class="main-wrap">
    <h1 class="page-heading"> FreshDesk Faq </h1>
    <?php 
		include_once('admin-settings.php');
        $FreshDeskSettingsPage = new FreshDeskSettingsPage();
      
        $allFaqData = $FreshDeskSettingsPage->create_faq_page();
  
        global $wpdb;
        $table = $wpdb->prefix . 'freshdesk';
        
        
    ?>    
    <?php if (!empty($allFaqData)) {?>
    <div class="content-class">
        <form method="post" action="" id="display-faq-form">
            <?php $i = 0; foreach ($allFaqData as $_allFaqData){
                $category_id = $_allFaqData['categorydata']['category_id'];
                $category_name = $_allFaqData['categorydata']['category_name'];
                $Display_cat = $wpdb->get_results("SELECT * FROM ". $table ." WHERE display = 'yes' AND category_id ='".$category_id."' GROUP by category_id");
                  ?>
                <div class="faq-class">
                    
                    <input id="<?php echo $category_id; ?>" class="category_id" type="checkbox" value="<?php echo $category_id; ?>" name="display_cat[]" <?php if($Display_cat[0]->category_id == $category_id ){echo "checked";} ?>><span class="main-cat-name"><?php echo $category_name; ?></span>
                    <?php 
                    if(count($_allFaqData['details']) > 0)
                    {
                        foreach ($_allFaqData['details'] as $detailData)
                        {
                            $folder_name = $detailData['folderdetail']['folder_name'];
                            ?>
                            <div class="sub-cat-div">
                                <span class="sub-cat-name">- <?php echo $folder_name; ?></span>
                                <?php //foreach ($detailData['articledata'] as $articledata){ ?>
    <!--                            <div class="q-a-list">
                                    -- <a href="<?php echo $articledata['id'];?>"><?php echo $articledata['title']; ?></a>
                                </div>-->
                                <?php //} ?>
                        </div>
                        <?php 
                        } 
                    } ?>
                </div>
            <?php $i++ ;} ?>
            <div class="category_diaplay_button">
                <input onclick="displayfaq_submit()" type="button" value="Save Changes" class="button button-primary">
            </div>
        </form>
        <div id="loader"></div>
    </div>
    <?php } ?>
</div>
