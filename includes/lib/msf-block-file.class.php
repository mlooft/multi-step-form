<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_File extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_multi;

	protected static $type = "fw-file";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required If true, Input for this field is required.
	 * @param boolean $multi If true, multiple files can be uploaded.
	 */
	public function __construct ( $label, $required, $multi ) {
		$this->_label = $label;
		$this->_required = $required;
		$this->_multi = $multi;
	}

	/**
	 * Returns the '_required'-Status of the Object.
	 * @return boolean $_required If true, Input for this field is required.
	 */
	public function get_required( ) {
	  return $this->_required;
	}

	public function render( $ids ) {
	  $group = $this->generate_id( $ids );
	  ?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-file" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<h3><?php echo $this->_label ?></h3>
				<input type="file" name="<?php echo $group; ?>" id="<?php echo $group ?>" class="fw-file-upload-input" <?php echo $this->_multi === 'true' ? 'multiple' : ''; ?>>
				<label class="fw-btn fw-button-fileupload" for="<?php echo $group ?>"><i class="fa fa-upload fw-file-upload-status" aria-hidden="true"></i><span><?php _e('Choose a file', 'multi-step-form') ?></span></label>
			</div>
			<div class="fw-clearfix"></div>
		</div>
	  <?php
	}

	public function as_aa() {
		return array(
			'type' => 'file',
			'label' => $this->_label,
			'required' => $this->_required,
			'multi' => $this->_multi,
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$label = $aa['label'];
		$required = $aa['required'];
		$multi = isset( $aa['multi'] ) ? $aa['multi'] : false;
		return new Mondula_Form_Wizard_Block_File( $label, $required, $multi );
	}
}
