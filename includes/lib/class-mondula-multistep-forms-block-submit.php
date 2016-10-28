<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Step_Submit extends Mondula_Form_Wizard_Block {

    protected static $type = "fw-submit";

    public function get_required( ) {
      return "true";
    }

    public function render( $ids ) {
        ?>
        <button type="button" class="fw-toggle-summary">SHOW SUMMARY</button>
        <div id="wizard-summary" class="fw-wizard-summary" style="display:none;" data-showsummary="<?php echo Mondula_Form_Wizard_Wizard::fw_get_option('showsummary' ,'fw_settings_basic', 'true');?>"></div>
        <div class="fw-clearfix"></div>
        <button type="button" class="fw-btn-submit">Submit</button>
        <div class="fw-clearfix"></div>
        <div class="fw-submit-alert" style="display:none"></div>
        <?php
    }

    public function render_mail ( $data ) {
        echo "Name: $data[name]" . PHP_EOL;
    }

    public function as_aa() {
        return array(
            'type' => 'submit'
        );
    }

    public static function from_aa( $aa ) {
        return new Mondula_Form_Wizard_Step_Submit();
    }
}
