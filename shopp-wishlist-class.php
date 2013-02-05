<?php

class ShoppWishlist
{
	var $wishlist	= array();
	var $wish		= false;

	function get_customer_id( $email )
	{
		global $wpdb, $table_prefix;
		
		return $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_prefix}shopp_customer WHERE email = '$email'"));
	}

	function tag ($property, $options=array())
	{
		global $user_ID, $Shopp;
			
		$ShoppSettings = &ShoppSettings();
		
		#http://greenandbenz.ohsopreview.co.uk/jewellery-box/share/MjczNzQ2ODYwNDAwJjImMzc2NzMy/
		
		// the wishlist is not available without accounts enabled
		if ( $ShoppSettings->get('account_system') != '' )
		{			if ( $ShoppSettings->get('account_system') == 'shopp' )
				$user_ID = $this->get_customer_id(shopp('customer','email','mode=value&return=true'));
			  
			  if ( (get_query_var('shoppwishlist') == 'share') ) {
			      $user_ID = get_query_var('customer');
			      
			      //$encoded_user_id = time() * rand(1,99)."&$user_ID&" . rand(100,999);
			      $user_ID = base64_decode(strrev(base64_decode(base64_decode(
			                            urldecode($user_ID)
			          ))));
			      
			      $user_ID = explode('&', $user_ID);
			      $user_ID = $user_ID[1];

//			      debug($user_ID);exit();
			 }
			      
			      
			switch ($property)
			{
				// account link
				case 'account-link':
				case 'account':
					$before = ($options['before']!='')?$options['before']:'';
					$inmenu = ($options['inmenu']!='')?$options['inmenu']:false;
					
					if ( $inmenu == true )
					{
						return $before.'<li><h3><a href="'.shopp('wishlist','url','return=true').'">'.__('View my wishlist','shoppwishlist').'</a></h3></li>';
					}
					else
					{
						return $before.'<p class="wishlist button"><a href="'.shopp('wishlist','url','return=true').'">'.__('View my wishlist','shoppwishlist').'</a></p>';
					}
				break;
				// end account-link
				
				// button/link for adding/removing product from the wishlist
				case 'add-button':
				case 'add-link':
					$before	= ($options['before']!='')?$options['before']:'';
					$add	= ($options['add']!='')?$options['add']:__('Add this product to my wishlist','shoppwishlist');
					$listed	= ($options['listed']!='')?$options['listed']:__('This product is already listed on my wishlist','shoppwishlist');
					
					if (! shopp('customer','loggedin') ) return $output;
					
					switch ( $ShoppSettings->get('account_system') )
					{
						#wordpress
						case 'wordpress':
							$wishlist_items = get_user_meta($user_ID, 'shoppwishlist', false);
						break;
						# end wordpress
						
						#shopp
						case 'shopp':
							$wishlist_items = get_option('shoppwishlist_customer_'.$user_ID, array());
						break;
						#end shopp
					} // end switch
					
					if (! array_key_exists(shopp('product','id','return=true'), array_top_level($wishlist_items)) )
					{
						$before .= '<p class="wishlist button"><a href="'.shopp('wishlist','url','type=add&return=true').'">'.$add.'</a></p>';
					}
					else
					{
						if ( $options['linklisted'] == true )
						{
							$before .= '<p class="wishlist button listed"><a href="'.shopp('wishlist','url','type=remove&return=true').'">'.$listed.'</a></p>';
						}
						else
						{
							$before .= '<p class="wishlist button listed">'.$listed.'</p>';
						}
					}
					
					return $before;
				break;
				// end button
				
				// URL for the wishlist, by default it's 'view'.
				case 'url':
					
					$pid = shopp('product','id','return=true');
					
					$permalinks = SHOPP_PRETTYURLS;
					$url		= trailingslashit(get_option('siteurl'));
										
					switch( $options['type'] )
					{
						case 'add':
							if ( $permalinks )
								return trailingslashit($url.'jewellery-box/add/product/'.$pid);
							
							return $url.'index.php?shoppwishlist=add&shopp_pid='.$pid;
						break;
						
						case 'share':
						
						$encoded_user_id = time() * rand(1,99)."&$user_ID&" . rand(100,999);
			      $encoded_user_id = urlencode(
			          base64_encode(base64_encode(strrev(base64_encode($encoded_user_id))))
			          
			          );
			      
						
							if ( $permalinks )
								return trailingslashit($url.'jewellery-box/share/'.$encoded_user_id);
							
							return $url.'index.php?shoppwishlist=share&customer='.$encoded_user_id;
						break;
						
						case 'delete':
						case 'remove':
							if ( $permalinks )
								return trailingslashit($url.'jewellery-box/remove/product/'.$pid);
							
							return $url.'index.php?shoppwishlist=remove&shopp_pid='.$pid;
						break;
						
						case 'wipe':
							if ( $permalinks )
								return trailingslashit($url.'jewellery-box/wipe/');
							
							return $url.'index.php?shoppwishlist=wipe';
						break;
						
						case 'view':
						case '':
						default:
							if ( $permalinks )
								return trailingslashit($url.'jewellery-box');
							
							return $url.'index.php?shoppwishlist=view';
						break;
					}
				break;
				// end URL
				
				// is-share
				case 'is-share':
				case 'isshare':
					global $wp_query;
					return (get_query_var('shoppwishlist') == 'share') ? true : false;
				break;
				// end is-share
				
				// has-wishlist
				case 'has-wishlist':
				case 'haswishlist':
					switch ( $ShoppSettings->get('account_system') )
					{
						# wordpress
						case 'wordpress':
							$meta = get_user_meta($user_ID, 'shoppwishlist', true);
							$this->wishlist = get_user_meta($user_ID, 'shoppwishlist', true);
						break;
						# end wordpress
						
						#shopp
						case 'shopp':
							#$customer = shopp('customer','loginname','mode=value&return=true');
							$this->wishlist = get_option('shoppwishlist_customer_'.$user_ID, array());
						break;
						# end shopp
					}
					return ($this->wishlist) ? true : false;
				break;
				// end has-wishlist
				
				// get-wishlist
				case 'get-wishlist':
				case 'getwishlist':
				case 'loop':
					
					switch ( $ShoppSettings->get('account_system') )
					{
						# wordpress
						case 'wordpress':
							
							if ( isset($options['num']) )
								return count(get_user_meta($user_ID, 'shoppwishlist', true));
							
							if (!isset($this->_wishlist_loop))
							{
								reset($this->wishlist);
								$this->wish = current($this->wishlist);
								$this->_windex = 0;
								$this->_wishlist_loop = true;
								// load current product
								shopp('storefront','product','id='.$this->wish.'&load=true');
							}
							else
							{
								$this->wish = next($this->wishlist);
								$this->_windex++;
								// load current product
								shopp('storefront','product','id='.$this->wish.'&load=true');
							}
							
							if ($this->wish !== false) return true;
							else
							{
								unset($this->_wishlist_loop);
								$this->_windex = 0;
								$this->wish = false;
								return false;
							}
						break;
						# end wordpress
						
						#shopp
						case 'shopp':
							#$customer = shopp('customer','loginname','mode=value&return=true');
							
							//echo print_r($Shopp->Order->Customer);
							
							if ( isset($options['num']) )
								return count(get_option('shoppwishlist_customer_'.$user_ID, array()));
							
							if (!isset($this->_wishlist_loop))
							{
								reset($this->wishlist);
								$this->wish = current($this->wishlist);
								$this->_windex = 0;
								$this->_wishlist_loop = true;
								// load current product
								shopp('catalog','product','id='.$this->wish.'&load=true');
							}
							else
							{
								$this->wish = next($this->wishlist);
								$this->_windex++;
								// load current product
								shopp('catalog','product','id='.$this->wish.'&load=true');
							}
							
							if ($this->wish !== false) return true;
							else
							{
								unset($this->_wishlist_loop);
								$this->_windex = 0;
								$this->wish = false;
								return false;
							}
						break;
						#end shopp
					}
				break;
				// end get-wishlist
				
			} // end switch
		} // end if
	} // end tag
	
} // end class ShoppWishlist
 
 
 

$ShoppWishlist = new ShoppWishlist();

 
 // register filter callback
 // use later priority (11 or higher) to override Shopp
 add_filter('shopp_themeapi_object', 'my_themeapi_wishlist_fltr', 11, 2);
  // Set my custom object for shopp('wishlist') calls
 function my_themeapi_wishlist_fltr ( $Object, $context ) {
     if ( $context == 'wishlist' ) {
     	global $ShoppWishlist;
       	$Object = $ShoppWishlist;
        //$Object = new ShoppWishlist();
     }
     return $Object;
 }
 
 add_filter('shopp_themeapi_wishlist','wishlist_tag', 10, 4);
 function wishlist_tag ( $result, $options, $tag, $Object ) {
 
     if ( is_a( $Object, 'ShoppWishlist' ) ) {
     
     	 //d("ShoppWishlist / $tag");
     
         $result = $Object->tag($tag, $options);
     } 
     return $result;
 }
 
 
 
 
 

?>