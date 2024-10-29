=== Banner Slider for Advertisement ===
Contributors: vipuljariwala
Donate link: http://www.wpwebs.com/demo/banner-slider-advertisement/
Tags: banners, advertisement, slider, banner sliders, auto slide, auto-play time, previous next, slider loop, auto height,below header, above footer, banner slider, banner advertisement, advertisement slider, home advertisement, category advertisement, search  advertisement, multilingual, images
Requires at least: 4.8
Tested up to: 4.9
Stable tag: 1.0.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Banner advertisement slider to maximize your revenue & earn money from home page, categories, tags and search like pages.

== Description ==

Banner advertisement to maximize your ad revenue by display image banners advertisement for different pages on different places.

There is not any direct WordPress action hook or filter function that allow you to insert new features like banner slider near header & footer or at any good place, so admin user/site manager should change and add plugin php code to allow site owner to display advertisement slider on front end site visitors.

**Important Note :: ** to display advertisement slider on user end, you have to edit header.php & footer.php files of current active theme. If you don't have knowledge of PHP or WordPress code, we have display how to add with screen image from admin->Plugin Settings->Document.


= Live Demo =

You can find banner slider display on different pages [plugin demo on our site](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-plugin-settings-setup-guide/)


= Different between Banner Slider and other slider plugins =

* Normal slider plugin display banners via widgets or shortcodes only for limited area.
* While banner slider allow you to display banners for home page, category pages, tag pages & search page.
* All pages have different places like display above & below header and above & below footer.
* Main idea for this plugin to advertisement of banners on different places and on different pages.
* Display banner slider as single banner or gallery slider to make page attractive.
* So number of advertisement display possibility for your site will be more.
* So it will chance to display more advertisement & increase advertisement revenue.
* Like this you may earn extra money for your site.



= Plugin Features =

* Screen touch slider.
* Display 5 advertisement banners images per slider.
* Display multiple banners(gallery) and/or single banner.
* Display banner slider above & below header.
* Display banner slider above & below footer.
* Banner slider in header & footer for home page.
* Banner slider in header & footer for post category pages.
* Banner slider in header & footer for post tag pages.
* Banner slider in header & footer for search page.
* Banner slider widget to display at any widget area directly.
* Banner images with click link URL add option.
* Banner slider pagination & navigation (previous/next) banner option.
* Banner slider auto play/auto slide option.
* Banner slider infinite loop option.
* Adjust banner slider adjust height as per banner images.
* Fix the slider width & slide time settings option.
* Banners for mobile show/hide.
* Slider effects like default/slide effect and fade effect.
* Responsive in size as per device screen size.
* Wordpress Multi-site ready.
* Mobile friendly.


= Live Demo =

You can find banner slider display on different pages [plugin demo on our site](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-plugin-settings-setup-guide/)


= How many places display advertisement banner slider =
1. Above Header
1. Below Header
1. Above Footer
1. Below Footer

= On which pages display advertisement banner slider =
1. Home page -- above & below header and above & below footer
1. Category pages -- above & below header and above & below footer
1. Tag pages -- above & below header and above & below footer
1. Search page -- above & below header and above & below footer


= How to Display Banners on front-end site =

Why you should edit header.php & footer.php files??
==> This is the only big question and the answer is :: every WordPress theme & child theme header.php & footer.php files have different HTML design and so we cannot say any one to add code exactly on particular line or near particular code. 
Even not sure that the WordPress theme you are using have added action hook for header.php & footer.php file.

* Step-by-step way to add banner slider code and update files.
* Go to WordPress admin->Banner Slider(new menu added)->Plugin Settings->Document Settings.
* See settings for display banner ABOVE and BELOW header. 
* Add the WordPress php hook code in "header.php" file. 
* Code for ABOVE header : do_action('wpw_banner_slider_before_header');
* Code for BELOW header : do_action('wpw_banner_slider_after_header');
* See settings for display banner ABOVE and BELOW footer.
* Add the WordPress php hook code in "footer.php" file. 
* Code for ABOVE footer is  : do_action('wpw_banner_slider_before_footer');
* Code for BELOW footer is  : do_action('wpw_banner_slider_after_footer');
* Display banners advertisement slider in sidebar or any widget area by drag & drop widget : "Banner Slider Advertisement"



= Docs & Support =

