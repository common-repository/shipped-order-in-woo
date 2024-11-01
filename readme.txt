=== Shipped Order in Woo ===
Author: Radixweb
Author URI: https://radixweb.com/about-us
Contributors: radixweb, laxman-prajapati
Tags: shipped, ship-order, order-status, order-status-delivered, custom-order-status
Requires at least: 5.4
Tested up to: 5.9
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin for adding a WooCommerce order status as Shipped.


== Description ==
**Shipped Order in WooCommerce** plugin is adding custom order status as Shipped.

When the order status change to Shipped, The custom will receive an email.

No need to do anything else to use this plugin. Just plugin install, activate and the order status as **Shipped** will automatically be listed on the order status listing.

If the order is available on order status as shipped and if you get deactivate this plugin. The Shipped order status order will get hide so if you need to deactivate this plugin, you can just change the order status from shipped to any else and after that, you can deactivate.


= Awesome features =
*   Shipped order status will automatically be listed on the order status listing
*   Technical knowledge not required
*   After the order status change to shipped, the customer will receive a notification via email
*   Able to customize the email template

== Installation ==

*   Install using the WordPress Plugin installer, or Extract the zip file and drop the contents in the wp-content/plugins/ directory of your WordPress installation
*   Activate the plugin through the "Plugins" administration page in WordPress


== Frequently Asked Questions ==
= 1) After deactivating this plugin, The Shipped order status orders will show? =
* If the order is available on order status as shipped and if you get deactivate this plugin. The Shipped order status order will get hide so if you need to deactivate this plugin, you can just change the order status from shipped to any else and after that, you can deactivate.

= 2) After the order status change to shipped, the customer will receive a notification via email? =
* Yes

= 3) We can able to override the email template?
* Yes you can follow the WooCommerce standard flow to customize the email template. Copy the "wp-content/plugins/shipped-order-in-woo/includes/emails/customer-shipped-order.php" to "wp-content/themes/yourtheme/woocommerce/emails/customer-shipped-order.php" The copied file will now override the existing email template.


== Screenshots ==
1. Orders listing with Shipped status
2. Change order status detail page
3. Shipped status email
4. Shipped status email template


== Changelog ==

= 1.0.0 - 18/02/2021 =
* Initial Release

= 1.0.1 - 19/03/2021 =
* Fix Minor bugs.

= 1.0.2 - 17/02/2022 =
* Fix Minor bugs.
