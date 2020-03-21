<?php
class Lab extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
	    $this->load->model('settings/settings_model');
		$this->load->model('module/module_model');
		$this->load->model('admin/admin_model');
		$this->load->model('patient/patient_model');
		$this->load->model('contact/contact_model');
		$this->load->model('lab_model');

		$this->load->helper('form');
		$this->load->helper('currency');
		$this->load->helper('mainpage_helper');

		$this->load->library('session');
		$this->load->library('form_validation');

        $this->load->database();
		$this->lang->load('main');
    }
	public function activate(){
		if (!is_dir('uploads/reports')) {
			mkdir('./uploads/reports', 0777, TRUE);
		}
		redirect('module/index');
	}
	public function tests(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['tests'] = $this->lab_model->get_tests();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$clinic_id = $this->session->userdata('clinic_id');
			$user_id = $this->session->userdata('user_id');
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$header_data['active_modules'] = $this->module_model->get_active_modules();
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();

			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('test_browse',$data);
			$this->load->view('templates/footer');
		}
	}
	public function insert_test(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('test_name', $this->lang->line('test_name'), 'required');
			$this->form_validation->set_rules('test_charges', $this->lang->line('test_charges'), 'required');
			if ($this->form_validation->run() === FALSE){
				$clinic_id = $this->session->userdata('clinic_id');
				$user_id = $this->session->userdata('user_id');
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$header_data['active_modules'] = $this->module_model->get_active_modules();
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();

				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('test_form');
				$this->load->view('templates/footer');
			}else{
				$this->lab_model->insert_test();
				$this->tests();
			}
		}
	}
	public function edit_test($test_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('test_name', $this->lang->line('test_name'), 'required');
			$this->form_validation->set_rules('test_charges', $this->lang->line('test_charges'), 'required');
			if ($this->form_validation->run() === FALSE){
				$data['test'] = $this->lab_model->get_test($test_id);
				$clinic_id = $this->session->userdata('clinic_id');
				$user_id = $this->session->userdata('user_id');
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$header_data['active_modules'] = $this->module_model->get_active_modules();
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();

				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('test_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->lab_model->edit_test($test_id);
				$this->tests();
			}
		}
	}
	public function delete_test($test_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->lab_model->delete_test($test_id);
			$this->tests();
		}
	}
	public function view_lab_tests($status = 'pending'){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			if($this->input->post('status')){
				$status = $this->input->post('status');
			}
			$data['lab_tests'] = $this->lab_model->get_lab_tests($status);
			$data['status'] = $status;
			$clinic_id = $this->session->userdata('clinic_id');
			$user_id = $this->session->userdata('user_id');
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$header_data['active_modules'] = $this->module_model->get_active_modules();
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('lab_tests',$data);
			$this->load->view('templates/footer');
		}
	}
	public function ajax_lab_tests($status = 'pending'){
		$lab_tests = $this->lab_model->get_lab_tests($status);

		$ajax_data = array();
		$i = 1;
		foreach($lab_tests as $lab_test){
			$col['sr_no'] = "$i";
			$col[$this->lang->line("patient")." ".$this->lang->line("name")] = $lab_test['patient_name'];
			$col[$this->lang->line("test")." ".$this->lang->line("name")] = $lab_test['test_name'];
			$col[$this->lang->line("status")] = ucfirst($lab_test['status']);
			if($lab_test['status'] == 'pending') {
				$col[$this->lang->line("action")] = "<a class='btn btn-primary square-btn-adjust' href='".site_url('lab/upload_result/'.$lab_test['visit_test_id'])."'>Upload Result</a>";
			}else{
				$col[$this->lang->line("action")] = "<a class='btn btn-primary square-btn-adjust' href='".site_url('lab/upload_result/'.$lab_test['visit_test_id'])."'>View Report</a>";
			}
			$ajax_data[] = $col;
			$i++;
		}

		echo '{ "data":'.json_encode($ajax_data).'}';
	}
	public function upload_result($visit_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['visit_id'] = $visit_id;
			$data['visit_test'] = $this->lab_model->get_visit_test($visit_id);
			$visit = $this->patient_model->get_visit_data($visit_id);
			$patient_id = $visit['patient_id'];
			$data['patient'] = $this->patient_model->get_patient_detail($patient_id);
			$data['def_dateformate'] = $this->settings_model->get_date_formate();
			$data['contact_details'] = $this->contact_model->get_all_contact_details();
			$data['addresses'] = $this->contact_model->get_contacts($data['patient']['contact_id']);

			$clinic_id = $this->session->userdata('clinic_id');
			$user_id = $this->session->userdata('user_id');
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$header_data['active_modules'] = $this->module_model->get_active_modules();
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('upload_result',$data);
			$this->load->view('templates/footer');
		}
	}

	public function upload_report_files($visit_id){
		$file_names = $this->input->post('file_name');
		foreach($file_names as $visit_test_id){
			$result = $this->do_upload($visit_test_id);
			if(isset($result['upload_data'])){
				$file_name =  $result['upload_data']['file_name'];
				$this->lab_model->save_report_file($file_name,$visit_test_id);

			}
			if($this->input->post('submit') == 'save_complete'){
				$this->lab_model->change_status($visit_test_id,'complete');
			}
			if($this->input->post('submit') == 'viewed'){
				$this->lab_model->change_status($visit_test_id,'viewed');
			}
		}
		$this->upload_result($visit_id);
	}
	public function do_upload($file_name){
		$config['upload_path']          = './uploads/reports';
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 1024;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$config['file_name']			= $file_name;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($file_name)) {
			$error = array('error' => $this->upload->display_errors());
			return $error;
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
	public function lab_test_count(){
		$new_lab_tests = $this->lab_model->get_new_lab_tests();
		echo $new_lab_tests;
		exit;
	}
}
?>