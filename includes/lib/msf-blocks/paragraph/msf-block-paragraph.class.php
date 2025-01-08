<?php

if (!defined('ABSPATH')) exit;

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
	public function __construct ($text) {
		$this->_text = $text;
	}

	public function render($ids) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-paragraph">
			<div class="fw-paragraph-container">
				<?php 
				$content = htmlspecialchars_decode($this->_text, ENT_QUOTES | ENT_HTML5);
				echo wp_kses_post($GLOBALS['wp_embed']->run_shortcode($content)); 
				?>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'paragraph',
			'text' => htmlspecialchars_decode($this->_text, ENT_QUOTES | ENT_HTML5)
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$text = $aa['text'];
		return new Mondula_Form_Wizard_Block_Paragraph($text);
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
