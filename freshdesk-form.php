<div class="main-wrap">
    <h1> Freshdesk Settings </h1>
        <div class="tabset">
          <!-- Tab 1 -->
          <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
          <label class="tab-label" for="tab1">General Setting</label>
          
          <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
          <label class="tab-label" for="tab2">Short Code</label>
<!--            Tab 3 
          <input type="radio" name="tabset" id="tab3" aria-controls="dunkles">
          <label for="tab3">Dunkles Bock</label>-->

          <div class="tab-panels">
            <section id="tab1" class="tab-panel">
                <div class="section-div">
                    <p class="section-heading">Connecting your freshdesk account with your WordPress here.</p>
                    <form method="post" action="" id="general-setting-form">
                        <?php $content = get_option('freshdesk_connection_detail'); ?>
                        <div class="field-class">
                            <div class="form-label-main">
                                <label class="form-label">Freshdesk Domain URL </label>  
                            </div>
                            https://<input autocomplete="off" id="freshdesk_url" name="fd_apikey[freshdesk_url]" value="<?php echo $content['freshdesk_url']?>" class="regular-text" placeholder="Ex: your_domain_name" type="text">.freshdesk.com/
                        </div>
                        
                        <div class="field-class">
                            <div class="form-label-main">
                                <label class="form-label">Select Method of Authentication </label>
                            </div>
                            <select id="use_apikey" name="fd_apikey[use_apikey]" onchange="select_method(this.value);">
                                <option value="api" <?php if ($content['freshdesk_apikey'] != ''){echo "selected";}?>>API</option>
                                <option value="user_pass" <?php if ($content['api_username'] != '' && $content['api_pwd'] != '' ){echo "selected";}?>>Username / Password</option>
                            </select>
                        </div>
                        
                        <div id="api-key" class="field-class">
                            <div class="form-label-main">
                                <label class="form-label">API Key</label> 
                            </div>
                            <input autocomplete="off" id="freshdesk_apikey" name="fd_apikey[freshdesk_apikey]" value="<?php echo $content['freshdesk_apikey']?>" class="regular-text" type="text">
                        </div>
                        
                        <div id="user-pass" class="field-class" style="display: none;">
                            <div class="field-sub-class">
                                <div class="form-label-main">
                                    <label class="form-label">Username</label> 
                                </div>
                                <input autocomplete="off" placeholder="Username" id="api_username" name="fd_apikey[api_username]" value="<?php echo $content['api_username']?>" class="regular-text" type="text">
                            </div>
                            <div class="field-sub-class">
                                <div class="form-label-main">
                                    <label class="form-label">Password</label>
                                </div>
                                <input autocomplete="off" placeholder="Password" id="api_pwd" name="fd_apikey[api_pwd]" class="regular-text" value="<?php echo $content['api_pwd']?>" type="password">
                            </div>    
                        </div>
                        <input onclick="gsetting_submit()" type="button" value="Save Changes" class="button button-primary">
                    </form>
                    <div id="message"></div>
                </div>
            </section>
           <section id="rauchbier" class="tab-panel">
               <h3> 1) Create a new Ticket in Freshdesk Account </h3>
               <div class="shortcode-div">
                   <div class="shortcode-sub-div">Place shortcode <b> [freshdesk-create-ticket] </b> in wordpress page, post or text widget.</div>
                    <div class="shortcode-sub-div">Place the code <b> <?php echo "do_shortcode('[freshdesk-create-ticket]')" ?> </b> in template files.</div>
               </div>
               <h3> 2) Get all Ticket in Freshdesk Account </h3>
               <div class="shortcode-div">
                    <div class="shortcode-sub-div">Place shortcode <b> [get_all_ticket] </b> in wordpress page, post or text widget.</div>
                    <div class="shortcode-sub-div">Place the code <b> <?php echo "do_shortcode('[get_all_ticket]')" ?> </b> in template files.</div>
               </div>
                <h3> 3) Display FAQ'S in Freshdesk Account </h3>
               <div class="shortcode-div">
                    <div class="shortcode-sub-div">Place shortcode <b> [freshdesk-faq] </b> in wordpress page, post or text widget.</div>
                    <div class="shortcode-sub-div">Place the code <b> <?php echo "do_shortcode('[freshdesk-faq]')" ?> </b> in template files.</div>
               </div>
            </section>
      <!--       <section id="dunkles" class="tab-panel">
              <h2>6C. Dunkles Bock</h2>
              <p><strong>Overall Impression:</strong> A dark, strong, malty German lager beer that emphasizes the malty-rich and somewhat toasty qualities of continental malts without being sweet in the finish.</p>
              <p><strong>History:</strong> Originated in the Northern German city of Einbeck, which was a brewing center and popular exporter in the days of the Hanseatic League (14th to 17th century). Recreated in Munich starting in the 17th century. The name “bock” is based on a corruption of the name “Einbeck” in the Bavarian dialect, and was thus only used after the beer came to Munich. “Bock” also means “Ram” in German, and is often used in logos and advertisements.</p>
            </section>-->
          </div>

        </div>
</div>