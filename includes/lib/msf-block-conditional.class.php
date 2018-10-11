<?php

/**
 * Description of class-mondula-multistep-forms-step-conditional
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Conditional extends Mondula_Form_Wizard_Block {

	private $_options;

	private $_elements;

	private static $_type = "fw-conditional";

	/**
	 * Creates an Object of this Class.
	 * @param $options
	 * @param $elements
	 */
	public function __construct ( $options, $elements ) {
		$this->_options = $options;
		$this->_elements = $elements;
	}

	public function render( $ids ) {
		$cnt = count( $this->_options );
		$group = $this->generate_id( $ids );
		?>
<div class="fw-conditional">
	<div class="fw-conditional-if">
		<?php
		for ( $i = 0; $i < $cnt; $i++ ) {
			?>
	<label data-labelId="<?php echo $i ?>"><?php echo $this->_options[$i]; ?></label><input type="radio" name="<?php echo $group; ?>" class="fw-radio-conditional" data-id="<?php echo $i; ?>">
			<?php
		}
		?>
	</div>
	<div class="fw-conditional-then-container">
		<?php
		for ( $i = 0; $i < $cnt; $i++ ) {
			$eltcnt = count( $this->_elements[$i] );
			?>
		<div class="fw-conditional-then" data-id="<?php echo $i; ?>">
			<?php
			array_push( $ids, $i);
			for ( $j = 0; $j < $eltcnt; $j++ ) {
				$this->_elements[$i][$j]->render( $ids );
			}
			array_pop( $ids );
			?>
		</div>
			<?php
		}
		?>
	</div>
</div>
		<?php
	}
}
