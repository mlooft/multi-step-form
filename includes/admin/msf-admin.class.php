<?php

/**
 * Admin UI and Logic.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Admin {

	private $_wizard_service;

	private $_token;

	private $_assets_url;

	private $_script_suffix;

	private $_version;

	private $_troubleshoot_mail_error;


	public function __construct(Mondula_Form_Wizard_Wizard_Service $wizard_service, $token, $assets_url, $script_suffix, $version) {
		$this->_wizard_service = $wizard_service;
		$this->_token = $token;
		$this->_assets_url = $assets_url;
		$this->_script_suffix = $script_suffix;
		$this->_version = $version;
		$this->_troubleshoot_mail_error = "";
		$this->init();
	}

	private function init() {
			add_action('admin_menu', array($this, 'setup_menu'));
			add_action('admin_init', array($this, 'download_form_json'));

			add_action('wp_ajax_fw_wizard_save', array($this, 'save'));
	}

	public function setup_menu() {
			$all = add_menu_page('Multi Step Form', 'Multi Step Form', 'manage_options', 'mondula-multistep-forms', array($this, 'menu'), 'dashicons-feedback', '35');
			add_submenu_page('mondula-multistep-forms', 'Multi Step Form List', 'Forms', 'manage_options', 'mondula-multistep-forms', array($this, 'menu'), 0);
			$add = add_submenu_page('mondula-multistep-forms', 'Multi Step Form Edit', 'Add New', 'manage_options', 'mondula-multistep-forms&edit', array($this, 'menu'), 1);
			do_action('multi-step-form/submenus');
			add_submenu_page('mondula-multistep-forms', 'Multi Step Form Troubleshooting', 'Troubleshooting', 'manage_options', 'mondula-multistep-forms-ts', array($this, 'troubleshooting'));
			add_action('admin_print_styles-' . $all, array($this, 'admin_js'));
			add_action('admin_print_styles-' . $add, array($this, 'admin_js'));
	}

	public static function get_translation() {
		return array(
			'tooltips' => array(
				'title' => __('The step title is displayed below the progress bar', 'multi-step-form'),
				'headline' => __('The step headline is displayed above the progress bar' , 'multi-step-form'),
				'copyText' => __('The step description is displayed below the step headline' , 'multi-step-form'),
				'removeStep' => __('remove step', 'multi-step-form'),
				'removeSection' => __('Remove section', 'multi-step-form'),
				'removeBlock' => __('Remove element', 'multi-step-form'),
				'multiChoice' => __('Multi-Select uses checkboxes. Single-Select has radio-buttons.', 'multi-step-form'),
				'paragraph' => __('Provide a static text block for explanations or additional info.', 'multi-step-form'),
				'dateformat' => __('Click for date format specifications', 'multi-step-form'),
			),
			'alerts' => array(
				'invalidEmail' => __('You need to enter a valid email address', 'multi-step-form'),
				'noSubject' => __('You need to provide an email subject', 'multi-step-form'),
				'noStepTitle' => __('WARNING: You need to provide a title for each step', 'multi-step-form'),
				'noSectionTitle' => __('WARNING: You need to provide a title for each section', 'multi-step-form'),
				'noFormTitle' => __('WARNING: You need to provide title for the form', 'multi-step-form'),
				'noBlockTitle' => __('WARNING: Every block needs a label/title.', 'multi-step-form'),
				'onlyFive' => __('ERROR: only 5 steps are allowed in the free version', 'multi-step-form'),
				'onlyTen' => __('ERROR: only 10 steps are possible', 'multi-step-form'),
				'reallyDeleteStep' => __('Do you really want to delete this step?', 'multi-step-form'),
				'reallyDeleteSection' => __('Do you really want to delete this section?', 'multi-step-form'),
				'reallyDeleteBlock' => __('Do you really want to delete this block?', 'multi-step-form'),
				'onlyOneRegistration' => __('Only one registration block allowed!', 'multi-step-form'),
				'ajaxSendError' => __('Form couldn\'t be saved. Check your internet connection.', 'multi-step-form'),
			),
			'title' => __('Step Title', 'multi-step-form'),
			'headline' => __('Step Headline', 'multi-step-form'),
			'copyText' => __('Step description', 'multi-step-form'),
			'partTitle' => __('Section Title', 'multi-step-form'),
			'sections' => __('Sections', 'multi-step-form'),
			'addStep' => __('Add Step', 'multi-step-form'),
			'addSection' => __('Add Section', 'multi-step-form'),
			'addElement' => __('Add Element', 'multi-step-form'),
			'label' => __('Label', 'multi-step-form'),
			'multifile' => __('Multiple Files', 'multi-step-form'),
			'dateformat' => __('Date Format', 'multi-step-form'),
			'required' => __('Required', 'multi-step-form'),
			'radio' => array(
				'header' => __('Header', 'multi-step-form'),
				'option' => __('Option', 'multi-step-form'),
				'options' => __('Options', 'multi-step-form'),
				'addOption' => __('Add option', 'multi-step-form'),
				'multiple' => __('Multiple Selection', 'multi-step-form'),
			),
			'select' => array(
				'options' => __('Options (one per line)', 'multi-step-form'),
				'search' => __('Enable search', 'multi-step-form'),
				'placeholder' => __('Set placeholder', 'multi-step-form'),
			),
			'paragraph' => array(
				'textHtml' => __('Text', 'multi-step-form'),
				'text' => __('Paragraph text', 'multi-step-form'),
			),
			'media' => array(
				'title' => __('Media', 'multi-step-form'),
				'frame_title' => __('Select Media', 'multi-step-form'),
				'tooltip' => __('Place an image or video in your form.', 'multi-step-form'),
				'select' => __('Select a media element', 'multi-step-form'),
				'file_title' => __('Title', 'multi-step-form'),
				'file_name' => __('Filename', 'multi-step-form'),
				'preview' => __('Preview', 'multi-step-form'),
			),
			'get_var' => array(
				'get_param' => __('GET Parameter', 'multi-step-form'),
			),
			'numeric' => array(
				'minimum' => __('Minimum', 'multi-step-form'),
				'maximum' => __('Maximum', 'multi-step-form'),
				'no_minimum' => __('No Minimum', 'multi-step-form'),
				'no_maximum' => __('No Maximum', 'multi-step-form'),
			),
			'email' => array(
				'confirm' => __('Confirm', 'multi-step-form'),
			),
			'filter' => __('RegEx Filter', 'multi-step-form'),
			'filterError' => __('Custom RegEx Error Message', 'multi-step-form'),
			'registration' => array(
				'info' => __('Please select the registration fields to be displayed to the user. Email is always required. If the user does not specify a username or password, WordPress is auto-generating these and sending them to the user via email.', 'multi-step-form'),
				'loggedin' => __('You are already registered and logged in.', 'multi-step-form'),
				'username' => __('Username', 'multi-step-form'),
				'email' => __('Email', 'multi-step-form'),
				'password' => __('Password', 'multi-step-form'),
				'password-confirm' => __('Confirm Password', 'multi-step-form'),
				'firstname' => __('First Name', 'multi-step-form'),
				'lastname' => __('Last Name', 'multi-step-form'),
				'website' => __('Website', 'multi-step-form'),
				'bio' => __('Biographical Info', 'multi-step-form'),
			),
		);
	}

	public function admin_js() {
		if (isset($_GET['edit'])) {
			$id = intval($_GET['edit']);
			$json = $this->_wizard_service->get_as_json($id);
			$i18n = $this->get_translation();
			$cc = Mondula_Form_Wizard_Wizard::fw_get_option('cc' ,'fw_settings_email', 'off');

			wp_register_script($this->_token . '-backend', esc_url($this->_assets_url) . 'scripts/msf-backend' . $this->_script_suffix . '.js', array('postbox', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-tooltip', 'jquery'), $this->_version);
			$ajax = array(
				'i18n' => $i18n,
				'ajaxurl' => admin_url('admin-ajax.php'),
				'id' => $id,
				'nonce' => wp_create_nonce($this->_token . $id),
				'json' => $json,
				'usedcc' => $cc,
			);
			wp_localize_script($this->_token . '-backend', 'wizard', $ajax);
			wp_enqueue_script($this->_token . '-backend');

			wp_register_style($this->_token . '-backend', esc_url($this->_assets_url) . 'styles/msf-backend' . $this->_script_suffix . '.css', array(), $this->_version);
			wp_enqueue_style($this->_token . '-backend');

			wp_enqueue_style($this->_token . '-vendor');
			wp_enqueue_script($this->_token . '-vendor');

			wp_enqueue_media();
		}
	}

	public function troubleshooting() {
		global $wpdb;
		global $wp_version;

		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		
		$mysqlVersion = empty($wpdb->use_mysqli) ? mysql_get_server_info() : mysqli_get_server_info($wpdb->dbh);
		$msfVersion = $this->_version;

		$msfp_base = 'multi-step-form-plus/multi-step-form-plus.php';
		if (is_plugin_active($msfp_base)) {
			$msfpVersion = get_plugin_data(WP_PLUGIN_DIR . '/' . $msfp_base)['Version'];
		} else {
			$msfpVersion = __('Not installed/active', 'multi-step-form');
		}

		$testmail = false;
		$dest_email = '';
		$email_result = false;
		if (isset($_POST['testmail-submit']) && isset($_POST['testmail-receiver']))
		{
			$this->_troubleshoot_mail_error = "";
			$testmail = true;
			$dest_email = sanitize_email($_POST['testmail-receiver']);
			$subject = "Multi Step Form Testmail";
			$message = ("This Testmail was send by the Multi Step" .
						" Plugin to test if wp_mail works.\n\n" .
						"If you did not request this email you can " .
						"ignore it or contact the sender of this mail.");
			$headers = [];

			$fromname = get_bloginfo('name');
			$frommail = get_bloginfo('admin_email');
			array_push($headers, 'From: ' . $fromname . ' <' . $frommail . '>' . "\r\n");
			
			add_action('wp_mail_failed', function ($wp_error) {
				$this->_troubleshoot_mail_error = $wp_error->get_error_message();
			}, 10, 1);
			$email_result = wp_mail($dest_email, $subject, $message, $headers);
			$email_error = $this->_troubleshoot_mail_error;
		}

		require 'partials/msf-troubleshooting.php';
	}

	public function menu() {
		$edit_url = esc_url(
			add_query_arg(
				array(
					'edit' => '',
				)
			)
		);
		$edit = isset($_GET['edit']);
		$delete = isset($_GET['delete']);
		$duplicate = isset($_GET['duplicate']);

		if ($edit) {
			$this->edit($_GET['edit']);
		} elseif ($delete) {
			// Verify the nonce for deleting
			if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_nonce')) {
				$this->delete($_GET['delete']);
			} else {
				// Handle the case where the nonce is invalid
				wp_die('Security check failed');
			}
		} elseif ($duplicate) {
			// Verify the nonce for duplicating
			if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'duplicate_nonce')) {
				$this->duplicate($_GET['duplicate']);
			} else {
				// Handle the case where the nonce is invalid
				wp_die('Security check failed');
			}
		} else {
			$this->table();
		}
	}

	public function delete($id) {
		$this->_wizard_service->delete($id);
		$this->table();
	}

	public function bulk_delete($ids) {
		foreach ($ids as $id) {
			$this->_wizard_service->delete($id);
		}
		$this->table();
	}

	public function duplicate($id) {
		$this->_wizard_service->duplicate($id);
		$this->table();
	}

	/**
	 * Render a notice on the current page.
	 * $type error, warning, success, info
	 */
	private function notice($type, $message) {
		?>
		<div class="notice notice-<?php echo $type; ?> is-dismissible">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	private function import_json($json) {
		$aa = json_decode($json, true);
		if (!$aa) {
			$this->notice('error', __('Invalid JSON-File. Check your syntax.', 'multi-step-form'));
		} else {
			if (!class_exists('Multi_Step_Form_Plus')) {
				$step_count = count($aa['wizard']['steps']);
				for ($i = 0; $i < $step_count; $i++) {
					if ($i > 4) {
						unset($aa['wizard']['steps'][ $i ]);
					}
				}
			}
			$this->_wizard_service->save(0, $aa);
		}
	}

	private function handle_json_upload() {
		if (isset($_FILES['json-import'])) {
			$overrides = array(
				'test_form' => false,
				'test_type' => false // WordPress is too restricted to easily allow json upload
			);
			$uploaded = wp_handle_upload($_FILES['json-import'], $overrides);
	
			// Error checking using WP functions
			if (isset($uploaded['error'])) {
				$this->notice('error', $uploaded['error']);
			} else  {
				$file_type = wp_check_filetype($uploaded['file'], array('json' => 'application/json'));
				if ($file_type['type'] !== 'application/json') {
					$this->notice('error', __('Forms must be imported as JSON files', 'multi-step-form'));
				} else {
					$this->import_json(file_get_contents($uploaded['file']));
				}
			}
		}
	}

	public function table() {
		$table = new Mondula_Form_Wizard_List_Table($this->_wizard_service);
		$this->handle_json_upload();
		$table->prepare_items();
		$edit_url = esc_url(
			add_query_arg(
				array(
					'edit' => '',
				)
			)
		);
		
		require 'partials/msf-table.php';

		if (!is_plugin_active('multi-step-form-plus/multi-step-form-plus.php')) {
			require 'partials/msf-plus-notice.php';
		}
	}

	public function edit($id) {
		add_thickbox();
		
		require 'partials/msf-editor.php';
	}

	public static function sanitize_form_block(&$block) {
		$block_type_arr = Mondula_Form_Wizard_Block::get_block_types();
		if (array_key_exists($block['type'], $block_type_arr)) {
			$block = call_user_func($block_type_arr[$block['type']]['class'] . '::sanitize_admin', $block);
		} else {
			$block = Mondula_Form_Wizard_Block::sanitize_admin($block);
		}
	}

	private function sanitize_form_data(&$data) {
		$data['wizard']['title'] = sanitize_text_field($data['wizard']['title']);
		/* Sanitize form Steps */
		foreach ($data['wizard']['steps'] as &$step) {
			$step['title'] = sanitize_text_field($step['title']);
			$step['headline'] = sanitize_text_field($step['headline']);
			$step['copy_text'] = sanitize_text_field($step['copy_text']);
			foreach ($step['parts'] as &$part) {
				$part['title'] = sanitize_text_field($part['title']);
				foreach ($part['blocks'] as &$block) {
					self::sanitize_form_block($block);
				}
			}
		}
		/* Sanitize Form Settings */
		foreach ($data['wizard']['settings'] as $key => &$setting) {
			switch ($key) {
				case 'thankyou':
					$setting = esc_url($setting);
					break;
				case 'to':
				case 'frommail':
					$setting = sanitize_email($setting);
					break;
				case 'headers':
					// The additional E-Mail headers are only set by wordpress admins and are safe.
					// We can't sanitize them the normal way, as they can contain E-Mail Adresses like Name <mail>.
					$setting = $setting;
					break;
				case 'header':
					$setting = sanitize_textarea_field($setting);
					break;
				case 'fromname':
				case 'subject':
					$setting = sanitize_text_field($setting);
					break;
				default:
					// TODO Review, this should actually call unset but I think some fields are missing
					$setting = sanitize_text_field($setting);
					break;
			}
		}
	}

	/**
	 * Saves a Form after editing in the admin form builder.
	 */
	public function save() {
		$_POST = stripslashes_deep($_POST);
		$id = isset($_POST['id']) ? intval($_POST['id']) : '';
		$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
		$data = isset($_POST['data']) ? $_POST['data'] : '{}';
		$data = json_decode($data, true);

		$this->sanitize_form_data($data);

		if (wp_verify_nonce($nonce, $this->_token . $id)) {
			if (!empty($data)) {
				$new_id = $this->_wizard_service->save($id, $data);
				if ($new_id != $id) {
					$id = $new_id;
					$response['nonce'] = wp_create_nonce($this->_token . $id);
				}
				$response['msg'] = __('Success. Form saved.', 'multi-step-form');
				$response['id'] = $id;
				wp_send_json_success($response);
				return;
			} else {
				wp_send_json_error(
					array(
						'msg' => 'Data is empty.',
					)
				);
				return;
			}
		} else {
			wp_send_json_error(
				array(
					'msg' => 'Nonce failed to verify.',
				)
			);
			return;
		}

		wp_send_json_error(
			array(
				'msg' => 'error',
			)
		);
	}

	/**
	 * Make a JSON string real pretty.
	 * @param $json JSON string
	 * @return pretty JSON string
	 */
	private function prettify_json($json) {
		$obj = json_decode($json);
		return json_encode($obj, JSON_PRETTY_PRINT);
	}

	/**
	 * Export a form as a JSON-Document, send headers and start download.
	 */
	public function download_form_json() {
		if (current_user_can('editor') || current_user_can('administrator')) {
			if (isset($_GET['page']) && $_GET['page'] === 'mondula-multistep-forms'
			&& isset($_GET['export'])) {
				/* Get JSON for form */
				$json = $this->prettify_json($this->_wizard_service->get_as_json($_GET['export']));
				$filename = sanitize_file_name('msf-' . $_GET['export'] . '_' . date('Y-m-d H:i:s'));
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header('Content-Type: application/json');
				header('Content-Disposition: attachment; filename=' . $filename . '.json;');
				header('Content-Transfer-Encoding: binary');
				echo $json;
				exit;
			}
		}
	}
}
