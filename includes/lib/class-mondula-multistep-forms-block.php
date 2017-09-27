<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-mondula-multistep-forms-block
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

	public function get_type() {
		return static::$type;
	}

	public static function from_aa( $block, $current_version, $serialized_version ) {
		switch ( $block['type'] ) {
			case 'radio':
				return Mondula_Form_Wizard_Block_Radio::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'select':
				return Mondula_Form_Wizard_Block_Select::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'checkbox':
				return Mondula_Form_Wizard_Block_Checkbox::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'text':
				return Mondula_Form_Wizard_Block_Text::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'textarea':
				return Mondula_Form_Wizard_Block_Textarea::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'email':
				return Mondula_Form_Wizard_Block_Email::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'file':
				return Mondula_Form_Wizard_Block_File::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'date':
				return Mondula_Form_Wizard_Block_Date::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'paragraph':
				return Mondula_Form_Wizard_Block_Paragraph::from_aa( $block, $current_version, $serialized_version );
				break;
			case 'registration':
				if ( class_exists( 'Multi_Step_Form_Plus' ) ) {
					return Multi_Step_Form_Plus_Block_Registration::from_aa( $block, $current_version, $serialized_version );
				}
				break;
			case 'conditional':
				if ( class_exists( 'Multi_Step_Form_Plus' ) ) {
					return Multi_Step_Form_Plus_Block_Conditional::from_aa( $block, $current_version, $serialized_version );
				}
				break;
			default:
				break;
		}
		return null;
	}
}
