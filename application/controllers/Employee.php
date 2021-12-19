<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	//If required
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400'); // cache for 1 day
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
	}

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}

	exit(0);
}


defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Employee extends REST_Controller
{
	public function index()
	{
	}

	public function employee_get($id = 0)
	{
		if ($id > 0) {
			$data = $this->db
				->select("E.*, CONCAT('" . site_url('uploads/') . "',E.image) AS image")
				->where('E.id', $id)
				->get('employees E')->row_array();
		} else {
			$data = $this->db->select("E.*, CONCAT('" . site_url('uploads/') . "',E.image) AS image")->get('employees E')->result_array();
		}
		$res = [
			'status' => true,
			'message' => 'Data fetched successfully',
			'data' => $data
		];
		$this->response($res, 200);
	}

	public function insert_post()
	{
		$this->load->library('form_validation');
		$postdata = $this->input->post();
		$this->form_validation->set_rules('name', "'name'", 'trim|required');
		$this->form_validation->set_rules('email', "'email'", 'trim|required|valid_email');
		$this->form_validation->set_rules('dob', "'dob'", 'required');
		$this->form_validation->set_rules('mobile', "'mobile'", 'required');
		$this->form_validation->set_rules('address', "'address'", 'trim|required');
		$this->form_validation->set_rules('pincode', "'pincode'", 'required');

		if ($this->form_validation->run() == FALSE) {
			$res = [
				'status' => false,
				'message' => validation_errors()
			];
			$error_code = 400;
		} else {
			$date = str_replace('/', '-', xss_clean($postdata['dob']));
			$data = [
				'name' => xss_clean($postdata['name']),
				'email' => xss_clean($postdata['email']),
				'dob' => date('Y-m-d', strtotime($date)),
				'mobile' => xss_clean($postdata['mobile']),
				'address' => xss_clean($postdata['address']),
				'pincode' => xss_clean($postdata['pincode'])
			];
			if (!empty($_FILES['image']['name'])) {
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
				$config['max_size'] = '2048';
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('image')) {
					$file = $this->upload->data();
					$data['image'] = $file['file_name'];
				} else {
					$res = [
						'status' => false,
						'message' => $this->upload->display_errors()
					];
					$this->response($res, 400);
					exit;
				}
			}
			if ($this->db->insert('employees', $data)) {
				$res = [
					'status' => true,
					'message' => 'Employee added successfully'
				];
				$error_code = 200;
			} else {
				$res = [
					'status' => false,
					'message' => 'Insert Failed'
				];
				$error_code = 500;
			}
		}
		$this->response($res, $error_code);
	}
	public function update_post()
	{
		$postdata = $this->input->post();
		$id = isset($postdata['id']) ? $postdata['id'] : 0;
		if ($id > 0) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', "'name'", 'trim|required');
			$this->form_validation->set_rules('email', "'email'", 'trim|required|valid_email');
			$this->form_validation->set_rules('dob', "'dob'", 'required');
			$this->form_validation->set_rules('mobile', "'mobile'", 'required');
			$this->form_validation->set_rules('address', "'address'", 'trim|required');
			$this->form_validation->set_rules('pincode', "'pincode'", 'required');

			if ($this->form_validation->run() == FALSE) {
				$res = [
					'status' => false,
					'message' => validation_errors()
				];
				$error_code = 400;
			} else {
				$date = str_replace('/', '-', xss_clean($postdata['dob']));
				$data = [
					'name' => xss_clean($postdata['name']),
					'email' => xss_clean($postdata['email']),
					'dob' => date('Y-m-d', strtotime($date)),
					'mobile' => xss_clean($postdata['mobile']),
					'address' => xss_clean($postdata['address']),
					'pincode' => xss_clean($postdata['pincode'])
				];

				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
					$config['max_size'] = '2048';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('image')) {
						$file = $this->upload->data();
						$data['image'] = $file['file_name'];
					} else {
						$res = [
							'status' => false,
							'message' => $this->upload->display_errors()
						];
						$this->response($res, 400);
						exit;
					}
				}
				$this->db->where('id', $id);
				if ($this->db->update('employees', $data)) {
					$res = [
						'status' => true,
						'message' => 'Employee updated successfully'
					];
					$error_code = 200;
				} else {
					$res = [
						'status' => false,
						'message' => 'Update Failed'
					];
					$error_code = 500;
				}
			}
		} else {
			$res = [
				'status' => false,
				'message' => 'Invalid id'
			];
			$error_code = 400;
		}
		$this->response($res, $error_code);
	}
	public function delete_delete($id = 0)
	{
		if ($id > 0) {
			$this->db->where('id', $id);
			$row = $this->db->get('employees')->row_array();

			if (isset($row) && !empty($row['image'])) {
				unlink('./uploads/' . $row['image']);
			}

			$this->db->where('id', $id);
			if ($this->db->delete('employees')) {
				$res = [
					'status' => true,
					'message' => 'Employee deleted successfully'
				];
				$error_code = 200;
			} else {
				$res = [
					'status' => false,
					'message' => 'Delete Failed'
				];
				$error_code = 500;
			}
		} else {
			$res = [
				'status' => false,
				'message' => 'Invalid id'
			];
			$error_code = 400;
		}
		$this->response($res, $error_code);
	}
}
