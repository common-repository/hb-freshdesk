// JavaScript Document

    function DisplayNewTicket() {
        jQuery("#add-new-ticket").show();
        jQuery(".add-new-ticket-class").hide();
    }
    
    function HideNewTicketDiv() {
        jQuery("#add-new-ticket").hide();
        jQuery(".add-new-ticket-class").show();
    }
    

 /// Create a New ticket ///
    function create_ticket() {
		jQuery(".ticket-response").hide();
	   
        var email = jQuery('#email').val();    
        var subject = jQuery('#subject').val();
        
        var ticket_type = jQuery('#ticket_type').val();    
        var ticket_status = 2;    
        var ticket_priority = jQuery('#ticket_priority').val(); 
        var description = jQuery('#description').val(); 
        
        if(subject  == '' || email == "" || description == ""){
            alert("Please fill all * field!");
            return false;
        }
        
        var data_array = {email:email, subject:subject, type:ticket_type, status:ticket_status, priority:ticket_priority, description:description};
        var data = {
            action: 'CreateNewTicket',
            data_arr:data_array
        };
        jQuery.post(hb_ajax_object.ajax_url, data, function(data){
			if(data == 400){
				jQuery(".ticket-response").empty();
				jQuery(".ticket-response").show();
				jQuery(".ticket-response").css("border", "1px solid #ff0000");
				jQuery(".ticket-response").append("<span style='color: #ff0000	;'><b>Error:</b> Please enter a valid Email address.</span>");
			}else if(data == 201){
				jQuery('#email').val("");
				jQuery('#subject').val("");
				jQuery('#description').val("");
				
				jQuery(".ticket-response").empty();
				jQuery(".ticket-response").show();
				jQuery(".ticket-response").css("border", "1px solid #28a745");
				jQuery(".ticket-response").append("<span style='color: #28a745;'> Your ticket has been created.</span>");
			}else{
				location.reload();
			}
        });   
    }
    
    
    jQuery( document ).ready(function() {
        var data = {
			action: 'ajaxDataSave',
			data:{}
		};
        jQuery.post(hb_ajax_object.ajax_url, data, function(data){
			//location.reload();    
        }); 
    });