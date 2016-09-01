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

        $this->_wizard = new Mondula_Form_Wizard_Wizard( 1 );
        $this->initialize_wizard();
    }

    private function options ( $header, $options ) {
        $opts = new Mondula_Form_Wizard_Step_Options();
        $opts->set_header( $header );
        $opts->set_options( $options );
        return $opts;
    }

    private function radio ( $header, $options ) {
        $elements = array();
        if ( ! empty( $header ) ) {
            $elements[] = array( 'type' => 'header', 'value' => $header);
        }
        foreach ($options as $opt) {
            $elements[] = array( 'type' => 'option', 'value' => $opt );
        }
        return new Mondula_Form_Wizard_Block_Radio( $elements, 'true' ); // TODO: make required variable
    }

    private function conditional( $options, $elements ) {
        return new Mondula_Form_Wizard_Block_Conditional( $options, $elements );
    }

    private function step ( $title, $blocks ) {
        $step = new Mondula_Form_Wizard_Wizard_Step_Part( $title, $blocks );
        return $step;
    }

    private function add_step ( $title, $parts ) {
        $copy = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam bibendum
            volutpat orci nec finibus. Nullam pulvinar nibh et aliquet sodales. Donec fringilla condimentum metus ac viverra. Sed dui mi, bibendum id purus vel, scelerisque tincidunt velit. Aenean bibendum nec sem a venenatis. Praesent lobortis nunc tortor, sit amet fringilla diam ullamcorper non. Vestibulum blandit, ligula vitae venenatis fermentum, orci risus consectetur magna, nec ultrices diam mauris id elit. Nullam vestibulum ligula et ex maximus vulputate. Donec non nisl sed magna sollicitudin auctor.";
        $this->_wizard->add_step( new Mondula_Form_Wizard_Wizard_Step( $title, $title, $copy, $parts ) );
    }

    private function initialize_wizard () {
//        $this->add_step( 'Schritt 1',
//            array(
//                $this->step( 'So lebe ich jetzt:', array(
//                    $this->radio( 'Haus', array(
//                        'Innenstadt',
//                        'Stadtgebiet',
//                        'Stadtrand',
//                        'Im Grünen'
//                    ) ),
//                    $this->radio( 'Wohnung', array(
//                        'Innenstadt',
//                        'Stadtgebiet',
//                        'Stadtrand',
//                        'Im Grünen'
//                    ) )
//                ) ),
//                $this->step( 'Das sind meine Träume:', array(
//                    $this->radio( 'Haus', array(
//                        'Innenstadt',
//                        'Stadtgebiet',
//                        'Stadtrand',
//                        'Im Grünen'
//                    ) ),
//                    $this->radio( 'Wohnung', array(
//                        'Innenstadt',
//                        'Stadtgebiet',
//                        'Stadtrand',
//                        'Im Grünen'
//                    ) )
//                ) )
//            )
//        );
        $this->add_step( 'Schritt 1',
            array(
                $this->step( 'So lebe ich jetzt:' , array(
                    $this->conditional( array(
                        'Haus', 'Wohnung'
                    ), array(
                        array( $this->radio( '' , array( 'Innenstadt', 'Stadtgebiet', 'Stadtrand', 'Im Grünen' ) ) ),
                        array( $this->radio( '' , array( 'Innenstadt', 'Stadtgebiet', 'Stadtrand', 'Im Grünen' ) ) )
                    ) )
                ) ),
                $this->step( 'Das sind meine Träume:', array(
                    $this->conditional( array(
                        'Haus', 'Wohnung'
                    ), array(
                        array( $this->radio( '' , array( 'Innenstadt', 'Stadtgebiet', 'Stadtrand', 'Im Grünen' ) ) ),
                        array( $this->radio( '' , array( 'Innenstadt', 'Stadtgebiet', 'Stadtrand', 'Im Grünen' ) ) )
                    ) )
                ) )
            )
        );
        $this->add_step( 'Schritt 2',
            array(
                $this->step( 'So lebe ich jetzt:', array(
                    $this->radio( 'Größe', array(
                        '0-30',
                        '30-60',
                        '60-100',
                        '100-200'
                    ) ),
                    $this->radio( 'Wohnungsart', array(
                        'Altbau',
                        'Neubau',
                        'Penthouse',
                        'Im Grünen'
                    ) )
                ) ),
                $this->step( 'Das sind meine Träume:', array(
                    $this->radio( 'Wohnfläche', array(
                        '0-30',
                        '30-60',
                        '60-100',
                        '100-200'
                    ) ),
                    $this->radio( 'Bauart', array(
                        'Altbau',
                        'Neubau',
                        'Penthouse',
                        'Im Grünen'
                    ) )
                ) )
            )
        );
        $this->add_step( 'Schritt 3',
            array(
                $this->step( 'So lebe ich jetzt:', array(
                    $this->radio( 'Eigenständig', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) ),
                    $this->radio( 'In der Familie', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) )
                ) ),
                $this->step( 'Das sind meine Träume:', array(
                    $this->radio( 'Selbständig', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) ),
                    $this->radio( 'Servicewohnen', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) )
                ) )
            )
        );
        $this->add_step( 'Schritt 4',
            array(
                $this->step( 'Weitere Angebote:', array(
                    $this->radio( 'Umzugshilfe', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) ),
                    $this->radio( 'Altersgerechte Umbauten', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) )
                ) ),
                $this->step( 'Weitere Angebote:', array(
                    $this->radio( 'Begleitende Beratung/Kfw-Förderungen', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) ),
                    $this->radio( 'Persönlicher Kontakt', array(
                        'Ja',
                        'Nein',
                        'Vielleicht',
                        'Beratung'
                    ) )
                ) )
            )
        );
        $this->add_step( 'Schritt 5',
            array(
                $this->step( 'Ihre Zusammenfassung:', array( new Mondula_Form_Wizard_Step_Submit() ) )
            )
        );
    }

    /**
    *  Queries the Database, gets and unserializes entries. Triggers rendering.
    **/
    public function handler( $atts ) {
        global $wpdb;

        $id = $atts['id'];

        if ( ! isset( $atts['id'] ) ) {
            return;
        }

        $table = "{$wpdb->prefix}mondula_form_wizards";

        // SQL Query for wizard ID
        $sql = $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id );


        $row = $wpdb->get_row( $sql );
        // var_dump( $row->wizard );
        // TODO change for PHP serialization to JSON
        $wizardser = $row->wizard;
        $wizard = unserialize( $wizardser );

        $data = array();
        $data['date'] = current_time( 'mysql' );
        $data['title'] = 'Generated';
        $data['wizard'] = $this->_wizard;

//        $wpdb->insert( $table, $data );

//         var_dump( $wizard );

       $wizard->render( $id );
        // $this->_wizard->render( 1 );
    }

    public function send_email () {
        global $phpmailer;

        $nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
        $data = isset( $_POST['fw_data'] ) ? $_POST['fw_data'] : array();
        $name = isset( $_POST['name'] ) ? $_POST['name'] : array();
        $email = isset( $_POST['email'] ) ? $_POST['email'] : array();

        if ( wp_verify_nonce( $nonce, $this->_token) ) {
            if ( ! empty( $data ) ) {
                // add_filter( 'wp_mail_content_type', array( $this , 'set_html_content_type' ) );

                $content = $this->_wizard->render_mail( $data, $name, $email );
                $mail = wp_mail( 'lewe.ohlsen@mondula.com', 'mondula-form-wizard', $content );

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
