<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Description of class-mondula-multistep-forms-wizard-repository
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Service {

    private $_repository;

    private $_plugin_version;

    public function __construct( Mondula_Form_Wizard_Wizard_Repository $repository, $plugin_version ) {
        $this->_repository = $repository;
        $this->_plugin_version = $plugin_version;
    }

    public function get_by_id( $id ) {
        $row = $this->_repository->find_by_id( $id );
        return Mondula_Form_Wizard_Wizard::from_aa( json_decode( $row->json, true ), $this->_plugin_version, $row->version );
    }

    public function get_all( ) {
        return $this->_repository->find();
    }

    public function get_as_json( $id ) {
      $all = $this->get_all();
        if ( ! empty( $id ) ) {
            $wizard = $this->get_by_id( $id );
        } else {
            $title = '';
            $wizard = new Mondula_Form_Wizard_Wizard();
        }
        return json_encode( array('wizard' => $wizard->as_aa() ) );
    }

    public function save( $id, $data ) {
        $row = array();
        $row['date'] = current_time( 'mysql' );
        $row['json'] = json_encode( $data['wizard'] );
        $row['version'] = $this->_plugin_version;
        // var_dump ( $row );
        if ( ! empty ( $id ) ) {
            // echo "data" . PHP_EOL;
            return $this->_repository->update( $id, $row );
        } else {
            return $this->_repository->save( $row );
        }
    }

    public function delete( $id ) {
        $this->_repository->delete( $id );
    }

}
