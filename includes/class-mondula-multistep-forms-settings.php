<?php
/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
class Mondula_Form_Wizard_Settings {

    private $settings_api;
		private static $_instance = null;
    private $_parent = null;

    private $_text_domain = 'mondula-multistep-forms';

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
        add_options_page( 'Multi-Step Form Builder', 'Multi-Step Form Builder', 'delete_posts', 'mondula_form_wizard_settings', array($this, 'plugin_page') );
    }
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'fw_settings_basic',
                'title' => __( 'Basic Settings', $this->_text_domain )
            ),
            array(
                'id' => 'fw_settings_styling',
                'title' => __( 'Styling', $this->_text_domain )
            ),
            array(
                'id' => 'wedevs_others',
                'title' => __( 'Other Settings', $this->_text_domain )
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
                //     'label'             => __( 'Text Input', $this->_text_domain ),
                //     'desc'              => __( 'Text input description', $this->_text_domain ),
                //     'type'              => 'text',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'intval'
                // ),
                // array(
                //     'name'              => 'number_input',
                //     'label'             => __( 'Number Input', $this->_text_domain ),
                //     'desc'              => __( 'Number field with validation callback `intval`', $this->_text_domain ),
                //     'type'              => 'number',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'intval'
                // ),
                // array(
                //     'name'  => 'textarea',
                //     'label' => __( 'Textarea Input', $this->_text_domain ),
                //     'desc'  => __( 'Textarea description', $this->_text_domain ),
                //     'type'  => 'textarea'
                // ),
                array(
                    'name'    => 'mailformat',
                    'label'   => __( 'Mail Format', $this->_text_domain ),
                    'desc'    => __( 'Choose formatting for form emails', $this->_text_domain ),
                    'type'    => 'radio',
                    'options' => array(
                        'html' => 'HTML',
                        'text'  => 'Plain Text'
                    )
                ),
                array(
                    'name'  => 'showsummary',
                    'label' => __( 'Summary', $this->_text_domain ),
                    'desc'  => __( 'Display Summary at the end of each form', $this->_text_domain ),
                    'type'  => 'checkbox',
                    'default' => 'on'
                )
                // array(
                //     'name'    => 'multicheck',
                //     'label'   => __( 'Multile checkbox', $this->_text_domain ),
                //     'desc'    => __( 'Multi checkbox description', $this->_text_domain ),
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
                //     'label'   => __( 'A Dropdown', $this->_text_domain ),
                //     'desc'    => __( 'Dropdown description', $this->_text_domain ),
                //     'type'    => 'select',
                //     'default' => 'no',
                //     'options' => array(
                //         'yes' => 'Yes',
                //         'no'  => 'No'
                //     )
                // ),
                // array(
                //     'name'    => 'password',
                //     'label'   => __( 'Password', $this->_text_domain ),
                //     'desc'    => __( 'Password description', $this->_text_domain ),
                //     'type'    => 'password',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'file',
                //     'label'   => __( 'File', $this->_text_domain ),
                //     'desc'    => __( 'File description', $this->_text_domain ),
                //     'type'    => 'file',
                //     'default' => '',
                //     'options' => array(
                //         'button_label' => 'Choose Image'
                //     )
                // )
            ),
            'fw_settings_styling' => array(
                array(
                    'name' => 'progressbarcolor',
                    'label' => __( 'Progress Bar Color', $this->_text_domain ),
                    'desc' => __( 'Choose a color for the progress bar', $this->_text_domain ),
                    'type' => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'activecolor',
                    'label'   => __( 'Active Step Color', $this->_text_domain ),
                    'desc'    => __( 'Choose a color for the active step', $this->_text_domain ),
                    'type'    => 'color',
                    'default' => ''
                ),
				array(
                    'name'    => 'donecolor',
                    'label'   => __( 'Visited Step Color', $this->_text_domain ),
                    'desc'    => __( 'Choose a color for the completed steps', $this->_text_domain ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'nextcolor',
                    'label'   => __( 'Next Step Color', $this->_text_domain ),
                    'desc'    => __( 'Choose a color for the steps to follow', $this->_text_domain ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name' => 'buttoncolor',
                    'label' => __( 'Button Color', $this->_text_domain ),
                    'desc' => __( 'Choose a color for the buttons', $this->_text_domain ),
                    'type' => 'color',
                    'default' => ''
                )

            ),
            'wedevs_others' => array(
                array(
                    'name'    => 'text',
                    'label'   => __( 'Text Input', $this->_text_domain ),
                    'desc'    => __( 'Text input description', $this->_text_domain ),
                    'type'    => 'text',
                    'default' => 'Title'
                ),
                array(
                    'name'  => 'textarea',
                    'label' => __( 'Textarea Input', $this->_text_domain ),
                    'desc'  => __( 'Textarea description', $this->_text_domain ),
                    'type'  => 'textarea'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', $this->_text_domain ),
                    'desc'  => __( 'Checkbox Label', $this->_text_domain ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', $this->_text_domain ),
                    'desc'    => __( 'A radio button', $this->_text_domain ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', $this->_text_domain ),
                    'desc'    => __( 'Multi checkbox description', $this->_text_domain ),
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
                    'label'   => __( 'A Dropdown', $this->_text_domain ),
                    'desc'    => __( 'Dropdown description', $this->_text_domain ),
                    'type'    => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', $this->_text_domain ),
                    'desc'    => __( 'Password description', $this->_text_domain ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', $this->_text_domain ),
                    'desc'    => __( 'File description', $this->_text_domain ),
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
