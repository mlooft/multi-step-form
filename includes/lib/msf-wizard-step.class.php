<?php

/**
 * Data model for a Step in a form.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Step {

	private $_title;

	private $_headline;

	private $_copy_text;

	private $_parts;

	public function __construct ( $title, $headline, $copy_text, $parts ) {
		$this->_title = $title;
		$this->_headline = $headline;
		$this->_copy_text = $copy_text;
		$this->_parts = $parts;
	}

	private function _get_class ( $len ) {
		// switch ( $len ) {
		//     case 3:
		//         return 'fw-one_third';
		//     case 2:
		//         return 'fw-one_half';
		//     default:
		//         return '';
		// }
		return '';
	}

	public function render( $wizardId, $stepId ) {
		$cnt = count( $this->_parts );
		$width = $this->_get_class( $cnt );
		$boxlayout = ( Mondula_Form_Wizard_Wizard::fw_get_option( 'boxlayout', 'fw_settings_styling', 'on' ) === 'on' ) ? '' : ' fw-plain-layout';
		for ($i = 0; $i < $cnt; $i++) {
			$part = $this->_parts[$i];
			if ($i > 0 && $part->same_title($this->_parts[$i - 1])) {
				$hidden = ' fw-title-hidden';
			} else {
				$hidden = '';
			}
			?>
<div class="fw-step-part <?php echo $width; echo $boxlayout; ?>" data-partId="<?php echo $i ?>">
	<h2 class="fw-step-part-title <?php echo $hidden; ?>">
			<?php
			  $part->render_title();
			?>
	</h2>
	<div class="fw-clearfix"></div>
	<div class="fw-step-part-body">
			<?php
			  $part->render_body( $wizardId, $stepId, $i );
			?>
	</div>
</div>
			<?php
		}
	}

	public function render_title ( ) {
		echo $this->_title;
	}

	public function render_headline ( ) {
		echo $this->_headline;
	}

	public function render_copy_text ( ) {
		echo $this->_copy_text;
	}

	public function as_aa() {
		$parts_aa = array();
		foreach ( $this->_parts as $part ) {
			$parts_aa[] = $part->as_aa();
		}
		return array(
			'title' => $this->_title,
			'headline' => $this->_headline,
			'copy_text' => $this->_copy_text,
			'parts' => $parts_aa
		);
	}

	public static function from_aa( $aa, $current_version, $serialized_version ) {
		// var_dump( $aa );
		$title = isset( $aa['title'] ) ? $aa['title'] : '';
		$headline = isset( $aa['headline'] ) ? $aa['headline'] : '';
		$copy_text = isset( $aa['copy_text'] ) ? $aa['copy_text'] : '';
		$parts = array();

		if ( isset( $aa['parts'] )  ) {
			foreach ( $aa['parts'] as $part ) {
				$parts[] = Mondula_Form_Wizard_Wizard_Step_Part::from_aa( $part, $current_version, $serialized_version );
			}
		}

		return new Mondula_Form_Wizard_Wizard_Step( $title, $headline, $copy_text, $parts);
	}
}
