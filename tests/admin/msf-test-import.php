<?php

/**
 * JSON import: the imported form must keep all field information.
 * Before the fix, the import ran array_map('sanitize_text_field', $block['elements']),
 * which turns every radio/checkbox element ({type, value}) into an empty string.
 */
class Test_Msf_Admin_Import extends WP_UnitTestCase {

	private function fixture() {
		return array(
			'wizard' => array(
				'title' => 'Imported form',
				'steps' => array(
					array(
						'title' => 'Step 1',
						'headline' => 'Headline',
						'copy_text' => 'Copy',
						'parts' => array(
							array(
								'title' => 'Part 1',
								'blocks' => array(
									array(
										'type' => 'radio',
										'label' => 'Domain registered?',
										'elements' => array(
											array('type' => 'header', 'value' => 'Is the domain registered?'),
											array('type' => 'option', 'value' => 'yes'),
											array('type' => 'option', 'value' => 'no'),
										),
										'required' => 'true',
										'multichoice' => 'false',
									),
									array(
										'type' => 'select',
										'elements' => array('Yes', 'No'),
										'required' => 'true',
										'label' => 'Copy to sender',
										'placeholder' => 'Yes',
										'search' => 'false',
									),
									array(
										'type' => 'paragraph',
										'text' => "We recommend Hetzner or Strato.\n\n<b>Bold</b> and <a href=\"https://www.strato.de/hosting/\">a link</a><br>",
									),
									array('type' => 'text', 'label' => 'Reference', 'required' => 'true'),
									array('type' => 'email', 'label' => 'Email', 'required' => 'true', 'confirm' => 'false'),
									array('type' => 'numeric', 'label' => 'Phone', 'required' => 'false', 'minimum' => '1', 'maximum' => '99'),
									array('type' => 'file', 'label' => 'Upload', 'required' => 'false', 'multi' => 'true'),
									array('type' => 'media', 'attachmentId' => '7135'),
								),
							),
						),
					),
				),
				'settings' => array(
					'thankyou' => 'https://example.com/thanks/',
					'to' => 'office@example.com',
					'frommail' => 'noreply@example.com',
					'fromname' => 'Example Inc.',
					'subject' => 'Order',
					'header' => '',
					'headers' => '',
					'replyto' => 'Email',
					'usercopy' => 'Email',
					'optin' => 'no-optin',
					'optin_success' => '',
					'replacements' => 'off',
				),
			),
		);
	}

	private function blocks($data) {
		return $data['wizard']['steps'][0]['parts'][0]['blocks'];
	}

	function test_import_keeps_radio_elements() {
		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($this->fixture());

		$radio = $this->blocks($imported)[0];
		$this->assertEquals(
			array(
				array('type' => 'header', 'value' => 'Is the domain registered?'),
				array('type' => 'option', 'value' => 'yes'),
				array('type' => 'option', 'value' => 'no'),
			),
			$radio['elements']
		);
	}

	function test_import_keeps_select_elements() {
		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($this->fixture());

		$this->assertEquals(array('Yes', 'No'), $this->blocks($imported)[1]['elements']);
	}

	function test_import_keeps_paragraph_formatting() {
		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($this->fixture());

		$this->assertEquals(
			"We recommend Hetzner or Strato.\n\n<b>Bold</b> and <a href=\"https://www.strato.de/hosting/\">a link</a><br>",
			$this->blocks($imported)[2]['text']
		);
	}

	function test_import_strips_scripts_from_paragraph() {
		$data = $this->fixture();
		$data['wizard']['steps'][0]['parts'][0]['blocks'][2]['text'] = 'Text<script>alert(1)</script><img src=x onerror=alert(1)>';

		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($data);

		$this->assertEquals('Textalert(1)<img src="x">', $this->blocks($imported)[2]['text']);
	}

	/**
	 * Import -> DB -> export must be lossless for a form as written by the export.
	 */
	function test_import_export_round_trip_is_lossless() {
		$fixture = $this->fixture();
		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($fixture);

		// same as Mondula_Form_Wizard_Wizard_Service::save() / get_as_json()
		$stored = json_decode(json_encode($imported['wizard']), true);
		$exported = Mondula_Form_Wizard_Wizard::from_aa($stored, '1.7.29', '1.7.29')->as_aa();

		$this->assertEquals($fixture['wizard'], $exported);
	}

	function test_import_limits_steps_in_free_version() {
		$this->assertFalse(class_exists('Multi_Step_Form_Plus'), 'test expects the free plugin only');

		$data = $this->fixture();
		$step = $data['wizard']['steps'][0];
		$data['wizard']['steps'] = array($step, $step, $step, $step, $step, $step, $step);

		$imported = Mondula_Form_Wizard_Admin::sanitize_import_data($data);

		$this->assertCount(5, $imported['wizard']['steps']);
		// must stay a list, otherwise json_encode() would turn the steps into an object
		$this->assertEquals(array(0, 1, 2, 3, 4), array_keys($imported['wizard']['steps']));
	}

	/**
	 * A block type that is not registered (e.g. a Plus "conditional" block imported into
	 * the free plugin) falls back to the generic sanitization, which must not drop nested data.
	 */
	function test_unknown_block_type_keeps_nested_data() {
		$block = array(
			'type' => 'conditional',
			'block' => array('type' => 'text', 'label' => 'Provider', 'required' => 'true'),
			'prec_block_id' => '11',
			'prec_value' => 'yes',
			'prec_operator' => 'eq',
			'visible' => 'show',
		);
		$expected = $block;

		Mondula_Form_Wizard_Admin::sanitize_form_block($block);

		$this->assertEquals($expected, $block);
	}
}
