<?php
if ( ! function_exists('mopr_ad'))
{
	function mopr_ad($type)
	{

		// Include the TI library
		require_once(MOPR_PATH . 'libraries/aduity_core.php');

		if ( ! mopr_get_option('aduity_ads_enabled'))
		{
			return;
		}
		
		$ad_location = mopr_get_option('aduity_ads_location');
		
		if ($type == 'top')
		{
			if ( ! ($ad_location == '1' || $ad_location == '0'))
			{
				return;
			}
		}
		else if ($type == 'bottom')
		{
			if ( ! ($ad_location == '2' || $ad_location == '0'))
			{
				return;
			}
		}
		else
		{
			return;
		}
		
		$ads_type = mopr_get_option('aduity_ads_type');
		
		if ($ads_type == '0' || $ads_type == '1')
		{
			// Include the Aduity library
			require_once(MOPR_PATH . 'libraries/aduity_core.php');
			
			$campaign = mopr_get_option('aduity_ads_campaign');
			
			if ($campaign != '0')
			{
				$ad = mopr_get_option('aduity_ads_ad');
				
				if ($ad != '0')
				{
					aduity_display_ad(array('campaign_public_key' => $campaign, 'ad_public_key' => $ad));
				}
				else
				{
					aduity_display_ad(array('campaign_public_key' => $campaign));
				}
			}
			else
			{
				aduity_display_ad();
			}
		}
		else if ($ads_type == '2')
		{
			aduity_display_ad(array('network_ads' => 1));
		}
		
		return;
	}
}

if ( ! function_exists('mopr_analytics'))
{
	function mopr_analytics()
	{
		if ( ! mopr_get_option('aduity_analytics_enabled'))
		{
			return;
		}
		
		// Include the Aduity library
		require_once(MOPR_PATH . 'libraries/aduity_core.php');
		
		$title = wp_title('', FALSE);
		
		if (is_home())
		{
			$title = get_bloginfo('name');
		}
		
		aduity_analytics(array('page_title' => $title));
	}
}
?>