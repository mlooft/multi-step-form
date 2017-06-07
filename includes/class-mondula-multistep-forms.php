<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Mondula_Form_Wizard {

	/**
	 * The single instance of Mondula_Form_Wizard.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

        private $_wizard_service;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.4' ) {
		$this->_version = $version;
		$this->_token = 'mondula_form_wizard';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'dist';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/dist/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

    // Set up service
    $this->_wizard_service = new Mondula_Form_Wizard_Wizard_Service(
        new Mondula_Form_Wizard_Wizard_Repository( 'mondula_form_wizards' ),
				$this->_version
    );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin_api = new Mondula_Form_Wizard_Admin_API();
                        $this->admin = new Mondula_Form_Wizard_Admin(
                            $this->_wizard_service,
                            $this->_token,
                            $this->assets_url,
                            $this->script_suffix,
                            $this->_version,
                            'multi-step-form' // text domain
                        );
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

                // Setup shortcode
                $this->shortcode = new Mondula_Form_Wizard_Shortcode( $this, $this->_token, $this->_wizard_service );
	} // End __construct ()

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css');
		wp_enqueue_style( $this->_token . 'jquery-ui' );
		wp_register_style( $this->_token . '-vendor-frontend', esc_url( $this->assets_url ) . 'vendor-frontend.min.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-vendor-frontend' );
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'frontend.min.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	}

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		$i18n = array(
			'sending' => __('sending data', 'multi-step-form'),
			'submitSuccess' => __('success', 'multi-step-form'),
			'submitError' => __('submit failed', 'multi-step-form'),
			'uploadingFile' => __('Uploading file', 'multi-step-form'),
			'chooseFile' => __('Choose a file', 'multi-step-form'),
			'showSummary' => __('show summary', 'multi-step-form'),
			'hideSummary' => __('hide summary', 'multi-step-form'),
			'errors' => array(
				'requiredFields' => __('Please fill all the required fields!', 'multi-step-form'),
				'requiredField' => __('This field is required', 'multi-step-form'),
				'someRequired' => __('Some required Fields are empty', 'multi-step-form'),
				'checkFields' => __('Please check the highlighted fields.', 'multi-step-form')
			)
		);
		wp_register_script( $this->_token . '-vendor-frontend', esc_url( $this->assets_url ) . 'vendor-frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-vendor-frontend' );
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'frontend' . $this->script_suffix . '.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
		$ajax = array(
				'i18n' => $i18n,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( $this->_token ),
		);
		wp_localize_script( $this->_token . '-frontend', 'ajax', $ajax );
	}

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_enqueue_style( $this->_token . '-admin' );
    wp_register_style( $this->_token . '-fa', '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
    wp_enqueue_style( $this->_token . '-fa' );
	}

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_enqueue_script( $this->_token . '-admin' );
	}

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'multi-step-form', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'multi-step-form';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Main Mondula_Form_Wizard Instance
	 *
	 * Ensures only one instance of Mondula_Form_Wizard is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Mondula_Form_Wizard()
	 * @return Main Mondula_Form_Wizard instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	}

}
