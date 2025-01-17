<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a text input field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Text extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;

	protected static $type = "fw-text";
	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required If true, Input for this field is required.
	 */
	public function __construct ($label, $required) {
		$this->_label = $label;
		$this->_required = $required;
	}

	public function render($ids) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-text" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<label for="msf-text-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><span class="msf-h3"><?php echo $this->_label ?></span></label>
				<input 
					type="text" 
					class="fw-text-input" 
					id="msf-text-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"
					data-id="text">
				<span class="fa fa-pencil form-control-feedback" aria-hidden="true"></span>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'text',
			'label' => $this->_label,
			'required' => $this->_required
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		return new Mondula_Form_Wizard_Block_Text($label, $required);
	}

	public static function addType($types) {

		$types['text'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Text',
			'title' => __('Text field', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Text::addType', 2);
