<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of a media output field.
 *
 * @author alex
 */
class Mondula_Form_Wizard_Block_Media extends Mondula_Form_Wizard_Block {

	private $_attachmentId;

	protected static $type = "fw-media";

	/**
	 * Creates an Object of this Class.
	 * @param string $text Content of the Paragraph.
	 */
	public function __construct ( $attachmentId ) {
		$this->_attachmentId = $attachmentId;
	}

	public function render( $ids ) {
		?>
		<div class="fw-step-block" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-media">
			<div class="fw-media-container">
                <?php 
                    if (wp_attachment_is('image', $this->_attachmentId)) {
                        echo wp_get_attachment_image($this->_attachmentId, 'full');
                    } else if (wp_attachment_is('video', $this->_attachmentId)) {
                        echo do_shortcode('[video src=' . wp_get_attachment_url($this->_attachmentId) . ']');
                    } else {
                        echo $GLOBALS['wp_embed']->run_shortcode( '[embed]' . wp_get_attachment_url($this->_attachmentId) . '[/embed]' );
                    }
                ?>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'media',
			'attachmentId' => $this->_attachmentId
		);
	}

	public static function from_aa( $aa , $current_version, $serialized_version ) {
		$attachmentId = $aa['attachmentId'];
		return new Mondula_Form_Wizard_Block_Media( $attachmentId );
	}

	public static function addType($types) {

		$types['media'] = array(
			'class' => 'Mondula_Form_Wizard_Block_Media',
			'title' => __('Media', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'Mondula_Form_Wizard_Block_Media::addType', 9);
