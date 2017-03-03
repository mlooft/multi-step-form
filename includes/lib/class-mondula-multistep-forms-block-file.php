<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-submit
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_File extends Mondula_Form_Wizard_Block {

    private $_label;
    private $_required;

    protected static $type = "fw-file";

    public function __construct ( $label, $required ) {
        $this->_label = $label;
        $this->_required = $required;
    }

    public function get_required( ) {
      return $this->_required;
    }

    public function render( $ids ) {
      ?>
      <div class="fw-input-container">
        <h3><?php echo $this->_label ?></h3>
        <input type="file" class="fw-file" data-id="file">
        <div class="fw-file-upload-response">
          <div class="fw-file-upload-status-uploading">
            <i class="fa fa-spinner" aria-hidden="true"></i>
            <span>Uploading file</span>
          </div>
          <div class="fw-file-upload-status-success">
            <i class="fa fa-check-circle fw-file-uploaded" aria-hidden="true"></i>
            <span>Upload successful</span>
          </div>
          <div class="fw-file-upload-status-error">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
            <span>Upload error</span>
          </div>
        </div>
      </div>
      <div class="fw-clearfix"></div>
      <?php
    }

    public function as_aa() {
        return array(
            'type' => 'file',
            'label' => $this->_label,
            'required' => $this->_required
        );
    }

    public static function from_aa( $aa , $current_version, $serialized_version ) {
        $label = $aa['label'];
        $required = $aa['required'];
        return new Mondula_Form_Wizard_Block_File( $label, $required );
    }
}
