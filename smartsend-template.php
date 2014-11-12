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
 * Template Function Overrides
 *
 */
 
/**
 * Part to add an action to the order info page (single print)
 *
 */
 
	 // define the item in the meta box by adding an item to the $actions array
	function smartsend_labelgenerator_add_order_meta_box_actions( $actions ) {
		$actions['ws_smartsend_generate_label_single'] = __( 'Generate label' , 'text_domain');
		return $actions;
	}
	// add our own item to the order actions meta box
	add_action( 'woocommerce_order_actions', 'smartsend_labelgenerator_add_order_meta_box_actions' );

	// process the custom order meta box order action
	add_action( 'woocommerce_order_action_ws_smartsend_generate_label_single', 'smartsend_labelgenerator_process_order_meta_box_actions' );


/**
 * Part to add an action to the order list (bulk print)
 *
 */
	
	//future way to add the button.  
	//add_action('bulk_actions-edit-shop_order','smartsend_labelgenerator_add_order_meta_box_actions' );
  
	// admin actions/filters
	add_action('admin_footer-edit.php', 'smartsend_labelgenerator_custom_bulk_admin_footer');
	add_action('load-edit.php',         'smartsend_labelgenerator_custom_bulk_action');
	add_action('admin_notices',         'smartsend_labelgenerator_custom_bulk_admin_notices');		
	
	
	/**
	 * Step 1: add the custom Bulk Action to the select menus
	 */
	function smartsend_labelgenerator_custom_bulk_admin_footer() {
		global $post_type;
	
		if($post_type == 'shop_order') {
			?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('<option>').val('export').text('<?php _e('Generate label')?>').appendTo("select[name='action']");
						jQuery('<option>').val('export').text('<?php _e('Generate label')?>').appendTo("select[name='action2']");
					});
				</script>
			<?php
		}
	}
	
	/**
	 * Step 2: handle the custom Bulk Action
	 * 
	 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
	 */
	function smartsend_labelgenerator_custom_bulk_action() {
		global $typenow;
		$post_type = $typenow;

		if($post_type == 'shop_order') {
		
			// get the action
			$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
			$action = $wp_list_table->current_action();

			$allowed_actions = array("export");
			if(!in_array($action, $allowed_actions)) return;

			// security check
			check_admin_referer('bulk-posts');

			// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
			if(isset($_REQUEST['post'])) {
				$post_ids = array_map('intval', $_REQUEST['post']);
			}
		
			if(empty($post_ids)) return;
		
			// this is based on wp-admin/edit.php
			$sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
			if ( ! $sendback )
				$sendback = admin_url( "edit.php?post_type=$post_type" );
		
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );
		
			switch($action) {
				case 'export':
				
					// if we set up user permissions/capabilities, the code might look like:
					//if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
					//	wp_die( __('You are not allowed to export this post.') );
				
				/*	$smartsend = 0;
					$json = new smartsend_label;
					foreach( $post_ids as $post_id ) {
					
						//if ( !$this->process_order_list_actions($post_id) )
						if ( !process_order_list_actions($post_id) )
							wp_die( __('Error exporting post.') );
	
						$smartsend++;
					} */
					$smartsend_label = new smartsend_label;
					
					$smartsend = $smartsend_label->actionBulk($post_ids);	
				
					$sendback = add_query_arg( array('smartsend' => urlencode(json_encode($smartsend['response'])), 'ids' => join(',', $post_ids), 'smartsenderror' => join(',', $smartsend['error'])), $sendback );
				break;
			
				default: return;
			}
		
			$sendback = remove_query_arg( array('export', 'message', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		
			wp_redirect($sendback);
			exit();
		}
	}
	
	/**
	 * Step 3: display an admin notice on the Posts page after exporting
	 */
	function smartsend_labelgenerator_custom_bulk_admin_notices() {
		global $post_type, $pagenow;
	
		if($pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_REQUEST['smartsend'])) {
			if($_REQUEST['smartsend'] != '') {
				$response_decoded = json_decode(stripslashes(urldecode($_REQUEST['smartsend'])));
				
				if(is_array($response_decoded)) {
					foreach($response_decoded as $notice) {
						if(isset($notice->link)) {
							echo "<div id=\"message\" class=\"updated\"><a href=\"{$notice->link}\" target=\"_blank\">{$notice->carrier} - Link til print</a></div>";
						} elseif(isset($notice->pdflink)) {
							echo "<div id=\"message\" class=\"updated\"><a href=\"{$notice->pdflink}\" target=\"_blank\">{$notice->carrier} - Link til pdf</a></div>";
						} else {
							echo "<div id=\"message\" class=\"".($notice->type == 'succes' ? 'updated' : $notice->type)."\"><p>{$notice->message}</p></div>";
						}
					}
				} else {
					echo "<div id=\"message\" class=\"error\"><p>No orders selected</p></div>";
				}
			}
			
			if(isset($_REQUEST['smartsenderror']) && $_REQUEST['smartsenderror'] != '') {
				$error_message = __('Unknown carrier for order(s):').' '.$_REQUEST['smartsenderror'];
				echo "<div id=\"message\" class=\"error\"><p>{$error_message}</p></div>";
			}
		}
	}

/**
 * Single and bulk order actions
 *
 */
 
 	// run the code that should execute with this action is triggered
	// This is the SINGLE ORDER ACTION
	function smartsend_labelgenerator_process_order_meta_box_actions( $order ) {
	
		// security check
		//check_admin_referer('bulk-posts');
		
		$sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
		
		$sendback = admin_url( "edit.php?post_type=shop_order" );
		
		$smartsend_label = new smartsend_label;
		$smartsend = $smartsend_label->actionSingle($order);	
				
		$sendback = add_query_arg( array('smartsend' => urlencode(json_encode($smartsend['response'])), 'ids' => $order->id, 'smartsenderror' => join(',', $smartsend['error'])), $sendback );
		
		$sendback = remove_query_arg( array('export', 'message', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		
		wp_redirect($sendback);
		exit();
	
		//Redirect to the order list
	/*	$sendback = admin_url( "edit.php?post_type=shop_order" );
		$sendback = add_query_arg( array('smartsend' => 1, 'ids' => $order->id ), $sendback );
		$sendback = remove_query_arg( array('export', 'message', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		wp_redirect($sendback);
		exit(); */
	}
/*
	function process_order_list_actions($post_id) {
		// do whatever work needs to be done
		$order = new WC_Order($post_id);
		//echo $order->get_order_number();
		//smartsend_labelgenerator_process_order_meta_box_actions( $order );
		return true;
	} */