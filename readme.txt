=== Amazon Images ===
Contributors: RyanNutt
Donate link: http://www.nutt.net/donate/
Tags: amazon, api, soap, image, images, advertising, affiliate
Requires at least: 2.6
Tested up to: 3.3.1
Stable tag: 0.2

Get images from Amazon using the Advertising API. 

== Description ==

Need larger images than the default 160px images that Amazon gives you when
you create an affiliate link?

Adds a tab to the WordPress media manager that allows you to get links to images
from the Amazon Advertising API to use in your posts. 

Part of the agreement when
you sign up with Amazon for access to their Advertising API is that you will 
only use it for affiliate marketing their site so you will likely be linking 
these images to Amazon. This plugin doesn't actually create the links so you'll
still need to do that. 

= Usage =

Once activated, the Amazon Images plugin adds a tab to the media manager. 

When editing a post or page click on the media manager link and then on the Amazon
Images tab. There will be a field to enter the ASIN of the product you'd like images
for. Enter the ASIN and your affiliate tag, if you haven't set the default in 
the plugin settings, and press Get Images. Through Ajax, the plugin will connect
to Amazon and get the links for you. 

= Thanks = 

This plugin uses the [Amazon-ECS-PHP-Library](https://github.com/Exeu/Amazon-ECS-PHP-Library/)
to handle the SOAP requests. 

== Installation ==

1. Upload the `amazon-images` folder to the `/wp-content/plugins/` directory or install directly through WordPress.
1. Activate the plugin through the 'Plugins' menu in WordPress

= Settings =

1. Once activated there will be an Amazon Images tab under the Settings menu
1. Enter your Amazon API Key and API Secret on that page
1. The default affiliate tag is optional, but you will need to to include a tag when retrieving images
1. Check which sized images you would like to link


== Frequently Asked Questions ==

= What do I need to get this working? =

You will need an account with Amazon for their [Product Advertising API](https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html). When you register and login you will have a 
public and secret key. You'll need to enter these on the Amazon Images plugin options page
to be able to get image information.

You will also need an affiliate tag, although if you're looking at this plugin you likely
already have one of these. 

= Why not just save the files? =

The user agreement for the Amazon API allows you to store images for up to 24 hours. So rather
than storing the images on your server this plugin links directly to the image at Amazon. 

During the import the images are temporarily downloaded so that WordPress can work with the
actual file to get size information. But the images are deleted immediately afterwards so they're
not on your server for more than a few seconds.

= So, they're hotlinked? =

Yes. 

The links are retrieved using the Advertising API, so presumably you are using the images to
link to content on Amazon and maybe including your affiliate tag with the link.

= What about non-US affiliates? =

Currently the plugin pulls images from the US Amazon site. It should work outside the US, but
living in the US I have no good way to test that. 

= I'm getting a SOAP not available error =

Your host probably doesn't have the SOAP library for PHP installed on your server. Ask 
them about it. 

= I'm still confused! =
Visit [http://www.nutt.net/tag/amazon-images/](my site) or use the WordPress forum link to the
right if you need help. 

== Screenshots ==

1. Media Manager tab with Amazon Images activated
2. Amazon Images settings page

== Changelog ==

= 0.1 =
* First release, nothing to change

= 0.2 =
* Oops, there was an extra page added to the Media tabset that didn't do anything. It's not there anymore.

== Upgrade Notice ==

= 0.1 = 
* First release, nothing to upgrade
