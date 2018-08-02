<?php
/*
 * Plugin Name: Multi Step Form
 * Version: 1.2.7
 * Plugin URI: http://www.mondula.com/
 * Description: Create and embed Multi Step Form.
 * Author: Mondula GmbH
 * Author URI: http://www.mondula.com/
 * Requires at least: 3.9
 * Tested up to: 4.7
 *
 * Text Domain: multi-step-form
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mondula GmbH
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-mondula-multistep-forms.php' );
require_once( 'includes/class-mondula-multistep-forms-settings.php' );
require_once( 'includes/class-mondula-multistep-forms-settings-api.php' );

// Load plugin libraries
require_once( 'includes/lib/class-mondula-multistep-forms-admin-api.php' );

require_once( 'includes/lib/class-mondula-multistep-forms-wizard-repository.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard-service.php' );

require_once( 'includes/admin/class-mondula-multistep-forms-admin.php' );
require_once( 'includes/admin/class-mondula-multistep-forms-list-table.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-post-type.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-shortcode.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard-step.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard-step-part.php' );

// Blocks
require_once( 'includes/lib/class-mondula-multistep-forms-block.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-checkbox.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-radio.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-email.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-file.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-date.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-paragraph.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-select.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-text.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-textarea.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-conditional.php' );


function activate_form_wizard( $network_wide = false ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/lib/class-mondula-multistep-forms-activator.php';
    Mondula_Form_Wizard_Activator::activate( $network_wide );
}

register_activation_hook( __FILE__, 'activate_form_wizard' );

function msf_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network( 'multi-step-form/mondula-form-wizard.php' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/lib/class-mondula-multistep-forms-activator.php';
        Mondula_Form_Wizard_Activator::activate_for_blog( $blog_id );
    }
}

add_action( 'wpmu_new_blog', 'msf_new_blog', 10, 6 );


function msf_drop_tables( $tables = array(), $blog_id = null ) {
  require_once plugin_dir_path( __FILE__ ) . 'includes/lib/class-mondula-multistep-forms-activator.php';
  return Mondula_Form_Wizard_Activator::drop_table( $tables, $blog_id );
}

add_filter( 'wpmu_drop_tables', 'msf_drop_tables', 10, 2);

/**
 * Returns the main instance of Mondula_Form_Wizard to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Mondula_Form_Wizard
 */
function Mondula_Form_Wizard () {
	$instance = Mondula_Form_Wizard::instance( __FILE__, '1.2.7' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Mondula_Form_Wizard_Settings::instance( $instance );
	}

	return $instance;
}

Mondula_Form_Wizard();
