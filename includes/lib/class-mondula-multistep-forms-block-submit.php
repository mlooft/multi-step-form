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
        <svg style="display:none;" class="fw-spinner" width='40px' height='40px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke-dasharray="163.36281798666926 87.9645943005142" stroke="#cec9c9" fill="none" stroke-width="20"><animateTransform attributeName="transform" type="rotate" values="0 50 50;180 50 50;360 50 50;" keyTimes="0;0.5;1" dur="1s" repeatCount="indefinite" begin="0s"></animateTransform></circle></svg>
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

    public static function from_aa( $aa , $current_version, $serialized_version ) {
        return new Mondula_Form_Wizard_Step_Submit();
    }
}
