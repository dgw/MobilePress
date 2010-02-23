<?php
if ( ! class_exists('MobilePress_admin'))
{
	/**
	 * MobilePress class for creating the admin area
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class MobilePress_admin {
		
		/**
		 * Checks whether we need to do any updates, an installation etc
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function initial_checks()
		{
			// If the table exists
			if (mopr_check_table_exists())
			{
				// Check to see if the options table is empty
				if (mopr_check_empty_table())
				{
					// If the table empty, throw message to add options
					$message = '<form method="post" action="admin.php?page=mobilepress"><p><strong>MobilePress table is empty! <input type="submit" name="add" value="Add Options!" class="button" /></strong></p></form>';
					mopr_display_notice($message);
				}
				else
				{
					// Check if an upgrade is needed
					if (MOPR_DBVERSION < MOPR_VERSION)
					{
						$message = '<form method="post" action="admin.php?page=mobilepress"><p><strong>Your MobilePress install is not the latest version! <input type="submit" name="upgrade" value="Upgrade!" class="button" /></strong></p></form>';	
						mopr_display_notice($message);
					}
				}
			}
			else
			{
				// If the table doesnt exist throw message to create it
				$message = '<form method="post" action="admin.php?page=mobilepress"><p><strong>MobilePress table does not exist! <input type="submit" name="create" value="Create!" class="button" /></strong></p></form>';
				mopr_display_notice($message);
			}
		}
		
		/**
		 * Renders the MobilePress ads and analytics page
		 *
		 * @package MobilePress
		 * @since 1.1
		 */
		function render_ads_analytics()
		{
			if (isset($_POST['add']))
			{
				global $wpdb;
				
				// Options to be added
				$updates	= array(
								'aduity_account_public_key'	=> $_POST['apk'],
								'aduity_account_secret_key'	=> $_POST['ask']
							);
				
				// Update the options table
				foreach ($updates as $name => $update)
				{
					$wpdb->query(
						$wpdb->prepare("
							UPDATE
								" . MOPR_TABLE . "
							SET
								option_value = '%s'
							WHERE
								option_name = '" . $name . "'
							", $update)
					);
				}
			}
			else if (isset($_POST['edit']))
			{
				global $wpdb;
				
				if ( ! isset($_POST['error']))
				{
					// Options to be edited
					$updates	= array(
									'aduity_account_public_key'	=> $_POST['apk'],
									'aduity_account_secret_key'	=> $_POST['ask'],
									'aduity_analytics_enabled'	=> $_POST['analytics_enabled'],
									'aduity_ads_enabled'		=> $_POST['ads_enabled'],
									'aduity_debug_mode'			=> $_POST['debug_mode'],
									'aduity_ads_type'			=> $_POST['type'],
									'aduity_ads_campaign'		=> $_POST['campaign'],
									'aduity_ads_ad'				=> $_POST['ad'],
									'aduity_ads_location'		=> $_POST['location']
								);
				}
				else
				{
					// Options to be edited
					$updates	= array(
									'aduity_account_public_key'	=> $_POST['apk'],
									'aduity_account_secret_key'	=> $_POST['ask']
								);
				}
				
				// Update the options table
				foreach ($updates as $name => $update)
				{
					$wpdb->query(
						$wpdb->prepare("
							UPDATE
								" . MOPR_TABLE . "
							SET
								option_value = '%s'
							WHERE
								option_name = '" . $name . "'
							", $update)
					);
				}
				
				mopr_display_notice("<p><strong>Options Saved</strong></p>");
			}
			
			$apk = mopr_get_option('aduity_account_public_key');
			$ask = mopr_get_option('aduity_account_secret_key');
			
			// Check if they have setup their account yet
			if ($apk == '' || $ask == '')
			{
				// Load ads and analytics setup view
				mopr_load_view('admin_ads_analytics_setup');
			}
			else
			{
				$data['apk'] 				= $apk;
				$data['ask'] 				= $ask;
				$data['analytics_enabled']	= mopr_get_option('aduity_analytics_enabled');
				$data['ads_enabled']		= mopr_get_option('aduity_ads_enabled');
				$data['debug_mode']			= mopr_get_option('aduity_debug_mode');
				$data['type']				= mopr_get_option('aduity_ads_type');
				$data['campaign']			= mopr_get_option('aduity_ads_campaign');
				$data['ad']					= mopr_get_option('aduity_ads_ad');
				$data['location']			= mopr_get_option('aduity_ads_location');
				
				// Load libraries
				$json	= mopr_load_json_library();
				$aduity	= mopr_load_aduity_api_library($apk, $ask);
				
				// Lets do some checks
				$validate = $json->decode($aduity->request('validate'));
				
				if ($validate->response == 'ok')
				{
					// Lets get the url of their Aduity account
					$data['url'] = $json->decode($aduity->request('get_account_url'));
					$data['url'] = $data['url']->url;
					
					// Importantly, we need to make sure this blog matches a site added to their Aduity account
					$sites	= $json->decode($aduity->request('get_sites'));
					$spk	= mopr_get_option('aduity_site_public_key');
					
					$domain = str_replace('http://', '', str_replace('https://', '', $_SERVER['HTTP_HOST']));
					$domain = str_replace('www.', '', $domain);
					$domain = explode('/', $domain);
					$domain = $domain[0];
					
					if ($sites->response == 'ok')
					{
						foreach ($sites->sites as $site_data)
						{
							if ($domain == $site_data->site_domain)
							{
								if ($spk != $site_data->site_public_key)
								{
									global $wpdb;
									
									// Update site public key
									$wpdb->query(
										$wpdb->prepare("
											UPDATE
												" . MOPR_TABLE . "
											SET
												option_value = '%s'
											WHERE
												option_name = 'aduity_site_public_key'
											", $site_data->site_public_key)
									);
										
									$updated = TRUE;
								}
								else if ($spk == $site_data->site_public_key)
								{
									$updated = TRUE;
								}
							}
						}
					}
					
					if ( ! isset($updated))
					{
						// Display site error
						mopr_display_notice('<p><strong>This domain has not been added to your Aduity account, please <a href="' . $data['url'] . '/sites/create_site">add it</a>.</strong></p>');
					}
					
					if ($data['debug_mode'])
					{
						// Display debug warning
						mopr_display_notice('<p><strong>Warning! Debug mode is enabled, thus ads will be displayed but not counted and analytics will not be tracked</strong></p>');
					}
					
					// Get campaigns
					$data['campaigns'] = $json->decode($aduity->request('get_campaigns'));
					
					if ($data['campaigns']->response == 'ok')
					{
						$data['campaigns'] = $data['campaigns']->campaigns;
					}
					else
					{
						$data['campaigns'] = NULL;
					}
					
					// Get ads
					$data['ads'] = $json->decode($aduity->request('get_ads'));
					
					if ($data['ads']->response == 'ok')
					{
						$data['ads'] = $data['ads']->ads;
					}
					else
					{
						$data['ads'] = NULL;
					}
				}
				else if ($validate->response == 'error')
				{
					// Deal with validation error here
					mopr_display_notice("<p><strong>Invalid account public key or account secret key, please check keys and try again.</strong></p>");
					
					// Tell view we have a validation error
					$data['validation_error'] = TRUE;
				}
				
				// Load ads and analytics view
				mopr_load_view('admin_ads_analytics', $data);
			}
		}
		
		/**
		 * Renders the MobilePress options page
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function render_options()
		{
			if (isset($_POST['save']))
			{
				global $wpdb;
				
				$themes_directory = trim($_POST['themes_directory'], '/');
				
				// Options to be added
				$updates	= array(
								'title'				=> $_POST['title'],
								'description'		=> $_POST['description'],
								'force_mobile'		=> $_POST['force_mobile'],
								'themes_directory'	=> $themes_directory
							);
				
				// Update the options table
				foreach ($updates as $name => $update)
				{
					$wpdb->query(
						$wpdb->prepare("
							UPDATE
								" . MOPR_TABLE . "
							SET
								option_value = '%s'
							WHERE
								option_name = '" . $name . "'
							", $update)
					);
				}
				
				mopr_display_notice("<p><strong>Options Saved</strong></p>");
				
				$update_success = TRUE;
			}
			else if (isset($_POST['upgrade']))
			{
				require_once(MOPR_PATH . 'classes/install.php');
				$upgrade = new MobilePress_install;
				
				mopr_display_notice("<p><strong>MobilePress Upgraded</strong></p>");
				
				$update_success = TRUE;
			}
			else if (isset($_POST['create']))
			{
				require_once(MOPR_PATH . 'classes/install.php');
				$install = new MobilePress_install;
				
				mopr_display_notice("<p><strong>MobilePress table has been created</strong></p>");
				
				$update_success = TRUE;
			}
			else if (isset($_POST['add']))
			{
				require_once(MOPR_PATH . 'classes/install.php');
				$install = new MobilePress_install;
				$install->add_defaults();
				
				mopr_display_notice("<p><strong>MobilePress options have been added to your table</strong></p>");
				
				$update_success = TRUE;
			}
			
			// Options to send to view
			$data['title']				= mopr_get_option('title', 1);
			$data['description']		= mopr_get_option('description', 1);
			$data['force_mobile']		= mopr_get_option('force_mobile', 1);
			$data['themes_directory']	= mopr_get_option('themes_directory', 1);
			
			// Check if table exists and if version is out dated, display appropriate message
			// Since we cannot redefine a constant (i.e. our version), we only run the checks if we have not just upgraded/updated
			if ( ! isset($update_success))
			{
				$this->initial_checks();
			}
			
			// Load options view
			mopr_load_view('admin_options', $data);
		}
		
		/**
		 * Renders the themes page
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function render_themes()
		{
			// What theme are we dealing with?
			if (isset($_GET['section']))
			{
				$section		= $_GET['section'];
				$current_theme	= mopr_get_option($section, 2);
				
				if ($section == 'iphone_theme')
				{
					$data['browser'] = "iPhone Browser";
				}
				else if ($section == 'default_theme')
				{
					$data['browser'] = "Default Browser";
				}
			}
			else
			{
				$section			= "default_theme";
				$current_theme		= mopr_get_option($section, 2);
				$data['browser']	= "Default Browser";
			}
			
			// Do we need to activate a theme?
			if (isset($_GET['action']) == "activate")
			{
				global $wpdb;
					
				$template	= $_GET['template'];
				$theme_name	= $_GET['theme'];
				
				$wpdb->query(
					$wpdb->prepare("
						UPDATE
							" . MOPR_TABLE . "
						SET
							option_value = '%s',
							option_value_2 = '%s'
						WHERE
							option_name = '%s'
						", $template, $theme_name, $section)
				);
				
				$message = "<p>" . $theme_name . " has been activated for the " . $data['browser'] . "</p>";
				
				// Update the current theme (we've just changed it)
				$current_theme = $theme_name;
				
				mopr_display_notice($message);
			}
			
			// Check if table exists and if version is out dated, display appropriate message
			$this->initial_checks();
			
			// Data to be passed to view
			$data['section']		= $section;
			$data['current_theme']	= $current_theme;
			$data['themes']			= $this->select_themes($section);
			
			// Load the theme view
			mopr_load_view('admin_themes', $data);
		}
	
		/**
		 * Function to check if themes exist and if default theme exists, if so, returns the themes
		 *
		 * @package MobilePress
		 * @since 1.1.1
		 */
		function select_themes($section)
		{
			// Get the defaul themes
			$default_themes = $this->get_themes(MOPR_ROOT_PATH . "system/themes");
			
			// Get any themes from the users local theme directory
			$local_themes = $this->get_themes(rtrim(WP_CONTENT_DIR, '/') . '/' . mopr_get_option('themes_directory', 1));
			
			if (is_array($default_themes) && is_array($local_themes))
			{
				// Merge themes
				$themes = array_merge($default_themes, $local_themes);
				ksort($themes);
			}
			else if (is_array($default_themes))
			{
				$themes = $default_themes;
				ksort($themes);
			}
			else if (is_array($local_themes))
			{
				$themes = $local_themes;
				ksort($themes);
			}
			else
			{
				$themes = array();
			}
			
			if (empty($themes))
			{
				$message = "<p>Please upload a theme to your mobilepress themes directory!</p>";
				mopr_display_notice($message);
				return false;
			}
			else
			{
				if (empty($themes['Default']['Title']))
				{
					$message = "<p>You need to upload the default theme!</p>";
					mopr_display_notice($message);
					return false;
				}
				else
				{
					return $themes;
				}
			}
		}
		
		// ----------------------------------------
		// CORE WORDPRESS FUNCTIONS FOR THEME VIEWS
		// ----------------------------------------
	
		/**
		 * Core WP function for getting themes (with a few modifications) - located at: wp-includes/theme.php
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function get_themes($directory) {
			$themes = array();
			$theme_loc = $theme_root = $directory;

			// Files in wp-content/themes directory and one subdir down
			$themes_dir = @ opendir($theme_root);
			if ( !$themes_dir )
				return false;

			while ( ($theme_dir = readdir($themes_dir)) !== false ) {
				if ( is_dir($theme_root . '/' . $theme_dir) && is_readable($theme_root . '/' . $theme_dir) ) {
					if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
						continue;
					$stylish_dir = @ opendir($theme_root . '/' . $theme_dir);
					$found_stylesheet = false;
					while ( ($theme_file = readdir($stylish_dir)) !== false ) {
						if ( $theme_file == 'style.css' ) {
							$theme_files[] = $theme_dir . '/' . $theme_file;
							$found_stylesheet = true;
							break;
						}
					}
					@closedir($stylish_dir);
					if ( !$found_stylesheet ) { // look for themes in that dir
						$subdir = "$theme_root/$theme_dir";
						$subdir_name = $theme_dir;
						$theme_subdir = @ opendir( $subdir );
						while ( ($theme_dir = readdir($theme_subdir)) !== false ) {
							if ( is_dir( $subdir . '/' . $theme_dir) && is_readable($subdir . '/' . $theme_dir) ) {
								if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
									continue;
								$stylish_dir = @ opendir($subdir . '/' . $theme_dir);
								$found_stylesheet = false;
								while ( ($theme_file = readdir($stylish_dir)) !== false ) {
									if ( $theme_file == 'style.css' ) {
										$theme_files[] = $subdir_name . '/' . $theme_dir . '/' . $theme_file;
										$found_stylesheet = true;
										break;
									}
								}
								@closedir($stylish_dir);
							}
						}
						@closedir($theme_subdir);
						$wp_broken_themes[$theme_dir] = array('Name' => $theme_dir, 'Title' => $theme_dir, 'Description' => __('Stylesheet is missing.'));
					}
				}
			}
			if ( is_dir( $theme_dir ) )
				@closedir( $theme_dir );

			if ( !$themes_dir || !$theme_files ) 
				return $themes;

			sort($theme_files);

			foreach ( (array) $theme_files as $theme_file ) {
				if ( !is_readable("$theme_root/$theme_file") ) {
					$wp_broken_themes[$theme_file] = array('Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.'));
					continue;
				}

				$theme_data = get_theme_data("$theme_root/$theme_file");

				$name        = $theme_data['Name'];
				$title       = $theme_data['Title'];
				$description = wptexturize($theme_data['Description']);
				$version     = $theme_data['Version'];
				$author      = $theme_data['Author'];
				$template    = $theme_data['Template'];
				$stylesheet  = dirname($theme_file);

				$screenshot = false;
				foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
					if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
						$screenshot = "screenshot.$ext";
						break;
					}
				}

				if ( empty($name) ) {
					$name = dirname($theme_file);
					$title = $name;
				}

				if ( empty($template) ) {
					if ( file_exists(dirname("$theme_root/$theme_file/index.php")) )
						$template = dirname($theme_file);
					else
						continue;
				}

				$template = trim($template);

				if ( !file_exists("$theme_root/$template/index.php") ) {
					$parent_dir = dirname(dirname($theme_file));
					if ( file_exists("$theme_root/$parent_dir/$template/index.php") ) {
						$template = "$parent_dir/$template";
					} else {
						$wp_broken_themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => __('Template is missing.'));
						continue;
					}
				}

				$stylesheet_files = array();
				$stylesheet_dir = @ dir("$theme_root/$stylesheet");
				if ( $stylesheet_dir ) {
					while ( ($file = $stylesheet_dir->read()) !== false ) {
						if ( !preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file) )
							$stylesheet_files[] = "$theme_loc/$stylesheet/$file";
					}
				}

				$template_files = array();
				$template_dir = @ dir("$theme_root/$template");
				if ( $template_dir ) {
					while(($file = $template_dir->read()) !== false) {
						if ( !preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file) )
							$template_files[] = "$theme_loc/$template/$file";
					}
				}

				$template_dir = dirname($template_files[0]);
				$stylesheet_dir = dirname($stylesheet_files[0]);

				if ( empty($template_dir) )
					$template_dir = '/';
				if ( empty($stylesheet_dir) )
					$stylesheet_dir = '/';

				// Check for theme name collision.  This occurs if a theme is copied to
				// a new theme directory and the theme header is not updated.  Whichever
				// theme is first keeps the name.  Subsequent themes get a suffix applied.
				// The Default always trump their pretenders.
				if ( isset($themes[$name]) ) {
					if ( ('Default' == $name) &&
							 ('default' == $stylesheet) ) {
						// If another theme has claimed to be one of our default themes, move
						// them aside.
						$suffix = $themes[$name]['Stylesheet'];
						$new_name = "$name/$suffix";
						$themes[$new_name] = $themes[$name];
						$themes[$new_name]['Name'] = $new_name;
					} else {
						$name = "$name/$stylesheet";
					}
				}
				
				$themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Stylesheet' => $stylesheet, 'Template Files' => $template_files, 'Stylesheet Files' => $stylesheet_files, 'Template Dir' => $template_dir, 'Stylesheet Dir' => $stylesheet_dir, 'Status' => $theme_data['Status'], 'Screenshot' => $screenshot, 'Tags' => $theme_data['Tags'], 'Theme Root' => $theme_root, 'Theme Root URI' => str_replace( WP_CONTENT_DIR, content_url(), $theme_root ) );
			}

			// Resolve theme dependencies.
			$theme_names = array_keys($themes);

			foreach ( (array) $theme_names as $theme_name ) {
				$themes[$theme_name]['Parent Theme'] = '';
				if ( $themes[$theme_name]['Stylesheet'] != $themes[$theme_name]['Template'] ) {
					foreach ( (array) $theme_names as $parent_theme_name ) {
						if ( ($themes[$parent_theme_name]['Stylesheet'] == $themes[$parent_theme_name]['Template']) && ($themes[$parent_theme_name]['Template'] == $themes[$theme_name]['Template']) ) {
							$themes[$theme_name]['Parent Theme'] = $themes[$parent_theme_name]['Name'];
							break;
						}
					}
				}
			}

			return $themes;
		}
	}
}
?>