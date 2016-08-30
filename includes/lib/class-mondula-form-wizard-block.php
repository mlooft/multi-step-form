<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-mondula-form-wizard-block
 *
 * @author alex
 */
abstract class Mondula_Form_Wizard_Block {

    public function generate_id( $ids ) {
        $result = 'fw';
        foreach ( $ids as $id ) {
            $result .= '-' . $id;
        }
        return $result;
    }

    abstract function render( $ids );

    public function get_type( ) {
        return static::$type;
    }
}
