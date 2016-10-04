<?php
/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
class Mondula_Form_Wizard_Settings {

    private $settings_api;
		private static $_instance = null;

    function __construct() {
        $this->settings_api = new Mondula_Form_Wizard_Settings_API;
        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }
    function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings
        $this->settings_api->admin_init();
    }
    function admin_menu() {
        add_options_page( 'Multi-Step Form Builder', 'Multi-Step Form Builder', 'delete_posts', 'mondula_form_wizard_settings', array($this, 'plugin_page') );
    }
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'fw_settings_basic',
                'title' => __( 'Basic Settings', 'wedevs' )
            ),
            array(
                'id' => 'fw_settings_styling',
                'title' => __( 'Styling', 'wedevs' )
            ),
            array(
                'id' => 'wedevs_others',
                'title' => __( 'Other Settings', 'wpuf' )
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
            'fw_settings_basic' => array(
                // array(
                //     'name'              => 'text_val',
                //     'label'             => __( 'Text Input', 'wedevs' ),
                //     'desc'              => __( 'Text input description', 'wedevs' ),
                //     'type'              => 'text',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'intval'
                // ),
                // array(
                //     'name'              => 'number_input',
                //     'label'             => __( 'Number Input', 'wedevs' ),
                //     'desc'              => __( 'Number field with validation callback `intval`', 'wedevs' ),
                //     'type'              => 'number',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'intval'
                // ),
                // array(
                //     'name'  => 'textarea',
                //     'label' => __( 'Textarea Input', 'wedevs' ),
                //     'desc'  => __( 'Textarea description', 'wedevs' ),
                //     'type'  => 'textarea'
                // ),
                array(
                    'name'    => 'mailformat',
                    'label'   => __( 'Mail Format', 'wedevs' ),
                    'desc'    => __( 'Choose formatting for form emails', 'wedevs' ),
                    'type'    => 'radio',
                    'options' => array(
                        'html' => 'HTML',
                        'text'  => 'Plain Text'
                    )
                ),
                array(
                    'name'  => 'showsummary',
                    'label' => __( 'Summary', 'wedevs' ),
                    'desc'  => __( 'Display Summary at the end of each form', 'wedevs' ),
                    'type'  => 'checkbox',
                    'default' => 'on'
                )
                // array(
                //     'name'    => 'multicheck',
                //     'label'   => __( 'Multile checkbox', 'wedevs' ),
                //     'desc'    => __( 'Multi checkbox description', 'wedevs' ),
                //     'type'    => 'multicheck',
                //     'options' => array(
                //         'one'   => 'One',
                //         'two'   => 'Two',
                //         'three' => 'Three',
                //         'four'  => 'Four'
                //     )
                // ),
                // array(
                //     'name'    => 'selectbox',
                //     'label'   => __( 'A Dropdown', 'wedevs' ),
                //     'desc'    => __( 'Dropdown description', 'wedevs' ),
                //     'type'    => 'select',
                //     'default' => 'no',
                //     'options' => array(
                //         'yes' => 'Yes',
                //         'no'  => 'No'
                //     )
                // ),
                // array(
                //     'name'    => 'password',
                //     'label'   => __( 'Password', 'wedevs' ),
                //     'desc'    => __( 'Password description', 'wedevs' ),
                //     'type'    => 'password',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'file',
                //     'label'   => __( 'File', 'wedevs' ),
                //     'desc'    => __( 'File description', 'wedevs' ),
                //     'type'    => 'file',
                //     'default' => '',
                //     'options' => array(
                //         'button_label' => 'Choose Image'
                //     )
                // )
            ),
            'fw_settings_styling' => array(
                array(
                    'name'    => 'activecolor',
                    'label'   => __( 'Active Step Color', 'wedevs' ),
                    'desc'    => __( 'Choose a color for the active step', 'wedevs' ),
                    'type'    => 'color',
                    'default' => ''
                ),
								array(
                    'name'    => 'donecolor',
                    'label'   => __( 'Visited Step Color', 'wedevs' ),
                    'desc'    => __( 'Choose a color for the completed steps', 'wedevs' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'nextcolor',
                    'label'   => __( 'Next Step Color', 'wedevs' ),
                    'desc'    => __( 'Choose a color for the steps to follow', 'wedevs' ),
                    'type'    => 'color',
                    'default' => ''
                ),
								array(
                    'name'  => 'customcss',
                    'label' => __( 'Custom CSS', 'wedevs' ),
										// TODO add link for CSS class list
                    'desc'  => __( 'Enter your CSS here. You can find a List of CSS-classes HERE', 'wedevs' ),
                    'type'  => 'textarea'
                )

            ),
            'wedevs_others' => array(
                array(
                    'name'    => 'text',
                    'label'   => __( 'Text Input', 'wedevs' ),
                    'desc'    => __( 'Text input description', 'wedevs' ),
                    'type'    => 'text',
                    'default' => 'Title'
                ),
                array(
                    'name'  => 'textarea',
                    'label' => __( 'Textarea Input', 'wedevs' ),
                    'desc'  => __( 'Textarea description', 'wedevs' ),
                    'type'  => 'textarea'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'wedevs' ),
                    'desc'  => __( 'Checkbox Label', 'wedevs' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'wedevs' ),
                    'desc'    => __( 'A radio button', 'wedevs' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'wedevs' ),
                    'desc'    => __( 'Multi checkbox description', 'wedevs' ),
                    'type'    => 'multicheck',
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'wedevs' ),
                    'desc'    => __( 'Dropdown description', 'wedevs' ),
                    'type'    => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'wedevs' ),
                    'desc'    => __( 'Password description', 'wedevs' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'wedevs' ),
                    'desc'    => __( 'File description', 'wedevs' ),
                    'type'    => 'file',
                    'default' => ''
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
