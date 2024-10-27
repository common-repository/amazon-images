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
$opts = $this->getOptions();
//print_r($opts); 

        ?>
<div class="wrap">
    <form action="options.php" method="post">
        <?php
        settings_fields('amazon_images_settings');
        do_settings_fields('amazon_images', ''); 
        ?>
    <h2>
        Amazon Image Settings
        <a href="http://www.nutt.net/tag/amazon-images/" title="More information on Amazon Images plugin" target="_blank">
            <img style="width:16px;height:16px;" src="<?php echo plugins_url('img/help.png', dirname(__FILE__)); ?>">
        </a>
    </h2>
    
        <?php
        /* Make sure that the SOAP module is available, and show error if not   */
        if (!$this->soapExists()) {
            echo '<div class="error">';
            echo '<p>The PHP SOAP module does not appear to be available on this server. This plugin will not work without the SOAP module.</p><p>Please check with your host for more information.</p>';
            echo '</div>'; 
        }
        ?>
        
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Amazon API Key</th>
            <td>
                <input type="text" name="amazon_images[apiKey]" value="<?php echo $opts['apiKey']; ?>">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Amazon API Secret</th>
            <td>
                <input type="text" name="amazon_images[apiSecret]" value="<?php echo $opts['apiSecret']; ?>">
            </td>
        </tr>
        <tr valign="top">
            <th></th>
            <td>
                You'll need to sign up for an 
                <a target="_blank" href="https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html">
                    Amazon Product Advertising API Key
                </a>
                for this plugin to work
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Default Affiliate Tag</th>
            <td>
                <input type="text" name="amazon_images[affiliateTag]" value="<?php echo $opts['affiliateTag'];?>">
            </td>
        </tr>
        <tr valign="top">
            <th></th>
            <td>
                You can change this individually when you load the images
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row">Image Sizes to pull</th>
            <td>
                <input type="checkbox" name="amazon_images[imgLarge]" value="1"<?php checked($opts['imgLarge']); ?>> Large<br>
                <input type="checkbox" name="amazon_images[imgMedium]" value="1"<?php checked($opts['imgMedium']); ?>> Medium<br>
                <input type="checkbox" name="amazon_images[imgSmall]" value="1"<?php checked($opts['imgSmall']); ?>> Small<br>
                <input type="checkbox" name="amazon_images[imgSwatch]" value="1"<?php checked($opts['imgSwatch']); ?>> Swatch<br>
                <input type="checkbox" name="amazon_images[imgThumbnail]" value="1"<?php checked($opts['imgThumbnail']); ?>> Thumbnail<br>
                <input type="checkbox" name="amazon_images[imgTiny]" value="1"<?php checked($opts['imgTiny']); ?>> Tiny<br>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Image Sets</th>
            <td><input type="checkbox" name="amazon_images[imageSets]" value="1"<?php checked($opts['imageSets']); ?>> Include in import</td>
        </tr>
    </table>
    
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>">
    </p>
</form>
</div>