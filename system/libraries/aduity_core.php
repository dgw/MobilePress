<?php
/*
	Aduity.com Request Code
	
	Ad serving and analytics tracking code using fsockopen()
	Compatible with PHP 4 and PHP 5
*/

// YOU CAN EDIT THESE ACCOUNT DETAILS IF NEED BE
define('ACCOUNT_PUBLIC_KEY', mopr_get_option('aduity_account_public_key'));
define('SITE_PUBLIC_KEY', mopr_get_option('aduity_site_public_key'));

// Set this value to 1 for debug mode
define('REQUEST_DEBUG', mopr_get_option('aduity_debug_mode'));

// NO NEED TO EDIT ANYTHING BELOW THIS LINE
if ( ! function_exists('aduity_analytics'))
{
	function aduity_analytics($request_params = NULL)
	{
		$request_type	= array(
							'request_type' => 1
						);
		
		if (is_array($request_params))
		{
			// Merge the request type and parameters
			$request_data = array_merge($request_type, $request_params);
		}
		else
		{
			$request_data = $request_type;
		}
		
		$request = new Aduity_request($request_data);
	}
}


if ( ! function_exists('aduity_display_ad'))
{
	function aduity_display_ad($request_params = NULL)
	{
		// Set the request type
		$request_type	= array(
							'request_type' => 2
						);
		
		if (is_array($request_params))
		{
			// Merge the request type and parameters
			$request_data = array_merge($request_type, $request_params);
		}
		else
		{
			$request_data = $request_type;
		}
		
		// Make the request
		$request = new Aduity_request($request_data);
	}
}

if ( ! class_exists('Aduity_request'))
{
	class Aduity_request {
	
		var $account_public_key		= NULL;
		var $site_public_key		= NULL;
		var $campaign_public_key	= NULL;
		var $ad_public_key			= NULL;
		var $ad_type				= NULL;
		var $keywords				= NULL;
		var $network_ads_only		= NULL;
		var $page_title				= NULL;
		var $request_data;
		var $request_type;
		var $request_server = 'r.aduity.com';
		
		function Aduity_request($request_data)
		{
			if (ACCOUNT_PUBLIC_KEY != '' && SITE_PUBLIC_KEY != '')
			{
				// Set the account public key
				$this->account_public_key = ACCOUNT_PUBLIC_KEY;
				
				// Set site public key
				$this->site_public_key = SITE_PUBLIC_KEY;
				
				// Set the request type for use throughout the class
				$this->request_type = $request_data['request_type'];
				
				// Set campaign public key if available
				if (array_key_exists('campaign_public_key', $request_data))
					$this->campaign_public_key = $request_data['campaign_public_key'];
						
				// Set the ad public key if available
				if (array_key_exists('ad_public_key', $request_data))
					$this->ad_public_key = $request_data['ad_public_key'];
					
				// Set the ad type if available
				if (array_key_exists('ad_type', $request_data))
					$this->ad_type = $request_data['ad_type'];
					
				// Are we only displaying network ads?
				if (array_key_exists('network_ads', $request_data))
					$this->network_ads_only = $request_data['network_ads'];
				
				// Set up our keywords if we have any
				if (array_key_exists('keywords', $request_data))
					$this->keywords = $request_data['keywords'];
				
				// Setup the page title for analytics
				if (array_key_exists('page_title', $request_data))
					$this->page_title = $request_data['page_title'];
				
				// Setup the data
				$this->_set_request_data();
				
				// Make the request
				if ($this->request_type == 1)
				{
					$this->analytics_request();
				}
				else if ($this->request_type == 2)
				{
					$this->ad_request();
				}
			}
			
			return;
		}
		
		function ad_request()
		{
			// Display the ad
			echo $this->make_request();
		}
		
		function analytics_request()
		{
			// All we do for now is make the request
			$this->make_request();
		}
		
		function make_request()
		{
			// Open a new connection to the server
			$conn = @fsockopen($this->request_server, 80, $errno, $errstr, 5);
			
			if ( ! $conn)
			{
				return NULL;
			}
			else
			{
				$header	 = "POST / HTTP/1.0\r\n";
				$header	.= "Host: " . $this->request_server . "\r\n";
				$header	.= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header	.= "Content-Length: " . strlen($this->request_data) . "\r\n";
				$header	.= "Connection: close\r\n\r\n";
				$header	.= $this->request_data;
				
				fputs($conn, $header, strlen($header));
				
				if ($this->request_type == 2)
				{
					// Lets grab the ad code
					while ( ! feof($conn))
					{
						$data = fgets($conn);
						
						if (isset($start_copy))
						{					
							$content .= $data;
						}
						
						if ($data == "\r\n" && ! isset($start_copy))
						{
							$start_copy = TRUE;
						}
					}
				}
			}
			
			fclose($conn);
			
			if (isset($content))
			{
				return $content;
			}
		}
		
		function _set_request_data()
		{
			// Create the data we need
			$this->request_data = '&apk=' . $this->account_public_key;
			
			$this->request_data .= '&spk=' . $this->site_public_key;
			
			$this->request_data .= '&rt=' . $this->request_type;
			
			// We only need this data it is set and we are serving an ad
			if (isset($this->campaign_public_key))
				$this->request_data .= '&cpk=' . $this->campaign_public_key;
			
			if (isset($this->ad_public_key))
				$this->request_data .= '&adpk=' . $this->ad_public_key;
			
			if (isset($this->ad_type))
				$this->request_data .= '&adt=' . $this->ad_type;
			
			if (isset($this->keywords))
				$this->request_data .= '&kw=' . $this->keywords;
			
			if (isset($this->network_ads_only))
				$this->request_data .= '&na=' . $this->network_ads_only;
			
			if (isset($this->page_title))
				$this->request_data .= '&pt=' . $this->page_title;
			
			// some variables we can determine on our own
			$this->request_data .= '&d=' . $_SERVER['HTTP_HOST'];
			$this->request_data .= '&p=' . $_SERVER['REQUEST_URI'];
			$this->request_data .= '&r=' . $_SERVER['HTTP_REFERER'];
			$this->request_data .= '&t=' . time();
			
			// is the user's brower Opera Mini?
			if (array_key_exists('HTTP_X_OPERAMINI_PHONE_UA', $_SERVER))
			{
				$this->request_data .= '&ua=' . $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'];
				$this->request_data .= '&browser=1';
			}
			else
			{
				$this->request_data .= '&ua=' . $_SERVER['HTTP_USER_AGENT'];	
				$this->request_data .= '&browser=0';		
			}
			
			if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
			{
				$this->request_data .= '&ip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];	
			}
			else
			{
				$this->request_data .= '&ip=' . $_SERVER['REMOTE_ADDR'];
			}
			
			$this->request_data .= '&db=' . REQUEST_DEBUG;
		}
	}
}
?>