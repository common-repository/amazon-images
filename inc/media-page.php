<?php
/**
 * Media Manager page for Amazon Images plugin for WordPress
 * @link http://www.nutt.net/tag/amazon-images/
 * @copyright Copyright 2012 Ryan Nutt
 * @license http://www.gnu.org/licenses/gpl.html
 */

if (!class_exists('AmazonImages')) {
    die('Oops'); 
}

wp_enqueue_script('amazon-images'); 
media_upload_header();
        
        ?>
<form method="post" action="" name="amazon_images_form" id="amazon_images_form">
      <h3 class="media-title">Add images from Amazon</h3>
      
      <?php
      if (!$this->apiKey || !$this->apiSecret) {
          echo '<div class="error"><p>API Key or API Secret not set. Please <a href="options-general.php?page=amazon-images-settings" target="_parent">check your settings</a>.</p></div>'; 
      }
      else if (!$this->soapExists()) {
            echo '<div class="error">';
            echo '<p>The PHP SOAP module does not appear to be available on this server. The Amazon Images plugin requires the SOAP module to communicate with Amazon and will not function without it.</p><p>Please check with your host for more information.</p>';
            echo '</div>'; 
      }
      else {
          ?>
      
      <div class="updated">
          <p>
              In the fields below, enter the 
              <a href="https://affiliate-program.amazon.com/gp/associates/help/t5/a16" target="_blank">
                  ASIN
              </a>
              for the product you're looking for. You can also change the affiliate tag if you would like, although it doesn't matter as long as the affiliate tag is valid. 
          </p>
      </div>
      
      <div id="amzn_table">
      <table class="form-table" style="margin-top: 16px;">
          <tr valign="top">
              <th scope="row">ASIN Number</th>
              <td>
                  <input type="text" id="asin" value="">
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">Affiliate Tag</th>
              <td>
                  <input type="text" id="affTag" value="<?php echo $this->affiliateTag; ?>">
              </td>
          </tr>
          
      </table>
          
          
          
      <p class="submit">
            <input id="amzn_button" type="button" class="button-primary" value="<?php _e('Get Images'); ?>">
            <img id="amzn_indicator" style="margin-left:16px;display:none;" src="<?php echo plugins_url('img/loading.gif', dirname(__FILE__)); ?>">
      </p>
      
      <div id="amzn_err" class="error" style="display:none;margin-top:8px;"><p id="amzn_err_msg"></p></div>
      <div id="amzn_msg" class="updated" style="display:none;margin-top:8px;"><p id="amzn_msg_msg"></p></div>
      </div>
      
      <?php
          
      }
      ?>
      
</form>
      
        <?php
        
        

?>