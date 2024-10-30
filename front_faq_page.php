<div class="container main-wrap">
    <h1 class="page-heading"> </h1>
    <?php 
        include_once('admin-settings.php');
        global $wpdb;
        $table = $wpdb->prefix . 'freshdesk';
        
        $FreshDeskSettingsPage = new FreshDeskSettingsPage();
        //$generateSeoURL = $FreshDeskSettingsPage->generateSeoURL();
        
        $allFaqDataFront = $wpdb->get_results("SELECT * FROM ". $table ." WHERE display = 'yes'");      
      
        $TempArrayout=array();
        if(count($allFaqDataFront) > 0)
        {
            foreach($allFaqDataFront as $_allFaqDataFront)
            {
                $TempArrayout[$_allFaqDataFront->category_id]['category_id']= $_allFaqDataFront->category_id;
                $TempArrayout[$_allFaqDataFront->category_id]['category_name']= $_allFaqDataFront->category_name;
            }
        }

        $siteUrl = get_permalink();
            
        $category_id = filter_var($_GET['category_id'], FILTER_SANITIZE_STRING);
        $folder_id = filter_var($_GET['folder_id'], FILTER_SANITIZE_STRING);
        $article_id = filter_var($_GET['article_id'], FILTER_SANITIZE_STRING);
        
        $folder_data = isset($category_id) ? $category_id : '';
        $article_data = isset($folder_id) ? $folder_id : '';
        $single_article = isset($article_id) ? $article_id : '';
        
      ?>    
    
    <div class="content-class">
        <!-- Folder list Data -->
        <?php
            if(isset($folder_data) && !empty($folder_data)) {
                $folders = $wpdb->get_results("SELECT * FROM ". $table ." WHERE category_id  = '". $folder_data ."' GROUP BY folder_id" ) ;
            ?>
                <div class="section-div-main">
                    <div class="section-div-sub">   
                        <?php 
                        if(count($folders) > 0)
                        {
                            foreach($folders as $_folders) 
                            { 
                                $postTitle = $_folders->folder_name;
                                $seoFriendlyURL = $FreshDeskSettingsPage->generateSeoURL($postTitle);
                                ?>    
                            <div class="freshdesk-list">
                                <a href="<?php echo $siteUrl?>?folder_id=<?php echo $_folders->folder_id; ?>&title=<?php echo $seoFriendlyURL;?> ">
                                    <?php echo $_folders->folder_name?>
                                </a>
                            </div>
                            <?php 
                            }
                        } ?>
                    </div>
                    <div class="back-button">
                        <button onclick="window.history.back()">Go Back</button>
                    </div>
                </div>
            <!-- Article List Data --> 
            <?php } else if(isset($article_data) && !empty($article_data)){
                $articles = $wpdb->get_results("SELECT * FROM ". $table ." WHERE folder_id  = '". $article_data ."'" ) ;
            ?>
                <div class="section-div-main">
                    <div class="section-div-sub">
                        <?php 
                        if(count($articles) > 0)
                        {
                            foreach($articles as $_articles)
                            { 
                                $postTitle = $_articles->title;
                                $seoFriendlyURL = $FreshDeskSettingsPage->generateSeoURL($postTitle);
                                ?> 
                                <div class="freshdesk-list">
                                    <a href="<?php echo $siteUrl?>?article_id=<?php echo $_articles->articles_id; ?>&title=<?php echo $seoFriendlyURL;?>">
                                        <?php echo $_articles->title?>
                                    </a>
                                </div>
                            <?php 
                            } 
                        }?>
                    </div>
                    <div class="back-button">
                        <button onclick="window.history.back()">Go Back</button>
                    </div>
                </div> 

            <!-- Single Article Data -->

            <?php } else if(isset($single_article) && !empty($single_article)){
                $SingleArticleData = $wpdb->get_results("SELECT * FROM ". $table ." WHERE articles_id  = '". $single_article ."'" ) ;

                $title = isset($SingleArticleData[0]->title) ? $SingleArticleData[0]->title : '';
                $description = isset($SingleArticleData[0]->description) ? $SingleArticleData[0]->description : '';
            ?>
                <div class="section-div-main">
                    <div class="section-div-sub">
                        <div class="freshdesk-list">
                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                    <div class="back-button">
                        <button onclick="window.history.back()">Go Back</button>
                    </div>
                </div>

            <!-- Category data list -->   
            <?php } else { ?>
            <div class="section-div-main">
                <?php 
                    if(!empty($TempArrayout)){
                        foreach ($TempArrayout as $_TempArrayout) { 
                            $postTitle = $_TempArrayout['category_name'];
                            $seoFriendlyURL = $FreshDeskSettingsPage->generateSeoURL($postTitle);
                            ?>
                        <div class="freshdesk-list">
                            <a href="<?php echo $siteUrl?>?category_id=<?php echo $_TempArrayout['category_id']; ?>&title=<?php echo $seoFriendlyURL;?>">
                                <?php echo $_TempArrayout['category_name']; ?>
                            </a>
                        </div>
                    <?php }
                } else { ?>
                        <div class="freshdesk-list">
                            Select FAQ category.
                        </div>
                <?php } ?>
            </div>
            <?php } ?>
    </div>
</div>