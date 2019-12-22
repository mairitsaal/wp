=== Plugin Name ===
Cloudimage - Fast and Responsive Images as a Service
Contributors: @cloudimage
Tags: CDN, speed, image resizing, image, SEO, resize, fast, compression, optimize
Requires at least: 4.8
Tested up to: 5.2.4
Requires PHP: 5.6
Stable tag: 2.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The easiest way to resize, compress, optimise and deliver lightning fast images to your users on any device via CDN.

== Description ==

**Did you know ?**
Faster images increase conversion and thus revenue.

Cloudimage resizes, optimises, compresses and distributes your images lightning fast over CDN on any device around the world.  
Apply image filters, custom transformations and watermarks to get the most out of your images and convert more users thanks to beautiful and fast images.  
Embeds lazyloading and progressive loading effect for best user experience.

The Cloudimage Wordpress plugin leverages the Cloudimage v7 API and offers 2 options for making images responsive on your theme:

1. Using standard HTML5 [srcscet](https://developer.mozilla.org/en-US/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images) tags.
Your WordPress theme must natively support the HTML5 tags for responsive images above.  
By using this methos, images in the WordPress media gallery will also be delivered over Cloudimage.

2. Using the powerful [Cloudimage Responsive JS Plugin](https://scaleflex.github.io/js-cloudimage-responsive/).  
The plugin smartly identifies the image container width and delivers the optimal image size.  
No need for your Theme to support responsive images.  
It also adds lazyloading and progressive loading effect to your images for best user experience.  
This option makes the lightest possible output code and does not modify images in the WordPress media gallery.

**No development needed, it's plug-and-play!**

Simply [register](https://www.cloudimage.io/en/register_page) for a free Cloudimage account and enjoy fast and responsive images.

<a href="http://www.youtube.com/watch?feature=player_embedded&v=JFZSE1vYb0k
" target="_blank"><img src="http://img.youtube.com/vi/JFZSE1vYb0k/0.jpg"
alt="Cloudimage resizes and optimises your images" width="360" height="270" border="1"/></a>

To start boosting your images, create a free account at [Cloudimage](https://cloudimage.io) to obtain a Cloudimage token.
You get 25GB of CDN traffic and image cache for free every month. If you exceed this limit, we will contact you to set up a paid plan.  
But do not worry, 25 GB should be enough for any small to medium-sized WordPress site.

More information on our paid plans [here](https://www.cloudimage.io/pricing).

**How does it work**
The Cloudimage plugin will rewrite the WordPress image URLs and replace them with Cloudimage URLs.
Your origin images will be downloaded from your storage (WordPress media gallery, S3 bucket, ...), resized by Cloudimage and distributed over CDN.  
**No development needed**.

[vimeo https://vimeo.com/379858127]

**Coming soon**

- Cloudimage statistics dashboard within the Cloudimage plugin configuration page in your WordPress admin
- Support for image  URL signatures

If you have suggestions for new features, feel free to email us at [hello@cloudimage.io](mailto:hello@cloudimage.io)

Also, follow [Cloudimage on Twitter](https://twitter.com/cloudimage_io)!

Cloudimage is crafted by the [Scaleflex](https://www.scaleflex.com) team.

== Installation ==

1. Search and install the plugin through the Plugins > Add New page in your WordPress dashboard. Alternatively, upload the plugin's .zip there
2. Register for a free account on [Cloudimage](https://cloudimage.io)
3. Activate the Cloudimage plugin through the Plugins page in your WordPress
4. Enter your Cloudimage token or custom CNAME in the plugin's configuration page

[vimeo https://vimeo.com/379858127]

== Frequently Asked Questions ==

= Question 1: How does Cloudimage resize and optimise my WordPress images?

Upon first load of your WordPress site after activating the Cloudimage plugin, the origin images will be downloaded by the Cloudimage image management infrastructure, resized, optimised and delivered over CDN to your end users.

Cloudimage adds an additional layer of image cache (shield) on top of the CDN to make every further request from the CDN to an origin image fast.  
Cloudimage does not store your WordPress images permanently, you should always keep your images in your WordPress gallery.

= Question 2:  Why are my images not going through Cloudimage?=

Check if you have a Cache service like W3 Total Cache / WP Super Cache / ...
In this case, you need to reload the cache to enable the transformation of your URL.

If the problem persist please [contact us](hello@cloudimage.io).

= Question 3: How much does Cloudimage cost? =

Cloudimage is a SaaS with a free tier subscription for 25GB CDN traffic and 25GB image cache per month.  
We offer paid plans with higher CDN traffic and image cache allowances, pricing [here](https://www.cloudimage.io/pricing).

= Question 4: Will my origin images be affected? =

Cloudimage donwloads your images on-the-fly and **does not** modify your origin images.

= Question 5: What happen if I deactivate Cloudimage WP plugin? =

Your WordPress site will be back as it was before the activation of the Cloudimage Plugin. We do not apply permanent changes to your WordPress site and/or origin images.

== Screenshots ==

1. Cloudimage website
2. Benchmark your images before and after Cloudimage
3. Plugin configuration page
4. Cloudimage Admin - Usage Statistics
5. Cloudimage Admin - Performance Statistics

== Changelog ==

= 1.0.0 =

* First version of Cloudimage WP plugin adapted from photon (Jetpack)

= 2.0.0 =

* Added support for Cloudimage v7 API
* Re-designed plugin configuration page
* Added support for the [Cloudimage Responsive JS Plugin](https://scaleflex.github.io/js-cloudimage-responsive/)
* Added native <noscript> tags to load images if JavaScript is disabled on user's browser

= 2.0.5 =

* Added option to disable lazyloading if handled by another plugin

= 2.1.0 =
* BlurHash implementation of progressive loading as alternative. Newly uploaded images and existing images on updated articles will load with the BlurHash progressive loading effect. See demo (link to blurhash demo page).

= 2.1.1 =
* Styling improvements in admin section
* Added better text on tooltips with additional information

= 2.1.2 =
* Improvements on blurhash loading

= 2.1.3 =
* Text improvements in admin section

= 2.1.4 =
* Bug fixes for unused variables, planned for version 3.0

= 2.1.5 =
* Insert different JavaScript responsive library if blurhash is used. Save progressive loading.

= 2.1.6 =
* Add default ci-ration = 1
* Change the version of the JavaScript responsive libraries

= 2.1.7 =
* Added new baloon with additional information in footer
* Changed link to cloudimage login page in footer

= 2.2.0 =
* Change version of the JavaScript responsive plugins
* fixed bug with is-resized class for resized images

= 2.3.0 =
* Change the default function of Cloudimage picture resizing from "fit" to "bound"

== Upgrade Notice ==
* Upgrading from version 1 to 2 can show you warnings in the admin section

= 1.0 =
* Create the plugin