<?php
/**
 * Plugin Name: Custom WC Log cleaner schedule
 * Description: Plugin to set the number of days after which WooCommerce logs should be cleared from `/wp-content/uploads/wc-logs/`
 * Version: 1.0.0
 * Author: AashikP
 * Author URI: https://aashikp.com
 * Text Domain: apcwclog
 * Requires at least: 5.0.0
 * Requires PHP: 7.3.5
 * WC requires at least: 4.0.0
 * WC tested up to: 5.4.1
 *
 * @package Custom WC Log cleaner schedule
 */

defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	add_filter( 'woocommerce_general_settings', 'apcwclog_set_number_of_days_to_keep_logs', 10, 2 );

	/**
	 * Custom WC Log cleaner schedule Settings (WooCommerce > Settings > General)
	 *
	 * @param array $settings -> Add to WooCommerce Settings.
	 */
	function apcwclog_set_number_of_days_to_keep_logs( $settings ) {

		$settings[] = array(
			'title' => __( 'WooCommerce Logs', 'apcwclog' ),
			'type'  => 'title',
			'id'    => 'apcwclog_settings',
		);

		// Set the number of days.
		$settings[] = array(
			'title'    => __( 'Days to keep logs', 'apcwclog' ),
			'desc'     => __( 'Set the minimum number of days you would like to keep WooCommerce Logs found under `/wp-content/uploads/wc-logs/` If empty, the extension won\'t make any changes to the default number of days, that is 30 days.', 'apcwclog' ),
			'id'       => 'apcwclog_days_to_keep_logs',
			'default'  => '',
			'type'     => 'number',
			'desc_tip' => true,
			'css'      => 'width:70px;',
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'apcwclog_settings',
		);
		return $settings;
	}

	/**
	 * Function to check user configured value
	 */
	function apcwclog_clean_logs() {
		$days_to_keep_log = intval( get_option( 'apcwclog_days_to_keep_logs', 30 ) );
		return $days_to_keep_log;
	}

	add_filter( 'woocommerce_logger_days_to_retain_logs', 'apcwclog_clean_logs' );

} else {

	/**
	 * WooCommerce fallback notice.
	 */
	function apcwclog_admin_notice() {
		?>
		<div class="error">
		<p>
			<?php
			esc_html_e( 'Custom WC Log cleaner schedule extension requires WooCommerce plugin to be installed and active.', 'apcwclog' );
			?>
		</p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'apcwclog_admin_notice' );
}
