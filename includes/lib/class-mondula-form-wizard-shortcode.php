<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Mondula_Form_Wizard_Shortcode {

    /**
     *
     */
    const CODE = 'wizard';

    private $_parent;

    private $_token;

    private $_wizard_service;

    private $_id;

    /**
     * Constructor function
     */
    public function __construct ( Mondula_Form_Wizard $parent, $token, Mondula_Form_Wizard_Wizard_Service $wizard_service ) {
        $this->_parent = $parent;
        $this->_token = $token;
        $this->_wizard_service = $wizard_service;

        add_shortcode( self::CODE, array( $this, 'handler' ) );

        add_action( 'wp_ajax_fw_send_email', array( $this, 'send_email' ) );
        add_action( 'wp_ajax_nopriv_fw_send_email', array( $this, 'send_email' ) );


    }

    public function get_wizard($id) {
      global $wpdb;

      $table = "{$wpdb->prefix}mondula_form_wizards";
      // SQL Query for wizard ID
      $sql = $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id );
      $row = $wpdb->get_row( $sql );
      // var_dump( $row->wizard );
      $wizardser = $row->wizard;
      return unserialize( $wizardser );
    }

    /**
    *  Queries the Database, gets and unserializes entries. Triggers rendering.
    **/
    public function handler( $atts ) {

        $id = $atts['id'];

        if ( ! isset( $atts['id'] ) ) {
            return;
        }

        $wizard = $this->get_wizard($id);

        $data = array();
        $data['date'] = current_time( 'mysql' );
        $data['title'] = 'Generated';
        $data['wizard'] = $wizard;

        $wizard->render( $id );
    }

    public function send_email () {
        global $phpmailer;

        $nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
        $id = isset( $_POST['id'] ) ? $_POST['id'] : '';
        $data = isset( $_POST['fw_data'] ) ? $_POST['fw_data'] : array();
        $name = isset( $_POST['name'] ) ? $_POST['name'] : array();
        $email = isset( $_POST['email'] ) ? $_POST['email'] : array();

        $wizard = $this->get_wizard($id);

        if ( wp_verify_nonce( $nonce, $this->_token) ) {
            if ( ! empty( $data ) ) {
                // add_filter( 'wp_mail_content_type', array( $this , 'set_html_content_type' ) );

                $content = $wizard->render_mail( $data, $name, $email );
                $maildata = $wizard->get_maildata();
                $mail = wp_mail( $maildata['to'], $maildata['subject'], $content );

                // remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

                if ( ! $mail ) {
                    var_dump( $phpmailer->ErrorInfo );
                }

                wp_die( $content );
            } else {
                wp_send_json_error( 'Data is empty.' );
            }
        } else {
            wp_send_json_error( "Nonce couldn't be verified." );
        }
    }

    public function set_html_content_type () {
        return 'text/html';
    }
}
