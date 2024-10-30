// JavaScript Document

    function select_method(method_name) {
      
        if( method_name == 'api' ) {
            jQuery( "#api-key" ).show();
            jQuery( "#user-pass" ).hide();
        } else {
                jQuery( "#api-key" ).hide();
                jQuery( "#user-pass" ).show();
        }        
    }
    
    // General Setting activation //
    
    function gsetting_submit() {
        
        var freshdesk_url = jQuery('#freshdesk_url').val();    
        var use_apikey = jQuery('#use_apikey').val();    
        var freshdesk_apikey = jQuery('#freshdesk_apikey').val();    
        var api_username = jQuery('#api_username').val();    
        var api_pwd = jQuery('#api_pwd').val();    
//            var data_arr = { freshdesk_url:freshdesk_url, freshdesk_apikey:freshdesk_apikey,api_username:api_username,api_pwd:api_pwd};

        var data = {
            action: 'FreshdeskConnection',
            data_arr:{ freshdesk_url:freshdesk_url,use_apikey:use_apikey, freshdesk_apikey:freshdesk_apikey,api_username:api_username,api_pwd:api_pwd}
        };
        
        jQuery.post(ajaxurl, data, function(data){
            
            if(data == "0") {
                 jQuery("#message").text("Invalid credentials!").css("color","red");
            } else if (data == "1") {
                jQuery("#message").text("Connection successfully established in your Freshdesk account !").css("color","green");
            }
        });   

    }
    
    // Display FAQ in front side //
    
    function displayfaq_submit() {
        var chkArray = [];
        var spinner = jQuery('#loader');
	jQuery(".category_id:checked").each(function() {
		chkArray.push(jQuery(this).val());
	});
	var selected_cat = chkArray.join(',') ;
        spinner.show();
        var data = {
                        action: 'DisplayFaq',
                        data:{selected_cat:selected_cat}
                    };
        jQuery.post(ajaxurl, data, function(data){
            spinner.hide();
          location.reload();    
        }); 
    }
    
   jQuery( document ).ready(function() {
        var data = {
                        action: 'ajaxDataSave',
                        data:{}
                    };
          jQuery.post(ajaxurl, data, function(data){
//          location.reload();    
        }); 
    
    });