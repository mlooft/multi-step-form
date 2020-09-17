<?php

if (!defined('ABSPATH')) exit;


/**
 * Description of class-mondula-multistep-forms-wizard-repository
 *
 * @author alex
 */
class Mondula_Form_Wizard_Wizard_Repository {

	private $_table;

	public function __construct($table_name) {
		global $wpdb;

		$this->_table = $wpdb->prefix . $table_name;
	}

	public function find_by_id($id) {
		global $wpdb;

		$sql = $wpdb->prepare("SELECT * FROM {$this->_table} WHERE id = %d", $id);

		return $wpdb->get_row($sql);
	}

	public function find() {
		global $wpdb;

		$sql = "SELECT * FROM {$this->_table}";

		return $wpdb->get_results($sql, ARRAY_A);
	}

	public function update($id, $data) {
		global $wpdb;

		$wpdb->update($this->_table, $data, array('id' => $id));

		return $id;
	}

	public function save($data) {
		global $wpdb;

		$wpdb->insert($this->_table, $data);

		return $wpdb->insert_id;
	}

	public function delete($id) {
		global $wpdb;

		$wpdb->delete($this->_table, array('id' => $id));
	}
	
	public function duplicate($id) {
	  $row = $this->find_by_id($id);
	  $data = array();
	  $data['date'] = current_time('mysql');
	  $data['json'] = $row->json;
	  $data['version'] = $row->version;
	  $this->save($data);
	}
}
