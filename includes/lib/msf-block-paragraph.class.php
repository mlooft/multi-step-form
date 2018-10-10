<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-date
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Paragraph extends Mondula_Form_Wizard_Block {

	private $_text;

	protected static $type = "fw-paragraph";

	/**
	 * Creates an Object of this Class.
	 * @param string $text Content of the Paragraph.
	 */
	public function __construct ( $text ) {
		$this->_text = $text;
	}

	/**
	 * Returns the '_required'-Status of the Object.
	 * @return boolean $_required If true, Input for this field is required.
	 */
	public function get_required( ) {
		return false;
	}

	public function render( $ids ) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-paragraph">
			<div class="fw-paragraph-container">
				<p><?php echo $this->_text; ?></p>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'paragraph',
			'text' => $this->_text
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$text = $aa['text'];
		return new Mondula_Form_Wizard_Block_Paragraph( $text );
	}
}
