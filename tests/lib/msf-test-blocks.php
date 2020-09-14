<?php

class Test_Msf_Lib_Blocks extends WP_UnitTestCase {

	function test_blocks_loaded() {
        $actual = Mondula_Form_Wizard_Block::get_block_types();

        $this->assertArrayHasKey('date', $actual);
        $this->assertArrayHasKey('email', $actual);
        $this->assertArrayHasKey('file', $actual);
        $this->assertArrayHasKey('media', $actual);
        $this->assertArrayHasKey('get-variable', $actual);
        $this->assertArrayHasKey('numeric', $actual);
        $this->assertArrayHasKey('paragraph', $actual);
        $this->assertArrayHasKey('radio', $actual);
        $this->assertArrayHasKey('select', $actual);
        $this->assertArrayHasKey('text', $actual);
        $this->assertArrayHasKey('textarea', $actual);
    }
}
