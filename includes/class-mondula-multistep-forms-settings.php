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

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );

        $this->_parent = $parent;
    }
    function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings
        $this->settings_api->admin_init();
    }
    function admin_menu() {
        add_options_page( 'Multi Step Form', 'Multi Step Form', 'delete_posts', 'mondula_form_wizard_settings', array($this, 'plugin_page') );
    }
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'fw_settings_email',
                'title' => __( 'Email Settings', 'multi-step-form' )
            ),
            array(
                'id' => 'fw_settings_styling',
                'title' => __( 'Styling', 'multi-step-form' )
            )
        );
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
                    'name'    => 'mailformat',
                    'label'   => __( 'Mail Format', 'multi-step-form' ),
                    'desc'    => __( 'Choose formatting for form emails', 'multi-step-form' ),
                    'type'    => 'radio',
                    'options' => array(
                        'html' => 'HTML',
                        'text'  => 'Plain Text'
                    ),
                    'default' => 'html'
                ),
                array(
                    'name'  => 'showsummary',
                    'label' => __( 'Summary', 'multi-step-form' ),
                    'desc'  => __( 'Display Summary at the end of each form', 'multi-step-form' ),
                    'type'  => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'cc',
                    'label' => __( 'CC', 'multi-step-form' ),
                    'desc'  => __( 'Send copy of submitted data to user', 'multi-step-form' ),
                    'type'  => 'checkbox',
                    'default' => 'off'
                )
            ),
            'fw_settings_styling' => array(
                array(
                    'name' => 'progressbar',
                    'label' => __( 'Progress Bar', 'multi-step-form' ),
                    'desc' => __( 'Show progress bar', 'multi-step-form' ),
                    'type' => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name' => 'boxlayout',
                    'label' => __( 'Boxed Layout', 'multi-step-form' ),
                    'desc' => __( 'Boxed frontend styling. Uncheck the checkbox to get a plain layout.', 'multi-step-form' ),
                    'type' => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'activecolor',
                    'label'   => __( 'Active Step Color', 'multi-step-form' ),
                    'desc'    => __( 'Choose a color for the active step', 'multi-step-form' ),
                    'type'    => 'color',
                    'default' => '#1d7071'
                ),
				array(
                    'name'    => 'donecolor',
                    'label'   => __( 'Visited Step Color', 'multi-step-form' ),
                    'desc'    => __( 'Choose a color for the completed steps', 'multi-step-form' ),
                    'type'    => 'color',
                    'default' => '#43a047'
                ),
                array(
                    'name'    => 'nextcolor',
                    'label'   => __( 'Next Step Color', 'multi-step-form' ),
                    'desc'    => __( 'Choose a color for the steps to follow', 'multi-step-form' ),
                    'type'    => 'color',
                    'default' => '#aaa'
                ),
                array(
                    'name' => 'buttoncolor',
                    'label' => __( 'Button Color', 'multi-step-form' ),
                    'desc' => __( 'Choose a color for the buttons', 'multi-step-form' ),
                    'type' => 'color',
                    'default' => '#1d7071'
                )
            )
        );
        return $settings_fields;
    }
    function plugin_page() {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }
		/**
		 * Main Mondula_Form_Wizard_Settings Instance
		 *
		 * Ensures only one instance of Mondula_Form_Wizard_Settings is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see Mondula_Form_Wizard()
		 * @return Main Mondula_Form_Wizard_Settings instance
		 */
		public static function instance ( $parent ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $parent );
			}
			return self::$_instance;
		} // End instance()

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone () {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
		} // End __clone()

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup () {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
		} // End __wakeup()
}
