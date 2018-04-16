=== Eletheme ===

Contributors: dudaster
Tags: Theme builder, Elementor

Requires at least: 4.0
Tested up to: 4.9.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Theme creator for Elementor Page Builder plugin.
Copyright (C) 2018 Eletemplator https://www.eletemplator.com/

== Description ==

Create your own theme using Elementor Plugin with this naming (replace the curly brackets with the actual value):

[body] - for header and footer
_[default] - container for all post types
_{{post->type}} - container for the post->type slug
_{{post->type}}_{{term->slug}}_[archive] - container for a specific category archive
_{{post->type}}_[archive] - container for archive of the post type
_{{post->type}}_[category] - container for archive of a category
_{{post->type}}_[loop] - summary template of a post in a loop
_{{post->type}}_[single] - single template of a post

Examples for porduct post type archive we should create a template called like this: _product_[archive], for single we must name it: _product_[single]
If you want to create a template for a specific category archive (let's say it is called "Apples") for a product type post: _product_apples_[archive], and for the single page you can use:_product_apples_[single].

You can create a default template for _[archive], _[single], _[loop] like this:
_[default]_[archive], _[default]_[single], _[default]_[loop]


If the term's template is not found, Eletheme tries to get post type template an if that is not found it loads the default template.

Inside a template add the any keyword inside curly brackets (ie. {{post_title}}) and it would be replaced with the actual value. Please check the default templates that installs themselfs in the elementor library.

Why use this naming instead of attaching atributes for each and every elementor library post?

This theme creator tries to recreate the wordpress theme creation. Instead of creating category-term_name.php you'll simply create a template with the name _post_term-name_[archive].

What are the keywords that I can put inside?

All the wp-query vars can be accessed and all the custom fields.
Please check https://github.com/dudaster/eletheme for more info.

== Installation ==
	
1. Make sure Elementor Plugin is installed and active.
2. In your admin panel, go to Appearance > Themes and click the Add New button.
3. Click Upload and Choose File, then select the theme's .zip file. Click Install Now.
4. Click Activate.
5. Check Elementor Library for the blank custom template that you can change to your likeing.

== Frequently Asked Questions ==

= Does this theme support any plugins? =

Yes. This theme was specifically made to work with the Elementor Page Builder plugin out of the box - simply plug and play.

= Can I create grid view Loop? =

No. You can create this list only using Elementor Pro and our Eleplug Plugin.

= Can I use it for WooCommerce? ==

Yes. the post type for the prodycts is product so you can use template names like: _product_[single], _product_[archive], _product_[loop]

= Can I see a list of websites made with this Theme builder? =

Check https://www.eletemplator.com/ or https://github.com/dudaster/eletheme

== Changelog ==

= 1.1.0 =
* added the ability to insert new keywords and values into tha page
* added {{current_year}} keyword
* fixed issue with Elementor PRO 2

= 1.0.9 =
* fixed issue with Eementor PRO Posts Widget

= 1.0.8 =
* added support for latest Eementor PRO Version

= 1.0.7 =
* added support for woocommerce 3.3

= 1.0.6 =
* added permalink keyword

= 1.0.5 =
* fixed loop bug

= 1.0.4 =
* Added Category archive template 

= 1.0.3 =
* Bugs fixed 

= 1.0.2 =
* Cleaning the code 

= 1.0.1 =
* Added default templates for [search] page and [404] page

= 1.0.0 =
* Initial release

== Support ==

https://github.com/dudaster/eletheme

Enjoy. Thanks, Eletemplator | https://www.eletemplator.com/