<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-block-radio
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Select extends Mondula_Form_Wizard_Block {

    private $_elements;
    private $_required;
    private $_search;
    private $_label;

    protected static $type = "fw-select";

    public function __construct ( $elements, $required, $label, $search ) {
        $this->_elements = $elements;
        $this->_required = $required;
        $this->_label = $label;
        $this->_search = $search;
    }

    public function get_required( ) {
      return $this->_required;
    }

    public function render( $ids ) {
        $cnt = count( $this->_elements );
        $group = $this->generate_id( $ids );
        ?>
          <h3><?php echo $this->_label ?></h3>
          <select data-search="<?php echo $this->_search?>">
            <?php for ( $i = 0; $i < $cnt; $i++ ) {
                $element = $this->_elements[$i];
            ?>
            <option id="<?php echo $group.'-'.$i ?>" type="select" name="<?php echo $group; ?>"><?php echo $element; ?></option>
            <?php } ?>
          </select>
          <?php
    }

    public function render_mail ( $data ) {
        echo "render_mail (radio)" . PHP_EOL;
        foreach ( $data as $key => $value ) {
            echo $this->_header  . " : " . $this->_opts[$key] . PHP_EOL;
        }
    }

    public function as_aa() {
        return array(
            'type' => 'select',
            'elements' => $this->_elements,
            'required' => $this->_required,
            'label' => $this->_label,
            'search' => $this->_search
        );
    }

    public static function from_aa( $aa ) {
        $elements = isset( $aa['elements'] ) ? $aa['elements'] : array();
        $required = $aa['required'];
        $label = $aa['label'];
        $search = $aa['search'];
        return new Mondula_Form_Wizard_Block_Select( $elements, $required, $label, $search);
    }
}
