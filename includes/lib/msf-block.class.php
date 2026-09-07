<?php

/**
 * Base class for all input blocks.
 */
abstract class Mondula_Form_Wizard_Block {

	public function generate_id($ids) {
		$result = 'fw';
		foreach ($ids as $id) {
			$result .= '-' . $id;
		}
		return $result;
	}

	abstract function render($ids);

	private static $block_types = null;

	public static function get_block_types() {
		if (self::$block_types === null) {
			self::$block_types = apply_filters('multi-step-form/block-types', array());
		}

		return self::$block_types;
	}

	public static function from_aa($block, $current_version, $serialized_version) {
		$block_type_arr = self::get_block_types();

		if (array_key_exists($block['type'], $block_type_arr)) {
			return call_user_func($block_type_arr[$block['type']]['class'] . '::from_aa', $block, $current_version, $serialized_version);
		}

		return null;
	}

	/**
	 * Generic fallback sanitization: every scalar value is run through sanitize_text_field().
	 * Nested arrays (e.g. the elements of a block type without its own sanitize_admin()) are
	 * sanitized recursively instead of being dropped.
	 */
	public static function sanitize_admin($block) {
		foreach ($block as &$value) {
			if (is_array($value)) {
				$value = self::sanitize_admin($value);
			} else {
				$value = sanitize_text_field($value);
			}
		}

		return $block;
	}
}
