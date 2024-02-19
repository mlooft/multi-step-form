<?php
/*
 * Plugin Name: Multi Step Form
 * Version: 1.7.18
 * Plugin URI: http://www.mondula.com/
 * Description: Create and embed Multi Step Form.
 * Author: Mondula GmbH
 * Author URI: http://www.mondula.com/
 * Requires at least: 5.0
 * Tested up to: 6.3.1
 *
 * Text Domain: multi-step-form
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mondula GmbH
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;

// Load plugin class files
require_once('includes/msf.class.php');
require_once('includes/msf-settings.class.php');
require_once('includes/msf-settings-api.class.php');

// Load plugin libraries
require_once('includes/lib/msf-wizard-repository.class.php');
require_once('includes/lib/msf-wizard-service.class.php');

require_once('includes/admin/msf-admin.class.php');
require_once('includes/admin/msf-list-table.class.php');
require_once('includes/lib/msf-shortcode.class.php');
require_once('includes/admin/blocks/msf-gutenberg.php');
require_once('includes/lib/msf-wizard.class.php');
require_once('includes/lib/msf-wizard-step.class.php');
require_once('includes/lib/msf-wizard-step-part.class.php');

// Blocks
require_once('includes/lib/msf-block.class.php');
require_once('includes/lib/msf-blocks/radio/msf-block-radio.class.php');
require_once('includes/lib/msf-blocks/email/msf-block-email.class.php');
require_once('includes/lib/msf-blocks/getvariable/msf-block-get-variable.class.php');
require_once('includes/lib/msf-blocks/numeric/msf-block-numeric.class.php');
require_once('includes/lib/msf-blocks/file/msf-block-file.class.php');
require_once('includes/lib/msf-blocks/date/msf-block-date.class.php');
require_once('includes/lib/msf-blocks/paragraph/msf-block-paragraph.class.php');
require_once('includes/lib/msf-blocks/media/msf-block-media.class.php');
require_once('includes/lib/msf-blocks/select/msf-block-select.class.php');
require_once('includes/lib/msf-blocks/text/msf-block-text.class.php');
require_once('includes/lib/msf-blocks/textarea/msf-block-textarea.class.php');


function activate_form_wizard($network_wide = false) {
	require_once plugin_dir_path(__FILE__) . 'includes/lib/msf-activator.class.php';
	Mondula_Form_Wizard_Activator::activate($network_wide);
}

register_activation_hook(__FILE__, 'activate_form_wizard');

function msf_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta) {
	if (is_plugin_active_for_network('multi-step-form/mondula-form-wizard.php')) {
		require_once plugin_dir_path(__FILE__) . 'includes/lib/msf-activator.class.php';
		Mondula_Form_Wizard_Activator::activate_for_blog($blog_id);
	}
}

add_action('wpmu_new_blog', 'msf_new_blog', 10, 6);


function msf_drop_tables($tables = array(), $blog_id = null) {
  	require_once plugin_dir_path(__FILE__) . 'includes/lib/msf-activator.class.php';
  	return Mondula_Form_Wizard_Activator::drop_table($tables, $blog_id);
}

add_filter('wpmu_drop_tables', 'msf_drop_tables', 10, 2);

/**
 * Returns the main instance of Mondula_Form_Wizard to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Mondula_Form_Wizard
 */
function Mondula_Form_Wizard() {
	$instance = Mondula_Form_Wizard::instance(__FILE__, '1.7.18');

	if (is_null($instance->settings)) {
		$instance->settings = Mondula_Form_Wizard_Settings::instance($instance);
	}

	return $instance;
}

Mondula_Form_Wizard();
