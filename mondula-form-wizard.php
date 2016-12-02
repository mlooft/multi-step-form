<?php
/*
 * Plugin Name: Multi Step Form
 * Version: 1.0
 * Plugin URI: http://www.mondula.com/
 * Description: Create and embed Multi Step Form.
 * Author: Mondula GmbH
 * Author URI: http://www.mondula.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: mondula-multistep-forms
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
require_once( 'includes/lib/class-mondula-multistep-forms-taxonomy.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-shortcode.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard-step.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-wizard-step-part.php' );

// Blocks
require_once( 'includes/lib/class-mondula-multistep-forms-block.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-checkbox.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-radio.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-email.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-date.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-paragraph.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-select.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-text.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-textarea.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-submit.php' );
require_once( 'includes/lib/class-mondula-multistep-forms-block-conditional.php' );


function activate_form_wizard() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/lib/class-mondula-multistep-forms-activator.php';
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
