<?php


if (!defined('ABSPATH')) exit;


class Mondula_Form_Wizard_Wizard {

	private $_steps = array();
	private $_settings = array();
	private $_title;

	public function get_settings(){
	  return $this->_settings;
	}

	public function set_settings ($settings) {
	  $this->_settings = $settings;
	}

	public function get_title() {
		return $this->_title;
	}

	public function set_title ($title) {
	  $this->_title = $title;
	}

	/**
	 *
	 * @param $array $elements Elements of the step to add
	 * @return void
	 */
	public function add_step ($steps) {
		$this->_steps[] = $steps;
	}

	public static function fw_get_option($option, $section, $default = '') {
	  $options = get_option($section);
	  if (isset($options[$option]))
			return $options[$option];
		else
			return $default;
	}

	private function render_progress_bar () {
		$cnt = count($this->_steps);
		
		require 'partials/progress-bar.php';
	}

	/**
	 * Renders the form to the client.
	 */
	public function render($wizard_id) {
		$progressbar = $this->fw_get_option('progressbar', 'fw_settings_styling', 'on') === 'on';
		$count = count($this->_steps);
		$classes = 'fw-wizard';
		if (!$progressbar) {
			$classes .= ' fw-no-progressbar';
		}
		if ($count > 5) {
			$classes .= ' fw-more-than-five';
		}
		$show_summary = Mondula_Form_Wizard_Wizard::fw_get_option('showsummary' ,'fw_settings_email', 'on') === 'on';
		$use_captcha = Mondula_Form_Wizard_Wizard::fw_get_option('recaptcha_enable' ,'fw_settings_captcha') === 'on';
		$captcha_key = Mondula_Form_Wizard_Wizard::fw_get_option('recaptcha_sitekey' ,'fw_settings_captcha', '');
		$captcha_invisible = Mondula_Form_Wizard_Wizard::fw_get_option('recaptcha_invisible' ,'fw_settings_captcha', 'on') === 'on';

		if ($use_captcha) {
			$recaptcha_url = add_query_arg(
				array(
					'render' => 'explicit',
				),
				'https://www.google.com/recaptcha/api.js'
			);
			wp_enqueue_script('google-recaptcha', $recaptcha_url, array(), '2.0', true);
		}

		ob_start();
		require 'partials/form.php';
		return ob_get_clean();
	}

	public function render_mail($data, $mailformat) {
		$headline = $this->get_headline($data);
		
		$mail_template = get_stylesheet_directory() . '/multi-step-form';
		
		if ($mailformat == 'text') {
		  	$filename = '/mail-plain.php';
		} else {
			$filename = '/mail-html.php';
		}

		$mail_template .= $filename;

		if (!file_exists($mail_template)) {
			// If the override template does NOT exist, fallback to the default template.
			$mail_template = __DIR__ . '/partials' . $filename;
		}

		// Output E-Mail
		ob_start();
		require $mail_template;
		return ob_get_clean();
	}

	public function send_mail($data, $attachments = array()) {
		$mailformat = Mondula_Form_Wizard_Wizard::fw_get_option('mailformat' ,'fw_settings_email', 'html');
		$content = $this->render_mail($data, $mailformat);
		$subject = $this->get_subject($data);
		$settings = $this->get_settings();

		if ($mailformat == 'html') {
			$headers = array('Content-Type: text/html; charset=UTF-8');
		} else {
			$headers = array('Content-Type: text/plain; charset=UTF-8');
		}

		$fromname = !empty($settings['fromname']) ? $settings['fromname'] : get_bloginfo('name');
		$frommail = !empty($settings['frommail']) ? $settings['frommail'] : get_bloginfo('admin_email');
		array_push($headers, 'From: ' . $fromname . ' <' . $frommail . '>' . "\r\n");

		if ($settings['replyto'] && $settings['replyto'] !== 'no-reply') {
			$replyMail = $this->find_field($data, $settings['replyto']);
			if ($replyMail) {
				$replyMail = sanitize_email($replyMail);
				array_push($headers, 'Reply-To: ' . $replyMail . "\r\n");
			}
		}

		if (isset($settings['headers']) && $settings['headers']) {
			$additional_headers = explode("\n", $settings['headers']);
			$headers = array_merge($headers, $additional_headers);
		}
		// send email to admin
		$mail_success = wp_mail($settings['to'], $subject, $content, $headers, $attachments);
		
		// send copy to user
		$mail_copy_success = true;
		if ($mail_success) {
			if (isset($settings['usercopy'])) {
				if ($settings['usercopy'] !== 'no-usercopy') {
					$userMail = $this->find_field($data, $settings['usercopy']);
					if ($userMail) {
						$userMail = sanitize_email($userMail);
						$mail_copy_success = wp_mail($userMail, $subject, $content, $headers);
					}
				}
			} else {
				$oldCc = Mondula_Form_Wizard_Wizard::fw_get_option('cc' ,'fw_settings_email', 'off');
				$firstEmail = isset($_POST['first_email']) ? sanitize_email($_POST['first_email']) : "";
				if ($oldCc === "on" && !empty($firstEmail)) {
					$firstEmail = sanitize_email($firstEmail);
					$mail_copy_success = wp_mail($firstEmail, $subject, $content, $headers);
				}
			}
		}

		return array(
			'mail' => $mail_success,
			'usercopy' => $mail_copy_success,
		);
	}

	private function str_replacements($input, $data) {
		if ($this->_settings['replacements']) {
			// Find all replacement positions in string
			preg_match_all("/[^\\\\]{(?P<tag>[^{}\\n]+)}/U", $input, $matches);
			
			// PHP BigO https://stackoverflow.com/a/2484455
			// n => Number of replacements
			// m => Number of (filled) form fields

			// Preallocate the required replacement keys O(n)
			$replacements = array();
			foreach ($matches['tag'] as $tag) {
				$replacements[$tag] = "";
			}

			// Go through the entire form O(m)
			foreach ($data as $section) {
				foreach ($section as $pairs) {
					foreach ($pairs as $key => $value) {
						// array_key_exists is O(n) but in reality due to using
						// a hashmap performs more like O(1)
						if (array_key_exists($key, $replacements)) {
							$replacements[$key] = sanitize_text_field($value);
						}
					}
				}
			}

			// Start replacing the keys with the actual values
			$input = preg_replace_callback("/[^\\\\]{(?P<tag>[^{}\\n]+)}/U", function($match) use ($replacements) {
				if (array_key_exists($match['tag'], $replacements)) {
					return $match[0][0] . $replacements[$match['tag']];
				} else {
					return $match[0];
				}
			}, $input);

			$input = str_replace("\\{", "{", $input);
		}

		return $input;
	}

	public function get_subject($data) {
		$subject = $this->_settings['subject'];
		$subject = $this->str_replacements($subject, $data);
		return $subject;
	}

	public function get_headline($data) {
		$headline = $this->_settings['header'];
		$headline = $this->str_replacements($headline, $data);
		return $headline;
	}

	public function find_field($data, $name) {
		foreach ($data as $fields) {
			foreach ($fields as $field) {
				foreach ($field as $key => $value) {
					if ($key === $name) {
						return $value;
					}
				}
			}
		}

		return false;
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

	public static function from_aa($aa, $current_version, $serialized_version) {
		$wizard = new Mondula_Form_Wizard_Wizard();
		$wizard->set_settings($aa['settings']);
		$wizard->set_title($aa['title']);
		foreach ($aa['steps'] as $step) {
			$wizard->add_step(
				Mondula_Form_Wizard_Wizard_Step::from_aa($step, $current_version, $serialized_version)
			);
		}
		return $wizard;
	}
}
