<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a radio/checkbox input field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Radio extends Mondula_Form_Wizard_Block {

	private $_elements;
	private $_required;
	private $_multichoice;

	protected static $type = "fw-radio";

	/**
	 * Creates an Object of this Class.
	 * @param array $elements Array of the Input-Options.
	 * @param boolean $required $required If true, Input for this field is required. 
	 * @param boolean $multichoice If true, turn Radio into Checkbox.
	 */
	public function __construct ($elements, $required, $multichoice) {
		$this->_elements = $elements;
		$this->_required = $required;
		$this->_multichoice = $multichoice;
	}

	public function render($ids) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-radio" data-required="<?php echo $this->_required; ?>">
			<?php
			$cnt = count($this->_elements);
			$group = $this->generate_id($ids);
			for ($i = 0; $i < $cnt; $i++) {
				$element = $this->_elements[$i];
				if ($element['type'] === 'option') {
					if ($this->_multichoice == 'true') {
					?>
					<div class="fw-choice fw-input-container" data-type="fw-checkbox">
						<input id="<?php echo $group.'-'.$i ?>" type="checkbox" class="fw-checkbox" name="<?php echo $group; ?>" data-id="<?php echo $i; ?>">
						<label for="<?php echo $group.'-'.$i; ?>" data-labelId="<?php echo $i ?>"><?php echo $element['value']; ?></label>
					</div>
					<?php
				} else {
					?>
					<span class="fw-choice fw-radio-row">
						<input id="<?php echo $group.'-'.$i ?>" type="radio" name="<?php echo $group; ?>" class="fw-radio" data-id="<?php echo $i; ?>">
						<label for="<?php echo $group.'-'.$i; ?>" data-labelId="<?php echo $i ?>"><?php echo $element['value']; ?></label>
					</span>
					<?php
				}
				} else if ($element['type'] === 'header') {
					?>
					<h3><?php echo $element['value']; ?></h3>
					<?php
				}
			}
		?>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'radio',
			'elements' => $this->_elements,
			'required' => $this->_required,
			'multichoice' => $this->_multichoice
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$elements = isset($aa['elements']) ? $aa['elements'] : array();
		$required = $aa['required'];
		$multichoice = $aa['multichoice'];
		return new Mondula_Form_Wizard_Block_Radio($elements, $required, $multichoice);
	}

	public static function sanitize_admin($block) {
		$allowedTags = wp_kses_allowed_html('post');
		unset($allowedTags['textarea']);
		
		foreach ($block['elements'] as &$element) {
			$element['type'] = sanitize_text_field($element['type']);
			$element['value'] = wp_kses($element['value'], $allowedTags);
		}
		$block['required'] = sanitize_text_field($block['required']);
		$block['multichoice'] = sanitize_text_field($block['multichoice']);

		return $block;
	}

	public static function addType($types) {

		$types['radio'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Radio',
			'title' => __('Radio/Checkbox', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Radio::addType', 0);
