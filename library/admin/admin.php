<?php
function arras_addmenu() {
	$options_page = add_menu_page( '', __('Arras Theme', 'arras'), 8, 'arras-options', 'arras_admin', get_template_directory_uri() . '/images/icon.png', 61);
	add_submenu_page( 'arras-options', __('Arras Theme Options', 'arras'), __('Theme Options', 'arras'), 8, 'arras-options', 'arras_admin' );
	add_submenu_page( 'arras-options', __('Arras Theme Options', 'arras'), __('Custom Header', 'arras'), 8, 'custom-header', 'custom-header' );
	
	$custom_background_page = add_submenu_page( 'arras-options', __('Custom Background', 'arras'), __('Custom Background', 'arras'), 8, 'arras-custom-background', 'arras_custom_background' );

	add_action('admin_print_scripts-'. $options_page, 'arras_admin_scripts');
	add_action('admin_print_styles-'. $options_page, 'arras_admin_styles');
	
	add_action('admin_print_scripts-' . $custom_background_page, 'arras_custom_background_scripts');
	add_action('admin_print_styles-' . $custom_background_page, 'arras_custom_background_styles');
}

function arras_admin() {
	global $arras_options;
	
	$notices = ''; // store notices here so that options_page.php will echo it out later
	
	if ( isset($_GET['page']) && $_GET['page'] == 'arras-options' ) {
		//print_r($_POST);
		
		if ( isset($_REQUEST['save']) ) {
			check_admin_referer('arras-admin');
			$arras_options->save_options();
			arras_update_options();
			$notices = '<div class="updated"><p>' . __('Your settings have been saved to the database.', 'arras') . '</p></div>';
		}
		
		if ( isset($_REQUEST['reset']) ) {
			check_admin_referer('arras-admin');
			delete_option('arras_options');
			arras_flush_options();
			$notices = '<div class="updated"><p>' . __('Your settings have been reverted to the defaults.', 'arras') . '</p></div>';
		}
		
		if ( isset($_REQUEST['clearcache']) ) {
			check_admin_referer('arras-admin');
			$cache_location = get_template_directory() . '/library/cache';
			if ( !$dh = @opendir($cache_location) ) return false;
			while ( false !== ($obj = readdir($dh)) ) {
				if($obj == '.' || $obj == '..') continue;
				@unlink(trailingslashit($cache_location) . $obj);
			}
			closedir($dh);
			$notices = '<div class="updated"><p>' . __('Thumbnail cache has been cleared.', 'arras') . '</p></div>';
		}
		
		include 'templates/options_page.php';
	}
}

function arras_admin_scripts() {
	wp_enqueue_script('jquery-ui-tabs', null, 'jquery-ui-core');
	wp_enqueue_script('arras-admin-js', get_template_directory_uri() . '/js/admin.js');
}

function arras_admin_styles() {
?> <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/admin.css" type="text/css" /> <?php
}

/* End of file admin.php */
/* Location: ./library/admin/admin.php */
