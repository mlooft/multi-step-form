<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-radio
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Select extends Mondula_Form_Wizard_Block {

	private $_elements;
	private $_required;
	private $_search;
	private $_label;
	private $_placeholder;

	protected static $type = "fw-select";

	/**
	 * Creates an Object of this Class.
	 * @param array $elements Array of the Input-Options.
	 * @param boolean $required If true, Input for this field is required. 
	 * @param string $label The Label the Object is being created with.
	 * @param string $placeholder Placeholder-Value before anything has been selected.
	 * @param boolean $search If true, enables the Option to search.
	 */
	public function __construct ( $elements, $required, $label, $placeholder, $search ) {
		$this->_elements = $elements;
		$this->_required = $required;
		$this->_label = $label;
		$this->_placeholder = $placeholder;
		$this->_search = $search;
	}

	/**
	 * Returns the '_required'-Status of the Object.
	 * @return boolean $_required If true, Input for this field is required.
	 */
	public function get_required( ) {
	  return $this->_required;
	}

	public function render( $ids ) {
		$cnt = count( $this->_elements );
		$group = $this->generate_id( $ids );
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-select" data-required="<?php echo $this->_required; ?>">	
			<h3><?php echo $this->_label ?></h3>
			<select class="fw-select" data-search="<?php echo $this->_search?>" data-placeholder="<?php echo $this->_placeholder?>" data-required="<?php echo $this->_required; ?>">
				<option></option>
				<?php for ( $i = 0; $i < $cnt; $i++ ) {
					$element = $this->_elements[$i];
				?>
				<option id="<?php echo $group.'-'.$i ?>" type="select" name="<?php echo $group; ?>"><?php echo $element; ?></option>
				<?php } ?>
			</select>
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
			'type' => 'select',
			'elements' => $this->_elements,
			'required' => $this->_required,
			'label' => $this->_label,
			'placeholder' => $this->_placeholder,
			'search' => $this->_search
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$elements = isset( $aa['elements'] ) ? $aa['elements'] : array();
		$required = $aa['required'];
		$label = $aa['label'];
		$search = $aa['search'];
		$placeholder = $aa['placeholder'];
		return new Mondula_Form_Wizard_Block_Select( $elements, $required, $label, $placeholder, $search);
	}
}
