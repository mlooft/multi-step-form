<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a variable get field.
 */
class Mondula_Form_Wizard_Block_Get_Variable extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_get_param;

	protected static $type = "fw-get-variable";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param string $get_param The GET Parameter to read.
	 */
	public function __construct ($label, $get_param) {
		$this->_label = $label;
		$this->_get_param = $get_param;
	}

	public function render($ids) {
		?>
		<div 
			class="fw-step-block" 
			data-blockId="<?php echo $ids[0]; ?>" 
			data-type="fw-get-variable" 
			data-label="<?php echo $this->_label ?>" 
			data-param="<?php echo $this->_get_param; ?>"
		>
			<input 
				type="hidden" 
				class="fw-text-input" 
				id="msf-text-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"
				data-id="text">
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'get-variable',
			'label' => $this->_label,
			'get_param' => $this->_get_param,
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$get_param = $aa['get_param'];
		return new Mondula_Form_Wizard_Block_Get_Variable($label, $get_param);
	}

	public static function addType($types) {

		$types['get-variable'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Get_Variable',
			'title' => __('Get Variable', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Get_Variable::addType', 20);
