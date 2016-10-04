<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Step_Submit extends Mondula_Form_Wizard_Block {

    protected static $type = "fw-submit";
    private $_namerequired;
    private $_mailrequired;

    public function __construct ( $namerequired, $mailrequired ) {
        $this->_namerequired = $namerequired;
        $this->_mailrequired = $mailrequired;
    }

    public function get_required( ) {
      return "true";
    }

    public function render( $ids ) {
        ?>
        <?php if ($this->_namerequired) { ?>
          <?php if ($this->_mailrequired) { ?>
            <div class="fw-one_half">
          <?php } ?>
              <div class="fw-input-container fw-first">
                  <label>Name</label><input type="text" class="fw-text-input" data-id="name" data-required="true" >
              </div>
          <?php if ($this->_mailrequired) { ?>
            </div>
          <?php } ?>
        <?php } ?>
        <?php if ($this->_mailrequired) { ?>
          <?php if ($this->_namerequired) { ?>
            <div class="fw-one_half">
          <?php } ?>
              <div class="fw-input-container">
                  <label>Email</label><input type="text" class="fw-text-input" data-id="email" data-required="true" >
              </div>
          <?php if ($this->_namerequired) { ?>
            </div>
          <?php } ?>
        <?php } ?>
        <div class="fw-clearfix"></div>
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
        echo "Email: $data[email]" . PHP_EOL;
    }

    public function as_aa() {
        return array(
            'type' => 'submit',
            'namerequired' => filter_var($this->_namerequired, FILTER_VALIDATE_BOOLEAN),
            'mailrequired' => filter_var($this->_mailrequired, FILTER_VALIDATE_BOOLEAN)
        );
    }

    public static function from_aa( $aa ) {
        $namerequired = filter_var($aa['requirename'], FILTER_VALIDATE_BOOLEAN);
        $mailrequired = filter_var($aa['requiremail'], FILTER_VALIDATE_BOOLEAN);
        return new Mondula_Form_Wizard_Step_Submit( $namerequired, $mailrequired );
    }
}
