<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-numeric
 *
 * @author nico, marten
 */
class Mondula_Form_Wizard_Block_Numeric extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_minimum;
	private $_maximum;

	protected static $type = "fw-numeric";

	public function __construct ($label, $required, $minimum, $maximum) {
		$this->_label = $label;
		$this->_required = $required;
		$this->_minimum = $minimum;
		$this->_maximum = $maximum;
	}

	public function get_required() {
	  return $this->_required;
	}

	public function render( $ids ) {
	  ?>
		<div 
			class="fw-step-block" 
			data-blockId="<?php echo $ids[0]; ?>" 
			data-type="fw-numeric" 
			data-required="<?php echo $this->_required; ?>" 
			data-minimum="<?php echo $this->_minimum; ?>"
			data-maximum="<?php echo $this->_maximum; ?>" >

			<div class="fw-input-container">
				<h3><?php echo $this->_label ?></h3>
				<input 
					type="number" 
					class="fw-text-input" 
					data-id="numeric" 
					min="<?php echo $this->_minimum; ?>" 
					max="<?php echo $this->_maximum; ?>">
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
		return new Mondula_Form_Wizard_Block_Numeric($label, $required, $minimum, $maximum);
	}
}
