<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
   exit('Please don\'t access this file directly.');
}
class FreshDeskSettingsPage{

    private $options;
	private $url_options;
	private $url_val;

    /** * Start up */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'fdenqueue_admin_css_js' ) );
        
        
        // Add Action Ajax call
        add_action( 'wp_ajax_FreshdeskConnection', array($this,'FreshdeskConnection'));
        add_action('wp_ajax_DisplayFaq',  array($this,'DisplayFaq'));
        add_action('wp_ajax_ajaxDataSave',  array($this,'ajaxDataSave'));
        add_action('wp_ajax_CreateNewTicket',  array($this,'CreateNewTicket'));
        add_action('wp_ajax_SingleTicketData',  array($this,'SingleTicketData'));
        
        add_action('wp_ajax_nopriv_DisplayFaq',  array($this,'DisplayFaq'));
        add_action('wp_ajax_nopriv_ajaxDataSave',  array($this,'ajaxDataSave'));
        add_action('wp_ajax_nopriv_CreateNewTicket',  array($this,'CreateNewTicket'));
        add_action('wp_ajax_nopriv_SingleTicketData',  array($this,'SingleTicketData'));
        
    }

    

    /* Add options page */
    public function add_plugin_page() {

        // Create a new menu  "Freshdesk"
        add_menu_page(
            'Admin Settings', 
            'Freshdesk', 
            'manage_options', 
            'freshdesk', 
            array( $this, 'create_admin_page' )
        );
       
        add_submenu_page(
                'freshdesk',
                'FAQ', 
                'FAQ',
                'manage_options', 
                'faq', 
                array( $this, 'create_faq_page' )
        );
    }
    
    /* Options page callback */
    public function create_admin_page() {
        include_once( 'freshdesk-form.php' );
        
    }
    
    
     /** * Enqueue CSS and JS on WordPress admin pages of WP Freshdesk */
    function fdenqueue_admin_css_js( $hook ) {

        // Load only on WP Freshdesk plugin pages

        wp_enqueue_script( 'fd-script_admin', plugins_url('js/fd-script.js', __FILE__), array('jquery'));
        wp_register_style( 'fd-style_admin', plugins_url('css/fd-style.css', __FILE__));
        wp_enqueue_style( 'fd-style_admin' );
        
      // Admin Ajax   
        wp_localize_script('ajax_url', 'the_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
        
    }
   
    
   // Start : Fresh Desk Connection // 
    function FreshdeskConnection() { 
        
        $data_arr = filter_var_array($_POST['data_arr'],FILTER_SANITIZE_STRING);
       
        if(isset($_POST) && $data_arr != ''){
            update_option('freshdesk_connection_detail', $data_arr);
        }

        $freshdesk_url = filter_var($_POST['data_arr']['freshdesk_url'], FILTER_SANITIZE_STRING);
        $use_apikey = filter_var($_POST['data_arr']['use_apikey'], FILTER_SANITIZE_STRING);
        $freshdesk_apikey = filter_var($_POST['data_arr']['freshdesk_apikey'], FILTER_SANITIZE_STRING);
        $api_username = filter_var($_POST['data_arr']['api_username'], FILTER_SANITIZE_STRING);
        $api_pwd = filter_var($_POST['data_arr']['api_pwd'], FILTER_SANITIZE_STRING);


        if( $use_apikey == "api" ){
                $apikey = ( $freshdesk_apikey != '' ) ? $freshdesk_apikey : '';
                $password = "";
        } else {
                $apikey = ( $api_username != '' ) ? $api_username : '';
                $password = ( $api_pwd != '' ) ? $api_pwd : '';
        }
        
        $urltopost = 'https://'.$freshdesk_url.'.freshdesk.com/api/v2/contacts';

        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( $apikey . ':' . $password )
              )
        );

        $response = wp_remote_get( $urltopost, $args );
     
        if ($response['response']['message'] != "OK") {
            echo "0";
        }else {
			echo "1";
        }
        exit();
    }
    // End : Fresh Desk Connection //
    
    
    // Start : Comman CURL Function //
    public function curl_api_function ($url, $method='GET', $data='') {
       
        $content = get_option('freshdesk_connection_detail');

        $freshdesk_url = $content['freshdesk_url'];
        $use_apikey = $content['use_apikey'];
        $freshdesk_apikey = $content['freshdesk_apikey'];
        $api_username = $content['api_username'];
        $api_pwd = $content['api_pwd'];

        if( $use_apikey == "api" ){
                $apikey = ( $freshdesk_apikey != '' ) ? $freshdesk_apikey : '';
                $password = "";
        } else {
                $apikey = ( $api_username != '' ) ? $api_username : '';
                $password = ( $api_pwd != '' ) ? $api_pwd : '';
        }

        $urltopost = 'https://'.$freshdesk_url.'.freshdesk.com/api/v2/'.$url;

        if($method == "POST") {
            $response = wp_remote_post( $urltopost, array(
					'method' => 'POST',
					'headers' => array( 
						'Authorization' => 'Basic ' . base64_encode( $apikey . ':' . $password ),
					'Content-Type' => 'application/json; charset=utf-8'),
					'body' => $data				
				)
			);
        }else{        
			$args = array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $apikey . ':' . $password )
				  )
			);		
			$response = wp_remote_get( $urltopost, $args );
        }
        return $response;
        exit();
    }
    
    // End : Comman CURL Function //
  
