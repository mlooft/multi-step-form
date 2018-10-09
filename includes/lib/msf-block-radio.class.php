<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-radio
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Radio extends Mondula_Form_Wizard_Block {

	private $_elements;
	private $_required;
	private $_multichoice;

	protected static $type = "fw-radio";

	public function __construct ( $elements, $required, $multichoice ) {
		$this->_elements = $elements;
		$this->_required = $required;
		$this->_multichoice = $multichoice;
	}

	public function get_required( ) {
	  return $this->_required;
	}

	public function render( $ids ) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-radio" data-required="<?php echo $this->_required; ?>">
			<?php
			$cnt = count( $this->_elements );
			$group = $this->generate_id( $ids );
			for ( $i = 0; $i < $cnt; $i++ ) {
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

	public function render_mail ( $data ) {
		echo "render_mail (radio)" . PHP_EOL;
		foreach ( $data as $key => $value ) {
			echo $this->_header  . " : " . $this->_opts[$key] . PHP_EOL;
		}
	}

	public function as_aa() {
		return array(
			'type' => 'radio',
			'elements' => $this->_elements,
			'required' => $this->_required,
			'multichoice' => $this->_multichoice
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$elements = isset( $aa['elements'] ) ? $aa['elements'] : array();
		$required = $aa['required'];
		$multichoice = $aa['multichoice'];
		return new Mondula_Form_Wizard_Block_Radio( $elements, $required, $multichoice );
	}
}
