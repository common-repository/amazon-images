/**
 * Media Manager page for Amazon Images plugin for WordPress
 * @link http://www.nutt.net/tag/amazon-images/
 * @copyright Copyright 2012 Ryan Nutt
 * @license http://www.gnu.org/licenses/gpl.html
 */

var amazonImages = {
    checkIFrame: function() {
        alert('123'); 
    },
    displayError: function(msg) {
        jQuery('#amzn_err_msg').html(msg.replace(/\n/, '<br>'));
        jQuery('#amzn_err').show(); 
    },
    displayMessage: function(msg) {
        jQuery('#amzn_msg_msg').html(msg.replace(/\n/, '<br>'));
        jQuery('#amzn_msg').show(); 
    }
};

jQuery(document).ready(function() {
    jQuery('#amzn_button').click(function() {
        jQuery('#amzn_err').hide(); 
        var postData = {
            action: 'amazon_images',
            asin: escape(jQuery('#asin').val().trim()),
            aff: escape(jQuery('#affTag').val().trim()),
            postID: post_id
        };
        
        if (postData.asin == '' || postData.aff == '') {
            amazonImages.displayError('Both ASIN and Affiliate Tag are required');
            return; 
        }
        else if (postData.asin.length != 10) {
            amazonImages.displayError('The ASIN should be exactly 10 characters');
            return; 
        }
                
        jQuery.ajax(ajaxurl, {
            data: postData,
            type: 'POST',
            success: function(data, status, xhr) {
                if (!data.status) {
                    amazonImages.displayError("The data returned from your server was not in the expected format.");
                    if (console) { console.info(data); }
                    return; 
                } 
                
                if (data.status != 'success') {
                    if (data.error != '') {
                        amazonImages.displayError(data.error);
                    }
                    else {
                        amazonImages.displayError("Unexpected error");
                    }
                    return;
                }
                else {
                    amazonImages.displayMessage(data.imageCount + ' images added. Click on the Media Library tab to find your images.'); 
                }
                
                
            },
            error: function(xhr, status, err) {
                if (console) { console.info(xhr.responseText); console.info(xhr); }
                amazonImages.displayError("There was an unexpected error attempting to connect to the plugin Ajax script.\nError: " + err);
            },
            complete: function(xhr, status) {
                jQuery('#amzn_indicator').hide(); 
            },
            beforeSend: function(xhr, settings) {
                jQuery('#amzn_indicator').show(); 
            }
        });
        
    });
});   