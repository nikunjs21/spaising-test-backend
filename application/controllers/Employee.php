<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Employee extends REST_Controller
{
	public function index()
	{
	}

	public function list_get()
	{
		$data = $this->db->select("E.*, CONCAT('" . site_url('uploads/') . "',E.image) AS image")->get('employees E')->result_array();
		$res = [
			'error_code' => 200,
			'status' => 'success',
			'message' => 'Employees list',
			'data' => $data
		];
		echo json_encode($res);
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
				'error_code' => 400,
				'status' => false,
				'message' => validation_errors()
			];
		} else {
			$data = [
				'name' => xss_clean($postdata['name']),
				'email' => xss_clean($postdata['email']),
				'dob' => xss_clean($postdata['dob']),
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
						'error_code' => 400,
						'status' => false,
						'message' => $this->upload->display_errors()
					];
					echo json_encode($res);
					exit;
				}
			}
			if ($this->db->insert('employees', $data)) {
				$res = [
					'error_code' => 200,
					'status' => true,
					'message' => 'Employee added successfully'
				];
			} else {
				$res = [
					'error_code' => 500,
					'status' => false,
					'message' => 'Insert Failed'
				];
			}
		}
		echo json_encode($res);
	}
	public function update_put()
	{
		$postdata = $this->put();
		print_r($postdata);
		$id = isset($postdata['id']) ? $postdata['id'] : 0;
		echo $id;
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
					'error_code' => 400,
					'status' => false,
					'message' => validation_errors()
				];
			} else {

				$data = [
					'name' => xss_clean($postdata['name']),
					'email' => xss_clean($postdata['email']),
					'dob' => xss_clean($postdata['dob']),
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
							'error_code' => 400,
							'status' => false,
							'message' => $this->upload->display_errors()
						];
						echo json_encode($res);
						exit;
					}
				}
				$this->db->where('id', $id);
				if ($this->db->update('employees', $data)) {
					$res = [
						'error_code' => 200,
						'status' => true,
						'message' => 'Employee updated successfully'
					];
				} else {
					$res = [
						'error_code' => 500,
						'status' => false,
						'message' => 'Update Failed'
					];
				}
			}
		} else {
			$res = [
				'error_code' => 400,
				'status' => false,
				'message' => 'Invalid id'
			];
		}
		echo json_encode($res);
	}
	public function delete_delete($id = 0)
	{
		if ($id > 0) {
			$this->db->where('id', $id);
			if ($this->db->delete('employees')) {
				$res = [
					'error_code' => 200,
					'status' => true,
					'message' => 'Employee deleted successfully'
				];
			} else {
				$res = [
					'error_code' => 500,
					'status' => false,
					'message' => 'Delete Failed'
				];
			}
		} else {
			$res = [
				'error_code' => 400,
				'status' => false,
				'message' => 'Invalid id'
			];
		}
		echo json_encode($res);
	}
}