You can find [plugin documents](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-plugin-settings-setup-guide/), [FAQ](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-faq/) and more detailed information on [wpwebs.com](http://www.wpwebs.com/demo/banner-slider-advertisement/). If you were unable to find the answer to your question on the FAQ or in any of the documentation, you should check the [support forum](https://wordpress.org/support/plugin/banner-slider-for-advertisement/) on WordPress.org. If you can't locate any topics that pertain to your particular issue, post a new topic for it.
Still you need any help, customization or direct help - [contact us](http://www.wpwebs.com/contact/)


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' menu in WordPress admin.
1. Use the 'Banner Slider' (new menu added) from WordPress admin menu to manage banners(add/edit/delete banners)
1. Use the Banner Slider->'Plugin Settings'->Document menu to see how to add banner display hook in related files. 
1. Use the Banner Slider->'Plugin Settings'->General menu to configure home page & search page banner settings
1. Manage category banners from Posts->Categories(menu)->Banner Slider Settings
1. Manage tag banners from Posts->Tags(menu)->Banner Slider Settings

For basic usage, you can also have a look at the [plugin web site](http://www.wpwebs.com/demo/banner-slider-advertisement/).

=== How to display banners on front-end site ===
There are two ways to display banner slider on front-end site 
(1) Edit header.php & footer.php files by adding related action hook above & below header & footer
(2) Use widget "Banner Slider Advertisement" by drag-drop to widget area and display slider.


* Go to WordPress admin->Banner Slider(new menu added)->Plugin Settings->Document Settings.
* See settings for display banner ABOVE and BELOW header. 
* Add the WordPress php hook code in "header.php" file. 
* Code for ABOVE header : do_action('wpw_banner_slider_before_header');
* Code for BELOW header : do_action('wpw_banner_slider_after_header');
* See settings for display banner ABOVE and BELOW footer.
* Add the WordPress php hook code in "footer.php" file. 
* Code for ABOVE footer is  : do_action('wpw_banner_slider_before_footer');
* Code for BELOW footer is  : do_action('wpw_banner_slider_after_footer');
* Display banners advertisement slider in sidebar or any widget area by drag & drop widget : "Banner Slider Advertisement"


=== Display banners by widget ===
1. Display banner advertisement slider in widget ready sidebar.
1. Use widget "Banner Slider Advertisement" by drag & drop widget from WordPress Admin->Appearance->Widgets->"Banner Slider Advertisement".
1. Select the banners slider created from WordPress Admin->Banner Slider
1. Banner slider will display as per banner settings & there is no extra settings from widget.
1. Just drag & drop the widget, select the banner and it done.


=== How to edit header.php file and add banner display code ===
1. Go to WordPress Admin->Banner Slider(left menu)->Plugin Settings->Document(tab)-> See & copy code :: Display banner ABOVE and BELOW header 
1. Code for ABOVE header : `<?php do_action('wpw_banner_slider_before_header');?>`
1. Code for BELOW header : `<?php do_action('wpw_banner_slider_after_header');?>`
1. Go to WordPress Admin->Appearance(left menu)->Themes->Get Current Active Theme
1. Go to WordPress Admin->Appearance(left menu)->Editor(only admin/super admin user can see this link)->Get current active theme selected->find "header.php"
1. Click the header.php file so it come in editor so you can edit
1. Find header start code. Most of case it should be like "< header ......" (see document tab image for more understanding)
1. Add header banner code in header.php file before "< header ......" OR you may like to add the code at proper place like at below "body" tag of header.php. 
1. Find header end code. Most of case it should be like "< /header ......" (see document tab image for more understanding)
1. Add header banner code in header.php file after "< /header ......" OR you may like to add the code at proper place like at ending of header.php. 


=== How to edit footer.php file and add banner display code ===
1. Go to WordPress Admin->Banner Slider(left menu)->Plugin Settings->Document(tab)-> See & copy code :: Display banner ABOVE and BELOW footer  
1. Code for ABOVE footer is  : do_action('wpw_banner_slider_before_footer');
1. Code for BELOW footer is  : do_action('wpw_banner_slider_after_footer');
1. Go to WordPress Admin->Appearance(left menu)->Themes->Get Current Active Theme
1. Go to WordPress Admin->Appearance(left menu)->Editor(only admin/super admin user can see this link)->Get current active theme selected->find "footer.php"
1. Click the footer.php file so it come in editor so you can edit
1. Find footer start code. Most of case it should be like "< footer ......" (see document tab image for more understanding)
1. Add footer banner code in footer.php file at before "< footer ......" OR you may like to add the code at proper place like at beginning of footer.php. 
1. Find footer end code. Most of case it should be like "< /footer ......" (see document tab image for more understanding)
1. Add footer banner code in footer.php file at after "< /footer ......" OR you may like to add the code at proper place like at end of footer.php but above "body" end. 



== Frequently Asked Questions ==

Do you have questions or issues with our plugin? Use these support channels appropriately.

1. [plugin documents](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-plugin-settings-setup-guide/)
1. [FAQ](http://www.wpwebs.com/demo/banner-slider-advertisement/banner-slider-for-advertisement-faq/)
1. [Support Forum](https://wordpress.org/support/plugin/banner-slider-advertisement/)

[Support](http://www.wpwebs.com/contact/)




== Screenshots ==

1. Banner advertisement list from WordPress admin.
2. Manage(add/edit) banner advertisement settings.
3. Banner settings for Categories.
4. Banner settings for Tags.
5. Banner slider plugin settings for home page & search page.
6. Banner slider plugin settings documentation.
7. Widget Settings to display banner slider.



== Changelog ==

= 1.0.1 =
-- New Features added with new banner slider spaces to display banner slider
(1) Display banner slider above header
(2) Display banner slider below footer
-- Display above settings for home page
-- Display above settings for search page
-- Display above settings for category pages
-- Display above settings for tag pages...

= 1.0.0 =
* Fresh Plugin release




== Upgrade Notice ==

= 1.0.0 =
Fresh and stable plugin release

= 1.0.1 =
New banner slider spaces to display advertisement above header & below footer for home, search, category & tag pages...
