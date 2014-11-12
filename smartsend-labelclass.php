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

class smartsend_label {

	private $json = array();
	private $error = array();
	private $response;
	private $posterror;

	public function __construct() {
	
	}
	
	private function addError($order_id) {
		$this->error[] = $order_id;
		return true;
	}
	
	private function getJson() {
		return $this->json;
	}
	
	private function getError() {
		return $this->error;
	}
	
	private function getPosterror() {
		return $this->posterror;
	}
	
	private function getResponse() {
		return $this->response;
	}
	
	private function setResponse($response) {
		$this->response = $response;
		return true;
	}
	
	private function setPosterror($error) {
		$this->posterror = $error;
		return true;
	}
	
	public function actionBulk($post_ids) {
		if(!is_array($post_ids))
			return false;
		
		$i=0;
		foreach( $post_ids as $order_id ) {
			$order 		= new WC_Order($order_id);
			if(!$this->addOrder($order)) {
				$this->addError($order_id);
			} else {
				$i++;
			}
		}
		
		if(!$this->addInfo( true ) )
			return false;
		
		if($i > 0) {
			if( $this->sendOrders() ) {				
				return array('response'=> $this->handleResponse(), 'error' => $this->getError() );
			} else {
				wp_die( 'Unexpected error when sending orders: <pre>' . var_export( $this->getPosterror(), true ) . '</pre>' );
			}
		} else {
			return array('sent'=> '', 'error' => $this->getError() );
		}
		
	}
	
	public function actionSingle($order) {
	
		if(!$this->addOrder($order)) {
			$this->addError($order->id);
		}
		$this->addInfo( false );
		
		if( $this->sendOrders() ) {				
			return array('response'=> $this->handleResponse(), 'error' => $this->getError() );
		} else {
			wp_die( 'Unexpected error when sending orders: <pre>' . var_export( $this->getPosterror(), true ) . '</pre>' );
		}
	
	}
	
	public function handleResponse() {
		$response_decoded = json_decode( $this->getResponse() );
		$messages = array();
		if($response_decoded->status > 210 && $response_decoded->status <= 999) {
			$messages[] = array(
				"type" 	=> 'error',
				"message" => $response_decoded->status.': '.$response_decoded->message
				);
		}
		
		//var_dump( $response_decoded ); exit();

		foreach($response_decoded->carriers as $carrier=>$carrier_response) {
			if($carrier_response != '') {
				if($carrier_response->status >= 200 && $carrier_response->status < 210) {
					if( $carrier_response->link != '') {
						$messages[] = array(
							"type" 		=> 'succes',
							"link" 		=> $carrier_response->link,
							"carrier" 	=> $carrier,
							);
					} elseif( $carrier_response->pdflink != '' ) {
						$messages[] = array(
							"type" 		=> 'succes',
							"pdflink" 	=> $carrier_response->pdflink,
							"carrier" 	=> $carrier,
							);
					} else {
						$messages[] = array(
							"type" 	=> ($order->status <= 210 && $order->status >= 200 ? 'succes' : 'error'),
							"message" => $carrier.' - '.$carrier_response->status.': '.$carrier_response->message
							);

					}
				} else {
					if( $carrier_response->link != '') {
						$messages[] = array(
							"type" 		=> 'succes',
							"link" 		=> $carrier_response->link,
							"carrier" 	=> $carrier,
							);
					} elseif( $carrier_response->pdflink != '' ) {
						$messages[] = array(
							"type" 		=> 'succes',
							"pdflink" 	=> $carrier_response->pdflink,
							"carrier" 	=> $carrier,
							);
					}
				
					$messages[] = array(
							"type" 	=> 'error',
							"message" => $carrier.' - '.$carrier_response->status.': '.$carrier_response->message
							);
					foreach($carrier_response->orders as $order) {
						$messages[] = array(
							"type" 	=> ($order->status <= 210 && $order->status >= 200 ? 'succes' : 'error'),
							"message" => "Order ".$order->id." from <i>".$carrier."</i><br>".$order->status.": ".$order->message
							);
					}
				}
			}
		}
		return $messages;
	}
	
	private function jsonRemoveUnicodeSequences($struct)
    {
        return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
    }
	
