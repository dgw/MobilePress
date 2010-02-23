<?php
if ( ! class_exists('Aduity_api_request'))
{
	class Aduity_api_request {
		
		var $api_uri		= 'api.aduity.com';
		var $apk			= '';
		var $ask			= '';
		var $request_uri	= '';
		var $version		= 'v1';
		
		function Aduity_api_request($apk, $ask)
		{
			$this->apk = $apk;
			$this->ask = $ask;
		}
		
		function make_request()
		{
			// Open a new connection to the ad server
			$conn = @fsockopen($this->api_uri, 80, $errno, $errstr, 5);
			
			if ( ! $conn)
			{
				return NULL;
			}
			else
			{
				$header	 = "GET /" . $this->version . $this->request_uri . " HTTP/1.0\r\n";
				$header	.= "Host: " . $this->api_uri . "\r\n";
				$header	.= "Connection: close\r\n\r\n";
				
				fputs($conn, $header, strlen($header));
				
				// Lets grab the JSON data
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
			
			fclose($conn);
			
			return $content;
		}
		
		function request($request_type, $request_params = NULL)
		{
			// First lets set initial request URI vars
			$this->request_uri = '?apk=' . $this->apk . '&ask=' . $this->ask;
			
			// Now deal with the request type
			$this->_set_request_type($request_type);
			
			// Now we deal with parameters
			if ($request_params != NULL && is_array($request_params))
			{
				foreach ($request_params as $param => $value)
				{
					$this->_set_request_params($param, $value);
				}
			}
			
			// Now lets make and return the request
			return $this->make_request();
		}
		
		function _set_request_params($param, $value)
		{
			$this->request_uri .= '&' . $param . '=' . $value;
		}
		
		function _set_request_type($request_type)
		{
			$this->request_uri .= "&request_type=" . $request_type;
		}
	}
}
?>