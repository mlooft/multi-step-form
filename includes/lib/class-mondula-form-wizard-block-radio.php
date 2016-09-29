<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-form-wizard-block-radio
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Radio extends Mondula_Form_Wizard_Block {

    private $_elements;
    private $_required;

    protected static $type = "fw-radio";

    public function __construct ( $elements, $required ) {
        $this->_elements = $elements;
        $this->_required = $required;
    }

    public function get_required( ) {
      return $this->_required;
    }

    public function render( $ids ) {
        $cnt = count( $this->_elements );
        $group = $this->generate_id( $ids );
        for ( $i = 0; $i < $cnt; $i++ ) {
            $element = $this->_elements[$i];
            if ($element['type'] === 'option') {
                ?>
                <span class="fw-radio-row">
                    <input id="<?php echo $group.'-'.$i ?>" type="radio" name="<?php echo $group; ?>" class="fw-radio" data-id="<?php echo $i; ?>">
                    <label for="<?php echo $group.'-'.$i; ?>" data-labelId="<?php echo $i ?>"><?php echo $element['value']; ?></label>
                </span>
                <?php
            } else if ($element['type'] === 'header') {
                ?>
                <p class="fw-block-header"><?php echo $element['value']; ?></p>
                <?php
            }
        }
    }

    public function render_mail ( $data ) {
        echo "render_mail (radio)" . PHP_EOL;
        foreach ( $data as $key => $value ) {
            echo $this->_header  . " : " . $this->_opts[$key] . PHP_EOL;
        }
    }

    public function as_aa() {
        return array(
            'type' => 'radio',
            'elements' => $this->_elements,
            'required' => $this->_required
        );
    }

    public static function from_aa( $aa ) {
        $elements = isset( $aa['elements'] ) ? $aa['elements'] : array();
        $required = $aa['required'];
        return new Mondula_Form_Wizard_Block_Radio( $elements, $required );
    }
}
