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
	private $_confirm;

	protected static $type = "fw-email";
	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required The If true, Input for this field is required.
	 * @param boolean $confirm true, if email needs to be confirmed
	 */
	public function __construct ($label, $required, $confirm) {
		$this->_label = $label;
		$this->_required = $required;
		$this->_confirm = $confirm;
	}

	public function render($ids) {
	  ?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-email" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<label for="msf-mail-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><span class="h3"><?php echo $this->_label ?></span></label>
				<input type="text" id="msf-mail-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"  class="fw-text-input one" data-id="email">
				<span class="fa fa-envelope form-control-feedback" aria-hidden="true"></span>
			</div>
			<?php if ($this->_confirm == 'true') { ?>
				<div class="fw-input-container">
					<label for="msf-mail-confirm-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><span class="h3"><?php echo __("Repeat to confirm", 'multi-step-form') ?></span></label>
					<input type="text" id="msf-mail-confirm-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"  class="fw-text-input two" data-id="email">
					<span class="fa fa-envelope form-control-feedback" aria-hidden="true"></span>
				</div>
			<?php } ?>
			<div class="fw-clearfix"></div>
		</div>
	  <?php
	}

	public function as_aa() {
		return array(
			'type' => 'email',
			'label' => $this->_label,
			'required' => $this->_required,
			'confirm' => $this->_confirm
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		$confirm = $aa['confirm'];
		return new Mondula_Form_Wizard_Block_Email($label, $required, $confirm);
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
