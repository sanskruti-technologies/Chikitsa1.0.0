<?php

class History extends CI_Controller {
    function __construct() {
        parent::__construct();
		
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->helper('form');
		$this->load->helper('my_string');
		$this->load->helper('mainpage_helper');

		$this->load->model('menu_model');
		$this->load->model('history_model');
		$this->load->model('doctor/doctor_model');
		$this->load->model('admin/admin_model');
		$this->load->model('patient/patient_model');
        $this->load->model('settings/settings_model');
		$this->load->model('module/module_model');

		$this->lang->load('main'); 
    }
	function sections(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			//Load the view
			$data['sections']= $this->history_model->get_sections();
			$header_data['level'] = $this->session->userdata('category');
			$clinic_id = $this->session->userdata('clinic_id'); 
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$user_id = $this->session->userdata('user_id'); 
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('sections',$data);
			$this->load->view('templates/footer');
		}
	}
	function add_section(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('section_name', 'Section Name', 'required');
			if ($this->form_validation->run() === FALSE){
				$header_data['level'] = $this->session->userdata('category');
			
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$user_id = $this->session->userdata('user_id'); 
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				$active_modules = $this->module_model->get_active_modules();
				$data['active_modules'] =  $active_modules;
				if (in_array("doctor", $active_modules)) {
					$data['departments'] = $this->doctor_model->get_all_departments();
				}
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('section_form',$data);
				$this->load->view('templates/footer');
			}else{
				$section_id = $this->history_model->add_section();
				redirect('history/sections');
			}
		}
	}
	function edit_section($section_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('section_name', 'Section Name', 'required');
			if ($this->form_validation->run() === FALSE){
				$data['section']=$this->history_model->get_section($section_id);
				$active_modules = $this->module_model->get_active_modules();
				$data['active_modules'] =  $active_modules;
				if (in_array("doctor", $active_modules)) {
					$data['departments'] = $this->doctor_model->get_all_departments();
				}
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$user_id = $this->session->userdata('user_id'); 
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				$header_data['level'] = $this->session->userdata('category');
			
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('section_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->history_model->edit_section($section_id);
				redirect('history/sections');
			}
		}
	}
	function delete_section($section_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->history_model->delete_section($section_id);
			$this->sections();
		}
	}
	function edit_section_fields($section_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('section_id', 'Section', 'required');
			if ($this->form_validation->run() === FALSE){
				$data['section']=$this->history_model->get_section($section_id);
				$data['fields']=$this->history_model->get_section_fields($section_id);
				$data['field_options']=$this->history_model->get_section_field_options($section_id);
				//...
				$data['field_repeat']=$this->history_model->get_section_field_options($section_id);
				
				
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['level'] = $this->session->userdata('category');
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$user_id = $this->session->userdata('user_id'); 
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();		
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('section_field_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->history_model->edit_field_section($section_id);
				redirect('history/sections');
			}
		}
	}
	function edit_section_conditions($section_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('section_id', 'Section ID', 'required');
			if ($this->form_validation->run() === FALSE){
				$data['section']=$this->history_model->get_section($section_id);
				$data['conditions']=$this->history_model->get_section_conditions($section_id);
				$data['fields']=$this->history_model->get_section_fields($section_id);
				$header_data['level'] = $this->session->userdata('category');
				
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$user_id = $this->session->userdata('user_id'); 
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();	
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('section_conditions_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->history_model->edit_section_conditions($section_id);
				redirect('history/sections');
			}
		}
	}
	function print_visit_history($visit_id){
		$data['section_master'] = $this->history_model->get_section_by_display_in("visits");
		$data['section_fields'] = $this->history_model->get_fields_by_display_in("visits");
		$data['field_options'] = $this->history_model->get_field_options_by_display_in("visits");
		$data['patient_history_details'] = $this->history_model->get_visit_history_details($visit_id);
		$patient_id = $this->patient_model->get_patient_id($visit_id);
		$data['patient'] = $this->patient_model->get_patient_detail($patient_id);
		$data['visit'] = $this->patient_model->get_visit_data($visit_id);
		$data['def_dateformate'] = $this->settings_model->get_date_formate();
		$data['def_timeformate'] = $this->settings_model->get_time_formate();
		$data['doctor'] = $this->doctor_model->get_doctor_details($data['visit']['doctor_id']);
		$this->load->view('print_fields',$data);
	}
	function remove_patient_value($history_id,$index){
		$this->history_model->remove_patient_value($history_id,$index);
	}
}

?>
