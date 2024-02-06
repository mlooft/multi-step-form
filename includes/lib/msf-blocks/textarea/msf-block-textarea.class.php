<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a textarea input field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Textarea extends Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_description = '';

	protected static $type = "fw-textarea";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required If true, Input for this field is required.
	 */
	public function __construct ($label, $required, $description = '') {
		$this->_label = $label;
		$this->_required = $required;
		$this->_description = $description;
	}

	public function render($ids) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-textarea" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<label for="msf-textarea-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"><h3><?php echo $this->_label ?></h3></label>
				<?php if ($this->_description !== '') : ?>
					<span class="msf-textarea-description"><?php echo sanitize_text_field($this->_description); ?></span>
				<?php endif; ?>
				<textarea 
					class="fw-textarea"
					id="msf-textarea-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"
					data-id="textarea"></textarea>
				<span class="fa fa-pencil t-area form-control-feedback" aria-hidden="true"></span>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'textarea',
			'label' => $this->_label,
			'required' => $this->_required,
			'description' => $this->_description,
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		$description = $aa['description'];
		return new Mondula_Form_Wizard_Block_Textarea($label, $required, $description);
	}

	public static function addType($types) {

		$types['textarea'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Textarea',
			'title' => __('Textarea', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Textarea::addType', 3);
