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
	    $labelId = 'msf-select-label-' . str_replace(' ', '-', strtolower($this->_label));
	    $selectId = 'msf-select-' . str_replace(' ', '-', strtolower($this->_label));
	    ?>
	   <div class="fw-step-block"
	         data-blockId="<?php echo htmlspecialchars($ids[0]); ?>"
	         data-type="fw-select"
	         data-required="<?php echo htmlspecialchars($this->_required); ?>">
	
	        <label id="<?php echo $labelId; ?>">
			<span class="msf-h3"><?php echo htmlspecialchars($this->_label); ?></span> 
	        </label>
	
	        <select class="fw-select"
	                id="<?php echo $selectId; ?>"
	                name="<?php echo htmlspecialchars($group); ?>"
	                <?php if ($this->_required) echo 'required'; ?>
	                data-search="<?php echo htmlspecialchars($this->_search); ?>"
	                data-placeholder="<?php echo htmlspecialchars($this->_placeholder); ?>">
	
	            <option value="">
	                <?php echo htmlspecialchars($this->_placeholder); ?>
	            </option>
	            <?php for ($i = 0; $i < $cnt; $i++) {
	                $element = $this->_elements[$i];
	                $optionId = $group . '-' . $i;
	            ?>
	            <option id="<?php echo htmlspecialchars($optionId); ?>" value="<?php echo htmlspecialchars($element); ?>">
	                <?php echo htmlspecialchars($element); ?>
	            </option>
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
