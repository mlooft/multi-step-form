<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-date
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Date extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_format;

	protected static $type = "fw-date";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required The If true, Input for this field is required.
	 * @param string $format The Format the Date will be shown in.
	 */
	public function __construct ( $label, $required, $format ) {
		$this->_label = $label;
		$this->_required = $required;
		if ( ! empty( $format ) ) {
			$this->_format = $format;
		} else {
			$this->_format = 'yy-mm-dd';
		}
	}
	/**
	 * Returns the '_required'-Status of the Object.
	 * @return boolean $_required If true, Input for this field is required.
	 */
	public function get_required( ) {
		return $this->_required;
	}

	public function render( $ids ) {
		$locale = substr( get_locale(), 0, 2) === 'de' ? 'de' : 'en'; // TODO this is possibly not a good idea
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-date" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<h3><?php echo $this->_label ?></h3><input type="text" class="fw-text-input fw-datepicker-here" data-id="date" data-language="<?php echo $locale; ?>" data-dateformat="<?php echo $this->_format ?>"><span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'date',
			'label' => $this->_label,
			'required' => $this->_required,
			'format' => $this->_format
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$label = $aa['label'];
		$required = $aa['required'];
		$format = $aa['format'];
		return new Mondula_Form_Wizard_Block_Date( $label, $required, $format );
	}
}
