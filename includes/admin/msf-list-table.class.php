<?php

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Table UI for Forms.
 */
class Mondula_Form_Wizard_List_Table extends WP_LIST_TABLE {
	protected $_text_domain = 'multi-step-form';
	private $_wizard_service;

	public function __construct(Mondula_Form_Wizard_Wizard_Service $wizard_service) {
		parent::__construct(array(
			'screen' => get_current_screen(),
		));
		$this->_wizard_service = $wizard_service;
	}

	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Title',
			'shortcode' => 'Shortcode',
			'sendto' => 'Send mails to',
			'date' => 'Date',
		);
		return $columns;
	}

	public function column_default($item, $column_name) {
		$wiz = json_decode($item['json'], true);
		switch ($column_name) {
			case 'title':
				$actions = array(
					'Edit' => '<a href="#"></a>',
				);
				$this->row_actions($actions);
				// fallthrough: title and date always go together
			case 'date':
				return $item[ $column_name ];
			case 'shortcode':
				return '[multi-step-form id="' . $item['id'] . '"]';
			case 'sendto':
				if ('' != $wiz['settings']['to']) {
					return $wiz['settings']['to'];
				} else {
					return '';
				}
			default:
				return print_r($item, true);
		}
	}

	private function generate_query_url($action, $id) {
		$query = remove_query_arg(array('edit', 'delete', 'duplicate'));
		$url = add_query_arg(array($action => $id), $query);

		// Create a unique action name for the nonce based on the action and the ID
		$nonce_action = $action . '_nonce';

		// Add the nonce to the URL
		return wp_nonce_url($url, $nonce_action);
	}

	public function column_title($item) {
		$wiz = json_decode($item['json'], true);

		$edit_url = $this->generate_query_url('edit', $item['id']);
		$delete_url = $this->generate_query_url('delete', $item['id']);
		$duplicate_url = $this->generate_query_url('duplicate', $item['id']);
		$export_url = $this->generate_query_url('export', $item['id']);

		$delete_confirm = 'onclick="return confirm(\'' . __('Are you sure you want to delete this form?', 'multi-step-form') . '\')"';

		$actions = array(
			'fw-edit' => '<a href="' . $edit_url . '">' . __('Edit', $this->_text_domain) . '</a>',
			'fw-delete' => '<a href="' . $delete_url . '" ' . $delete_confirm . '>' . __('Delete', $this->_text_domain) . '</a>',
			'fw-duplicate' => '<a href="' . $duplicate_url . '">' . __('Duplicate', $this->_text_domain) . '</a>',
			'fw-export' => '<a href="' . $export_url . '">' . __('Export', $this->_text_domain) . '</a>',
		);
		if (!$wiz['title'] || '' == $wiz['title']) {
			$wiz['title'] = 'My Multi Step Form';
		}
		return '<a href="' . $edit_url . '">' . $wiz['title'] . '</a>' . $this->row_actions($actions);
	}

	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->_wizard_service->get_all();

		$this->process_bulk_action();
	}


	function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete',
		);
		return $actions;
	}

	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="wizard[]" value="%s" />', $item['id']
		);
	}

	public function process_bulk_action() {

		// security check!
		if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
			$nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
			$action = 'bulk-' . $this->_args['plural'];

			if (!wp_verify_nonce($nonce, $action)) {
					wp_die('Nope! Security check failed!');
			}
		}

		$action = $this->current_action();

		switch ($action) {

			case 'delete':
				// wp_die('Delete something');
				if ( !check_admin_referer('bulk-delete-action', 'bulk_delete_nonce')) {
					wp_die('Security check failed. Please try again.');
					break;
				}
				$wizard_ids = $_GET['wizard'];
				foreach ($wizard_ids as $wizard_id) {
					$this->_wizard_service->delete($wizard_id);
					$array_id = -1;
					foreach ($this->items as $id => $item) {
						if ($item['id'] == $wizard_id) {
							$array_id = $id;
							break;
						}
					}
					if ($array_id >= 0) {
						unset($this->items[$array_id]);
					}
				}
				break;

			case 'save':
				wp_die('Save something');
				break;

			default:
				// do nothing or something else
				return;
				break;
		}
		return;
	}
}
