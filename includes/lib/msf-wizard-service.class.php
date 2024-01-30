<?php

if (!defined('ABSPATH')) {
	exit;
}

class Mondula_Form_Wizard_Wizard_Service {

	private $_repository;
	private $_plugin_version;

	private static $_instance = null;

	public function __construct(Mondula_Form_Wizard_Wizard_Repository $repository, $plugin_version) {
		$this->_repository = $repository;
		$this->_plugin_version = $plugin_version;

		static::$_instance = $this;
	}

	public function get_by_id($id) {
		$row = $this->_repository->find_by_id($id);
		if ($row)
		{
			file_put_contents('test.txt', var_export(Mondula_Form_Wizard_Wizard::from_aa(json_decode($row->json, true), $this->_plugin_version, $row->version), true));
			return Mondula_Form_Wizard_Wizard::from_aa(json_decode($row->json, true), $this->_plugin_version, $row->version);
		} else {
			return null;
		}
	}

	public static function get_form_by_id($id) {
		if (static::$_instance === null) {
			return null;
		}

		return static::$_instance->get_by_id($id);
	}

	public function get_all() {
		return $this->_repository->find();
	}

	public function get_as_json($id) {
		$all = $this->get_all();
		if (!empty($id)) {
			$wizard = $this->get_by_id($id);
			if ($wizard === null) {
				$wizard = new Mondula_Form_Wizard_Wizard();
			}
		} else {
			$title = '';
			$wizard = new Mondula_Form_Wizard_Wizard();
		}
		return json_encode(
			array(
				'wizard' => $wizard->as_aa(),
			)
		);
	}

	public function save($id, $data) {
		$row = array();
		$row['date'] = current_time('mysql');
		$row['json'] = json_encode($data['wizard']);
		$row['version'] = $this->_plugin_version;

		if (!empty($id)) {
			return $this->_repository->update($id, $row);
		} else {
			return $this->_repository->save($row);
		}
	}

	public function delete($id) {
		$this->_repository->delete($id);
	}

	public function duplicate($id) {
		$this->_repository->duplicate($id);
	}
}
