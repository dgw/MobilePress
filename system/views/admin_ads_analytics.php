<div class="wrap">
	<script src="<?php echo get_bloginfo('wpurl'); ?><?php echo MOPR_SCRIPT_PATH; ?>system/libraries/js/jquery.livequery.js" type="text/javascript" charset="utf-8"></script>
	<script>
	jQuery(document).ready(function($){
		var enabled		= <?php if(isset($ads_enabled) && $ads_enabled == '1') echo "1"; else echo "0"; ?>;
		var type		= <?php if(isset($type) && $type == '2') echo "2"; else echo $type; ?>;
		var campaign	= '<?php if(isset($campaign)) echo $campaign; else echo "0"; ?>';
		var campaigns	= <?php if(isset($campaigns)) echo count($campaigns); else echo "0"; ?>;
		
		// Is ad serving enabled?
		if (enabled == 0)
		{
			$('div#ad_serving').hide();
		}
		else if (enabled == 1)
		{
			$('div#ad_serving').show();
		}
		
		// What type of ads are we serving?
		if (type == 2)
		{
			$('tr.managed').hide();
		}
		
		// Do we need to show ads?
		if (campaign == '0')
		{
			$('tr.ads').hide();
		}
		else
		{
			// Load ads
			load_ads(campaign, type);
		}
		
		// Check type and if we have campaigns, display warning if appropriate
		if (type != 2 && campaigns == 0)
		{
			$('div#campaign_warning').append('<span id="message" class="updated fade" style="padding:5px;"><strong>Warning! You do not have any managed campaigns, thus managed ads will not be displayed. Please <a href="<?php echo $url; ?>/campaigns/create">create a campaign</a>.</strong></span>');
			$('tr.managed').hide();
		}
		
		$('select#ads_enabled').livequery('change', function(){
			enabled = $('select#ads_enabled').val();
			
			if (enabled == 0)
			{
				$('div#ad_serving').hide();
			}
			else if (enabled == 1)
			{
				$('div#ad_serving').show();
			}
		});
		
		$('select#type').livequery('change', function(){
			type = $('select#type').val();
			
			if (type == 2)
			{
				$('div#campaign_warning').hide();
				$('div#ad_warning').hide();
				$('tr.managed').hide();
			}
			else
			{
				$('div#campaign_warning').show();

				if (campaigns > 0)
				{
					$('div#ad_warning').show();
					$('tr.managed').show();

					if (campaign == '0')
					{
						$('tr.ads').hide();
					}
				}
			}
		});
		
		$('select#campaign').livequery('change', function(){
			campaign = $('select#campaign').val();
			
			if (campaign == '0')
			{
				$('div#ad_warning').empty();
				$('tr.ads').hide();
			}
			else
			{
				// Load ads
				$('tr.ads').show();
				load_ads(campaign, type);
			}
		});
		
		function load_ads(campaign, type)
		{
			$('div#ad_warning').empty();
			$('select#ads').empty();
			$('select#ads').append('<option value="0">All Ads</option>');
			
			<?php
			if (isset($ads))
			{
				$js_campaigns = array();
				
				foreach ($ads as $ad_data)
				{
					if ( ! array_key_exists($ad_data->ad_campaign, $js_campaigns))
					{
						$js_campaigns[$ad_data->ad_campaign] = 1;
					}
					
					$js_ads[$ad_data->ad_campaign][$ad_data->ad_public_key] = $ad_data->ad_title;
				}
				
				foreach ($js_campaigns as $js_campaign => $zilch)
				{
					echo "\t\t\tif (campaign == '" . $js_campaign . "')\n\t\t\t{\n";
					
					$options = '';
					
					foreach ($js_ads[$js_campaign] as $ad_public_key => $ad_title)
					{
						$sel = '';
						
						if ($ad == $ad_public_key)
						{
							$sel = " selected";
						}
						
						$options .= '<option value="' . $ad_public_key . '"' . $sel . '>' . $ad_title . '</option>';
					}
					
					echo "\t\t\t\t$('select#ads').append('" . $options . "');\n";
					echo "\t\t\t\treturn;\n";
					echo "\t\t\t}\n";
				}
			}
			?>
			
			if (type == 1)
			{
				$('div#ad_warning').append('<span id="message" class="updated fade" style="padding:5px;"><strong>Warning! You do not have any ads in the selected campaign, thus no ads will be displayed. Please <a href="<?php echo $url; ?>/ads/create">create an ad</a>.</strong></span>');
			}
			else if (type == 0)
			{
				$('div#ad_warning').append('<span id="message" class="updated fade" style="padding:5px;"><strong>Warning! You do not have any ads in the selected campaign, thus only network ads will be displayed. Please <a href="<?php echo $url; ?>/ads/create">create an ad</a>.</strong></span>');
			}
		}
	});
	</script>
	<div id="icon-options-general" class="icon32"><br /></div>
	<form method="post" action="admin.php?page=mobilepress-ads-analytics">
	<?php if( ! isset($validation_error)) { ?>
		<h2>Ads & Analytics Settings</h2>
		<table class="form-table">
			<tr>
				<th scope="row">Enable Analytics:</th>
				<td>
					<select name="analytics_enabled">
						<option value="0"<?php if (isset($analytics_enabled) && ! $analytics_enabled) echo " selected"; ?>>No</option>
						<option value="1"<?php if (isset($analytics_enabled) && $analytics_enabled) echo " selected"; ?>>Yes</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">Enable Ad Serving:</th>
				<td>
					<select name="ads_enabled" id="ads_enabled">
						<option value="0"<?php if (isset($ads_enabled) && ! $ads_enabled) echo " selected"; ?>>No</option>
						<option value="1"<?php if (isset($ads_enabled) && $ads_enabled) echo " selected"; ?>>Yes</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">Enable Debug Mode:</th>
				<td>
					<select name="debug_mode" id="debug_mode">
						<option value="0"<?php if (isset($debug_mode) && ! $debug_mode) echo " selected"; ?>>No</option>
						<option value="1"<?php if (isset($debug_mode) && $debug_mode) echo " selected"; ?>>Yes</option>
					</select>
					<span class="description">Useful for testing purposes. Ads will display in web browser when enabled</span>
				</td>
			</tr>
		</table>
		<div id="ad_serving">
		<h2>Ad Serving Options</h2>
		<div id="campaign_warning"></div>
		<div id="ad_warning"></div>
		<table class="form-table">
			<tr>
				<th scope="row">Type Of Ads:</th>
				<td>
					<select name="type" id="type">
						<option value="0"<?php if (isset($type) && $type == '0') echo " selected"; ?>>Managed & Network</option>
						<option value="1"<?php if (isset($type) && $type == '1') echo " selected"; ?>>Just Managed</option>
						<option value="2"<?php if (isset($type) && $type == '2') echo " selected"; ?>>Just Network</option>
					</select>
				</td>
			</tr>
			<tr class="managed">
				<th scope="row">Choose A Campaign:</th>
				<td>
					<select name="campaign" id="campaign">
						<option value="0"<?php if (isset($campaign) && $campaign == '0') echo " selected"; ?>>All Campaigns</option>
						<?php
						if (isset($campaigns))
						{
							foreach ($campaigns as $campaign_data)
							{
								$sel = '';
								if ($campaign == $campaign_data->campaign_public_key) $sel = ' selected';
								echo '<option value="' . $campaign_data->campaign_public_key . '"' . $sel . '>' . $campaign_data->campaign_name . '</option>';
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="managed ads">
				<th scope="row">Choose An Ad:</th>
				<td>
					<select name="ad" id="ads"></select>
				</td>
			</tr>
			<tr>
				<th scope="row">Ad Location:</th>
				<td>
					<select name="location">
						<option value="0"<?php if (isset($location) && $location == '0') echo " selected"; ?>>Header & Footer</option>
						<option value="1"<?php if (isset($location) && $location == '1') echo " selected"; ?>>Just Header</option>
						<option value="2"<?php if (isset($location) && $location == '2') echo " selected"; ?>>Just Footer</option>
					</select>
				</td>
			</tr>
		</table>
		</div>
	<?php } else { ?>
		<input type="hidden" name="error" value="1" />
	<?php } ?>
		<h2>Aduity Account Details</h2>
		<table class="form-table">
			<tr>
				<th scope="row">Account Public Key:</th>
				<td>
					<input type="text" name="apk" class="regular-text" value="<?php if(isset($apk)) echo $apk; ?>" /> <span class="description">Find in your account settings.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Account Secret Key:</th>
				<td>
					<input type="text" name="ask" class="regular-text" value="<?php if(isset($ask)) echo $ask; ?>" /> <span class="description">Find in your account settings.</span>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="edit" class="button-primary" value="Save Settings!" /></p>
	</form>
</div>