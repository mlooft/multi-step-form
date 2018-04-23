<?php
/**
 * WordPress settings API demo class
 *
 * @author Alexander Njemz
 */
class Mondula_Form_Wizard_Settings {

	private $settings_api;
	private static $_instance = null;
	private $_parent = null;

	function __construct( $parent ) {
		$this->settings_api = new Mondula_Form_Wizard_Settings_API;

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		$this->_parent = $parent;
	}

	/**
	 * Initialzie the settings and call the functions for fields and sections..
	 */
	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	/**
	 * Register the admin menu.
	 */
	function admin_menu() {
		add_submenu_page( 'mondula-multistep-forms', 'Settings', 'Settings', 'manage_options', 'mondula_form_wizard_settings', array( $this, 'plugin_page' ) );
		// old location: add_options_page( 'Multi Step Form', 'Multi Step Form', 'delete_posts', 'mondula_form_wizard_settings', array( $this, 'plugin_page' ) );
	}

	/**
	 * Get the settings sections that are displayed in horizontal tabs.
	 */
	function get_settings_sections() {
		$sections = array(
			array(
				'id' => 'fw_settings_email',
				'title' => __( 'Email', 'multi-step-form' ),
			),
			array(
				'id' => 'fw_settings_styling',
				'title' => __( 'Styling', 'multi-step-form' ),
			),
		);
		/* If plus is active, add menu section. */
		if ( is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' ) ) {
			/* Form Entries */
			array_push( $sections, array(
				'id' => 'fw_settings_entries',
				'title' => __( 'Form Entries', 'multi-step-form' ),
			));
			/* Conditional Fields */
			array_push( $sections, array(
				'id' => 'fw_settings_conditional',
				'title' => __( 'Conditional Fields', 'multi-step-form' ),
			));
			/* User Registration */
			array_push( $sections, array(
				'id' => 'fw_settings_registration',
				'title' => __( 'User Registration', 'multi-step-form' ),
			));
			/* PLUS: License Key */
			array_push( $sections, array(
				'id' => 'fw_settings_plus',
				'title' => __( 'PLUS', 'multi-step-form' ),
			));
		} else {
			array_push( $sections, array(
				'id' => 'fw_settings_plus',
				'title' => __( 'GET MSF-PLUS', 'multi-step-form' ),
			));
		}

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$settings_fields = array(
			'fw_settings_email' => array(
				array(
					'name' => 'mailformat',
					'label' => __( 'Mail Format', 'multi-step-form' ),
					'desc' => __( 'Choose formatting for form emails', 'multi-step-form' ),
					'type' => 'radio',
					'options' => array(
						'html' => 'HTML',
						'text'  => 'Plain Text',
					),
					'default' => 'html',
				),
				array(
					'name'  => 'showsummary',
					'label' => __( 'Summary', 'multi-step-form' ),
					'desc'  => __( 'Display Summary at the end of each form', 'multi-step-form' ),
					'type'  => 'checkbox',
					'default' => 'on',
				),
				array(
					'name'  => 'cc',
					'label' => __( 'CC', 'multi-step-form' ),
					'desc'  => __( 'Send copy of submitted data to user', 'multi-step-form' ),
					'type'  => 'checkbox',
					'default' => 'off',
				),
			),
			'fw_settings_styling' => array(
				array(
					'name' => 'progressbar',
					'label' => __( 'Progress Bar', 'multi-step-form' ),
					'desc' => __( 'Show progress bar', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'on',
				),
				array(
					'name' => 'boxlayout',
					'label' => __( 'Boxed Layout', 'multi-step-form' ),
					'desc' => __( 'Boxed frontend styling. Uncheck the checkbox to get a plain layout.', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'on',
				),
				array(
					'name' => 'activecolor',
					'label' => __( 'Active Step Color', 'multi-step-form' ),
					'desc' => __( 'Choose a color for the active step', 'multi-step-form' ),
					'type' => 'color',
					'default' => '#1d7071',
				),
				array(
					'name' => 'donecolor',
					'label' => __( 'Visited Step Color', 'multi-step-form' ),
					'desc' => __( 'Choose a color for the completed steps', 'multi-step-form' ),
					'type' => 'color',
					'default' => '#43a047',
				),
				array(
					'name' => 'nextcolor',
					'label' => __( 'Next Step Color', 'multi-step-form' ),
					'desc' => __( 'Choose a color for the steps to follow', 'multi-step-form' ),
					'type' => 'color',
					'default' => '#aaa',
				),
				array(
					'name' => 'buttoncolor',
					'label' => __( 'Button Color', 'multi-step-form' ),
					'desc' => __( 'Choose a color for the buttons', 'multi-step-form' ),
					'type' => 'color',
					'default' => '#1d7071',
				),
			),
		);

		/* License Key */
		if ( is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' ) ) {
			$settings_fields['fw_settings_plus']['license_key'] = array(
				'name' => 'license_key',
				'label' => __( 'License Key', 'multi-step-form' ),
				'desc' => __( 'Please enter your MSF-Plus license key.<br> Having trouble? <a href="mailto:info@mondula.com">Leave us a message</a>.', 'multi-step-form' ),
				'type' => 'text',
				'default' => '',
			);
		}

		/* If plus is active, add menu items. */
		if ( is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' ) ) {
			$settings_fields['fw_settings_entries'] = array(
				array(
					'name' => 'entries_enable',
					'label' => __( 'Enable entry saving', 'multi-step-form' ),
					'desc' => __( 'Save submitted forms to the database', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name'  => 'entries_perpage',
					'label' => __( 'Entries per page', 'multi-step-form' ),
					'desc'  => __( 'How many entries to show on each page in the backend', 'multi-step-form' ),
					'type'  => 'number',
					'default' => '20',
				),
				array(
					'name' => 'entries_csvseparator',
					'label' => __( 'CSV Export: Separator', 'multi-step-form' ),
					'desc' => __( 'To avoid early column breaks, you should set your own column separator', 'multi-step-form' ),
					'type' => 'text',
					'default' => ',',
				),
				// TODO: Plus feature on next update
				// array(
				// 	'name'  => 'entries_unread',
				// 	'label' => __( 'Highlight unread', 'multi-step-form' ),
				// 	'desc'  => __( 'Print new entries in bold', 'multi-step-form' ),
				// 	'type'  => 'checkbox',
				// 	'default' => 'off',
				// ),
			);
			/* Conditional fields Settings */
			$settings_fields['fw_settings_conditional'] = array(
				array(
					'name' => 'conditional_enable',
					'label' => __( 'Enable conditional blocks', 'multi-step-form' ),
					'desc' => __( 'Display some form elements only when others are filled.', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'off',
				),
			);
			/* Conditional fields Settings */
			$settings_fields['fw_settings_registration'] = array(
				array(
					'name' => 'registration_enable',
					'label' => __( 'Enable user registration', 'multi-step-form' ),
					'desc' => __( 'Enable the registration block. You need to add it to each form.', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'registration_notification',
					'label' => __( 'Notify new users', 'multi-step-form' ),
					'desc' => __( 'Email login credentials to a newly-registered user.', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'registration_meta',
					'label' => __( 'Save Metadata', 'multi-step-form' ),
					'desc' => __( 'Save the filled forms as metadata along with registered/registering users.', 'multi-step-form' ),
					'type' => 'checkbox',
					'default' => 'off',
				),
			);
		}
		return $settings_fields;
	}

	/**
	 * Define the plugin page markup.
	 */
	function plugin_page() {
		echo '<div class="wrap">';
		$this->settings_api->show_navigation();
		if (! is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' ) ) {
			echo '<div class="notice notice-info msf-notice" style="border-left-color: #ff6d00;	margin: 20px 0;	border-width: 8px; padding: 15px 10px;">
				<h2>Multi Step Form Plus</h2> 
				<p>
					Our first extension for Multi Step Form is now available. <br>
					Get new feautres, such as "conditional fields", "up to 10 steps", "save form data" and more... 
				</p>
				<p>
					<a class="button" href="https://mondula.com/multi-step-form-plus/" title="More about MSF Plus" target="_blank">Get your upgrade now</a> 
				</p>
			</div>';
		}
		$this->settings_api->show_forms();
		echo '</div>';
	}

	/**
	 * Get all the pages
	 * @return array page names with key value pairs
	 */
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}
		return $pages_options;
	}

	/**
	 * Main Mondula_Form_Wizard_Settings Instance
	 * Ensures only one instance of Mondula_Form_Wizard_Settings is loaded or can be loaded.
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}
}
