<?php
/**
 * Ajax script for Amazon Images plugin for WordPress
 * @link http://www.nutt.net/tag/amazon-images/
 * @copyright Copyright 2012 Ryan Nutt
 * @license http://www.gnu.org/licenses/gpl.html
 */

if (!class_exists('AmazonImages')) {
    die('Oops'); 
}

if (!$this->soapExists()) {
    amzn_error('Your server does not appear to have the SOAP library available'); 
}

$opts = get_option('amazon_images', array('apiKey' => '', 'apiSecret' => '', 'affiliateTag' => '')); 

$ret = array('status' => 'error', 'error' => '', 'images' => 0);

include_once(dirname(__FILE__).'/AmazonECS.class.php');
$ecs = new AmazonECS($this->apiKey, $this->apiSecret, 'com', $_POST['aff']);

try {
    $response = $ecs->responseGroup('Images,Small')->lookup($_POST['asin']);
}
catch (Exception $e) {
    amzn_error('Amazon servers reported an error: '.$e->faultstring); 
}

if (isset($response->Items->Request->Errors->Error->Message)) {
    amzn_error('Amazon servers reported an error: '.$response->Items->Request->Errors->Error->Message);
}

$item = $response->Items->Item; 

/** @var int Number of total images found */
$imgCount = 0;

/** @var int Number added. This will not include duplicates. */
$added = 0;
//print_r($response); 
if (isset($response->Items->Item->ItemAttributes->Title )) {
    $title = $response->Items->Item->ItemAttributes->Title;
}
else {
    $title = 'Amazon Product '.$_POST['asin'];
}

//print_r($item);
//print_r($opts); 
//print_r($_POST); 
if ($opts['imgLarge'] && isset($item->LargeImage->URL)) {
    if ($this->addAttachment($item->LargeImage->URL, $title.' - Large', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imgMedium'] && isset($item->MediumImage->URL)) {
    if ($this->addAttachment($item->MediumImage->URL, $title.' - Medium', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imgSmall'] && isset($item->SmallImage->URL)) {
    if ($this->addAttachment($item->SmallImage->URL, $title.' - Small', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imgSwatch'] && isset($item->SwatchImage->URL)) {
    if ($this->addAttachment($item->SwatchImage->URL, $title.' - Swatch', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imgTiny'] && isset($item->TinyImage->URL)) {
    if ($this->addAttachment($item->TinyImage->URL, $title.' - Tiny', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imgThumbnail'] && isset($item->ThumbnailImage->URL)) {
    if ($this->addAttachment($item->ThumbnailImage->URL, $title.' - Thumbnail', $_POST['postID'])) {
        ++$added; 
    } 
}
if ($opts['imageSets'] && isset($item->ImageSets->ImageSet) && is_array($item->ImageSets->ImageSet)) {
    foreach ($item->ImageSets->ImageSet as $set) {
        if ($opts['imgLarge'] && isset($set->LargeImage->URL)) {
            if ($this->addAttachment($set->LargeImage->URL, $title.' - Large', $_POST['postID'])) {
                ++$added; 
            } 
        }
        if ($opts['imgMedium'] && isset($set->MediumImage->URL)) {
            if ($this->addAttachment($set->MediumImage->URL, $title.' - Medium', $_POST['postID'])) {
                ++$added; 
            } 
        }
        if ($opts['imgSmall'] && isset($set->SmallImage->URL)) {
            if ($this->addAttachment($set->SmallImage->URL, $title.' - Small', $_POST['postID'])) {
                ++$added; 
            } 
        }
        if ($opts['imgSwatch'] && isset($set->SwatchImage->URL)) {
            if ($this->addAttachment($set->SwatchImage->URL, $title.' - Swatch', $_POST['postID'])) {
                ++$added; 
            } 
        }
        if ($opts['imgTiny'] && isset($set->TinyImage->URL)) {
            if ($this->addAttachment($set->TinyImage->URL, $title.' - Tiny', $_POST['postID'])) {
                ++$added; 
            } 
        }
        if ($opts['imgThumbnail'] && isset($set->ThumbnailImage->URL)) {
            if ($this->addAttachment($set->ThumbnailImage->URL, $title.' - Thumbnail', $_POST['postID'])) {
                ++$added; 
            } 
        }
    }
}

$ret['status'] = 'success';
$ret['error'] = '';
$ret['imageCount'] = $added; 
header('Content-type: application/json');
echo json_encode($ret);
die(); 


function amzn_error($errMsg) {
    $ret = array(
        'status' => 'error',
        'error' => $errMsg,
        'images' => 0
    );
    
    header('Content-type: application/json');
    echo json_encode($ret);
    die(); 
}
?>