	private function addInfo($bulk) {
	
		$plugin_info = get_plugin_data(__DIR__ . '/smartsend-labelgenerator.php', $markup = true, $translate = true );
		
		$this->json['settings'] = array();
		$this->json['settings']['username'] = get_option( 'smartsend_general_id' , 'demo' );
	//	$this->json['settings']['smartsendKey'] = get_option( 'smartsend_general_key' );
		$this->json['settings']['bulk'] = $bulk;
		$this->json['settings']['CMSversion'] = $this->wpbo_get_woo_version_number();
		$this->json['settings']['CMSplatform'] = 'WooCommerce';
		$this->json['settings']['APPversion'] = $plugin_info["Version"];
		$this->json['settings']['testing'] = (get_option( 'smartsend_general_testing' ) == '1' ? true : false);
		$this->json['settings']['hash'] = (string) hash_hmac("sha256", $this->jsonRemoveUnicodeSequences($this->json['orders']), get_option( 'smartsend_general_key' , 'demo' ));
		
		return true;
	
	}
	
	private function getSmartsendCheckoutData($order) {
		// To check for Smart Send checkout
		$store_pickup = get_post_custom($order->id);
		if(is_array($store_pickup)) {
			$store_pickup = unserialize($store_pickup['store_pickup'][0]);
			if(!is_array($store_pickup)) {
				$store_pickup = unserialize($store_pickup);
				if( isset($store_pickup['id']) && $store_pickup['id'] != '') {
					return $store_pickup;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	private function determineCheckout($order) {

		// To check for vConnect checkout
		$pacsoftServicePointArray = explode(":",$order->shipping_address_2);
		
		// Check which one was used - if any
		if (isset($pacsoftServicePointArray) && $pacsoftServicePointArray[0] == 'ServicePointID') {
			return 'vconnect';
		} elseif($this->getSmartsendCheckoutData($order) != false) {
			return 'smartsend';
		} else {
			return false;
		}
	}
	
	private function determineCarrierSystem($order) {
		if($this->determineCarrierSettings($order) == 'postdanmark1' 
			|| $this->determineCarrierSettings($order) == 'postdanmark2'
			|| $this->determineCarrierSettings($order) == 'postdanmark3' ) {
			return 'postdk';
		} elseif($this->determineCarrierSettings($order) == 'gls1'
			|| $this->determineCarrierSettings($order) == 'gls2'  
			|| $this->determineCarrierSettings($order) == 'gls3' ) {
			return 'gls';
		} elseif($this->determineCarrierSettings($order) == 'bring1') {
			return 'bringdk';
		} elseif($this->determineCarrierSettings($order) == 'closest') {
			$pickup_date = $this->getSmartsendCheckoutData($order);
			if($pickup_date['type'] == 'PostDanmark') {
				return 'postdk';
			} elseif($pickup_date['type'] == 'GLS') {
				return 'gls';
			} elseif($pickup_date['type'] == 'SwipBox') {
				return 'swipbox';
			} elseif($pickup_date['type'] == 'Bring') {
				return 'bringdk';
			} else {
				return false;
			}	
		} else {
			return $this->determineCarrierSettings($order);
		}
	}
	
	private function determineCarrierSettings($order) {
	
		if($this->getShippingMethod($order) == get_option( 'smartsend_postdanmark1_shippingmethod' , 'PostDanmark' )) {
			return 'postdanmark1';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_postdanmark2_shippingmethod' )) {
			return 'postdanmark2';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_postdanmark3_shippingmethod' )) {
			return 'postdanmark3';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_gls1_shippingmethod' , 'GLS' )) {
			return 'gls1';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_gls2_shippingmethod' )) {
			return 'gls2';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_gls3_shippingmethod' )) {
			return 'gls3';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_swipbox_shippingmethod' , 'SwipBox' )) {
			return 'swipbox';
		} elseif($this->getShippingMethod($order) == get_option( 'smartsend_bring1_shippingmethod' , 'Bring' )) {
			return 'bring1';
		} elseif($this->getShippingMethod($order) == 'PickupPoints') {
			return 'closest';
		} else {
			return false;
		}
	
	}
	
	private function getShippingMethod($order) {
		$method_array = array_values($order->get_shipping_methods());
		$method_id = $method_array[0]['method_id'];
		if(isset($method_id) && $method_id != '') {
			return $method_id;
		} else {
			return false;
		}
	}
	
	private function getOrderTotalWeight($order) {
		$weight = 0;
		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach( $order->get_items() as $item ) {
				if ( $item['product_id'] > 0 ) {
					$_product = $order->get_product_from_item( $item );
					if ( ! $_product->is_virtual() ) {
						$weight += $_product->get_weight() * $item['qty'];
					}
				}
			}
		}
		if ( $weight > 0 ) {
			return $weight;
		} else {
			return null;
		}
	}
	
	private function addOrder($order) {
	
		$order_information = array(
			'ordernumber' 	=> $order->id,
			'reference' 	=> $order->id.'-'.time().'-'.rand(100000,999999),
			'fromID' 		=> '1',
			'total' 		=> $order->get_total(),
			'postage'		=> $order->get_total_shipping(),
			'subtotal'		=> $order->get_total()-$order->get_total_shipping(),
			'currency' 		=> $order->get_order_currency(),
			'freetext1' 	=> $order->customer_note,
			'freetext2' 	=> '',
			"container" 	=> array(
				array(
					"type" 		=> "parcel",
                    "measure" 	=> "totals",
                    "copies"	=> 1,
					"weight" 	=> $this->getOrderTotalWeight($order),
					"contents" 	=> ''
					)
				)
			);
		
		$checkout = $this->determineCheckout($order);
		
		if( $checkout == 'smartsend' ) {
			$order_information['rcvid'] = $this->addRecieverShipping($order);
			$order_information['agentto'] = $this->addAgenttoSmartsend($order);;
		} elseif( $checkout == 'vconnect' ) {
			$order_information['rcvid'] = $this->addRecieverBilling($order);
			$order_information['agentto'] = $this->addAgenttoVconnect($order);
		} else {
			$order_information['rcvid'] = $this->addRecieverShipping($order);
			$order_information['agentto'] = null;
		}
		
		$carrier_system = $this->determineCarrierSystem($order);
		if(!$carrier_system)
			return false;
		
		$order_information['system'] = $carrier_system;
	
		$shipping_method = $this->determineCarrierSettings($order);
		if(!$shipping_method)
			return false;
			
		if($shipping_method == 'postdanmark1') {
			$order_information['services'] = $this->addSettingsPostdanmark($order,1);
		} elseif($shipping_method == 'postdanmark2') {
			$order_information['services'] = $this->addSettingsPostdanmark($order,2);
		} elseif($shipping_method == 'postdanmark3') {
			$order_information['services'] = $this->addSettingsPostdanmark($order,3);
		} elseif($shipping_method == 'swipbox') {
			$order_information['services'] = $this->addSettingsSwipbox($order);
		} elseif($shipping_method == 'gls1') {
			$order_information['services'] = $this->addSettingsGls($order,1);
		} elseif($shipping_method == 'gls2') {
			$order_information['services'] = $this->addSettingsGls($order,2);
		} elseif($shipping_method == 'gls3') {
			$order_information['services'] = $this->addSettingsGls($order,3);
		} elseif($shipping_method == 'bring1') {
			$order_information['services'] = $this->addSettingsBring($order,1);
		} elseif($shipping_method == 'closest') {
			$order_information['services'] = $this->addSettingsClosest($order);
		} else {
			return false;
		}
		
		if(!is_array($this->json['orders'])) {
			 $this->json['orders'] = array();
		}
		
		$this->json['orders'][] = $order_information;
		
		return true;
	
	}
	
	private function addSettingsClosest($order) {
		
		$pickup_date = $this->getSmartsendCheckoutData($order);
		
		if($pickup_date['type'] == 'PostDanmark') {
		/* Add Post Danmark settings */
			return array(
				'shipdate' 	=> array(
					'enable' 	=> 0,
					'misc' 		=> ''//"2014-03-17"
				),
				'srvid' 	=> 'P19DK',
				'addon' 	=> array(
					'pupopt' 	=> array(
						'enable' 	=> 1,
					),
					'DLV' 		=> array(
						'enable' 	=> 0,
					),
					'NOTEMAIL' 	=> array(
						'enable' 	=> (int) get_option( 'smartsend_closest_notemail' ),
						'misc' 		=> $order->billing_email
					),
					'NOTSMS' 	=> array(
					   'enable' 	=> (int) get_option( 'smartsend_closest_notsms' ),
						'misc'		=> $order->billing_phone
					)
				),
				'enot' 		=> array(
					'enable' 	=> 0,
					'from' 		=> '',
					'to'		=> ''
				)
		   );
		   
		} elseif($pickup_date['type'] == 'GLS') {
		/* Add GLS settings */
			return array(
				'addon' 	=> array(
					'NOTEMAIL' 	=> array(
						'enable' 	=> (int) get_option( 'smartsend_closest_notemail' ),
						'misc' 		=> $order->billing_email
					),
					'NOTSMS' 	=> array(
					   'enable' 	=> (int) get_option( 'smartsend_closest_notsms' ),
						'misc'		=> $order->billing_phone
					)
				)
			);
		} elseif($pickup_date['type'] == 'SwipBox') {
		/* Add SwipBox settings */
			return array(
				'sr_hours' 		=> 72,
				's_webshop_id'	=> null,
				'pay_status'	=> null,
				'parcel_size'	=> (int) get_option( 'smartsend_closest_swipbox_size' , 1),
				'test_parcel'	=> (int) get_option( 'smartsend_general_testing' , 0),
				'parcel_id'		=> 0,
				'return_parcel'	=> 0
        	);
		} elseif($pickup_date['type'] == 'Bring') {
		/* Add Bring settings */
			return array(
				'srvid' 	=> 'private',
				'DLV' 		=> array(
					'enable' 	=> 0,
				),
				'NOTEMAIL' 	=> array(
					'enable' 	=> (int) get_option( 'smartsend_closest_notemail' , 1),
					'misc' 		=> $order->billing_email
				),
				'NOTSMS' 	=> array(
				   'enable' 	=> (int) get_option( 'smartsend_closest_notsms' , 1),
					'misc'		=> $order->billing_phone
				)
			);
		} else {
			return false;
		}
	}
	
	private function addSettingsPostdanmark($order, $id) {
		
		if($this->determineCheckout($order)) {
			$srvid = 'P19DK';
		} else {
			if(get_option( 'smartsend_postdanmark'.(string)$id.'_srvid' , 'auto') == 'auto') {
				if($order->billing_company != '') {
					$srvid = 'commercial';
				} else {
					$srvid = 'private';
				}
			} else {
				$srvid = get_option( 'smartsend_postdanmark'.(string)$id.'_srvid' );
			}
		}
	
		return array(
        	'shipdate' 	=> array(
            	'enable' 	=> 0,
                'misc' 		=> ''//"2014-03-17"
            ),
            'srvid' 	=> $srvid,
            'addon' 	=> array(
                'pupopt' 	=> array(
                    'enable' 	=> (!$this->determineCheckout($order) ? 0 : 1 )
                ),
                'DLV' 		=> array(
                	'enable' 	=> (!$this->determineCheckout($order) ? (int)get_option( 'smartsend_postdanmark'.(string)$id.'_dlv' ) : 0 ),
                ),
                'NOTEMAIL' 	=> array(
        	        'enable' 	=> (int) get_option( 'smartsend_postdanmark'.(string)$id.'_notemail' ),
                    'misc' 		=> $order->billing_email
                ),
                'NOTSMS' 	=> array(
    	           'enable' 	=> (int) get_option( 'smartsend_postdanmark'.(string)$id.'_notsms' ),
                    'misc'		=> $order->billing_phone
                )
            ),
            'enot' 		=> array(
                'enable' 	=> 0,
                'from' 		=> '',
                'to'		=> ''
            )
       );
	}
	
	private function addSettingsSwipbox($order) {
		    
		return array(
			'sr_hours' 		=> 72,
            's_webshop_id'	=> null,
            'pay_status'	=> null,
            'parcel_size'	=> (int) get_option( 'smartsend_swipbox_size' , 1),
            'test_parcel'	=> (int) get_option( 'smartsend_general_testing' , 0),
        	'parcel_id'		=> 0,
        	'return_parcel'	=> 0
        	);
	}
	
	private function addSettingsGls($order, $id) {
		return array(
            'addon' 	=> array(
                'NOTEMAIL' 	=> array(
        	        'enable' 	=> (int) get_option( 'smartsend_gls'.(string)$id.'_notemail' ),
                    'misc' 		=> $order->billing_email
                ),
                'NOTSMS' 	=> array(
    	           'enable' 	=> (int) get_option( 'smartsend_gls'.(string)$id.'_notsms' ),
                    'misc'		=> $order->billing_phone
                )
            )
        );
	}
	
	private function addSettingsBring($order, $id) {
	
		if($this->determineCheckout($order)) {
			$srvid = 'private';
		} else {
			if(get_option( 'smartsend_bring'.(string)$id.'_srvid' , 'auto') == 'auto') {
				if($order->billing_company != '') {
					$srvid = 'commercial';
				} else {
					$srvid = 'private';
				}
			} else {
				$srvid = get_option( 'smartsend_bring'.(string)$id.'_srvid' );
			}
		}
	
		return array(
			'srvid' 	=> $srvid,
			'DLV' 		=> array(
				'enable' 	=> (!$this->determineCheckout($order) ? (int)get_option( 'smartsend_bring'.(string)$id.'_dlv' ) : 0 ),
			),
			'NOTEMAIL' 	=> array(
				'enable' 	=> (int) get_option( 'smartsend_bring'.(string)$id.'_notemail' ),
				'misc' 		=> $order->billing_email
			),
			'NOTSMS' 	=> array(
			   'enable' 	=> (int) get_option( 'smartsend_bring'.(string)$id.'_notsms' ),
				'misc'		=> $order->billing_phone
			)
        );
	}
	
	private function addRecieverShipping($order) {
		return array(
			'id' 		=> (isset($order->user_id) ? $order->user_id : 'guest').'-'.rand(100000,999999),
			'name'    	=> $order->shipping_first_name.' '.$order->shipping_last_name,
			'contact' 	=> '',
			'company'   => $order->shipping_company,
			'address1'  => $order->shipping_address_1,
			'address2'  => $order->shipping_address_2,
			'city'      => $order->shipping_city,
			'zip'     	=> $order->shipping_postcode,
			'country'   => $order->shipping_country,
			'email' 	=> $order->billing_email,
        	'phone' 	=> $order->billing_phone,
        	'sms' 		=> $order->billing_phone
 			);
	}
	
	private function addRecieverBilling($order) {
		return array(
			'id' 		=> (isset($order->user_id) ? $order->user_id : 'guest').'-'.rand(100000,999999),
			'name'    	=> $order->billing_first_name.' '.$order->billing_last_name,
			'contact' 	=> '',
			'company'   => $order->billing_company,
			'address1'  => $order->billing_address_1,
			'address2'  => $order->billing_address_2,
			'city'      => $order->billing_city,
			'zip'     	=> $order->billing_postcode,
			'country'   => $order->billing_country,
			'email' 	=> $order->billing_email,
        	'phone' 	=> $order->billing_phone,
        	'sms' 		=> $order->billing_phone
 			);
	}
	
	private function addAgenttoSmartsend($order) {
	
		$store_pickup = $this->getSmartsendCheckoutData($order);
	
		$agentto = array(
			'id' 		=> $store_pickup['id'] ,
			'name'    	=> $store_pickup['company'],
			'contact' 	=> '',
			'company'   => '',
			'address1'  => $store_pickup['street'],
			'address2'  => '',
			'city'      => $store_pickup['city'],
			'zip'     	=> $store_pickup['zip'] ,
			'country'   => $store_pickup['country'],
            'agentno'	=> $store_pickup['id'] 
 			);
 			
 		if($this->determineCarrierSystem($order) == 'postdk') {
 			$agentto['parid']	= 'PDK';
 		}
 		
 		return $agentto;
	}
	
	private function addAgenttoVconnect($order) {
	
		$pacsoftServicePointArray = explode(":",$order->shipping_address_2);
	
		$agentto = array(
			'id' 		=> $pacsoftServicePointArray[1],
			'name'    	=> $order->shipping_first_name.' '.$order->shipping_last_name,
			'contact' 	=> '',
			'company'   => $order->shipping_company,
			'address1'  => $order->shipping_address_1,
			'address2'  => '',
			'city'      => $order->shipping_city,
			'zip'     	=> $order->shipping_postcode,
			'country'   => $order->shipping_country,
            'agentno'	=> $pacsoftServicePointArray[1]
 			);
 			
 		if($this->determineCarrierSystem($order) == 'postdk')
 			$agentto['parid']	= 'PDK';
 			
 		return $agentto;
	}
	
	private function sendOrders() {
	
		if(get_option( 'smartsend_general_testing' ) == "1") {
			$URL = 'https://system.smartsend.dk/test/ajax/';
		} else {
			$URL = 'https://system.smartsend.dk/ajax/';
		}
 
			$json =  http_build_query(
				array(
					"data" => json_encode( $this->getJson() )
					)
				);
 // var_dump( $this->getJson() ); exit();
 // var_dump( parse_url(get_site_url(), PHP_URL_HOST) ); exit();
 			$ch = curl_init($URL);
			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_REFERER, 'http://'.parse_url(get_site_url(), PHP_URL_HOST) );
			/*curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    			'Content-Type: application/json',                                                                                
   				'Content-Length: ' . strlen($test))                                                                       
			);*/
			
			$output = urldecode( curl_exec($ch) );
			$error 	= curl_error($ch);
			curl_close($ch);
			
//  var_dump( json_decode($output) ); exit();
 				
			if( $error ) {
				$this->setPosterror( $error );
				return false;
			} else {
					$output = json_decode( $output );
					unset($output->debug);
//  var_dump( $output );
					$output = json_encode( $output );
//var_dump( $output );
				$this->setResponse( $output );
				return true;
			}
	
	}  
	
	public function wpbo_get_woo_version_number() {
        // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
	// Otherwise return null
		return NULL;
	}
}
	
	
}