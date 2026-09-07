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

	/**
	 * The paragraph text is HTML (bold, links and line breaks from the editor; exports contain
	 * it decoded), so it must not go through sanitize_text_field(), which strips all tags and
	 * collapses line breaks. Dangerous markup is removed by wp_kses(); render() filters the
	 * text again on output.
	 */
	public static function sanitize_admin($block) {
		$text = isset($block['text']) ? $block['text'] : '';
		$block = parent::sanitize_admin($block);

		$allowed_tags = wp_kses_allowed_html('post');
		unset($allowed_tags['textarea']);
		$block['text'] = wp_kses($text, $allowed_tags);

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
