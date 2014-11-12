<?php
/**
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
 * Settings
 * Guide here:
 *	A: http://codex.wordpress.org/Creating_Options_Pages
 * 	B: http://codex.wordpress.org/Administration_Menus
 */

	/** Step 1. */
	function smartsend_labelgenerator_settings_menu() {
		add_options_page( 'Smart Send Settings', 'Smart Send', 'manage_options', 'smartsend-settings', 'smartsend_labelgenerator_settings_options' );
	}
	
	/** Step 2. */
	add_action( 'admin_menu', 'smartsend_labelgenerator_settings_menu' );

	/** Step 3. */
	function smartsend_labelgenerator_settings_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		// variables for the field and option names
		$hidden_field_name = 'smartsend_submit_hidden';

		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

			// Save the posted value in the database
				// General
				update_option( 'smartsend_general_id', $_POST[ 'smartsend_general_id' ] );
				update_option( 'smartsend_general_key', $_POST[ 'smartsend_general_key' ] );
				update_option( 'smartsend_general_testing', $_POST[ 'smartsend_general_testing' ] );
				// Post Danmark 1
				update_option( 'smartsend_postdanmark1_shippingmethod', $_POST[ 'smartsend_postdanmark1_shippingmethod' ] );
				update_option( 'smartsend_postdanmark1_notsms', $_POST[ 'smartsend_postdanmark1_notsms' ] );
				update_option( 'smartsend_postdanmark1_notemail', $_POST[ 'smartsend_postdanmark1_notemail' ] );
				update_option( 'smartsend_postdanmark1_dlv', $_POST[ 'smartsend_postdanmark1_dlv' ] );
				update_option( 'smartsend_postdanmark1_srvid', $_POST[ 'smartsend_postdanmark1_srvid' ] );
				// Post Danmark 2
				update_option( 'smartsend_postdanmark2_shippingmethod', $_POST[ 'smartsend_postdanmark2_shippingmethod' ] );
				update_option( 'smartsend_postdanmark2_notsms', $_POST[ 'smartsend_postdanmark2_notsms' ] );
				update_option( 'smartsend_postdanmark2_notemail', $_POST[ 'smartsend_postdanmark2_notemail' ] );
				update_option( 'smartsend_postdanmark2_dlv', $_POST[ 'smartsend_postdanmark2_dlv' ] );
				update_option( 'smartsend_postdanmark2_srvid', $_POST[ 'smartsend_postdanmark2_srvid' ] );
				// Post Danmark 3
				update_option( 'smartsend_postdanmark3_shippingmethod', $_POST[ 'smartsend_postdanmark3_shippingmethod' ] );
				update_option( 'smartsend_postdanmark3_notsms', $_POST[ 'smartsend_postdanmark3_notsms' ] );
				update_option( 'smartsend_postdanmark3_notemail', $_POST[ 'smartsend_postdanmark3_notemail' ] );
				update_option( 'smartsend_postdanmark3_dlv', $_POST[ 'smartsend_postdanmark3_dlv' ] );
				update_option( 'smartsend_postdanmark3_srvid', $_POST[ 'smartsend_postdanmark3_srvid' ] );
				// SwipBox
				update_option( 'smartsend_swipbox_shippingmethod', $_POST[ 'smartsend_swipbox_shippingmethod' ] );
				update_option( 'smartsend_swipbox_size', $_POST[ 'smartsend_swipbox_size' ] );
				// GLS 1
				update_option( 'smartsend_gls1_shippingmethod', $_POST[ 'smartsend_gls1_shippingmethod' ] );
				update_option( 'smartsend_gls1_notsms', $_POST[ 'smartsend_gls1_notsms' ] );
				update_option( 'smartsend_gls1_notemail', $_POST[ 'smartsend_gls1_notemail' ] );
				// GLS 2
				update_option( 'smartsend_gls2_shippingmethod', $_POST[ 'smartsend_gls2_shippingmethod' ] );
				update_option( 'smartsend_gls2_notsms', $_POST[ 'smartsend_gls2_notsms' ] );
				update_option( 'smartsend_gls2_notemail', $_POST[ 'smartsend_gls2_notemail' ] );
				// GLS 3
				update_option( 'smartsend_gls3_shippingmethod', $_POST[ 'smartsend_gls3_shippingmethod' ] );
				update_option( 'smartsend_gls3_notsms', $_POST[ 'smartsend_gls3_notsms' ] );
				update_option( 'smartsend_gls3_notemail', $_POST[ 'smartsend_gls3_notemail' ] );
				// Bring 1
				update_option( 'smartsend_bring1_shippingmethod', $_POST[ 'smartsend_bring1_shippingmethod' ] );
				update_option( 'smartsend_bring1_notsms', $_POST[ 'smartsend_bring1_notsms' ] );
				update_option( 'smartsend_bring1_notemail', $_POST[ 'smartsend_bring1_notemail' ] );
				update_option( 'smartsend_bring1_dlv', $_POST[ 'smartsend_bring1_dlv' ] );
				update_option( 'smartsend_bring1_srvid', $_POST[ 'smartsend_bring1_srvid' ] );
				// Closest
				update_option( 'smartsend_closest_notsms', $_POST[ 'smartsend_closest_notsms' ] );
				update_option( 'smartsend_closest_notemail', $_POST[ 'smartsend_closest_notemail' ] );
				update_option( 'smartsend_closest_swipbox_size', $_POST[ 'smartsend_closest_swipbox_size' ] );

			// Put an settings updated message on the screen

			?>
			<div class="updated"><p><strong><?php _e('settings saved.', 'woocommerce-smartsend' ); ?></strong></p></div>
			<?php

		}
		
		// Read in existing option value from database
			// General
			$smartsend_general_id 					= get_option( 'smartsend_general_id' , 'demo' );
			$smartsend_general_key 					= get_option( 'smartsend_general_key' ,'demo' );
			$smartsend_general_testing 				= get_option( 'smartsend_general_testing' );
			// Post Danmark 1
			$smartsend_postdanmark1_shippingmethod 	= get_option( 'smartsend_postdanmark1_shippingmethod' , 'PostDanmark' );
			$smartsend_postdanmark1_notsms 			= get_option( 'smartsend_postdanmark1_notsms' );
			$smartsend_postdanmark1_notemail	 	= get_option( 'smartsend_postdanmark1_notemail' );
			$smartsend_postdanmark1_dlv	 			= get_option( 'smartsend_postdanmark1_dlv' );
			$smartsend_postdanmark1_srvid	 		= get_option( 'smartsend_postdanmark1_srvid' );
			// Post Danmark 2
			$smartsend_postdanmark2_shippingmethod 	= get_option( 'smartsend_postdanmark2_shippingmethod' );
			$smartsend_postdanmark2_notsms 			= get_option( 'smartsend_postdanmark2_notsms' );
			$smartsend_postdanmark2_notemail	 	= get_option( 'smartsend_postdanmark2_notemail' );
			$smartsend_postdanmark2_dlv	 			= get_option( 'smartsend_postdanmark2_dlv' );
			$smartsend_postdanmark2_srvid	 		= get_option( 'smartsend_postdanmark2_srvid' );
			// Post Danmark 3
			$smartsend_postdanmark3_shippingmethod 	= get_option( 'smartsend_postdanmark3_shippingmethod' );
			$smartsend_postdanmark3_notsms 			= get_option( 'smartsend_postdanmark3_notsms' );
			$smartsend_postdanmark3_notemail	 	= get_option( 'smartsend_postdanmark3_notemail' );
			$smartsend_postdanmark3_dlv	 			= get_option( 'smartsend_postdanmark3_dlv' );
			$smartsend_postdanmark3_srvid	 		= get_option( 'smartsend_postdanmark3_srvid' );
			// SwipBox
			$smartsend_swipbox_shippingmethod 		= get_option( 'smartsend_swipbox_shippingmethod' , 'SwipBox' );
			$smartsend_swipbox_size 				= get_option( 'smartsend_swipbox_size' );
			// GLS 1
			$smartsend_gls1_shippingmethod 			= get_option( 'smartsend_gls1_shippingmethod' , 'GLS' );
			$smartsend_gls1_notsms 					= get_option( 'smartsend_gls1_notsms' );
			$smartsend_gls1_notemail 				= get_option( 'smartsend_gls1_notemail' );
			// GLS 2
			$smartsend_gls2_shippingmethod 			= get_option( 'smartsend_gls2_shippingmethod' );
			$smartsend_gls2_notsms 					= get_option( 'smartsend_gls2_notsms' );
			$smartsend_gls2_notemail 				= get_option( 'smartsend_gls2_notemail' );
			// GLS 3
			$smartsend_gls3_shippingmethod 			= get_option( 'smartsend_gls3_shippingmethod' );
			$smartsend_gls3_notsms 					= get_option( 'smartsend_gls3_notsms' );
			$smartsend_gls3_notemail 				= get_option( 'smartsend_gls3_notemail' );
			// Bring 1
			$smartsend_bring1_shippingmethod 		= get_option( 'smartsend_bring1_shippingmethod' , 'Bring' );
			$smartsend_bring1_notsms 				= get_option( 'smartsend_bring1_notsms' );
			$smartsend_bring1_notemail 				= get_option( 'smartsend_bring1_notemail' );
			$smartsend_bring1_dlv	 				= get_option( 'smartsend_bring1_dlv' );
			$smartsend_bring1_srvid	 				= get_option( 'smartsend_bring1_srvid' );
			// Closest
			$smartsend_closest_notsms 				= get_option( 'smartsend_closest_notsms' );
			$smartsend_closest_notemail 			= get_option( 'smartsend_closest_notemail' );
			$smartsend_closest_swipbox_size 		= get_option( 'smartsend_closest_swipbox_size' );

		// Now display the settings editing screen

		echo '<div class="wrap">';
		// header
		echo "<h1>" . __( 'Smart Send Settings', 'woocommerce-smartsend' ) . "</h2><hr />";

		// settings form

		?>

		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_general_id"><?php _e("Smart Send ID (mail):", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<input name="smartsend_general_id" value="<?php echo $smartsend_general_id; ?>" class=" input-text" type="text">
				<p class="description"><?php _e("Use 'demo' to test the system."); ?></p>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_general_key"><?php _e("Smart Send Key:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<input name="smartsend_general_key" value="<?php echo $smartsend_general_key; ?>" class=" input-text" type="text">
				<p class="description"><?php _e("Use 'demo' to test the system."); ?></p>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_general_testing"><?php _e("Testing or live system:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_general_testing">
					<option value="0"<?php echo ($smartsend_general_testing == "0" ? " selected" : ""); ?>>Live</option>
					<option value="1"<?php echo ($smartsend_general_testing == "1" ? " selected" : ""); ?>>Test</option>
				</select>
				<p class="description"><?php _e("Should always be the live system"); ?></p>
			</td>
		</tr>
		
		</tbody>
		</table>
		<div class="smartsend">
			<p style="background: #FFFFFF;border: 1px solid orange;font-weight: bold; padding: 20px;width:55%">
            	If <i>username</i> and <i>licence key</i> is set to <i>demo</i>, then the labels will not be functional and cannot be used to other than system test!</br>
            	If instead you enter a valid user you can use your own carrier information and hence create <i>real usable labels</i>.</br>
            	You can create a <i>free 30 day account</i> at <a href="http://smartsend.dk/prov-gratis" target="_blank">Smart Send</a> - no credit card information needed. Try without any obligations or subscriptions.
            </p>
        </div>
		<hr />

		<h2><?php _e( 'Post Danmark (1) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_postdanmark1_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark1_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_postdanmark1_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark1_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark1_notsms">
					<option value="1"<?php echo ($smartsend_postdanmark1_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark1_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark1_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark1_notemail">
					<option value="1"<?php echo ($smartsend_postdanmark1_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark1_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark1_dlv"><?php _e("Home delivery:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark1_dlv">
					<option value="1"<?php echo ($smartsend_postdanmark1_dlv == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark1_dlv == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark1_srvid"><?php _e("Shipping service:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark1_srvid">
					<option value="auto"<?php echo ($smartsend_postdanmark1_srvid == "auto" ? " selected" : ""); ?>>Standard</option>
					<option value="commercial"<?php echo ($smartsend_postdanmark1_srvid == "commercial" ? " selected" : ""); ?>>Commercial</option>
					<option value="private"<?php echo ($smartsend_postdanmark1_srvid == "private" ? " selected" : ""); ?>>Private</option>
					<option value="PDK332"<?php echo ($smartsend_postdanmark1_srvid == "PDK332" ? " selected" : ""); ?>>Post Danmark Business Priority (Samsending)</option>
					<option value="PDK330"<?php echo ($smartsend_postdanmark1_srvid == "PDK330" ? " selected" : ""); ?>>Post Danmark Business Priority (Single)</option>
					<option value="P24DK"<?php echo ($smartsend_postdanmark1_srvid == "P24DK" ? " selected" : ""); ?>>Post Danmark Customer Return </option>
					<option value="PDKCRP"<?php echo ($smartsend_postdanmark1_srvid == "PDKCRP" ? " selected" : ""); ?>>Post Danmark Customer Return Pickup (kun erhverv)</option>
					<option value="PDKEP"<?php echo ($smartsend_postdanmark1_srvid == "PDKEP" ? " selected" : ""); ?>>Post Danmark Erhvervspakke</option>
					<option value="P52DK"<?php echo ($smartsend_postdanmark1_srvid == "P52DK" ? " selected" : ""); ?>>Post Danmark Pallet</option>
					<option value="P52DKR"<?php echo ($smartsend_postdanmark1_srvid == "P52DKR" ? " selected" : ""); ?>>Post Danmark Pallet Return</option>
					<option value="PDK359"<?php echo ($smartsend_postdanmark1_srvid == "PDK359" ? " selected" : ""); ?>>Post Danmark Parcel Economy</option>
					<option value="PDK340"<?php echo ($smartsend_postdanmark1_srvid == "PDK340" ? " selected" : ""); ?>>Post Danmark Private Priority</option>
					<option value="P19DK"<?php echo ($smartsend_postdanmark1_srvid == "P19DK" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden </option>
					<option value="P19DKNO"<?php echo ($smartsend_postdanmark1_srvid == "P19DKNO" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden (Norge)</option>
					<option value="P19DKBP"<?php echo ($smartsend_postdanmark1_srvid == "P19DKBP" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden Samsending 	</option>
					<option value="PDKWAY"<?php echo ($smartsend_postdanmark1_srvid == "PDKWAY" ? " selected" : ""); ?>>Post Danmark Waybill</option>
					<option value="PDKBREVI"<?php echo ($smartsend_postdanmark1_srvid == "PDKBREVI" ? " selected" : ""); ?>>Post Danmark Varebrev til udlandet </option>
				</select>
			</td>
		</tr>
		
		
		</tbody>
		</table>
		<hr />

		<h2><?php _e( 'Post Danmark (2) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_postdanmark2_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark2_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_postdanmark2_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark2_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark2_notsms">
					<option value="1"<?php echo ($smartsend_postdanmark2_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark2_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark2_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark2_notemail">
					<option value="1"<?php echo ($smartsend_postdanmark2_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark2_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark2_dlv"><?php _e("Home delivery:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark2_dlv">
					<option value="1"<?php echo ($smartsend_postdanmark2_dlv == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark2_dlv == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark2_srvid"><?php _e("Shipping service:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark2_srvid">
					<option value="auto"<?php echo ($smartsend_postdanmark2_srvid == "auto" ? " selected" : ""); ?>>Standard</option>
					<option value="commercial"<?php echo ($smartsend_postdanmark2_srvid == "commercial" ? " selected" : ""); ?>>Commercial</option>
					<option value="private"<?php echo ($smartsend_postdanmark2_srvid == "private" ? " selected" : ""); ?>>Private</option>
					<option value="PDK332"<?php echo ($smartsend_postdanmark2_srvid == "PDK332" ? " selected" : ""); ?>>Post Danmark Business Priority (Samsending)</option>
					<option value="PDK330"<?php echo ($smartsend_postdanmark2_srvid == "PDK330" ? " selected" : ""); ?>>Post Danmark Business Priority (Single)</option>
					<option value="P24DK"<?php echo ($smartsend_postdanmark2_srvid == "P24DK" ? " selected" : ""); ?>>Post Danmark Customer Return </option>
					<option value="PDKCRP"<?php echo ($smartsend_postdanmark2_srvid == "PDKCRP" ? " selected" : ""); ?>>Post Danmark Customer Return Pickup (kun erhverv)</option>
					<option value="PDKEP"<?php echo ($smartsend_postdanmark2_srvid == "PDKEP" ? " selected" : ""); ?>>Post Danmark Erhvervspakke</option>
					<option value="P52DK"<?php echo ($smartsend_postdanmark2_srvid == "P52DK" ? " selected" : ""); ?>>Post Danmark Pallet</option>
					<option value="P52DKR"<?php echo ($smartsend_postdanmark2_srvid == "P52DKR" ? " selected" : ""); ?>>Post Danmark Pallet Return</option>
					<option value="PDK359"<?php echo ($smartsend_postdanmark2_srvid == "PDK359" ? " selected" : ""); ?>>Post Danmark Parcel Economy</option>
					<option value="PDK340"<?php echo ($smartsend_postdanmark2_srvid == "PDK340" ? " selected" : ""); ?>>Post Danmark Private Priority</option>
					<option value="P19DK"<?php echo ($smartsend_postdanmark2_srvid == "P19DK" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden </option>
					<option value="P19DKNO"<?php echo ($smartsend_postdanmark2_srvid == "P19DKNO" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden (Norge)</option>
					<option value="P19DKBP"<?php echo ($smartsend_postdanmark2_srvid == "P19DKBP" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden Samsending 	</option>
					<option value="PDKWAY"<?php echo ($smartsend_postdanmark2_srvid == "PDKWAY" ? " selected" : ""); ?>>Post Danmark Waybill</option>
					<option value="PDKBREVI"<?php echo ($smartsend_postdanmark2_srvid == "PDKBREVI" ? " selected" : ""); ?>>Post Danmark Varebrev til udlandet </option>
				</select>
			</td>
		</tr>
		
		
		</tbody>
		</table>
		<hr />

		<h2><?php _e( 'Post Danmark (3) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_postdanmark3_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark3_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_postdanmark3_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark3_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark3_notsms">
					<option value="1"<?php echo ($smartsend_postdanmark3_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark3_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark3_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark3_notemail">
					<option value="1"<?php echo ($smartsend_postdanmark3_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark3_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark3_dlv"><?php _e("Home delivery:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark3_dlv">
					<option value="1"<?php echo ($smartsend_postdanmark3_dlv == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_postdanmark3_dlv == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_postdanmark3_srvid"><?php _e("Shipping service:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_postdanmark3_srvid">
					<option value="auto"<?php echo ($smartsend_postdanmark3_srvid == "auto" ? " selected" : ""); ?>>Standard</option>
					<option value="commercial"<?php echo ($smartsend_postdanmark3_srvid == "commercial" ? " selected" : ""); ?>>Commercial</option>
					<option value="private"<?php echo ($smartsend_postdanmark3_srvid == "private" ? " selected" : ""); ?>>Private</option>
					<option value="PDK332"<?php echo ($smartsend_postdanmark3_srvid == "PDK332" ? " selected" : ""); ?>>Post Danmark Business Priority (Samsending)</option>
					<option value="PDK330"<?php echo ($smartsend_postdanmark3_srvid == "PDK330" ? " selected" : ""); ?>>Post Danmark Business Priority (Single)</option>
					<option value="P24DK"<?php echo ($smartsend_postdanmark3_srvid == "P24DK" ? " selected" : ""); ?>>Post Danmark Customer Return </option>
					<option value="PDKCRP"<?php echo ($smartsend_postdanmark3_srvid == "PDKCRP" ? " selected" : ""); ?>>Post Danmark Customer Return Pickup (kun erhverv)</option>
					<option value="PDKEP"<?php echo ($smartsend_postdanmark3_srvid == "PDKEP" ? " selected" : ""); ?>>Post Danmark Erhvervspakke</option>
					<option value="P52DK"<?php echo ($smartsend_postdanmark3_srvid == "P52DK" ? " selected" : ""); ?>>Post Danmark Pallet</option>
					<option value="P52DKR"<?php echo ($smartsend_postdanmark3_srvid == "P52DKR" ? " selected" : ""); ?>>Post Danmark Pallet Return</option>
					<option value="PDK359"<?php echo ($smartsend_postdanmark3_srvid == "PDK359" ? " selected" : ""); ?>>Post Danmark Parcel Economy</option>
					<option value="PDK340"<?php echo ($smartsend_postdanmark3_srvid == "PDK340" ? " selected" : ""); ?>>Post Danmark Private Priority</option>
					<option value="P19DK"<?php echo ($smartsend_postdanmark3_srvid == "P19DK" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden </option>
					<option value="P19DKNO"<?php echo ($smartsend_postdanmark3_srvid == "P19DKNO" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden (Norge)</option>
					<option value="P19DKBP"<?php echo ($smartsend_postdanmark3_srvid == "P19DKBP" ? " selected" : ""); ?>>Post Danmark Privatpakker Norden Samsending 	</option>
					<option value="PDKWAY"<?php echo ($smartsend_postdanmark3_srvid == "PDKWAY" ? " selected" : ""); ?>>Post Danmark Waybill</option>
					<option value="PDKBREVI"<?php echo ($smartsend_postdanmark3_srvid == "PDKBREVI" ? " selected" : ""); ?>>Post Danmark Varebrev til udlandet </option>
				</select>
			</td>
		</tr>
		
		
		</tbody>
		</table>
		<hr />

		<h2><?php _e( 'SwipBox Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_swipbox_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_swipbox_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_swipbox_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_swipbox_size"><?php _e("Package size:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_swipbox_size">
					<option value="1"<?php echo ($smartsend_swipbox_size == "1" ? " selected" : ""); ?>>Small</option>
					<option value="2"<?php echo ($smartsend_swipbox_size == "2" ? " selected" : ""); ?>>Medium</option>
					<option value="3"<?php echo ($smartsend_swipbox_size == "3" ? " selected" : ""); ?>>Large</option>
				</select>
				<p class="description"><?php _e("Small: 10x40x60cm, Medium: 20x40x60cm, Large: 40x40x60cm"); ?></p>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />
		
		<h2><?php _e( 'GLS (1) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_gls1_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls1_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_gls1_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls1_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls1_notsms">
					<option value="1"<?php echo ($smartsend_gls1_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls1_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls1_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls1_notemail">
					<option value="1"<?php echo ($smartsend_gls1_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls1_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />
		
		<h2><?php _e( 'GLS (2) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_gls2_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls2_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_gls2_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls2_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls2_notsms">
					<option value="1"<?php echo ($smartsend_gls2_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls2_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls2_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls2_notemail">
					<option value="1"<?php echo ($smartsend_gls2_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls2_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />
		
		<h2><?php _e( 'GLS (3) Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_gls3_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls3_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_gls3_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls3_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls3_notsms">
					<option value="1"<?php echo ($smartsend_gls3_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls3_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_gls3_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_gls3_notemail">
					<option value="1"<?php echo ($smartsend_gls3_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls3_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />
				
		<h2><?php _e( 'Bring Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_bring1_shippingmethod"><?php _e("Shipping method:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_bring1_shippingmethod">
					<option value="">Disabled</option>
					<?php
					$shipping_methods = smartsend_labelgenerator_get_all_shipping_methods();
					foreach($shipping_methods as $shipping_method) {
						echo '<option value="'.$shipping_method->id.'"'.($smartsend_bring1_shippingmethod == $shipping_method->id ? " selected" : "").'>'.$shipping_method->method_title.' ('.$shipping_method->title.')</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_bring1_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_bring1_notsms">
					<option value="1"<?php echo ($smartsend_bring1_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_bring1_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_bring1_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_bring1_notemail">
					<option value="1"<?php echo ($smartsend_bring1_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_bring1_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_bring1_dlv"><?php _e("Home delivery:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_bring1_dlv">
					<option value="1"<?php echo ($smartsend_bring1_dlv == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_bring1_dlv == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_bring1_srvid"><?php _e("Shipping service:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_bring1_srvid">
					<option value="auto"<?php echo ($smartsend_bring1_srvid == "auto" ? " selected" : ""); ?>>Standard</option>
					<option value="commercial"<?php echo ($smartsend_bring1_srvid == "commercial" ? " selected" : ""); ?>>Commercial</option>
					<option value="private"<?php echo ($smartsend_bring1_srvid == "private" ? " selected" : ""); ?>>Private</option>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />
		
				<h2><?php _e( 'Closest Pickup Settings', 'woocommerce-smartsend' ); ?></h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="smartsend_closest_notsms"><?php _e("SMS notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_closest_notsms">
					<option value="1"<?php echo ($smartsend_closest_notsms == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_closest_notsms == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_closest_notemail"><?php _e("E-mail notification:", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_closest_notemail">
					<option value="1"<?php echo ($smartsend_gls1_notemail == "1" ? " selected" : ""); ?>>Yes</option>
					<option value="0"<?php echo ($smartsend_gls1_notemail == "0" ? " selected" : ""); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="smartsend_closest_swipbox_size"><?php _e("Package size (only for SwipBox):", 'woocommerce-smartsend' ); ?></label></th>
			<td>
				<select name="smartsend_closest_swipbox_size">
					<option value="1"<?php echo ($smartsend_closest_swipbox_size == "1" ? " selected" : ""); ?>>Small</option>
					<option value="2"<?php echo ($smartsend_closest_swipbox_size == "2" ? " selected" : ""); ?>>Medium</option>
					<option value="3"<?php echo ($smartsend_closest_swipbox_size == "3" ? " selected" : ""); ?>>Large</option>
				</select>
				<p class="description"><?php _e("Small: 10x40x60cm, Medium: 20x40x60cm, Large: 40x40x60cm"); ?></p>
			</td>
		</tr>
		</tbody>
		</table>
		<hr />

		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>

		</form>
		</div>

		<?php
	}

/**
* All helping functions
*
*/

	function smartsend_labelgenerator_get_all_shipping_methods() {
		$shipping = new WC_Shipping;
		if(!$shipping->load_shipping_methods() ) return false;
		
		if(is_array($shipping->get_shipping_methods())) {
			return $shipping->get_shipping_methods();
		} else {
			return false;
		}
	}
	
	function smartsend_labelgenerator_print_all_shipping_methods() {
		$shipping = smartsend_labelgenerator_get_all_shipping_methods();
		
		if(!$shipping ) {
			return false;
		} else {
			foreach($shipping as $shipping_method) {
				/**
				 * id: 					Unique shipping method id.
				 * method_title: 		Admin title.
				 * method_description 	Description shown in admin.
				 * title				Frontend title.
				 */
				echo $shipping_method->method_title ." (ID: ". $shipping_method->id .", Title: ". $shipping_method->title .")</br>";
			}
		}
	}