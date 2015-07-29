<?php	
require_once(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'paid-memberships-pro' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'gateways' . DIRECTORY_SEPARATOR . 'class.pmprogateway.php');
	//load classes init method
	add_action('init', array('PMProGateway_clickandpledge', 'init'));

	/**
	 * PMProGateway_gatewayname Class
	 *
	 * Handles example integration.
	 *
	 */
	class PMProGateway_clickandpledge extends PMProGateway
	{
		public $responsecodes;
		public $VaultGUID;
		public $TransactionNumber;
		public $country_code;
		
		function PMProGateway($gateway = NULL)
		{
			$this->gateway = $gateway;
			$this->responsecodes = array(2054=>'Total amount is wrong',2055=>'AccountGuid is not valid',2056=>'AccountId is not valid',2057=>'Username is not valid',2058=>'Password is not valid',2059=>'Invalid recurring parameters',2060=>'Account is disabled',2101=>'Cardholder information is null',2102=>'Cardholder information is null',2103=>'Cardholder information is null',2104=>'Invalid billing country',2105=>'Credit Card number is not valid',2106=>'Cvv2 is blank',2107=>'Cvv2 length error',2108=>'Invalid currency code',2109=>'CreditCard object is null',2110=>'Invalid card type ',2111=>'Card type not currently accepted',2112=>'Card type not currently accepted',2210=>'Order item list is empty',2212=>'CurrentTotals is null',2213=>'CurrentTotals is invalid',2214=>'TicketList lenght is not equal to quantity',2215=>'NameBadge lenght is not equal to quantity',2216=>'Invalid textonticketbody',2217=>'Invalid textonticketsidebar',2218=>'Invalid NameBadgeFooter',2304=>'Shipping CountryCode is invalid',2305=>'Shipping address missed',2401=>'IP address is null',2402=>'Invalid operation',2501=>'WID is invalid',2502=>'Production transaction is not allowed. Contact support for activation.',2601=>'Invalid character in a Base-64 string',2701=>'ReferenceTransaction Information Cannot be NULL',2702=>'Invalid Refrence Transaction Information',2703=>'Expired credit card',2805=>'eCheck Account number is invalid',2807=>'Invalid payment method',2809=>'Invalid payment method',2811=>'eCheck payment type is currently not accepted',2812=>'Invalid check number',1001=>'Internal error. Retry transaction',1002=>'Error occurred on external gateway please try again',2001=>'Invalid account information',2002=>'Transaction total is not correct',2003=>'Invalid parameters',2004=>'Document is not a valid xml file',2005=>'OrderList can not be empty',3001=>'Invalid RefrenceTransactionID',3002=>'Invalid operation for this transaction',4001=>'Fraud transaction',4002=>'Duplicate transaction',5001=>'Declined (general)',5002=>'Declined (lost or stolen card)',5003=>'Declined (fraud)',5004=>'Declined (Card expired)',5005=>'Declined (Cvv2 is not valid)',5006=>'Declined (Insufficient fund)',5007=>'Declined (Invalid credit card number)');
			$this->VaultGUID = '';
			$this->TransactionNumber = '';
			$this->country_code = array( 'DE' => '276','AT' => '040','BE' => '056','CA' => '124','CN' => '156','ES' => '724',	'FI' => '246','FR' => '250','GR' => '300', 'IT' => '380','JP' => '392','LU' => '442', 'NL' => '528','PL' => '616','PT' => '620','CZ' => '203','GB' => '826','SE' => '752', 'CH' => '756','DK' => '208','US' => '840','HK' => '344','NO' => '578','AU' => '036',	'SG' => '702','IE' => '372','NZ' => '554','KR' => '410','IL' => '376','ZA' => '710','NG' => '566','CI' => '384','TG' => '768','BO' => '068','MU' => '480','RO' => '642',	'SK' => '703','DZ' => '012','AS' => '016','AD' => '020','AO' => '024','AI' => '660',	'AG' => '028','AR' => '032','AM' => '051','AW' => '533','AZ' => '031','BS' => '044',	'BH' => '048','BD' => '050','BB' => '052','BY' => '112','BZ' => '084','BJ' => '204',	'BT' => '060','56' => '064','BW' => '072','BR' => '076','BN' => '096','BF' => '854',	'MM' => '104','BI' => '108','KH' => '116','CM' => '120','CV' => '132','CF' => '140',	'TD' => '148','CL' => '152','CO' => '170','KM' => '174','CD' => '180','CG' => '178',	'CR' => '188','HR' => '191','CU' => '192','CY' => '196','DJ' => '262','DM' => '212',	'DO' => '214','TL' => '626','EC' => '218','EG' => '818','SV' => '222','GQ' => '226',	'ER' => '232','EE' => '233','ET' => '231','FK' => '238','FO' => '234','FJ' => '242', 'GA' => '266','GM' => '270','GE' => '268','GH' => '288','GD' => '308','GL' => '304', 'GI' => '292','GP' => '312','GU' => '316','GT' => '320','GG' => '831','GN' => '324', 'GW' => '624','GY' => '328','HT' => '332','HM' => '334','VA' => '336','HN' => '340', 'IS' => '352','IN' => '356','ID' => '360','IR' => '364','IQ' => '368','IM' => '833', 'JM' => '388','JE' => '832','JO' => '400','KZ' => '398','KE' => '404','KI' => '296', 'KP' => '408','KW' => '414','KG' => '417','LA' => '418','LV' => '428','LB' => '422','LS' => '426','LR' => '430','LY' => '434','LI' => '438','LT' => '440','MO' => '446','MK' => '807','MG' => '450','MW' => '454','MY' => '458','MV' => '462','ML' => '466','MT' => '470','MH' => '584','MQ' => '474','MR' => '478','HU' => '348','YT' => '175','MX' => '484','FM' => '583','MD' => '498','MC' => '492','MN' => '496','ME' => '499','MS' => '500','MA' => '504','MZ' => '508','NA' => '516','NR' => '520','NP' => '524','BQ' => '535','NC' => '540','NI' => '558','NE' => '562','NU' => '570','NF' => '574','MP' => '580','OM' => '512','PK' => '586','PW' => '585','PS' => '275','PA' => '591','PG' => '598','PY' => '600','PE' => '604','PH' => '608','PN' => '612','PR' => '630','QA' => '634','RE' => '638','RU' => '643','RW' => '646','BL' => '652','KN' => '659', 'LC' => '662','MF' => '663','PM' => '666','VC' => '670','WS' => '882','SM' => '674',	'ST' => '678','SA' => '682','SN' => '686','RS' => '688','SC' => '690','SL' => '694','SI' => '705','SB' => '090','SO' => '706','GS' => '239','LK' => '144','SD' => '729','SR' => '740','SJ' => '744','SZ' => '748','SY' => '760','TW' => '158','TJ' => '762','TZ' => '834','TH' => '764','TK' => '772','TO' => '776','TT' => '780','TN' => '788','TR' => '792','TM' => '795','TC' => '796','TV' => '798','UG' => '800','UA' => '804','AE' => '784','UY' => '858','UZ' => '860','VU' => '548','VE' => '862','VN' => '704','VG' => '092','VI' => '850','WF' => '876','EH' => '732','YE' => '887','ZM' => '894','ZW' => '716','AL' => '008','AF' => '004','AQ' => '010','BA' => '070','BV' => '074','IO' => '086','BG' => '100','KY' => '136','CX' => '162','CC' => '166','CK' => '184','GF' => '254','PF' => '258','TF' => '260','AX' => '248','CW' => '531','SH' => '654','SX' => '534','SS' => '728','UM' => '581'		
          );
			return $this->gateway;
		}										

		/**
		 * Run on WP init
		 *
		 * @since 1.8
		 */
		static function init()
		{
			//make sure example is a gateway option
			add_filter('pmpro_gateways', array('PMProGateway_clickandpledge', 'pmpro_gateways'));

			//add fields to payment settings
			add_filter('pmpro_payment_options', array('PMProGateway_clickandpledge', 'pmpro_payment_options'));
			add_filter('pmpro_payment_option_fields', array('PMProGateway_clickandpledge', 'pmpro_payment_option_fields'), 10, 2);

			//add some fields to edit user page (Updates)
			add_action('pmpro_after_membership_level_profile_fields', array('PMProGateway_clickandpledge', 'user_profile_fields'));
			add_action('profile_update', array('PMProGateway_clickandpledge', 'user_profile_fields_save'));

			//updates cron
			add_action('pmpro_activation', array('PMProGateway_clickandpledge', 'pmpro_activation'));
			add_action('pmpro_deactivation', array('PMProGateway_clickandpledge', 'pmpro_deactivation'));
			add_action('pmpro_cron_example_subscription_updates', array('PMProGateway_clickandpledge', 'pmpro_cron_example_subscription_updates'));

			//code to add at checkout if example is the current gateway
			$gateway = pmpro_getOption("gateway");
			
			if($gateway == "clickandpledge")
			{
				add_action('pmpro_checkout_preheader', array('PMProGateway_clickandpledge', 'pmpro_checkout_preheader'));
				add_filter('pmpro_checkout_order', array('PMProGateway_clickandpledge', 'pmpro_checkout_order'));
				//add_filter('pmpro_include_billing_address_fields', array('PMProGateway_clickandpledge', 'pmpro_include_billing_address_fields'));				add_filter('pmpro_include_billing_address_fields', '__return_false');
				add_filter('pmpro_include_cardtype_field', array('PMProGateway_clickandpledge', 'pmpro_include_billing_address_fields'));
				//add_filter('pmpro_include_payment_information_fields', array('PMProGateway_clickandpledge', 'pmpro_include_payment_information_fields'));				add_filter('pmpro_include_payment_information_fields', '__return_true');
			}
		}

		/**
		 * Make sure example is in the gateways list
		 *
		 * @since 1.8
		 */
		static function pmpro_gateways($gateways)
		{
			if(empty($gateways['clickandpledge']))
				$gateways['clickandpledge'] = __('Click & Pledge', 'pmpro');

			return $gateways;
		}
		static function pmpro_checkout_preheader()		{			global $gateway, $pmpro_level;		}				/**		 * Check settings if billing address should be shown.		 * @since 1.8		 */				static function pmpro_include_billing_address_fields($include)		{			$include = true;			return $include;		}				/**		 * Use our own payment fields at checkout. (Remove the name attributes.)				 * @since 1.8		 */		/*		static function pmpro_include_payment_information_fields($include)		{			//global vars			//global $pmpro_requirebilling, $pmpro_show_discount_code, $discount_code, $CardType, $AccountNumber, $ExpirationMonth, $ExpirationYear;			//return true;		}*/
		/**
		 * Get a list of payment options that the example gateway needs/supports.
		 *
		 * @since 1.8
		 */
		static function getGatewayOptions()
		{
			$options = array(
				'sslseal',
				'nuclear_HTTPS',
				'gateway_environment',				
				'clickandpledge_AccountID',
				'clickandpledge_AccountGuid',
				'clickandpledge_email_customer',
				'clickandpledge_OrganizationInformation',
				'clickandpledge_TermsCondition',
				'clickandpledge_email_customer_trial',
				'clickandpledge_OrganizationInformation_trial',
				'clickandpledge_TermsCondition_trial',
				'clickandpledge_email_customer_recurring',
				'clickandpledge_OrganizationInformation_subscription',
				'clickandpledge_TermsCondition_subscription',
				'currency',
				'use_ssl',
				'tax_state',
				'tax_rate',
				'accepted_credit_cards'
			);

			return $options;
		}
		
		/**
		 * Set payment options for payment settings page.
		 *
		 * @since 1.8
		 */
		static function pmpro_payment_options($options)
		{
			
			//get example options
			$clickandpledge_options = PMProGateway_clickandpledge::getGatewayOptions();

			//merge with others.
			$options = array_merge($clickandpledge_options, $options);

			return $options;
		}

		/**
		 * Display fields for Click & Pledge options.
		 *
		 * @since 1.8
		 */
		static function pmpro_payment_option_fields($values, $gateway)
		{
		//global $pmpro_currencies;
		//echo '<pre>';
		//print_r($pmpro_currencies);
		?>
		<tr class="pmpro_settings_divider gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<td colspan="2">
				<?php _e('Click & Pledge Settings', 'pmpro'); ?>
			</td>
		</tr>
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<th scope="row" valign="top">	
				<label for="clickandpledge_AccountID"><?php _e('Account ID', 'pmpro');?>:</label>
			</th>
			<td>
				<input type="text" id="clickandpledge_AccountID" name="clickandpledge_AccountID" size="60" value="<?php echo esc_attr($values['clickandpledge_AccountID'])?>" class="required"/><br />
				Get your "Account ID" from Click & Pledge. [Portal > Account Info > API Information].
			</td>
		</tr>
		
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<th scope="row" valign="top">	
				<label for="clickandpledge_AccountGuid"><?php _e('API Account GUID', 'pmpro');?>:</label>
			</th>
			<td>
				<input type="text" id="clickandpledge_AccountGuid" name="clickandpledge_AccountGuid" size="60" value="<?php echo esc_attr($values['clickandpledge_AccountGuid'])?>" class="required"/><br />
				Get your "API Account GUID" from Click & Pledge [Portal > Account Info > API Information].
			</td>
		</tr>
		
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<th scope="row" valign="top">	
				<label for="clickandpledge_email_customer"><?php _e('Send Receipt to Patron (Initial Payment)', 'pmpro');?>:</label>
			</th>
			<td>
				<input type="checkbox" id="clickandpledge_email_customer" name="clickandpledge_email_customer" size="60" value="1" <?php if(esc_attr($values['clickandpledge_email_customer']) == '1') { ?>checked<?php } ?>/>
			</td>
		</tr>
		
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<th scope="row" valign="top">	
				<label for="clickandpledge_OrganizationInformation"><?php _e('Receipt Header', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_OrganizationInformation" name="clickandpledge_OrganizationInformation" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_OrganizationInformation'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_OrganizationInformation_countdown">1500</span> characters left.
			</td>
		</tr>
		
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
			<th scope="row" valign="top">	
				<label for="clickandpledge_TermsCondition"><?php _e('Terms & Conditions', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_TermsCondition" name="clickandpledge_TermsCondition" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_TermsCondition'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_TermsCondition_countdown">1500</span> characters left.
			</td>
		</tr>
		
		<script>
		   jQuery(document).ready(function(){				
				if(jQuery('#gateway').val() == 'clickandpledge')
				{
					jQuery('.gateway_clickandpledge').show();
					jQuery('input[name="creditcards_dinersclub"]').attr('checked', false);
					jQuery('input[name="creditcards_enroute"]').attr('checked', false);					
				}
				jQuery('input[name="creditcards_dinersclub"]').click(function(){
					if(jQuery('#gateway').val() == 'clickandpledge') {
						alert('Click and pledge do not support this card');
						return false;
					}
				});
				jQuery('input[name="creditcards_enroute"]').click(function(){
					if(jQuery('#gateway').val() == 'clickandpledge') {
						alert('Click and pledge do not support this card');
						return false;
					}
				});
				function limitText(limitField, limitCount, limitNum) {
					if (limitField.val().length > limitNum) {
						limitField.val( limitField.val().substring(0, limitNum) );
					} else {
						limitCount.html (limitNum - limitField.val().length);
					}
				}
				function in_array(needle, haystack, argStrict) {
				  var key = '',
					strict = !! argStrict;
				  if (strict) {
					for (key in haystack) {
					  if (haystack[key] === needle) {
						return true;
					  }
					}
				  } else {
					for (key in haystack) {
					  if (haystack[key] == needle) {
						return true;
					  }
					}
				  }
				  return false;
				}
				limitText(jQuery('#clickandpledge_OrganizationInformation'),jQuery('#clickandpledge_OrganizationInformation_countdown'),1500);				
				limitText(jQuery('#clickandpledge_TermsCondition'),jQuery('#clickandpledge_TermsCondition_countdown'),1500);
				//OrganizationInformation
				jQuery('#clickandpledge_OrganizationInformation').keydown(function(){
					limitText(jQuery('#clickandpledge_OrganizationInformation'),jQuery('#clickandpledge_OrganizationInformation_countdown'),1500);
				});
				jQuery('#clickandpledge_OrganizationInformation').keyup(function(){
					limitText(jQuery('#clickandpledge_OrganizationInformation'),jQuery('#clickandpledge_OrganizationInformation_countdown'),1500);
				});				
				//TermsCondition
				jQuery('#clickandpledge_TermsCondition').keydown(function(){
					limitText(jQuery('#clickandpledge_TermsCondition'),jQuery('#clickandpledge_TermsCondition_countdown'),1500);
				});
				jQuery('#clickandpledge_TermsCondition').keyup(function(){
					limitText(jQuery('#clickandpledge_TermsCondition'),jQuery('#clickandpledge_TermsCondition_countdown'),1500);
				});
				
				jQuery('#clickandpledge_AccountID').closest('form').submit(function(event){
					if(jQuery('#gateway').val() == 'clickandpledge') {
						if(jQuery.trim(jQuery('#clickandpledge_AccountID').val()) == '')
						{
							alert('Please enter Account ID');
							jQuery('#clickandpledge_AccountID').focus();
							return false;
						}
						if(jQuery('#clickandpledge_AccountID').val().length > 10)
						{
							alert('Please enter only 10 digits');
							jQuery('#clickandpledge_AccountID').focus();
							return false;
						}
						
						if(jQuery.trim(jQuery('#clickandpledge_AccountGuid').val()) == '')
						{
							alert('Please enter API Account GUID');
							jQuery('#clickandpledge_AccountGuid').focus();
							return false;
						}
						if(jQuery('#clickandpledge_AccountGuid').val().length != 36)
						{
							alert('AccountGuid should be 36 characters');
							jQuery('#clickandpledge_AccountGuid').focus();
							return false;
						}
						
						var all_cards = new Array();
						var all_cards_object = jQuery( "input[name*='creditcards_']" );
						var cnp_accepted_cards = new Array('creditcards_visa', 'creditcards_mastercard', 'creditcards_amex', 'creditcards_discover', 'creditcards_jcb');
						var selected_cards = new Array();
						all_cards_object.each(function(){
							all_cards.push(jQuery(this).attr('name'));
							if(jQuery(this).is(':checked')) 
							selected_cards.push(jQuery(this).attr('name'));
						});
						
						if(selected_cards.length == 0)
						{
							alert('Please select at least  one card');
							jQuery('input[name="creditcards_visa"]').focus();
							return false;
						}
						
						for(var i = 0; i < selected_cards.length; i++ )
						{
							if(!in_array(selected_cards[i], cnp_accepted_cards))
							{
								alert('Click & Pledge not accepting '+selected_cards[i].substr(12).toUpperCase());
								jQuery(this).focus();
								return false;
							}
						}
						
						
						var cards = 0;
						if(jQuery('input[name="creditcards_visa"]').is(':checked'))
						{
							cards++;
						}					
						if(jQuery('input[name="creditcards_mastercard"]').is(':checked'))
						{
							cards++;
						}
						if(jQuery('input[name="creditcards_amex"]').is(':checked'))
						{
							cards++;
						}
						if(jQuery('input[name="creditcards_discover"]').is(':checked'))
						{
							cards++;
						}
						if(jQuery('input[name="creditcards_jcb"]').is(':checked'))
						{
							cards++;
						}
						if(cards == 0) 
						{
							alert('Please select at least  one card');
							jQuery('input[name="creditcards_visa"]').focus();
							return false;
						}
					}
				});				
		   });
		</script>
		<tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_email_customer_trial"><?php _e('Send Receipt to Patron (Custom Trial)', 'pmpro');?>:</label>
			</th>
			<td>
				<input type="checkbox" id="clickandpledge_email_customer_trial" name="clickandpledge_email_customer_trial" size="60" value="1" <?php if(esc_attr($values['clickandpledge_email_customer_trial']) == '1') { ?>checked<?php } ?>/>
			</td>
	   </tr>
	   <tr class="gateway gateway_clickandpledge clickandpledge_OrganizationInformation_trial" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_OrganizationInformation_trial"><?php _e('Receipt Header', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_OrganizationInformation_trial" name="clickandpledge_OrganizationInformation_trial" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_OrganizationInformation_trial'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_OrganizationInformation_countdown_trial">1500</span> characters left.
			</td>
	   </tr>
	   
	   <tr class="gateway gateway_clickandpledge clickandpledge_TermsCondition_trial" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_TermsCondition_trial"><?php _e('Terms & Conditions', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_TermsCondition_trial" name="clickandpledge_TermsCondition_trial" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_TermsCondition_trial'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_TermsCondition_countdown_trial">1500</span> characters left.
			</td>
	   </tr>
	   <script>
	   jQuery(document).ready(function(){
			function limitText(limitField, limitCount, limitNum) {
				if (limitField.val().length > limitNum) {
					limitField.val( limitField.val().substring(0, limitNum) );
				} else {
					limitCount.html (limitNum - limitField.val().length);
				}
			}
			limitText(jQuery('#clickandpledge_OrganizationInformation_trial'),jQuery('#clickandpledge_OrganizationInformation_countdown_trial'),1500);
			limitText(jQuery('#clickandpledge_TermsCondition_trial'),jQuery('#clickandpledge_TermsCondition_countdown_trial'),1500);
			//OrganizationInformation
			jQuery('#clickandpledge_OrganizationInformation_trial').keydown(function(){
				limitText(jQuery('#clickandpledge_OrganizationInformation_trial'),jQuery('#clickandpledge_OrganizationInformation_countdown_trial'),1500);
			});
			jQuery('#clickandpledge_OrganizationInformation').keyup(function(){
				limitText(jQuery('#clickandpledge_OrganizationInformation'),jQuery('#clickandpledge_OrganizationInformation_countdown'),1500);
			});			
			//TermsCondition
			jQuery('#clickandpledge_TermsCondition_trial').keydown(function(){
				limitText(jQuery('#clickandpledge_TermsCondition_trial'),jQuery('#clickandpledge_TermsCondition_countdown_trial'),1500);
			});
			jQuery('#clickandpledge_TermsCondition_trial').keyup(function(){
				limitText(jQuery('#clickandpledge_TermsCondition_trial'),jQuery('#clickandpledge_TermsCondition_countdown_trial'),1500);
			});			
	   });
	   </script>
	   
	   <tr class="gateway gateway_clickandpledge" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_email_customer_recurring"><?php _e('Send Receipt to Patron (Recurring Subscription)', 'pmpro');?>:</label>
			</th>
			<td>
				<input type="checkbox" id="clickandpledge_email_customer_recurring" name="clickandpledge_email_customer_recurring" size="60" value="1" <?php if(esc_attr($values['clickandpledge_email_customer_recurring']) == '1') { ?>checked<?php } ?>/>
			</td>
	   </tr>
		<tr class="gateway gateway_clickandpledge clickandpledge_OrganizationInformation_subscription" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_OrganizationInformation_subscription"><?php _e('Receipt Header', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_OrganizationInformation_subscription" name="clickandpledge_OrganizationInformation_subscription" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_OrganizationInformation_subscription'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_OrganizationInformation_countdown_subscription">1500</span> characters left.
			</td>
	   </tr>
	  
	   <tr class="gateway gateway_clickandpledge clickandpledge_TermsCondition_subscription" <?php if($gateway != "clickandpledge") { ?>style="display: none;"<?php } ?>>
		   <th scope="row" valign="top">	
				<label for="clickandpledge_TermsCondition_subscription"><?php _e('Terms & Conditions', 'pmpro');?>:</label>
			</th>
			<td>
				<textarea id="clickandpledge_TermsCondition_subscription" name="clickandpledge_TermsCondition_subscription" rows="3" cols="80"><?php echo esc_attr($values['clickandpledge_TermsCondition_subscription'])?></textarea>
				<br />
				Maximum: 1500 characters, the following HTML tags are allowed: &lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.<br>You have <span id="clickandpledge_TermsCondition_countdown_subscription">1500</span> characters left.
			</td>
	   </tr>
	   <script>
		   jQuery(document).ready(function(){
				function limitText(limitField, limitCount, limitNum) {
					if (limitField.val().length > limitNum) {
						limitField.val( limitField.val().substring(0, limitNum) );
					} else {
						limitCount.html (limitNum - limitField.val().length);
					}
				}
				limitText(jQuery('#clickandpledge_OrganizationInformation_subscription'),jQuery('#clickandpledge_OrganizationInformation_countdown_subscription'),1500);
				limitText(jQuery('#clickandpledge_TermsCondition_subscription'),jQuery('#clickandpledge_TermsCondition_countdown_subscription'),1500);
				
				//OrganizationInformation
				jQuery('#clickandpledge_OrganizationInformation_subscription').keydown(function(){
					limitText(jQuery('#clickandpledge_OrganizationInformation_subscription'),jQuery('#clickandpledge_OrganizationInformation_countdown_subscription'),1500);
				});
				jQuery('#clickandpledge_OrganizationInformation_subscription').keyup(function(){
					limitText(jQuery('#clickandpledge_OrganizationInformation_subscription'),jQuery('#clickandpledge_OrganizationInformation_countdown_subscription'),1500);
				});
				
				//TermsCondition
				jQuery('#clickandpledge_TermsCondition_subscription').keydown(function(){
					limitText(jQuery('#clickandpledge_TermsCondition_subscription'),jQuery('#clickandpledge_TermsCondition_countdown_subscription'),1500);
				});
				jQuery('#clickandpledge_TermsCondition_subscription').keyup(function(){
					limitText(jQuery('#clickandpledge_TermsCondition_subscription'),jQuery('#clickandpledge_TermsCondition_countdown_subscription'),1500);
				});
		   });
		</script>
		<?php
		}

		/**
		 * Filtering orders at checkout.
		 *
		 * @since 1.8
		 */
		static function pmpro_checkout_order($morder)
		{
			return $morder;
		}

		/**
		 * Code to run after checkout
		 *
		 * @since 1.8
		 */
		static function pmpro_after_checkout($user_id, $morder)
		{
		}
		
		/**
		 * Fields shown on edit user page
		 *
		 * @since 1.8
		 */
		static function user_profile_fields($user)
		{
		}

		/**
		 * Process fields from the edit user page
		 *
		 * @since 1.8
		 */
		static function user_profile_fields_save($user_id)
		{
		}

		/**
		 * Cron activation for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_activation()
		{
			wp_schedule_event(time(), 'daily', 'pmpro_cron_example_subscription_updates');
		}

		/**
		 * Cron deactivation for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_deactivation()
		{
			wp_clear_scheduled_hook('pmpro_cron_example_subscription_updates');
		}

		/**
		 * Cron job for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_cron_example_subscription_updates()
		{
		}

		function fetch_periodicity($cycle_period, $cycle_number)
		{
			$Periodicity = '';
			switch($cycle_period)
			{
				case 'Day':
					if(in_array($cycle_number, array(7,14,30,61,91,183,365))) {
						if($cycle_number == 7) {
						$Periodicity = 'Week';
						}
						elseif($cycle_number == 14) {
						$Periodicity = '2 Weeks';
						}
						elseif($cycle_number == 30) {
						$Periodicity = 'Month';
						}
						elseif($cycle_number == 61) {
						$Periodicity = '2 Months';
						}
						elseif($cycle_number == 91) {
						$Periodicity = 'Quarter';
						}elseif($cycle_number == 183) {
						$Periodicity = '6 Months';
						} else {
						$Periodicity = 'Year';
						}
					}
					else
					{
						$Periodicity = $cycle_number . " Days";
					}
				break;
				case 'Week':
					$days = $cycle_number; //This will convert week into days
					if(in_array($days, array(1,2,4,8,12,24,52))) {
						if($cycle_number == 1) {
						$Periodicity = 'Week';
						}
						elseif($cycle_number == 2) {
						$Periodicity = '2 Weeks';
						}
						elseif($cycle_number == 4) {
						$Periodicity = 'Month';
						}
						elseif($cycle_number == 8) {
						$Periodicity = '2 Months';
						}
						elseif($cycle_number == 12) {
						$Periodicity = 'Quarter';
						}
						elseif($cycle_number == 24) {
						$Periodicity = '6 Months';
						}
						elseif($cycle_number == 52) {
						$Periodicity = 'Year';
						}
					}
					else
					{
						$Periodicity = $cycle_number . " Week(s)";
					}
				break;
				case 'Month':
					if(in_array($cycle_number, array(1,2,3,6,12))) {
						if($cycle_number == 1) {
						$Periodicity = 'Month';
						}elseif($cycle_number == 2) {
						$Periodicity = '2 Months';
						}elseif($cycle_number == 3) {
						$Periodicity = 'Quarter';
						}elseif($cycle_number == 6) {
						$Periodicity = '6 Months';
						} else {
						$Periodicity = 'Year';
						}
					}
					else
					{
						$Periodicity = $cycle_number . " Months";
					}
				break;
				case 'Year':
					if(in_array($cycle_number, array(1))) {
						$Periodicity = 'Year';
					}
					else
					{
						$Periodicity = $cycle_number . " Years";
					}
				break;
				
			}
			return $Periodicity;
		}
		
		function process(&$order)
		{			
			if(!extension_loaded('soap')) {
				$order->error .= " " . __("SOAP Client is need to process C&P Transaction. Please contact administrator.", "pmpro");
				return false;
			}
			$currency = pmpro_getOption("currency");
			if(!in_array($currency,array('USD','EUR','CAD','GBP','HKD'))){
				$order->error .= " " . __("Click & Pledge does not allow <b>$currency</b> currency for transactions. Please contact administrator.", "pmpro");
				return false;
			}
			$accepted_credit_cards = explode(',',pmpro_getOption("accepted_credit_cards"));
			$card_type = $order->cardtype;
			if(count($accepted_credit_cards) > 0) {
				if(!in_array($card_type, $accepted_credit_cards)) {
					$order->error .= " " . __("We are not accepting <b>$card_type</b> type card.", "pmpro");
					return false;
				}
			}			
			if(pmpro_getOption("clickandpledge_AccountGuid") == '') {
				$order->error .= " " . __("Click & Pledge payments settings wrong. Account GUID not set properly. Please contact administrator.", "pmpro");
				return false;
			}
			if(pmpro_getOption("clickandpledge_AccountID") == '') {
				$order->error .= " " . __("Click & Pledge payments settings wrong. Account ID not set properly. Please contact administrator.", "pmpro");
				return false;
			}			
			if(pmpro_isLevelRecurring($order->membership_level))
			{
				$Periodicity = '';
				$Periodicity = $this->fetch_periodicity($order->membership_level->cycle_period, $order->membership_level->cycle_number);
								
				if(!in_array($Periodicity, array('Week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year'))) {
					$order->error .= " " . __("Click & Pledge does not allow <b>$Periodicity</b> for recurring. Please contact administrator.", "pmpro");
					return false;
				}
				
				if(floatval($order->PaymentAmount) == 0 && floatval($order->membership_level->trial_amount) == 0)
				{
					$order->error .= " " . __("Click & Pledge does not allow 0 amount for recurring. Please contact administrator.", "pmpro");
					return false;
				}				
			}
			
			//Transactions start			
			$result = $this->charge($order);
			if($result) {				
				//setup recurring billing
				if(pmpro_isLevelRecurring($order->membership_level))
				{	
					if(pmpro_isLevelTrial($order->membership_level) && floatval($order->membership_level->trial_amount) != 0)
					{
						//$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";
						$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod)) . "T0:0:0";		
						$this->VaultGUID = $result['VaultGUID']; //1st Payment VaultGUID
						$this->TransactionNumber = $result['TransactionNumber']; //1st Payment Transaction Number
						
						$trialauth = $this->authorize($order, 'trial');
						//print_r($trialauth);
						//		die();
						if( $trialauth )
						{
							$order->notes = "Trial Period Transaction ID : " . $trialauth['TransactionNumber'];	
							$order->updateStatus("authorized");
							$order->status = "success";	
							if(floatval($order->PaymentAmount) == 0) {
								return true;
							}							
							$trial_period_days = ceil(abs(strtotime(date("Y-m-d")) - strtotime($order->ProfileStartDate)) / 86400);							
							if(!empty($order->TrialBillingCycles))						
							{							
								$trialOccurrences = (int)$order->TrialBillingCycles;
								if($order->TrialBillingPeriod == "Year")
									$trial_period_days = $trial_period_days + (365 * $order->TrialBillingFrequency * $trialOccurrences);	//annual
								elseif($order->BillingPeriod == "Day")
									$trial_period_days = $trial_period_days + (1 * $order->TrialBillingFrequency * $trialOccurrences);		//daily
								elseif($order->BillingPeriod == "Week")
									$trial_period_days = $trial_period_days + (7 * $order->TrialBillingFrequency * $trialOccurrences);	//weekly
								else
									$trial_period_days = $trial_period_days + (30 * $order->TrialBillingFrequency * $trialOccurrences);	//assume monthly				
							}
							
							//add a period to the start date to account for the initial payment
							$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $trial_period_days . " Day")) . "T0:0:0";
							if(floatval($order->PaymentAmount) != 0) 
							{
								$auth = $this->authorize($order, 'authorize'); //Recurring
								
								if( $auth )
								{
									//$order->subscription_transaction_id = $auth['VaultGUID'];
									$order->subscription_transaction_id = $auth['TransactionNumber'];
									$order->updateStatus("authorized");
									$order->status = "success";	
									
									return true;
								}
								else
								{
									if(empty($order->error))
									$order->error = __("Unknown error: Payment failed.", "pmpro");
									$order->error .= " " . __("partial payment was made that we could not refund. Please contact the site owner immediately to correct this.", "pmpro");
									return false;
								}
							}
						}
						else
						{
							if(empty($order->error))
							$order->error = __("Unknown error: Payment failed.", "pmpro");
							$order->error .= " " . __("A partial payment was made that we could not refund. Please contact the site owner immediately to correct this.", "pmpro");
							return false;
						}
					}
					else
					{
						//add a period to the start date to account for the initial payment
						$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod)) . "T0:0:0";				
					}
					$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
					
					$this->VaultGUID = $result['VaultGUID'];
					$this->TransactionNumber = $result['TransactionNumber'];					
					
					$auth = $this->authorize($order, 'authorize');
					
					if( $auth )
					{
						$order->subscription_transaction_id = $auth['TransactionNumber'];
						$order->updateStatus("authorized");
						$order->status = "success";	
						return true;
					}
					else
					{
						if(empty($order->error))
						$order->error = __("Unknown error: Payment failed.", "pmpro");
						$order->error .= " " . __("A partial payment was made that we could not refund. Please contact the site owner immediately to correct this.", "pmpro");
						return false;
					}
				}
				else
				{
					//only a one time charge
					$order->status = "success";	//saved on checkout page											
					return true;
				}
			}
			else
			{					
				if(empty($order->error))
					$order->error = __("Unknown error: Payment failed.", "pmpro");
				
				return false;
			}			
		}
		
		/*
			Run an authorization at the gateway.

			Required if supporting recurring subscriptions
			since we'll authorize $1 for subscriptions
			with a $0 initial payment.
		*/
		function authorize(&$order, $case)
		{
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			if(!empty($order->gateway_environment))
				$gateway_environment = $order->gateway_environment;
			if(empty($gateway_environment))
				$gateway_environment = pmpro_getOption("gateway_environment");
			if($gateway_environment == "live")
				$mode = "Production";		
			else
				$mode = "Test";	
			
			$xml = $this->getPaymentXML( $order, $case );
			
			$response = $this->sendPayment( $xml );
			if($response === FALSE)
			{
				$order->error = 'We are unable to connect Click & Pledge. Please try after some time.';
				$order->shorterror = 'We are unable to connect Click & Pledge. Please try after some time.';
				return false;
			}
			$ResultCode = $response->OperationResult->ResultCode;
			$transation_number = $response->OperationResult->TransactionNumber;
			$VaultGUID = $response->OperationResult->VaultGUID;
			if ($ResultCode == '0') {
				// transaction was successful, so record transaction number and continue
				$order->payment_transaction_id = $VaultGUID;
				$order->updateStatus("authorized");
				$return = array(
					'VaultGUID' => $VaultGUID,
					'TransactionNumber' => $transation_number,					
				);
				return $return;				
			}
			else {
				$order->errorcode = true;
				if( in_array( $ResultCode, array( 2051,2052,2053 ) ) )
				{
					$AdditionalInfo = $response->OperationResult->AdditionalInfo;
				}
				else
				{
					if( isset( $this->responsecodes[$ResultCode] ) )
					{
						$AdditionalInfo = $this->responsecodes[$ResultCode];
					}
					else
					{
						$AdditionalInfo = 'Unknown error ('.$ResultCode.')';
					}
				}
				$order->error = $AdditionalInfo;
				$order->shorterror = $AdditionalInfo;
				return false;
			}															
			return true;					
		}
		
		/*
			Void a transaction at the gateway.

			Required if supporting recurring transactions
			as we void the authorization test on subs
			with a $0 initial payment and void the initial
			payment if subscription setup fails.
		*/
		function void(&$order)
		{
			//need a transaction id
			if(empty($order->payment_transaction_id))
				return false;
			
			//code to void an order at the gateway and test results would go here

			//simulate a successful void
			$order->payment_transaction_id = "TEST" . $order->code;
			$order->updateStatus("voided");					
			return true;
		}

		private function sendPayment($xml) {			
			$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
			$client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
			$soapParams = array('instruction'=>$xml);		 
			$response = $client->Operation($soapParams);
			return $response;
		}		
		
		/*
			Make a charge at the gateway.

			Required to charge initial payments.
		*/
		function charge(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			$xml = $this->getPaymentXML( $order, 'charge' );			
			$response = $this->sendPayment( $xml );
			
			if($response === FALSE)
			{
				$order->error = 'We are unable to connect Click & Pledge. Please try after some time.';
				$order->shorterror = 'We are unable to connect Click & Pledge. Please try after some time.';
				return false;
			}
			$ResultCode = $response->OperationResult->ResultCode;
			$transation_number = $response->OperationResult->TransactionNumber;
			$VaultGUID = $response->OperationResult->VaultGUID;
			if ($ResultCode == '0') {
				// transaction was successful, so record transaction number and continue
				$order->payment_transaction_id = $VaultGUID;
				$order->updateStatus("success");
				$return = array(
					'VaultGUID' => $VaultGUID,
					'TransactionNumber' => $transation_number,					
				);
				return true;				
			}
			else {
				$order->errorcode = true;
				if( in_array( $ResultCode, array( 2051,2052,2053 ) ) )
				{
					$AdditionalInfo = $response->OperationResult->AdditionalInfo;
				}
				else
				{
					if( isset( $this->responsecodes[$ResultCode] ) )
					{
						$AdditionalInfo = $this->responsecodes[$ResultCode];
					}
					else
					{
						$AdditionalInfo = 'Unknown error ('.$ResultCode.')';
					}
				}
				$order->error = $AdditionalInfo;
				$order->shorterror = $AdditionalInfo;
				return false;
			}
			//code to charge with gateway and test results would go here

			//simulate a successful charge
			//$order->payment_transaction_id = "TEST" . $order->code;
			//$order->updateStatus("success");					
			return true;						
		}
		
		function safeString( $str,  $length=1, $start=0 )
		{
			$str = preg_replace('/\x03/', '', $str); //Remove new line characters
			return substr( htmlspecialchars( ( $str ) ), $start, $length );
		}
		
		function search_country( $country )
		{
			foreach ($this->country_code as $cname => $code)
			{
				if ($cname == $country)
					return $code;
			}
		}
		
		/**
	     * Get user's IP address
	     */
		function get_user_ip() {
			$ipaddress = '';
			 if (isset($_SERVER['HTTP_CLIENT_IP']))
				 $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			 else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				 $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			 else if(isset($_SERVER['HTTP_X_FORWARDED']))
				 $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			 else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				 $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			 else if(isset($_SERVER['HTTP_FORWARDED']))
				 $ipaddress = $_SERVER['HTTP_FORWARDED'];
			 else
				 $ipaddress = $_SERVER['REMOTE_ADDR'];
			$parts = explode(',', $ipaddress);
			if(count($parts) > 1) $ipaddress = $parts[0];
			 return $ipaddress; 
		}
		
		public function getPaymentXML( $orderplaced, $case = '' ) 
		{			
			$dom = new DOMDocument('1.0', 'UTF-8');
			$root = $dom->createElement('CnPAPI', '');
			$root->setAttribute("xmlns","urn:APISchema.xsd");
			$root = $dom->appendChild($root);
			$version=$dom->createElement("Version","1.5");
			$version=$root->appendChild($version);
			$engine = $dom->createElement('Engine', '');
			$engine = $root->appendChild($engine);
			$application = $dom->createElement('Application','');
			$application = $engine->appendChild($application);
			$applicationid=$dom->createElement('ID','CnP_WordPress_PaidMembershipsPro');
			$applicationid=$application->appendChild($applicationid);
			$applicationname=$dom->createElement('Name','CnP_WordPress_PaidMembershipsPro'); 
			$applicationid=$application->appendChild($applicationname);
			$applicationversion=$dom->createElement('Version','1.0.2');
			$applicationversion=$application->appendChild($applicationversion);
		
			$request = $dom->createElement('Request', '');
			$request = $engine->appendChild($request);
			$operation=$dom->createElement('Operation','');
			$operation=$request->appendChild( $operation );
			$operationtype=$dom->createElement('OperationType','Transaction');
			$operationtype=$operation->appendChild($operationtype);
			
			if($this->get_user_ip() != '') {
				$ipaddress=$dom->createElement('IPAddress',$this->get_user_ip());
				$ipaddress=$operation->appendChild($ipaddress);
			}
			
			$httpreferrer=$dom->createElement('UrlReferrer',htmlentities($_SERVER['HTTP_REFERER']));
			$httpreferrer=$operation->appendChild($httpreferrer);
			
			$authentication=$dom->createElement('Authentication','');
			$authentication=$request->appendChild($authentication);
			$accounttype=$dom->createElement('AccountGuid',pmpro_getOption("clickandpledge_AccountGuid") ); 
			$accounttype=$authentication->appendChild($accounttype);
			
			$accountid=$dom->createElement('AccountID',pmpro_getOption("clickandpledge_AccountID") );
			$accountid=$authentication->appendChild($accountid);
			 
			$order=$dom->createElement('Order','');
			$order=$request->appendChild($order);
			
			if(empty($order->gateway_environment))
				$gateway_environment = pmpro_getOption("gateway_environment");
			else
				$gateway_environment = $order->gateway_environment;
			if($gateway_environment == "live")
				$mode = "Production";		
			else
				$mode = "Test";
			
			$ordermode=$dom->createElement('OrderMode',$mode);
			$ordermode=$order->appendChild($ordermode);				
										
			$cardholder=$dom->createElement('CardHolder','');
			$cardholder=$order->appendChild($cardholder);
			
			if(count($orderplaced->billing)) {
			$billinginfo=$dom->createElement('BillingInformation','');
			$billinginfo=$cardholder->appendChild($billinginfo);
			
			if($orderplaced->FirstName) {
			$billfirst_name=$dom->createElement('BillingFirstName',$this->safeString($orderplaced->FirstName,50));
			$billfirst_name=$billinginfo->appendChild($billfirst_name);
			}			
			
			if($orderplaced->LastName) {	
			$billlast_name=$dom->createElement('BillingLastName',$this->safeString($orderplaced->LastName,50));
			$billlast_name=$billinginfo->appendChild($billlast_name);
			}
			if (isset($orderplaced->Email) && $orderplaced->Email != '')
			{
				$bill_email=$dom->createElement('BillingEmail',$orderplaced->Email);
				$bill_email=$billinginfo->appendChild($bill_email);
			}
						
			if( $orderplaced->billing->phone != '' )
			{
				$bill_phone=$dom->createElement('BillingPhone',$this->safeString($orderplaced->billing->phone, 50));
				$bill_phone=$billinginfo->appendChild($bill_phone);
			}
			} //Billing Information
			
			
			if( $orderplaced->Address1 != '' ) {		
			$billingaddress=$dom->createElement('BillingAddress','');
			$billingaddress=$cardholder->appendChild($billingaddress);
			
			if( $orderplaced->Address1 != '' ) {
			$billingaddress1=$dom->createElement('BillingAddress1',$this->safeString($orderplaced->Address1,100));
			$billingaddress1=$billingaddress->appendChild($billingaddress1);
			}
			if( $orderplaced->Address2 != '' ) {
			$billingaddress2=$dom->createElement('BillingAddress2',$this->safeString($orderplaced->Address2,100));
			$billingaddress2=$billingaddress->appendChild($billingaddress2);
			}
			
			if(!empty($orderplaced->billing->city)) {
			$billing_city=$dom->createElement('BillingCity',$this->safeString($orderplaced->billing->city,50));
			$billing_city=$billingaddress->appendChild($billing_city);
			}
			if(!empty($orderplaced->billing->state)) {
			$billing_state=$dom->createElement('BillingStateProvince',$this->safeString($orderplaced->billing->state,50));
			$billing_state=$billingaddress->appendChild($billing_state);
			}
			
			if(!empty($orderplaced->billing->zip)) {		
			$billing_zip=$dom->createElement('BillingPostalCode',$this->safeString( $orderplaced->billing->zip,20 ));
			$billing_zip=$billingaddress->appendChild($billing_zip);
			}
		
			if(!empty($orderplaced->billing->country)) {
			$billing_country_id = '';
			if(ini_get('allow_url_fopen')) //To check if fopen is "ON"
			{
				$countries = simplexml_load_file( WP_PLUGIN_URL.DIRECTORY_SEPARATOR.plugin_basename( dirname(__FILE__)).DIRECTORY_SEPARATOR.'Countries.xml' );		
				foreach( $countries as $country ){
					if( $country->attributes()->Abbrev == $orderplaced->billing->country ){
						$billing_country_id = $country->attributes()->Code;
					} 
				}
			}
			
			if($billing_country_id) {
			$billing_country=$dom->createElement('BillingCountryCode',str_pad($billing_country_id, 3, "0", STR_PAD_LEFT));
			$billing_country=$billingaddress->appendChild($billing_country);
			} else {
				$billing_country_id = $this->search_country($orderplaced->billing->country);
				if($billing_country_id) {
				$billing_country=$dom->createElement('BillingCountryCode',str_pad($billing_country_id, 3, "0", STR_PAD_LEFT));
				$billing_country=$billingaddress->appendChild($billing_country);
				}
			}
			
			}
			} //Billing Address
			
			$VaultGUID = $this->VaultGUID;			
			$paymentmethod=$dom->createElement('PaymentMethod','');
			$paymentmethod=$cardholder->appendChild($paymentmethod);
			
			
			$payment_type=$dom->createElement('PaymentType','CreditCard');
			$payment_type=$paymentmethod->appendChild($payment_type);
			$creditcard=$dom->createElement('CreditCard','');
			$creditcard=$paymentmethod->appendChild($creditcard);
			
			$credit_card_name = $orderplaced->billing->name;						
			$credit_name=$dom->createElement('NameOnCard',$this->safeString( $credit_card_name, 50));
			$credit_name=$creditcard->appendChild($credit_name);
				
			$credit_number=$dom->createElement('CardNumber',$this->safeString( str_replace(' ', '', $orderplaced->accountnumber), 17));
			$credit_number=$creditcard->appendChild($credit_number);
			$credit_cvv=$dom->createElement('Cvv2',$orderplaced->CVV2);
			$credit_cvv=$creditcard->appendChild($credit_cvv);
			$credit_expdate=$dom->createElement('ExpirationDate',str_pad($orderplaced->expirationmonth,2,'0',STR_PAD_LEFT) ."/" .substr($orderplaced->expirationyear,2,2));
			$credit_expdate=$creditcard->appendChild($credit_expdate);
			
			
			$total_calculate = 0;
			
			if(isset($orderplaced->membership_level) && count($orderplaced->membership_level))
			{
				$orderitemlist=$dom->createElement('OrderItemList','');
				$orderitemlist=$order->appendChild($orderitemlist);		
				$p = 0;
				
				$orderitem=$dom->createElement('OrderItem','');
				$orderitem=$orderitemlist->appendChild($orderitem);
				$itemid=$dom->createElement('ItemID',($p+1));
				$itemid=$orderitem->appendChild($itemid);				
			
				$itemname=$dom->createElement('ItemName',$this->safeString(trim($orderplaced->membership_level->name), 50));
				$itemname=$orderitem->appendChild($itemname);
				$quntity=$dom->createElement('Quantity',1);
				$quntity=$orderitem->appendChild($quntity);					
				
				if(pmpro_isLevelRecurring($orderplaced->membership_level) && $case == 'authorize') {	
					$unitprice=$dom->createElement('UnitPrice',($orderplaced->PaymentAmount*100));
				}else if(pmpro_isLevelRecurring($orderplaced->membership_level) && pmpro_isLevelTrial($orderplaced->membership_level) && $case == 'trial') {	
					$unitprice=$dom->createElement('UnitPrice',($orderplaced->membership_level->trial_amount*100));
				} else {
					$unitprice=$dom->createElement('UnitPrice',($orderplaced->InitialPayment*100));
				}				
				$unitprice=$orderitem->appendChild($unitprice);				
			}
				
			$receipt=$dom->createElement('Receipt','');
			$receipt=$order->appendChild($receipt);
			$recipt_lang=$dom->createElement('Language','ENG');
			$recipt_lang=$receipt->appendChild($recipt_lang);
			
			
			
			
			$clickandpledge_email_customer = pmpro_getOption("clickandpledge_email_customer");
			$clickandpledge_email_customer_recurring = pmpro_getOption("clickandpledge_email_customer_recurring");
			$clickandpledge_email_customer_trial = pmpro_getOption("clickandpledge_email_customer_trial");
			if($clickandpledge_email_customer == '1' || $clickandpledge_email_customer_recurring == '1' || $clickandpledge_email_customer_trial == '1') 
			{
				if(isset($orderplaced->Email) && $orderplaced->Email != '')
				{
					if ($clickandpledge_email_customer == '1' && $case == 'charge') 
					{										
						$clickandpledge_OrganizationInformation = pmpro_getOption("clickandpledge_OrganizationInformation");		
						if( $clickandpledge_OrganizationInformation != '')
						{
							$recipt_org=$dom->createElement('OrganizationInformation',$this->safeString($clickandpledge_OrganizationInformation, 1500));
							$recipt_org=$receipt->appendChild($recipt_org);
						}
						
						$clickandpledge_ThankYouMessage = pmpro_getOption("clickandpledge_ThankYouMessage");
						if( $clickandpledge_ThankYouMessage != '')
						{
							$recipt_thanks=$dom->createElement('ThankYouMessage',$this->safeString($clickandpledge_ThankYouMessage, 500));
							$recipt_thanks=$receipt->appendChild($recipt_thanks);
						}
						
						$clickandpledge_TermsCondition = pmpro_getOption("clickandpledge_TermsCondition");
						if( $clickandpledge_TermsCondition != '')
						{
							$recipt_terms=$dom->createElement('TermsCondition',$this->safeString($clickandpledge_TermsCondition, 1500));
							$recipt_terms=$receipt->appendChild($recipt_terms);
						}
						$recipt_email=$dom->createElement('EmailNotificationList','');
						$recipt_email=$receipt->appendChild($recipt_email);	
						$email_note=$dom->createElement('NotificationEmail',$orderplaced->Email);
						$email_note=$recipt_email->appendChild($email_note);
					}
					elseif ($clickandpledge_email_customer_recurring == '1' && $case == 'authorize') 
					{										
						$clickandpledge_OrganizationInformation_subscription = pmpro_getOption("clickandpledge_OrganizationInformation_subscription");		
						if( $clickandpledge_OrganizationInformation_subscription != '')
						{
							$recipt_org=$dom->createElement('OrganizationInformation',$this->safeString($clickandpledge_OrganizationInformation_subscription, 1500));
							$recipt_org=$receipt->appendChild($recipt_org);
						}
						
						$clickandpledge_ThankYouMessage_subscription = pmpro_getOption("clickandpledge_ThankYouMessage_subscription");
						if( $clickandpledge_ThankYouMessage_subscription != '')
						{
							$recipt_thanks=$dom->createElement('ThankYouMessage',$this->safeString($clickandpledge_ThankYouMessage_subscription, 500));
							$recipt_thanks=$receipt->appendChild($recipt_thanks);
						}
						
						$clickandpledge_TermsCondition_subscription = pmpro_getOption("clickandpledge_TermsCondition_subscription");
						if( $clickandpledge_TermsCondition_subscription != '')
						{
							$recipt_terms=$dom->createElement('TermsCondition',$this->safeString($clickandpledge_TermsCondition_subscription, 1500));
							$recipt_terms=$receipt->appendChild($recipt_terms);
						}
						$recipt_email=$dom->createElement('EmailNotificationList','');
						$recipt_email=$receipt->appendChild($recipt_email);					
						$email_note=$dom->createElement('NotificationEmail',$orderplaced->Email);
						$email_note=$recipt_email->appendChild($email_note);
					}				
					elseif ($clickandpledge_email_customer_trial == '1' && $case == 'trial') 
					{										
						$clickandpledge_OrganizationInformation_trial = pmpro_getOption("clickandpledge_OrganizationInformation_trial");		
						if( $clickandpledge_OrganizationInformation_trial != '')
						{
							$recipt_org=$dom->createElement('OrganizationInformation',$this->safeString($clickandpledge_OrganizationInformation_trial, 1500));
							$recipt_org=$receipt->appendChild($recipt_org);
						}
						
						$clickandpledge_ThankYouMessage_trial = pmpro_getOption("clickandpledge_ThankYouMessage_trial");
						if( $clickandpledge_ThankYouMessage_trial != '')
						{
							$recipt_thanks=$dom->createElement('ThankYouMessage',$this->safeString($clickandpledge_ThankYouMessage_trial, 500));
							$recipt_thanks=$receipt->appendChild($recipt_thanks);
						}
						
						$clickandpledge_TermsCondition_trial = pmpro_getOption("clickandpledge_TermsCondition_trial");
						if( $clickandpledge_TermsCondition_trial != '')
						{
							$recipt_terms=$dom->createElement('TermsCondition',$this->safeString($clickandpledge_TermsCondition_trial, 1500));
							$recipt_terms=$receipt->appendChild($recipt_terms);
						}
						$recipt_email=$dom->createElement('EmailNotificationList','');
						$recipt_email=$receipt->appendChild($recipt_email);	
						$email_note=$dom->createElement('NotificationEmail',$orderplaced->Email);
						$email_note=$recipt_email->appendChild($email_note);
					}
				}
			}
			
			
			$transation=$dom->createElement('Transaction','');
			$transation=$order->appendChild($transation);
			$trans_type=$dom->createElement('TransactionType','Payment');
			$trans_type=$transation->appendChild($trans_type);
			$trans_desc=$dom->createElement('DynamicDescriptor','DynamicDescriptor');
			$trans_desc=$transation->appendChild($trans_desc); 
			
			if(pmpro_isLevelRecurring($orderplaced->membership_level) && in_array($case, array('authorize', 'trial'))){			
				if($orderplaced->TotalBillingCycles > 1) {
					//Recurring
					$trans_recurr=$dom->createElement('Recurring','');
					$trans_recurr=$transation->appendChild($trans_recurr);
					if($case == 'authorize') {
						if($orderplaced->TotalBillingCycles == 0) {
							$total_installment=$dom->createElement('Installment',999);
							$total_installment=$trans_recurr->appendChild($total_installment);
						}
						else
						{
							$total_installment=$dom->createElement('Installment',$orderplaced->TotalBillingCycles);
							$total_installment=$trans_recurr->appendChild($total_installment);
						}
					} else {
						//Trial
						if($orderplaced->TrialBillingCycles == 0) {
							$total_installment=$dom->createElement('Installment',999);
							$total_installment=$trans_recurr->appendChild($total_installment);
						}
						else
						{
							$total_installment=$dom->createElement('Installment',$orderplaced->TrialBillingCycles);
							$total_installment=$trans_recurr->appendChild($total_installment);
						}
					}
					
					$Periodicity = '';
					$Periodicity = $this->fetch_periodicity($orderplaced->membership_level->cycle_period, $orderplaced->membership_level->cycle_number);
					
					if($Periodicity)
					{
					$total_periodicity=$dom->createElement('Periodicity',$Periodicity);
					$total_periodicity=$trans_recurr->appendChild($total_periodicity);
					}
					else
					{
						return false;
					}
					
					$RecurringMethod=$dom->createElement('RecurringMethod','Subscription');
					$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
				}
			}
			
			$trans_totals=$dom->createElement('CurrentTotals','');
			$trans_totals=$transation->appendChild($trans_totals);				
			
			if( isset($orderplaced->tax) && $orderplaced->tax != 0 && !in_array($case, array('trial', 'authorize') ))
			{
				$total_tax=$dom->createElement('TotalTax',number_format($orderplaced->tax, 2, '.', '')*100);
				$total_tax=$trans_totals->appendChild($total_tax);
			}
			
			if(pmpro_isLevelRecurring($orderplaced->membership_level) && in_array($case, array('trial', 'authorize'))){
				if($case == 'authorize') {
					$total_amount=$dom->createElement('Total',($orderplaced->PaymentAmount*100));
					$total_amount=$trans_totals->appendChild($total_amount);
				} else {
					$total_amount=$dom->createElement('Total',($orderplaced->TrialAmount*100));
					$total_amount=$trans_totals->appendChild($total_amount);
				}
			} else {
				$InitialPayment = $orderplaced->tax+$orderplaced->InitialPayment;
				$total_amount=$dom->createElement('Total',($InitialPayment*100));
				$total_amount=$trans_totals->appendChild($total_amount);
			}
			if( isset($orderplaced->discount_code) && $orderplaced->discount_code != '' )
			{
				$trans_coupon=$dom->createElement('CouponCode',$this->safeString($orderplaced->discount_code,50));
				$trans_coupon=$transation->appendChild($trans_coupon);
			}
			
			if(pmpro_isLevelRecurring($orderplaced->membership_level) && in_array($case, array('trial', 'authorize'))){
				$chargeDate=$dom->createElement('ChargeDate',date('y/m/d', strtotime($orderplaced->ProfileStartDate)));
				$chargeDate=$transation->appendChild($chargeDate);
			}
			
			if( isset($orderplaced->tax) && $orderplaced->tax != 0 && !in_array($case, array('trial', 'authorize') ))
			{
			$trans_tax=$dom->createElement('TransactionTax',number_format($orderplaced->tax, 2, '.', '')*100);
			$trans_tax=$transation->appendChild($trans_tax);		
			}
			
			$strParam =$dom->saveXML();
			//echo $strParam;
			//die();
			return $strParam;
		}
		
		/*
			Setup a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
		function subscribe(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//filter order before subscription. use with care.
			$order = apply_filters("pmpro_subscribe_order", $order, $this);
			
			//code to setup a recurring subscription with the gateway and test results would go here

			//simulate a successful subscription processing
			$order->status = "success";		
			$order->subscription_transaction_id = "TEST" . $order->code;				
			return true;
		}	
		
		/*
			Update billing at the gateway.

			Required if supporting recurring subscriptions and
			processing credit cards on site.
		*/
		function update(&$order)
		{
			//code to update billing info on a recurring subscription at the gateway and test results would go here

			//simulate a successful billing update
			return true;
		}
		
		/*
			Cancel a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
		function cancel(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to cancel a subscription at the gateway and test results would go here

			//simulate a successful cancel			
			$order->updateStatus("cancelled");					
			return true;
		}	
		
		/*
			Get subscription status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getSubscriptionStatus(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to get subscription status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			return array();
		}

		/*
			Get transaction status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getTransactionStatus(&$order)
		{			
			//code to get transaction status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			return array();
		}
	}