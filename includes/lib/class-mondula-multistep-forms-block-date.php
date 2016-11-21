<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-date
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Date extends Mondula_Form_Wizard_Block {

    private $_label;
    private $_required;

    protected static $type = "fw-date";

    public function __construct ( $label, $required ) {
        $this->_label = $label;
        $this->_required = $required;
    }

    public function get_required( ) {
        return $this->_required;
    }

    public function render( $ids ) {
        $locale = substr( get_locale(), 0, 2) === 'de' ? 'de' : 'en'; // TODO this is possibly not a good idea
        ?>
        <div class="fw-input-container">
            <h3><?php echo $this->_label ?></h3>
            <input type="text"
                class="fw-text-input datepicker-here"
                data-id="date"
                data-language="<?php echo $locale; ?>">
            <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="fw-clearfix"></div>
        <?php
    }

    public function as_aa() {
        return array(
            'type' => 'date',
            'label' => $this->_label,
            'required' => $this->_required
        );
    }

    public static function from_aa( $aa ) {
        $label = $aa['label'];
        $required = $aa['required'];
        return new Mondula_Form_Wizard_Block_Date( $label, $required );
    }
}
