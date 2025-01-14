<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a numeric input field.
 */
class Mondula_Form_Wizard_Block_Numeric extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_minimum;
	private $_maximum;

	protected static $type = "fw-numeric";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required If true, Input for this field is required.
	 * @param integer $minimum Lower Threshold of Numeric Input.
	 * @param integer $maximum Upper Threshold of Numeric Input.
	 */
	public function __construct ($label, $required, $minimum, $maximum) {
		$this->_label = $label;
		$this->_required = $required;
		$this->_minimum = $minimum;
		$this->_maximum = $maximum;
	}

	public function render($ids) {
		$minimumDefined = strlen($this->_minimum) > 0;
		$maximumDefined = strlen($this->_maximum) > 0;
	?>
		<div 
			class="fw-step-block" 
			data-blockId="<?php echo $ids[0]; ?>" 
			data-type="fw-numeric" 
			data-required="<?php echo $this->_required; ?>" 
			<?php if ($minimumDefined) { echo 'data-min="' . $this->_minimum . '"'; } ?>
			<?php if ($maximumDefined) { echo 'data-max="' . $this->_maximum . '"'; } ?>
		>

			<div class="fw-input-container">
				<label for="msf-numeric-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><span class="h3"><?php echo $this->_label ?></span></label>
				<input 
					type="text" 
					class="fw-text-input" 
					data-id="numeric"
					id="msf-numeric-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"
					placeholder="<?php 
						if ($minimumDefined && $maximumDefined) { 
							echo $this->_minimum . ' - ' . $this->_maximum;
						} else if ($minimumDefined) {
							echo __("min. ", 'multi-step-form') . $this->_minimum;
						} else if ($maximumDefined) {
							echo __("max. ", 'multi-step-form') . $this->_maximum;
						} else {
							echo "";
						}
					?>"
					<?php if ($minimumDefined) { echo 'min="' . $this->_minimum . '"'; } ?>
					<?php if ($maximumDefined) { echo 'max="' . $this->_maximum . '"'; } ?>
				>
				<span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
			</div>
			<div class="fw-clearfix"></div>
		</div>
	  <?php
	}

	public function as_aa() {
		return array(
			'type' => 'numeric',
			'label' => $this->_label,
			'required' => $this->_required,
			'minimum' => $this->_minimum,
			'maximum' => $this->_maximum
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		$minimum = $aa['minimum'];
		$maximum = $aa['maximum'];

		if (!ctype_digit(ltrim($minimum, '-'))) {
			$minimum = '';
		}

		if (!ctype_digit(ltrim($maximum, '-'))) {
			$maximum = '';
		}

		if (strlen($minimum) > 0 && strlen($maximum) > 0) {
			if (intval($minimum) >= intval($maximum)) {
				$maximum = strval(intval($minimum) + 1);
			}
		}

		return new Mondula_Form_Wizard_Block_Numeric($label, $required, $minimum, $maximum);
	}

	public static function addType($types) {

		$types['numeric'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Numeric',
			'title' => __('Numeric', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Numeric::addType', 5);
