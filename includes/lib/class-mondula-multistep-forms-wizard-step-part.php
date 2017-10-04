<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Description of class-mondula-multistep-forms-wizard-step
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Step_Part {

	private $_title;

	protected $_blocks;

	public function __construct( $title, $blocks ) {
		$this->_title = $title;
		$this->_blocks = $blocks;
	}

	public function same_title( Mondula_Form_Wizard_Wizard_Step_Part $that ) {
		return $this->_title === $that->_title;
	}

	public function render_title() {
		echo $this->_title;
	}

	public function render_body( $wizard_id, $step_id, $part_id ) {
		$cnt = count( $this->_blocks );
		$ids = array( $wizard_id, $step_id, $part_id );

		for ( $i = 0; $i < $cnt; $i++ ) {
			$block = $this->_blocks[ $i ];
			?>
				<?php
				array_push( $ids, $i );
				$block->render( $ids );
				array_pop( $ids );
				?>
			<?php
		}
	}

	public function render_mail( $data ) {
		echo $this->_title . PHP_EOL;
		foreach ( $data as $key => $value ) {
			$this->_blocks[ $key ]->render_mail( $value );
		}
	}

	public function as_aa() {
		$blocks_aa = array();
		foreach ( $this->_blocks as $block ) {
			if ( $block ) {
				$blocks_aa[] = $block->as_aa();
			}
		}
		return array(
			'title' => $this->_title,
			'blocks' => $blocks_aa,
		);
	}

	public static function from_aa( $aa, $current_version, $serialized_version ) {
		$title = isset( $aa['title'] ) ? $aa['title'] : '';
		$blocks = array();

		if ( isset( $aa['blocks'] ) ) {
			foreach ( $aa['blocks'] as $block ) {
				$blocks[] = Mondula_Form_Wizard_Block::from_aa( $block, $current_version, $serialized_version );
			}
		}

		return new Mondula_Form_Wizard_Wizard_Step_Part( $title, $blocks );
	}
}
