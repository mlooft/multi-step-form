<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a select input field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Select extends Mondula_Form_Wizard_Block {

	private $_elements;
	private $_required;
	private $_search;
	private $_label;
	private $_placeholder;

	protected static $type = "fw-select";

	/**
	 * Creates an Object of this Class.
	 * @param array $elements Array of the Input-Options.
	 * @param boolean $required If true, Input for this field is required. 
	 * @param string $label The Label the Object is being created with.
	 * @param string $placeholder Placeholder-Value before anything has been selected.
	 * @param boolean $search If true, enables the Option to search.
	 */
	public function __construct ($elements, $required, $label, $placeholder, $search) {
		$this->_elements = $elements;
		$this->_required = $required;
		$this->_label = $label;
		$this->_placeholder = $placeholder;
		$this->_search = $search;
	}

	public function render($ids) {
		$cnt = count($this->_elements);
		$group = $this->generate_id($ids);
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-select" data-required="<?php echo $this->_required; ?>">	
			<label for="msf-select-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><h3><?php echo $this->_label ?></h3></label>
			<select class="fw-select" 
				data-search="<?php echo $this->_search?>" 
				data-placeholder="<?php echo $this->_placeholder?>" 
				data-required="<?php echo $this->_required; ?>"
				id="msf-select-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>">
				<option></option>
				<?php for ($i = 0; $i < $cnt; $i++) {
					$element = $this->_elements[$i];
				?>
				<option id="<?php echo $group.'-'.$i ?>" type="select" name="<?php echo $group; ?>"><?php echo $element; ?></option>
				<?php } ?>
			</select>
		</div>
		  <?php
	}

	public function as_aa() {
		return array(
			'type' => 'select',
			'elements' => $this->_elements,
			'required' => $this->_required,
			'label' => $this->_label,
			'placeholder' => $this->_placeholder,
			'search' => $this->_search
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$elements = isset($aa['elements']) ? $aa['elements'] : array();
		$required = $aa['required'];
		$label = $aa['label'];
		$search = $aa['search'];
		$placeholder = $aa['placeholder'];
		return new Mondula_Form_Wizard_Block_Select($elements, $required, $label, $placeholder, $search);
	}

	public static function sanitize_admin($block) {
		$block['required'] = sanitize_text_field($block['required']);
		$block['search'] = sanitize_text_field($block['search']);
		$block['label'] = sanitize_text_field($block['label']);
		$block['placeholder'] = sanitize_text_field($block['placeholder']);
		foreach ($block['elements'] as &$element) {
			$element = sanitize_text_field($element);
		}

		return $block;
	}

	public static function addType($types) {

		$types['select'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Select',
			'title' => __('Select/Dropdown', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Select::addType', 1);
