<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Description of class-mondula-form-wizard-wizard-repository
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Service {

    private $_repository;

    public function __construct( Mondula_Form_Wizard_Wizard_Repository $repository ) {
        $this->_repository = $repository;
    }

    public function get_by_id( $id ) {
        return $this->_repository->find_by_id( $id );
    }

    public function get_all( ) {
        return $this->_repository->find();
    }

    public function get_as_json( $id ) {
        if ( ! empty( $id ) ) {
            $row = $this->_repository->find_by_id( $id );
            $title = $row->title;
            $wizard = unserialize( $row->wizard );
            // var_dump($wizard);
        } else {
            $title = '';
            $wizard = new Mondula_Form_Wizard_Wizard();
        }
        return json_encode( array( 'title' =>  $title, 'wizard' => $wizard->as_aa() ) );
    }

    private function from_json( $wizard_json ) {
        //var_dump( $_POST );
        //var_dump( $wizard_json );
        $wizard = new Mondula_Form_Wizard_Wizard();
        // $w = json_decode( $wizard_json );
        foreach ($wizard_json['steps'] as $step) {
            $wizard->add_step( Mondula_Form_Wizard_Wizard_Step::from_aa( $step ) );
        }
        return serialize( $wizard );
    }

    public function save( $id, $data ) {
        $row = array();
        $row['title'] = $data['title'];
        $row['date'] = current_time( 'mysql' );
        $row['wizard'] = $this->from_json($data['wizard']);
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
