<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-form-wizard-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Step_Submit extends Mondula_Form_Wizard_Block {

    protected static $type = "fw-submit";

    public function render( $ids ) {
        ?>
<div id="wizard-summary" class="fw-wizard-summary"></div>
<div class="fw-one_half">
    <div class="fw-input-container fw-first">
        <label>Name</label><input type="text" class="fw-text-input" data-id="name" data-required >
    </div>
</div>
<div class="fw-one_half">
    <div class="fw-input-container">
        <label>Email</label><input type="text" class="fw-text-input" data-id="email" data-required >
    </div>
</div>
<div class="fw-clearfix"></div>
<button type="button" class="fw-btn-submit">Absenden</button>
        <?php
    }

    public function render_mail ( $data ) {
        echo "Name: $data[name]" . PHP_EOL;
        echo "Email: $data[email]" . PHP_EOL;
    }

    public function as_aa() {
        return array(
            'type' => 'submit'
        );
    }
}
