<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of a paragraph output field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Paragraph extends Mondula_Form_Wizard_Block {

	private $_text;

	protected static $type = "fw-paragraph";

	/**
	 * Creates an Object of this Class.
	 * @param string $text Content of the Paragraph.
	 */
	public function __construct ( $text ) {
		$this->_text = $text;
	}

	/**
	 * Returns the '_required'-Status of the Object.
	 * @return boolean $_required If true, Input for this field is required.
	 */
	public function get_required( ) {
		return false;
	}

	public function render( $ids ) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-paragraph">
			<div class="fw-paragraph-container">
				<p><?php echo $GLOBALS['wp_embed']->run_shortcode($this->_text); ?></p>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'paragraph',
			'text' => $this->_text
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$text = $aa['text'];
		return new Mondula_Form_Wizard_Block_Paragraph( $text );
	}

	public static function sanitize_admin( $block ) {
		$allowedTags = wp_kses_allowed_html('post');
		unset($allowedTags['textarea']);

		$block['text'] = wp_kses($block['text'], $allowedTags);

		return $block;
	}

	public static function addType($types) {

		$types['paragraph'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Paragraph',
			'title' => __('Paragraph', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Paragraph::addType', 8);
