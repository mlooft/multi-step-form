<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mondula_Form_Wizard_Shortcode {

	/**
	 *
	 */
	const CODE_OLD = 'wizard';
	const CODE = 'multi-step-form';

	private $_parent;

	private $_token;

	private $_wizard_service;

	private $_id;

	/**
	 * Constructor function
	 */
	public function __construct( Mondula_Form_Wizard $parent, $token, Mondula_Form_Wizard_Wizard_Service $wizard_service ) {
		$this->_parent = $parent;
		$this->_token = $token;
		$this->_wizard_service = $wizard_service;

		add_shortcode( self::CODE_OLD, array( $this, 'handler' ) );
		add_shortcode( self::CODE, array( $this, 'handler' ) );

		add_action( 'wp_ajax_fw_send_email', array( $this, 'fw_send_email' ) );
		add_action( 'wp_ajax_nopriv_fw_send_email', array( $this, 'fw_send_email' ) );

		add_action( 'wp_ajax_fw_upload_file', array( $this, 'fw_upload_file' ) );
		add_action( 'wp_ajax_nopriv_fw_upload_file', array( $this, 'fw_upload_file' ) );

		add_action( 'wp_ajax_fw_delete_files', array( $this, 'fw_delete_files' ) );
		add_action( 'wp_ajax_nopriv_fw_delete_files', array( $this, 'fw_delete_files' ) );
	}



	public function get_wizard( $id ) {
		return $this->_wizard_service->get_by_id( $id );
	}

	/**
	*  Queries the Database, gets and unserializes entries. Triggers rendering.
	**/
	public function handler( $atts ) {

		$id = $atts['id'];

		if ( ! isset( $atts['id'] ) ) {
			return;
		}

		$wizard = $this->get_wizard( $id );

		$data = array();
		$data['date'] = current_time( 'mysql' );
		$data['title'] = 'Generated';
		$data['wizard'] = $wizard;

		return $wizard->render( $id );
	}

	/**
	 * AJAX action called by wp_ajax_fw_delete_files. The files in $filenames
	 * are deleted from the msf-temp directory. This function is called when
	 * a user has already uploaded files but decides to exit the form.
	 **/
	public function fw_delete_files() {
		$filenames = isset( $_POST['filenames'] ) ? $_POST['filenames'] : array();
		$filepaths = $this->generate_attachment_paths( $filenames );
		if ( count( $filepaths ) != 0 ) {
			$this->delete_files( $filepaths );
			echo 'tempfiles deleted';
		}
	}

	/**
	 * Helper for fw_delete_files. Deletes an uploaded file
	 * from the msf-temp directory.
	 **/
	private function delete_files( $filepaths ) {
		foreach ( $filepaths as $filepath ) {
			wp_delete_file( $filepath );
		}
	}
	/**
	 * AJAX action called by wp_ajax_fw_upload_file.
	 * Temporarily upload a file to wp-content/uploads/msf-temp directory.
	 * The file remains on the server until the form is submitted by the client.
	 **/
	 public function fw_upload_file() {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$tempdir = wp_upload_dir();
		$upload_overrides = array(
			'test_form' => false,
		);
		if ( wp_verify_nonce( $nonce, $this->_token ) ) {
			add_filter( 'upload_dir', 'wpse_change_upload_dir_temporarily' );

			/**
			* Temporarily change the WP upload directory to wp-content/uploads/temp
			*
			*/
			function wpse_change_upload_dir_temporarily( $dirs ) {
				$dirs['subdir'] = '/msf-temp';
				$dirs['path'] = $dirs['basedir'] . '/msf-temp';
				$dirs['url'] = $dirs['baseurl'] . '/msf-temp';
				return $dirs;
			}

			$uploaded_files = array();
			$response = array();
			$response['filenames'] = array();

			foreach ( $_FILES as $file ) {
				$uploaded_file = wp_handle_upload( $file, $upload_overrides );
				if ( ! isset( $uploaded_file['error'] ) ) {
					array_push( $uploaded_files,  $uploaded_file );
				} else {
					$response['error'] = $uploaded_file['error'];
				}
			}

			if ( ! isset( $response['error'] ) && count( $uploaded_files ) === count( $_FILES ) ) {
				$response['success'] = true;
				foreach ( $uploaded_files as $file ) {
					array_push( $response['filenames'], basename( $file['url'] ) );
				}
			} else {
				$response['success'] = false;
			}
			echo json_encode( $response );
			remove_filter( 'upload_dir', 'wpse_change_upload_dir_temporarily' );
			wp_die();
		} else {
			wp_send_json_error( "Nonce couldn't be verified." );
		}
	}

	private function generate_attachment_paths( $files ) {
		$attachments = array();
		for ( $i = 0; $i < count( $files ); $i++ ) {
			if ( $files[ $i ] != '' ) {
				$attachments[ $i ] = WP_CONTENT_DIR . '/uploads/msf-temp/' . sanitize_file_name( $files[ $i ] );
			}
		}
		return $attachments;
	}

	private function sanitize_data( &$data, &$email ) {
		$email = sanitize_email($email);
		foreach ( $data as &$fields ) {
			foreach ( $fields as &$field) {
				foreach ($field as $key => &$value) {
					$value = sanitize_textarea_field($value);
				}
			}
		}
	}

	public function fw_send_email() {
		global $phpmailer;

		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$id = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$data = isset( $_POST['fw_data'] ) ? $_POST['fw_data'] : array();
		$name = isset( $_POST['name'] ) ? $_POST['name'] : array();
		$email = isset( $_POST['email'] ) ? $_POST['email'] : array();
		$reg = isset( $_POST['reg'] ) ? $_POST['reg'] : null;
		$files = isset( $_POST['attachments'] ) ? $_POST['attachments'] : array();

		$wizard = $this->get_wizard( $id );

		if ( wp_verify_nonce( $nonce, $this->_token ) ) {
			if ( ! empty( $data ) ) {
				/* Sanitize data */
				$this->sanitize_data( $data, $email );
				/* Send data to PRO */
				do_action( 'msfp_save', $id, $data );
				/* Register user */
				if ( $reg ) {
					do_action( 'msfp_register', $reg, $data, $id );
				}

				/* Send email */
				$mailformat = Mondula_Form_Wizard_Wizard::fw_get_option( 'mailformat' ,'fw_settings_email', 'html' );
				$cc = Mondula_Form_Wizard_Wizard::fw_get_option( 'cc' ,'fw_settings_email', 'off' );
				$content = $wizard->render_mail( $data, $name, $email, $mailformat );
				$settings = $wizard->get_settings();
				$attachments = $this->generate_attachment_paths( $files );

				if ( $mailformat == 'html' ) {
					add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
					$headers = array( 'Content-Type: text/html; charset=UTF-8' );
				} else {
					$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
				}
				if ( $settings['frommail'] || $settings['fromname'] ) {
					$fromname = $settings['fromname'] != '' ? $settings['fromname'] : get_bloginfo( 'name' );
					$frommail = $settings['frommail'] != '' ? $settings['frommail'] : get_bloginfo( 'admin_email' );
					array_push( $headers, 'From: ' . $fromname . ' <' . $frommail . '>' . "\r\n" );
				}
				if ( isset( $settings['headers'] ) && $settings['headers'] ) {
					$additional_headers = explode("\n", $settings['headers'] );
					$headers = array_merge( $headers, $additional_headers );
				}
				// send email to admin
				$mail = wp_mail( $settings['to'], $settings['subject'], $content , $headers, $attachments );
				// send copy to user
				if ( count( $email ) == 1 && $cc === 'on' ) {
					$copy = wp_mail( $email, 'CC: ' . $settings['subject'], $content, $headers );
				}
				remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

				// delete temporary files from webserver after mail is sent
				$this->delete_files( $attachments );

				wp_die( $content );
			} else {
				wp_send_json_error( 'Data is empty.' );
			}
		} else {
			wp_send_json_error( "Nonce couldn't be verified." );
		}
	}

	public function set_html_content_type() {
		return 'text/html';
	}
}
