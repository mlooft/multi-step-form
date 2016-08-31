<?php
/*
 * Plugin Name: Mondula Form Wizard
 * Version: 1.0
 * Plugin URI: http://www.mondula.com/
 * Description: Simple form wizard.
 * Author: Mondula GmbH
 * Author URI: http://www.mondula.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: mondula-form-wizard
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mondula GmbH
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-mondula-form-wizard.php' );
require_once( 'includes/class-mondula-form-wizard-settings.php' );
require_once( 'includes/class-mondula-form-wizard-settings-api.php' );

// Load plugin libraries
require_once( 'includes/lib/class-mondula-form-wizard-admin-api.php' );

require_once( 'includes/lib/class-mondula-form-wizard-wizard-repository.php' );
require_once( 'includes/lib/class-mondula-form-wizard-wizard-service.php' );

require_once( 'includes/admin/class-mondula-form-wizard-admin.php' );
require_once( 'includes/admin/class-mondula-form-wizard-list-table.php' );
require_once( 'includes/lib/class-mondula-form-wizard-post-type.php' );
require_once( 'includes/lib/class-mondula-form-wizard-taxonomy.php' );
require_once( 'includes/lib/class-mondula-form-wizard-shortcode.php' );
require_once( 'includes/lib/class-mondula-form-wizard-wizard.php' );
require_once( 'includes/lib/class-mondula-form-wizard-wizard-step.php' );
require_once( 'includes/lib/class-mondula-form-wizard-wizard-step-part.php' );

// Blocks
require_once( 'includes/lib/class-mondula-form-wizard-block.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-checkbox.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-radio.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-text.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-textarea.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-submit.php' );
require_once( 'includes/lib/class-mondula-form-wizard-block-conditional.php' );


function activate_form_wizard() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/lib/class-mondula-form-wizard-activator.php';
    Mondula_Form_Wizard_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_form_wizard' );

/**
 * Returns the main instance of Mondula_Form_Wizard to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Mondula_Form_Wizard
 */
function Mondula_Form_Wizard () {
	$instance = Mondula_Form_Wizard::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Mondula_Form_Wizard_Settings::instance( $instance );
	}

	return $instance;
}

Mondula_Form_Wizard();
