<?php
/**
 * Base class for all input blocks.
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

	private static $block_types = null;

	public static function get_block_types() {
		if (self::$block_types === null) {
			self::$block_types = array(
				'radio' => 'Mondula_Form_Wizard_Block_Radio::from_aa',
				'select' => 'Mondula_Form_Wizard_Block_Select::from_aa',
				'text' => 'Mondula_Form_Wizard_Block_Text::from_aa',
				'textarea' => 'Mondula_Form_Wizard_Block_Textarea::from_aa',
				'email' => 'Mondula_Form_Wizard_Block_Email::from_aa',
				'numeric' => 'Mondula_Form_Wizard_Block_Numeric::from_aa',
				'file' => 'Mondula_Form_Wizard_Block_File::from_aa',
				'date' => 'Mondula_Form_Wizard_Block_Date::from_aa',
				'paragraph' => 'Mondula_Form_Wizard_Block_Paragraph::from_aa',
			);
			self::$block_types = apply_filters('multi-step-form/block-types', self::$block_types);
		}

		return self::$block_types;
	}

	public static function from_aa( $block, $current_version, $serialized_version ) {
		$block_type_arr = self::get_block_types();

		if (array_key_exists($block['type'], $block_type_arr)) {
			return call_user_func($block_type_arr[$block['type']], $block, $current_version, $serialized_version);
		}

		return null;
	}
}
