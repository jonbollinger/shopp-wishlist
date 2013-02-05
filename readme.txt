=== Plugin Name ===
Contributors: illutic
Tags: wishlist
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 1.2

A wishlist plugin for Shopp (v1.2+).

== Description ==

This plugin adds a wishlist possibility to your Shopp (www.shopplugin.net) webshop.

== Installation ==

1. Upload `shopp-wishlist` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the `Shopp Wishlist` widget to your sidebar
4. Your customers can view the default wishlist (wishlist.php template) through `domain.com/wishlist` or `domain.com/wishlist/view`

== Frequently Asked Questions ==

= How can I edit the templates for the wishlist? =

Copy `wishlist.php` and `sidewishlist.php` to your theme's folder or the shopp folder inside your theme. Now edit them to your likings :)
You can use all shopp-product tags inside the while-loop.

= I would like to move the add-to-wishlist link, is this possible? =

Yes, it is. Add the following to your functions.php:

`remove_filter('shopp_product_description','shoppwishlist_button');`

Then use the following inside product.php where you want the link/text to appear, it's adjusted automatically (depending on whether the item is on the wishlist or not):

`shopp('wishlist','add-button')`

= I am having issues, or would like to suggest a new feature. =

Please let me know! You can add a new ticket in the open Shopp tracker, the Shopp Wishlist area (https://shopp.lighthouseapp.com/projects/63871-shopp-wishlist/overview). If you already have an account in the Shopp tracker you can add tickets with that account in that area too.

= How can customers add items from their wishlist to their cart? =

Add the form for adding to the cart inside the templates.

= Can my customers share their wishlist with others? =

Yes. Every customer has their own wishlist URL on `domain.com/wishlist/share/customer/{wp_user_id}` when you have WP accounts activated, otherwise it's `domain.com/wishlist/share/customer/{shopp_customer_id}`.

= Does the wishlist work with Shopp accounts too? =

From version 1.2 it does, yes.

== Changelog ==

= 1.2.1b1 =
- Support for Shopp 1.2

= 1.2 =
* Added shopp() tags support (with backwards compatibility), this also means changes inside the wishlist templates (whishlist.php & sidewishlist.php)
* Added support for Shopp accounts

= 1.1 =
* Some bugfixes
* Added support for default permalinks

= 1.0 =
* Initial release