<?php
/**
 * Plugin Name: Shipped Order in Woo
 * Plugin URI: https://wordpress.org/plugins/shipped-order-in-woo/
 * Description: Plugin for adding a WooCommerce order status as Shipped.
 * Version: 1.0.2
 * Author: Radixweb
 * Author URI: https://radixweb.com/about-us
 * Developer: Laxman Prajapati
 * Developer URI: https://profiles.wordpress.org/laxman-prajapati/
 * Text Domain: shipped-order-in-woo
 * Domain Path: /languages
 *
 * WC requires at least: 5.4
 * WC tested up to: 5.9
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
function wso_activate() {
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
 
    if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'WooCommerce' ) ) {
        // Plugin Deactivated.
        deactivate_plugins( plugin_basename( __FILE__ ) );
        $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires ', 'shipped-order-in-woo' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '">WooCommerce</a>' . esc_html__( ' plugin to be active.', 'shipped-order-in-woo' ) . '</p>';
        die( $error_message );
    }
}
register_activation_hook( __FILE__, 'wso_activate' );

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function wc_shipped_order_email( $email_classes ) {
	// Include our custom email class
	require_once( 'includes/class-shipped-order-in-woo.php' );

	// Add the email class to the list of email classes that WooCommerce loads
	$email_classes['WC_Shipped_Order_Email'] = new WC_Shipped_Order_Email();
	return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'wc_shipped_order_email' );

/**
 * Register wc-shipped order status
 **/
function wc_shipped_order_status() {
	register_post_status( 'wc-shipped', array(
	    'label' => _x('Shipped','shipped-order-in-woo'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' )
	));
}
add_action( 'init', 'wc_shipped_order_status');

/**
 * wc-shipped order status metabox action
 **/
function wc_shipped_order_meta_box_actions($actions) {
    $actions['wc_shipped'] = __( 'Shipped', 'shipped-order-in-woo');
    return $actions; 
}
add_action( 'woocommerce_order_actions', 'wc_shipped_order_meta_box_actions' );

/**
 * wc-shipped order status
 **/
function wc_shipped_order_statuses($order_statuses) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-completed' === $key ) {
            $new_order_statuses['wc-shipped'] = __('Shipped','shipped-order-in-woo');    
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'wc_shipped_order_statuses');

/**
 * wc-shipped order status notification
 **/
function wc_shipped_order_notification( $order_id, $from_status, $to_status, $order ) {
    if( $order->has_status( 'shipped' )) {
        $email_notifications = WC()->mailer()->get_emails();
        $email_notifications['WC_Shipped_Order_Email']->heading = __('Your Order shipped','shipped-order-in-woo');
        $email_notifications['WC_Shipped_Order_Email']->subject = 'Your {site_title} order shipped receipt from {order_date}';
        $email_notifications['WC_Shipped_Order_Email']->trigger( $order_id );
    }
}
add_action('woocommerce_order_status_changed', 'wc_shipped_order_notification', 10, 4);

/**
 * wc-shipped order status email action
 **/
function wc_shipped_order_email_actions( $actions ){
    $actions[] = 'woocommerce_order_status_wc-shipped';
    return $actions;
}
add_filter( 'woocommerce_email_actions', 'wc_shipped_order_email_actions' );

/**
 * wc-shipped order status plugin directory
 **/
function wc_shipped_order_plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

/**
 * wc-shipped order status locate template
 **/
function wc_shipped_order_locate_template( $template, $template_name, $template_path ) {
    global $woocommerce;
    $_template = $template;
    if ( ! $template_path ) $template_path = $woocommerce->template_url;
    $plugin_path  = wc_shipped_order_plugin_path() . '/includes/';
    $template = locate_template(
        array(
            $template_path . $template_name,
            $template_name
        )
    );
    if ( ! $template && file_exists( $plugin_path . $template_name ) )
        $template = $plugin_path . $template_name;
    if ( ! $template )
        $template = $_template;
    return $template;
}
add_filter( 'woocommerce_locate_template', 'wc_shipped_order_locate_template', 10, 3 );