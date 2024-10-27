<?php
/*
  Plugin Name: Amazon Images
  Plugin URI: http://www.nutt.net/tag/amazon-images/
  Description: Load images from Amazon.com into the media manager for a post using your Amazon Advertising API key
  Version: 0.2
  Author: Ryan Nutt
  Author URI: http://www.nutt.net
  License: GPL3
 */

/*  Copyright 2012 Ryan Nutt

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


new AmazonImages();

class AmazonImages {
    
    private $apiKey = false;
    private $apiSecret = false;
    private $affiliateTag = false; 
    
    private $defaultOptions = array(
        'apiKey' => '',
        'apiSecret' => '',
        'affiliateTag' => '',
        'imgLarge' => true,
        'imgMedium' => true,
        'imgSmall' => false,
        'imgSwatch' => false,
        'imgThumbnail' => false,
        'imgTiny' => false,
        'imageSets' => true
    );
    
    public function __construct() {
        if (!is_admin()) {
            return; 
        }

        // Filters & Actions for media manager tab
        add_filter('media_upload_tabs', array($this, 'media_tabs'));
        //add_action('admin_menu', array($this, 'admin_menu'));
        add_action('media_upload_amazon_image', array($this, 'media_handle')); 

        // Filters & Actions for options page
        add_action('admin_menu', array($this, 'add_options_menu'));
        add_action('admin_init', array($this, 'register_settings')); 
        
        add_action('wp_ajax_amazon_images', array($this, 'ajax'));
        
        wp_register_script('amazon-images', plugins_url('js/amazon-images.js', __FILE__), 'jquery'); 
        
        $opts = get_option('amazon_images', array('apiKey' => '', 'apiSecret' => '', 'affiliateTag' => ''));
        
        $this->apiKey = $opts['apiKey'];
        $this->apiSecret = $opts['apiSecret'];
        $this->affiliateTag = $opts['affiliateTag']; 
        
    }
    
    public function media_tabs($arr) {
        $arr['amazon_image'] = 'Amazon Images';
        return $arr;
    }

    
    public function media_page() {
        include(dirname(__FILE__).'/inc/media-page.php'); 
    }
    
    public function media_handle() {
        return wp_iframe(array($this, 'media_page'));
    }
    
    /**
     * Helper function to add an attachment to the database
     * 
     * @param String $imageURL  Full URL of the remote file
     * @param String $name      Name of the item retrieved, used for the attachment title
     * @param int $postID       Parent post for the attachment
     * @return boolean 
     */
    public function addAttachment($imageURL, $name, $postID) {
        
        /* Turn off thumbnailing. Since we're not supposed to store the Amazon
         * images locally for more than 24 hours, we don't want to create the
         * thumbnail images either. 
         */
        $originalThumbs = array(
            'thumbnail_size_w' => get_option('thumbnail_size_w'),
            'thumbnail_size_h' => get_option('thumbnail_size_h'),
            'thumbnail_crop' => get_option('thumbnail_crop')
        );
        update_option('thumbnail_size_w', 0);
        update_option('thumbnail_size_h', 0);
        update_option('thumbnail_crop', 0); 
        
        $get = wp_remote_get($imageURL); 
        if (is_wp_error($get)) {
            return false; 
        }
        
        $mimeType = wp_remote_retrieve_header($get, 'content-type');
        
        $file = wp_upload_bits(basename($imageURL), '', wp_remote_retrieve_body($get));
        
        $attach = array(
            'guid' => $imageURL,
            'post_mime_type' => $mimeType,
            'post_title' => $name,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attach, '', $postID);
        require_once(ABSPATH.'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']); 
        
        wp_update_attachment_metadata($attach_id, $attach_data); 
        
        /* Reset thumbnails to their original settings */
        update_option('thumbnail_size_h', $originalThumbs['thumbnail_size_w']);
        update_option('thumbnail_size_w', $originalThumbs['thumbnail_size_h']);
        update_option('thumbnail_crop', $originalThumbs['thumbnail_crop']);
        
        return true;
        
    }
    
    
    public function add_options_menu() {
        add_options_page('Amazon Images Settings', 'Amazon Images', 'manage_options', 'amazon-images-settings', array($this, 'draw_settings'));
    }
    
    public function draw_settings() {
        include(dirname(__FILE__).'/inc/options-page.php'); 
    }
    
    public function register_settings() {
        register_setting('amazon_images_settings', 'amazon_images', array($this, 'sanitize'));
    }
    
    public function sanitize($input) {
        //var_dump($input); die(); 
        $defaults = array('apiKey' => '', 'apiSecret' => '', 'affiliateTag' => '');
        
        $rayChecks = array('imgLarge', 'imgSmall', 'imgMedium', 'imgSwatch', 'imgThumbnail', 'imgTiny', 'imageSets');
        foreach ($rayChecks as $check) {
            $defaults[$check] = false;
            if (isset($input[$check])) {
                $defaults[$check] = true; 
            }
        }
        
        return array_merge($defaults, $input); 
    }
    
    public function soapExists() {
        return class_exists('SoapClient'); 
    }
    
    
    public function ajax() {
        include(dirname(__FILE__).'/inc/ajax.php');
        die();
        
    }
    
    public function getOptions() {
        $opts = get_option('amazon_images', array());
        return array_merge($this->defaultOptions, $opts); 
    }
    
   
}
?>