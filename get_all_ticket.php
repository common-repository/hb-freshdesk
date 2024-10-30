<div class="container main-wrap">
<?php
//Silence is golden 
include_once('admin-settings.php');
    $FreshDeskSettingsPage = new FreshDeskSettingsPage();
    $all_ticket_url = "tickets";
    $FreshDeskSettingsPage->curl_api_function($all_ticket_url, "GET", "");
    $GetAllTicket = $FreshDeskSettingsPage->curl_api_function($all_ticket_url);
    $siteUrl = get_permalink();
    $get_ticket_id = filter_var($_GET['ticket_id'], FILTER_SANITIZE_STRING);
    $ticket_data = isset($get_ticket_id) ? $get_ticket_id : '';   
    if(isset($ticket_data) && !empty($ticket_data)) {
        $get_ticket_url = "tickets/".$ticket_data.'?include=conversations';
        $FreshDeskSettingsPage->curl_api_function($get_ticket_url, "GET", "");
        $TicketData = $FreshDeskSettingsPage->curl_api_function($get_ticket_url);
    }
?>
    <div class="content-class">
        <input type="hidden" id="current_url" value="<?php echo $siteUrl;?>">
        <?php if(isset($TicketData) && !empty($TicketData)) { 			if(isset($TicketData['response']['code']) && $TicketData['response']['code'] == 200){
            $TicketData_array = json_decode($TicketData['body'], true);
            ?>
                <div class="ticket_main">
                    <div class="ticket_subject">
                        <label class="ticket_heading">Subject :</label>
                        <div class="ticket_content">
                            <?php echo $TicketData_array['subject']; ?>
                        </div>
                    </div>
                    <div class="ticket_desc">
                        <label class="ticket_heading">Description :</label> 
                        <div class="ticket_content">
                            <?php echo $TicketData_array['description']; ?>
                        </div>
                    </div>
                   <div class="ticket_conversations">
                       <label class="ticket_heading">Conversations :</label>
                        <?php 
                            $ConversationArray = $TicketData_array['conversations'];
                            if(!empty($ConversationArray)){
                                foreach ($ConversationArray as $_ConversationArray) { ?>
                                <div class="ticket_content">
                                    <div class="ticket_con_body">
                                        <label class="ticket_heading">Body :</label>
                                        <div class="ticket_content">
                                            <?php echo $_ConversationArray['body'];?>
                                        </div>
                                    </div>
<!--                                    <div class="ticket_con_body">
                                        <label class="ticket_heading">Body Text :</label> 
                                        <div class="ticket_content">
                                            <?php echo $_ConversationArray['body_text'];?>
                                        </div>
                                    </div>-->
                                    <div class="ticket_con_body">
                                        <label class="ticket_heading">Support Email :</label> 
                                        <div class="ticket_content">
                                            <?php echo $_ConversationArray['support_email'];?>
                                        </div>
                                    </div> 
                                    <div class="ticket_con_body">
                                        <label class="ticket_heading">From Email :</label> 
                                        <div class="ticket_content">
                                            <?php echo $_ConversationArray['from_email'];?>
                                        </div>
                                    </div>
                                </div>
                        <?php } } else { echo "No Conversations";} ?>
                    </div>
                    <div class="back-button">
                        <button onclick="window.history.back()">Go Back</button>
                    </div>
                </div>
			<?php } ?> 
        <?php } else {
            $GetAllTicket_Array = json_decode($GetAllTicket['body'], true);			if($GetAllTicket_Array['code'] != "invalid_credentials"){				
            foreach ($GetAllTicket_Array as $_GetAllTicket) { ?>
            <div>
                <!--<span onclick="single_ticket(<?php //echo $_GetAllTicket->id; ?>)"><?php //echo $_GetAllTicket->subject; ?></span>-->
                <a href="<?php echo $siteUrl?>?ticket_id=<?php echo $_GetAllTicket['id'];?>"><?php echo $_GetAllTicket['subject']; ?></a>
            </div>
        <?php } } } ?>
    </div>
</div>