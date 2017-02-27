<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of class-mondula-multistep-forms-wizard-step
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Step_Part {

    private $_title;

    protected $_blocks;

    public function __construct ( $title, $blocks ) {
        $this->_title = $title;
        $this->_blocks = $blocks;
    }

    public function same_title ( Mondula_Form_Wizard_Wizard_Step_Part $that ) {
        return $this->_title === $that->_title;
    }

    public function render_title () {
        echo $this->_title;
    }

    public function render_body ( $wizardId, $stepId, $partId ) {
        $cnt = count($this->_blocks);
        $ids = array( $wizardId, $stepId, $partId );

        for ( $i = 0; $i < $cnt; $i++ ) {
            $block = $this->_blocks[$i];
            ?>
            <div class="fw-step-block" data-blockId="<?php echo $i ?>" data-type="<?php echo $block->get_type(); ?>" data-required="<?php echo $block->get_required() ?>">
              <?php
              array_push( $ids, $i );
              $block->render( $ids );
              array_pop( $ids );
              ?>
            </div>
            <?php
        }
    }

    public function render_mail ( $data ) {
        echo $this->_title . PHP_EOL;
        foreach ( $data as $key => $value ) {
            $this->_blocks[$key]->render_mail( $value );
        }
    }

    public function as_aa() {
        $blocks_aa = array();
        foreach ( $this->_blocks as $block ) {
            $blocks_aa[] = $block->as_aa();
        }
        return array(
            'title' => $this->_title,
            'blocks' => $blocks_aa
        );
    }

    public static function from_aa( $aa , $current_version, $serialized_version ) {
        $title = isset( $aa['title'] ) ? $aa['title'] : '';
        $blocks = array();

        if ( isset( $aa['blocks'] ) ) {
            foreach( $aa['blocks'] as $block ) {
                // echo 'block' . PHP_EOL;
                // var_dump( $block );
                switch ( $block['type'] ) {
                    case 'radio':
                        $blocks[] = Mondula_Form_Wizard_Block_Radio::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'select':
                        $blocks[] = Mondula_Form_Wizard_Block_Select::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'checkbox':
                        $blocks[] = Mondula_Form_Wizard_Block_Checkbox::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'text':
                        $blocks[] = Mondula_Form_Wizard_Block_Text::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'textarea':
                        $blocks[] = Mondula_Form_Wizard_Block_Textarea::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'email':
                        $blocks[] = Mondula_Form_Wizard_Block_Email::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'file':
                        $blocks[] = Mondula_Form_Wizard_Block_File::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'date':
                        $blocks[] = Mondula_Form_Wizard_Block_Date::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'paragraph':
                        $blocks[] = Mondula_Form_Wizard_Block_Paragraph::from_aa( $block, $current_version, $serialized_version );
                        break;
                    case 'submit':
                        $blocks[] = Mondula_Form_Wizard_Step_Submit::from_aa( $block, $current_version, $serialized_version );
                        break;
                    default:
                        break;
                }

            }
        }

        return new Mondula_Form_Wizard_Wizard_Step_Part( $title, $blocks );
    }
}
