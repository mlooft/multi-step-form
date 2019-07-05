<?php


if ( ! defined( 'ABSPATH' ) ) exit;


class Mondula_Form_Wizard_Wizard {

	private $_steps = array();
	private $_settings = array();
	private $_title;

	public function get_settings(){
	  return $this->_settings;
	}

	public function set_settings ( $settings ) {
	  $this->_settings = $settings;
	}

	public function get_title() {
		return $this->_title;
	}

	public function set_title ( $title ) {
	  $this->_title = $title;
	}

	/**
	 *
	 * @param $array $elements Elements of the step to add
	 * @return void
	 */
	public function add_step ( $steps ) {
		$this->_steps[] = $steps;
	}

	public static function fw_get_option($option, $section, $default = '') {
	  $options = get_option($section);
	  if ( isset( $options[$option] ) )
			return $options[$option];
		else
			return $default;
	}

	private function render_progress_bar () {
		$cnt = count( $this->_steps );
		
		require 'partials/progress-bar.php';
	}

	/**
	 * Renders the form to the client.
	 */
	public function render( $wizard_id ) {
		$progressbar = $this->fw_get_option( 'progressbar', 'fw_settings_styling', 'on' ) === 'on';
		$count = count( $this->_steps );
		$classes = 'fw-wizard';
		if ( ! $progressbar ) {
			$classes .= ' fw-no-progressbar';
		}
		if ( $count > 5 ) {
			$classes .= ' fw-more-than-five';
		}
		$show_summary = Mondula_Form_Wizard_Wizard::fw_get_option('showsummary' ,'fw_settings_email', 'on') === 'on';
		$use_captcha = Mondula_Form_Wizard_Wizard::fw_get_option('recaptcha_enable' ,'fw_settings_captcha') === 'on';
		$captcha_key = Mondula_Form_Wizard_Wizard::fw_get_option('recaptcha_sitekey' ,'fw_settings_captcha', '');

		if ($use_captcha) {
			$recaptcha_url = add_query_arg(
				array('render' => $captcha_key),
				'https://www.google.com/recaptcha/api.js'
			);
			wp_enqueue_script('google-recaptcha', $recaptcha_url, array(), '3.0', true);
		}

		ob_start();

		require 'partials/form.php';

		return ob_get_clean();
	}

	public function render_mail($data, $mailformat) {
		ob_start();
		
		if ($mailformat == 'text') {
		  	require 'partials/mail-plain.php';
		} else {
			require 'partials/mail-html.php';
		}

		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	public function as_aa() {
		$steps_json = array();
		foreach ($this->_steps as $step) {
			$steps_json[] = $step->as_aa();
		}
		return array(
			'title' => $this->_title,
			'steps' => $steps_json,
			'settings' => $this->_settings
		);
	}

	public static function from_aa( $aa, $current_version, $serialized_version ) {
		$wizard = new Mondula_Form_Wizard_Wizard();
		$wizard->set_settings( $aa['settings'] );
		$wizard->set_title( $aa['title'] );
		foreach ( $aa['steps'] as $step ) {
			$wizard->add_step(
				Mondula_Form_Wizard_Wizard_Step::from_aa( $step, $current_version, $serialized_version )
			);
		}
		return $wizard;
	}
}
