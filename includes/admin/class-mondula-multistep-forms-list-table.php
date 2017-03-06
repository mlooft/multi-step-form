<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Description of class-mondula-multistep-forms-list-table
 *
 * @author alex
 */
class Mondula_Form_Wizard_List_Table extends WP_LIST_TABLE {

    var $example_data = array(
        array(
            'title' => 'Wizard 1',
            'shortcode' => '[wizard id=1]',
            'date' => '2015-07-13'
        )
    );

    private $_wizard_service;

    private $_text_domain;

    public function __construct ( Mondula_Form_Wizard_Wizard_Service $wizard_service, $text_domain ) {
        parent::__construct( array(
            'screen' => get_current_screen()
        ) );
        $this->_wizard_service = $wizard_service;
        $this->_text_domain = $text_domain;
    }

    function get_columns ( ) {
       $columns = array(
           'cb' => '<input type="checkbox" />',
           'title' => 'Title',
           'shortcode' => 'Shortcode',
           'sendto' => 'Send mails to',
           'date' => 'Date'
       );
       return $columns;
    }

    public function column_default( $item, $column_name ) {
      $wiz = json_decode( $item['json'], true );
      switch( $column_name ) {
          case 'title':
              $actions = array(
                  'Edit' => '<a href="#"></a>'
              );
              $this->row_actions( $actions );
          case 'date':
              return $item[ $column_name ];
          case 'shortcode':
              return '[wizard id="' .  $item['id'] .'"]';
          case 'sendto':
              if ( $wiz['settings']['to'] != '' ) {
                return $wiz['settings']['to'];
              } else {
                return '';
              }
          default:
              return print_r( $item, true );
      }
    }

    public function column_title( $item ) {
      $wiz = json_decode( $item['json'], true );

      $edit_url = esc_url( add_query_arg( array( 'edit' => $item['id'] ) ) );
      $delete_url = esc_url( add_query_arg ( array ( 'delete' => $item['id'] ) ) );

      $actions = array(
          'fw-edit' => '<a href="' . $edit_url . '">' . __( 'Edit', $this->_text_domain ) . '</a>',
          'fw-delete' => '<a href="' . $delete_url . '">' . __( 'Delete', $this->_text_domain ) . '</a>'
      );
      return sprintf('<a href="' . $edit_url . '">' . __( $wiz['title'] , $this->_text_domain ) . '</a>'.'%1$s', $this->row_actions($actions));
    }

//    public function handle_row_actions( $item, $column_name, $primary ) {
//        switch ($column_name) {
//            case 'title':
//                return 'Title';
//            default:
//                return 'default';
//        }
//    }


    function prepare_items ( ) {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items = $this->_wizard_service->get_all();

        $this->process_bulk_action();
    }


    function get_bulk_actions () {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="wizard[]" value="%s" />', $item['id']
        );
    }

    public function process_bulk_action() {

        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }

        $action = $this->current_action();

        switch ( $action ) {

            case 'delete':
                // wp_die( 'Delete something' );
                $wizard_ids = $_GET['wizard'];
                foreach ( $wizard_ids as $wizard_id ) {
                    $this->_wizard_service->delete( $wizard_id );
                }
                break;

            case 'save':
                wp_die( 'Save something' );
                break;

            default:
                // do nothing or something else
                return;
                break;
        }

        return;
    }
}
