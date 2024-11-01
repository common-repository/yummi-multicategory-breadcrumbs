<?php
/*
		Plugin Name: Yummi Breadcrumbs (SubCategories Support)
		Description: Yummi Breadcrumbs is Super light weight & easy plugin with support SubCategories showing, SEO friendly and Auto Installation.
		Version: 2.0.1
		Author: Alex Egorov
		Author URI: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SLHFMF373Z9GG&source=url
		Plugin URI: https://wordpress.org/plugins/yummi-multicategory-breadcrumbs/
		GitHub Plugin URI:
		License: GPLv2 or later (license.txt)
		Text Domain: yummi-multicategory-breadcrumbs
		Domain Path: /languages
*/

/*  Copyright 2015 Alex Egorov (email: egorov.work {at} gmail.com)  */

define('YUMMI_FILE', __FILE__);
define('YUMMI_PATH', plugin_dir_path(__FILE__));
define('YUMMI_REFERENCE', 'Yummi Breadcrumbs (SubCategories Support)');

if ( is_admin() ) {
	require YUMMI_PATH . 'admin.php';
	new yummi();
}

add_action( 'plugins_loaded', 'yummi_load_plugin_textdomain' );
function yummi_load_plugin_textdomain() {
	load_plugin_textdomain( 'yummi-multicategory-breadcrumbs', false, basename(dirname(__FILE__)) . '/languages/' ); //basename(dirname(__FILE__)) именно так и никак иначе
}

add_action('admin_init', 'yummi_options_redirect');
function yummi_options_redirect() {
    add_option('name', 'value');
    if (get_option('yummi_options_redirect', false)) {
        delete_option('yummi_options_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=yummibreadcrumbs");
        }
    }
}

function yummi_plugin_action_links($links, $file) {
    static $this_plugin;
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) { // check to make sure we are on the correct plugin
			$settings_link = '<a href="https://yummi.club/" target="_blank">' . __('Demo', 'yummi-multicategory-breadcrumbs') . '</a> | ';
			$settings_link .= '<a href="https://yummi.club/paypal" target="_blank">❤ ' . __('Donate', 'yummi-multicategory-breadcrumbs') . '</a> | ';
      $settings_link .= '<a href="admin.php?page=yummibreadcrumbs">' . __('Settings', 'yummi-multicategory-breadcrumbs') . '</a>'; // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page

        array_unshift($links, $settings_link); // add the link to the list
    }
    return $links;
}
add_filter('plugin_action_links', 'yummi_plugin_action_links', 10, 2);

/* Красивая функция вывода масивов */
if (!function_exists('prr')){ function prr($str) { echo "<pre>"; print_r($str); echo "</pre>\r\n"; }}

/*
//Check minimum PHP requirements, which is 5.2 at the moment.
if (version_compare(PHP_VERSION, "5.2", "<")) {
	add_action('admin_notices', 'sm_AddPhpVersionError');
	$fail = true;
}

//Check minimum WP requirements, which is 3.3 at the moment.
if (version_compare($GLOBALS["wp_version"], "3.3", "<")) {
	add_action('admin_notices', 'sm_AddWpVersionError');
	$fail = true;
}*/

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require YUMMI_PATH . 'includes/br.php';
require YUMMI_PATH . 'includes/br_amp.php';
require YUMMI_PATH . 'includes/additional.php';
?>
