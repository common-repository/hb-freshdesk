<?php
/*
 Plugin Name:HB Freshdesk
 Description: This plugin is used for connect your FreshDesk account in your wordpress site. You can easily to get your FreshDesk FAQs and Ticket, And also create a new ticket in your FreshDesk account directly using this plugin, And also check your ticket conversation.
 Version: 3.3
 Author: Hiren Patel ,Hike Branding
 Author URI: https://www.hikebranding.com/
 License: GPLv2 or later
*/

defined( 'ABSPATH' ) or die();

if(!class_exists("HBFreshDeskAPI")){
    class HBFreshDeskAPI{
         private $options;
         
        function __construct() {
            include_once( 'admin-settings.php' );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }
         
        /*
            * Function Name: enqueue_scripts
            * Function Description: Adds scripts to wp pages
        */

        function enqueue_scripts() {
            
            wp_register_style( 'fd-style_front', plugins_url('css/fd-style.css', __FILE__) );
            wp_enqueue_style( 'fd-style_front' );
            
            wp_register_script( 'fd-script-frontend', plugins_url('js/fd-script-frontend.js', __FILE__), array('jquery'));
            wp_enqueue_script( 'fd-script-frontend' );
			
            wp_localize_script('fd-script-frontend', 'hb_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        }
    }
        
    function hbfreshdesk_plugin_options_install() {
   	global $wpdb;
  	$table = $wpdb->prefix . 'freshdesk';
	if($wpdb->get_var("show tables like '$table'") != $table)
	{		
            $sql = "CREATE TABLE " . $table . " (
            `id` bigint(20)  NOT NULL AUTO_INCREMENT,
            `freshdesk_url` text COLLATE utf8mb4_unicode_ci,
            `category_id`  text COLLATE utf8mb4_unicode_ci,
            `category_name`  text COLLATE utf8mb4_unicode_ci,
            `folder_id`  text COLLATE utf8mb4_unicode_ci,
            `folder_name`  text COLLATE utf8mb4_unicode_ci,
            `articles_id` bigint(20),
            `title` text COLLATE utf8mb4_unicode_ci,
            `description` longtext COLLATE utf8mb4_unicode_ci,
            `display` text COLLATE utf8mb4_unicode_ci,
            `status` text COLLATE utf8mb4_unicode_ci,
            FULLTEXT index (title),
            PRIMARY KEY  (id)
            ) ENGINE=InnoDB ". $wpdb->get_charset_collate() ."";

            require(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
	}
    }    
    register_activation_hook(__FILE__,'hbfreshdesk_plugin_options_install');    
}
new HBFreshDeskAPI();
?>