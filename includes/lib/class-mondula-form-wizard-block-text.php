<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-form-wizard-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Text extends Mondula_Form_Wizard_Block {

    private $_label;

    protected static $type = "fw-text";

    public function __construct ( $label ) {
        $this->_label = $label;
    }

    // TODO: add text field label variable and ids
    public function render( $ids ) {
        ?>
    <div class="fw-input-container">
        <label><?php echo $this->_label ?></label><input type="text" class="fw-text-input" data-id="text" data-required >
    </div>
    <div class="fw-clearfix"></div>
        <?php
    }

    // TODO fw-text render_mail
    public function render_mail ( $data ) {
        echo "Name: $data[name]" . PHP_EOL;
        echo "Email: $data[email]" . PHP_EOL;
    }

    public function as_aa() {
        return array(
            'type' => 'text',
            'label' => $this->_label
        );
    }

    public static function from_aa( $aa ) {
        $label = $aa['label'];
        return new Mondula_Form_Wizard_Block_Text( $label );
    }
}
