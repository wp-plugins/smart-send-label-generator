<?php
/*
Plugin Name: Smart Send Labelgenerator
Plugin URI: http://smartsend.dk/integrationer/woocommerce
Description: Automated shipping for WooCommerce. Generate shipping labels for Post Danmark (PacsoftOnline), GLS, SwipBox and Bring directly from WooCommerce.
Author: Smart Send ApS
Author URI: http://www.smartsend.dk
Version: 6.0.7

	Copyright: (c) 2014 Smart Send ApS (email : kontakt@smartsend.dk)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	This module and all files are subject to the GNU General Public License v3.0
	that is bundled with this package in the file license.txt.
	It is also available through the world-wide-web at this URL:
	http://www.gnu.org/licenses/gpl-3.0.html
	If you did not receive a copy of the license and are unable to
	obtain it through the world-wide-web, please send an email
	to license@smartsend.dk so we can send you a copy immediately.

	DISCLAIMER
	Do not edit or add to this file if you wish to upgrade the plugin to newer
	versions in the future. If you wish to customize the plugin for your
	needs please refer to http://www.smartsend.dk
*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if ( ! class_exists( 'WC_Smartsend' ) ) {
		
		/**
		 * Localisation
		 **/
		load_plugin_textdomain( 'wc_smartsend', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

		class WC_Smartsend {
			public function __construct() {
				// called only after woocommerce has finished loading
				add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
				
				// called after all plugins have loaded
				add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
				
				// called just before the woocommerce template functions are included
				add_action( 'init', array( &$this, 'include_template_functions' ), 20 );
				
				include( 'smartsend-settings.php' );
				
				// indicates we are running the admin
				if ( is_admin() ) {
					if (!class_exists("smartsend_label"))
						require(plugin_dir_path( __FILE__ ) . 'smartsend-label.php');
					$this->label = new Smartsend_label();	
				}
				
				// indicates we are being served over ssl
				if ( is_ssl() ) {
					// ...
				}
    
				// take care of anything else that needs to be done immediately upon plugin instantiation, here in the constructor
			}
			
			/**
			 * Take care of anything that needs woocommerce to be loaded.  
			 * For instance, if you need access to the $woocommerce global
			 */
			public function woocommerce_loaded() {
				// ...
			}
			
			/**
			 * Take care of anything that needs all plugins to be loaded
			 */
			public function plugins_loaded() {
				// ...
			}
			
			/**
			 * Override any of the template functions from woocommerce/woocommerce-template.php 
			 * with our own template functions file
			 */
			public function include_template_functions() {
				include( 'smartsend-template.php' );
				
			}
			
		}
		
		require_once( __DIR__ . '/smartsend-labelclass.php' );

		// finally instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_smartsend'] = new WC_Smartsend();
		
	/*
	 * Adding a settings link to the plugin page
	 */
		// Add a link to the settings page onto the plugin page
		if(isset($GLOBALS['wc_smartsend']))
		{
			// Add the settings link to the plugins page
			function plugin_settings_link($links)
			{ 
				$settings_link = '<a href="options-general.php?page=smartsend-settings">Settings</a>'; 
				array_unshift($links, $settings_link); 
				return $links; 
			}

			$plugin = plugin_basename(__FILE__); 
			add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
		}
	}
}