//*****************************************//
//******* Start : FAQ page Function *******//
//*****************************************//    
    
    
    // Start : Admin Side FAQ diaplay //
    public function create_faq_page() {
        
        include_once( 'faq_page.php' );

        global $wpdb;
		$table = $wpdb->prefix . 'freshdesk';

        $AllArticleData = $wpdb->get_results( "SELECT * FROM ". $table ."" );
        
        $TempArrayout = array();
        if(count($AllArticleData) > 0)
        {
            foreach ($AllArticleData as $_AllArticleData) {
                $TempArrayout[$_AllArticleData->category_id]['categorydata']= array('category_id'=>$_AllArticleData->category_id,'category_name'=>$_AllArticleData->category_name);
                $TempArrayout[$_AllArticleData->category_id]['details'][$_AllArticleData->folder_id]['folderdetail']=array('folder_id'=>$_AllArticleData->folder_id,'folder_name'=>$_AllArticleData->folder_name);
                $TempArrayout[$_AllArticleData->category_id]['details'][$_AllArticleData->folder_id]['articledata'][]=array('folder_id'=>$_AllArticleData->folder_id,'articles_id'=>$_AllArticleData->articles_id,'title'=>$_AllArticleData->title,'description'=>$_AllArticleData->description);
            }
        }
        return $TempArrayout;
        exit();  
    }
    
    // End : Admin Side FAQ diaplay //
   
    // Start : Allow Display Front Side FAQ. //
    function DisplayFaq() {

        global $wpdb;
	    $table = $wpdb->prefix . 'freshdesk';
        
        $selected_data = filter_var_array($_POST['data'],FILTER_SANITIZE_STRING);
        if(count($selected_data) > 0)
        {
            foreach ($selected_data as $key => $value){
                $category_id = explode(',',$value);
            }
        }

        $content = get_option('freshdesk_connection_detail');

        $freshdesk_url = $content['freshdesk_url'];
        $display_blank = '';
        $wpdb->query( $wpdb->prepare( "UPDATE $table set display = %s WHERE freshdesk_url = %s", $display_blank,$freshdesk_url ) );

        if(count($category_id) > 0)
        {
            foreach ($category_id as $_category_id) {
                $display_blank = "yes";
                $wpdb->query( $wpdb->prepare( "UPDATE $table set display = %s WHERE category_id = %s", $display_blank,$_category_id ) );
                
            }
        }
        exit();
    }
    // End : Allow Display Front Side FAQ. //
    
    // Start : Create a Shortcode Display Front Side FAQ. //
    
    public static function FaqPageFront() {
         ob_start();
        include_once( 'front_faq_page.php' );
        return ob_get_clean();
    }
    
    // End : Create a Shortcode Display Front Side FAQ. //
    
    // Start : Auto Insert data in database fatch data in freshdesk account //
    function ajaxDataSave(){
       
        include_once( 'faq_page.php' );
        $cat_url = "solutions/categories";
        //$this->curl_api_function($cat_url, 'GET','');
        $categoryData = $this->curl_api_function($cat_url);
       
        $categoryData_array = json_decode($categoryData['body'], true);   
        
        if(!empty($categoryData)){
          
            global $wpdb;
            $table = $wpdb->prefix . 'freshdesk';

            $FinalFAQArray = array();
            $article = array();
            if(count($categoryData_array) > 0)
            {
                foreach ($categoryData_array as $_categoryData) {

                    $Subcate_url = "solutions/categories/".$_categoryData['id']."/folders";
                    $SubcategoryData = $this->curl_api_function($Subcate_url);
                    $categoryData1 = $this->curl_api_function($Subcate_url);
                   
                    $categoryData1_array = json_decode($categoryData1['body'], true);
                    
                    foreach ($categoryData1_array as $_categoryData1) {
                        $Faq_url = "solutions/folders/".$_categoryData1['id']."/articles";
                        $FaqData = $this->curl_api_function($Faq_url);
                        $AllFaqData = $this->curl_api_function($Faq_url);
                        $FinalFAQArray[$_categoryData['id']][]['category_name'] = $_categoryData['name']; 
                        $FinalFAQArray[$_categoryData['id']][$_categoryData1['id']][]['folder_name'] = $_categoryData1['name'];
                        $FinalFAQArray[$_categoryData['id']][$_categoryData1['id']][]['articles_data'] = $AllFaqData;
                    }
                }
            }

            $content = get_option('freshdesk_connection_detail');
            $freshdesk_url = $content['freshdesk_url'];
            $display = '';
            if(count($FinalFAQArray) > 0)
            {
                foreach ($FinalFAQArray as $key => $value) {

                    $category_id = $key; // Get Category ID
                    $category_name = isset($value[0]['category_name']) ? $value[0]['category_name'] : ''; // Get Category Name
                    if(count($value) > 0)
                    {
                        foreach ($value as $key1 => $value1) {
                            if($key1 == 0) {
                                $key1 = '';
                            } else {
                                $key1 = $key1;
                            }

                            $folder_id = $key1; // Get Folder ID
                            $folder_name = isset($value1[0]['folder_name']) ? $value1[0]['folder_name'] : ''; // Get Folder Name

                            $articles_data = isset($value1[1]['articles_data']) ? $value1[1]['articles_data'] : '';
                                
                            if($articles_data['response']['message'] == "OK"){
                                $articles_data_array = json_decode($articles_data['body'], true);
                                if(count($articles_data_array) > 0)
                                {
                                    foreach ($articles_data_array as $_articles_data) {
                                        $articleData = $wpdb->get_results( "SELECT * FROM ". $table ." WHERE articles_id  = '". $_articles_data['id'] ."'" );

                                        if($articleData[0]->articles_id == $_articles_data['id']){
                                            $wpdb->query( $wpdb->prepare( "UPDATE $table set title = %s, description = %s, status = %s WHERE articles_id = %s", $_articles_data['title'],$_articles_data['description'],$_articles_data['status'] ,$_articles_data['id'] ) );
                                            echo "Update Article!";
                                        } else {
                                                $wpdb->query($wpdb->prepare("INSERT INTO `".$table."` (`freshdesk_url`, `category_id`,`category_name`,`folder_id`,`folder_name`,`articles_id`,`title`,`description`,`display`,`status`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", $freshdesk_url, $category_id, $category_name, $folder_id, $folder_name, $_articles_data['id'] ,$_articles_data['title'], $_articles_data['description'] ,$display ,$_articles_data['status']));
                                                echo "New Article insert !!";
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo "New Article not available!!";
        } else {
            echo "No Articles are available in your Freshdesk account!";
        }
        exit();
    }
    // End : Auto Insert data in database fatch data in freshdesk account //

    
    
    
//*****************************************//
//******* End : FAQ page Function *******//
//*****************************************//    
    
//*****************************************//
//******* Start : SEO Function ************//
//*****************************************//      
    function generateSeoURL($string, $wordLimit = 0){
        $separator = '-';

        if($wordLimit != 0){
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'                    => '',
            '[^\w\d _-]'            => '',
            '\s+'                    => $separator,
            '('.$quoteSeparator.')+'=> $separator
        );

        $string = strip_tags($string);
        if(count($trans) > 0)
        {
            foreach ($trans as $key => $val){
                $string = preg_replace('#'.$key.'#i'.(''), $val, $string);
            }
        }    
        $string = strtolower($string);

        return trim(trim($string, $separator));
    }
    
//*****************************************//
//******* Start : SEO Function ************//
//*****************************************//        
    
    
    
//*****************************************//
//******* Start : Ticket page Function *******//
//*****************************************//    
    
    
    // Start : Create a Freshdesk Ticket //
    
    public static function create_ticket_page() {
        ob_start();
        include_once( 'create_ticket.php' );
        return ob_get_clean();
    }
    
    // End : Create a Freshdesk Ticket //
    
    function CreateNewTicket() {
        $data = json_encode($_POST['data_arr'],JSON_NUMERIC_CHECK);
        $new_ticket_url = "tickets";
        $results = $this->curl_api_function($new_ticket_url, "POST", $data);
        $TicketData = $this->curl_api_function($new_ticket_url);
		$response = isset($results['response']['code']) ? $results['response']['code'] : '0';
		echo $response;
        exit();
    }
    
    // Start : Get All Freshdesk Ticket //
    
    public static function get_all_ticket_page() {
        ob_start();
        include_once( 'get_all_ticket.php' );
        return ob_get_clean();
    }
    
    // End : Get All Freshdesk Ticket //
    
}

add_shortcode( 'freshdesk-faq', array( 'FreshDeskSettingsPage', 'FaqPageFront' ));
add_shortcode( 'freshdesk-create-ticket', array( 'FreshDeskSettingsPage', 'create_ticket_page' ));
add_shortcode( 'get_all_ticket', array( 'FreshDeskSettingsPage', 'get_all_ticket_page' ));

if( is_admin() )
    new FreshDeskSettingsPage();

?>