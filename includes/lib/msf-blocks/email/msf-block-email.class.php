<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of an email input field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Email extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;

	protected static $type = "fw-email";
	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required The If true, Input for this field is required.
	 */
	public function __construct ($label, $required) {
		$this->_label = $label;
		$this->_required = $required;
	}

	public function render($ids) {
	  ?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-email" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<h3><?php echo $this->_label ?></h3><input type="text" id="msf-mail-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"  class="fw-text-input" data-id="email"><span class="fa fa-envelope form-control-feedback" aria-hidden="true"></span>
			</div>
			<div class="fw-clearfix"></div>
		</div>
	  <?php
	}

	public function as_aa() {
		return array(
			'type' => 'email',
			'label' => $this->_label,
			'required' => $this->_required
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		return new Mondula_Form_Wizard_Block_Email($label, $required);
	}

	public static function addType($types) {

		$types['email'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Email',
			'title' => __('Email', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Email::addType', 4);
