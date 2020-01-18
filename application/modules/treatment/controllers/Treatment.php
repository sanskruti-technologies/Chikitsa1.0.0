<?php
class Treatment extends CI_Controller {
    function __construct() {
        parent::__construct();
		
		$this->load->model('menu_model');
		$this->load->model('treatment_model');
		$this->load->model('settings/settings_model');
		$this->load->model('admin/admin_model');
		$this->load->model('module/module_model');
		$this->load->model('bill/bill_model');
		
		$this->lang->load('main');
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('security');
		$this->load->helper('currency_helper');
		$this->load->helper('mainpage');
		
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('export');
    }
	/**Treatments*/
    function index() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('treatment', $this->lang->line('treatment_name'), 'trim|required|xss_clean|is_unique[treatments.treatment]');
            $this->form_validation->set_rules('treatment_price', $this->lang->line('treatment')." ".$this->lang->line('price'), 'trim|required|xss_clean');
            $data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            if ($this->form_validation->run() === FALSE) {
                
            } else {
                $this->treatment_model->add_treatment();
            }
			$data['treatments'] = $this->treatment_model->get_treatments();
			$data['tax_rates'] = $this->settings_model->get_tax_rates(); 
			$data['tax_rate_array'] = $this->settings_model->get_tax_rate_array(); 
			$data['tax_rate_name'] = $this->settings_model->get_tax_rate_name(); 
			
			$user_id = $this->session->userdata('user_id'); 
			$clinic_id = $this->session->userdata('clinic_id'); 
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$active_modules = $this->module_model->get_active_modules();
			$header_data['active_modules'] = $active_modules;
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			if (in_array("doctor", $active_modules)) {	
				$this->load->model('doctor/doctor_model');
				$data['departments'] = $this->doctor_model->get_all_departments();
			}
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('treatments_list', $data);
			$this->load->view('templates/footer');
        }
    }
	function insert(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('treatment', $this->lang->line('treatment_name'), 'trim|required|xss_clean|is_unique[treatments.treatment]');
            $this->form_validation->set_rules('treatment_price', $this->lang->line('treatment')." ".$this->lang->line('price'), 'trim|required|xss_clean');
            
			if ($this->form_validation->run() === FALSE) {
			
				$user_id = $this->session->userdata('user_id'); 
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$active_modules = $this->module_model->get_active_modules();
				$header_data['active_modules'] = $active_modules;
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				
				$data['tax_rates'] = $this->settings_model->get_tax_rates(); 
				$data['tax_rate_array'] = $this->settings_model->get_tax_rate_array(); 
				$data['tax_rate_name'] = $this->settings_model->get_tax_rate_name(); 
				if (in_array("doctor", $active_modules)) {	
					$this->load->model('doctor/doctor_model');
					$data['departments'] = $this->doctor_model->get_all_departments();
				}
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('treatment_form',$data);
				$this->load->view('templates/footer');
			}else{
				 $this->treatment_model->add_treatment();
				 $this->index();
			}
		}
	}
	function add_treatment() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('treatment', $this->lang->line('treatment_name'), 'trim|required|xss_clean|is_unique[treatments.treatment]');
            $this->form_validation->set_rules('treatment_price', $this->lang->line('treatment')." ".$this->lang->line('price'), 'trim|required|xss_clean');
            
			if ($this->form_validation->run() === FALSE) {
				$user_id = $this->session->userdata('user_id'); 
				$clinic_id = $this->session->userdata('clinic_id'); 
				$data = NULL;
				
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$active_modules = $this->module_model->get_active_modules();
				$header_data['active_modules'] = $active_modules;
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				if (in_array("doctor", $active_modules)) {	
					$this->load->model('doctor/doctor_model');
					$data['departments'] = $this->doctor_model->get_all_departments();
				}
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('treatment_form',$data);
				$this->load->view('templates/footer');
			}else{
				 $this->treatment_model->add_treatment();
				 $this->index();
			}
		}
	}
	function edit_treatment($id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {				
            redirect('login/index');
        } else {
			
			$treatment = $this->treatment_model->get_treatment($id);
			$original_value = $treatment['treatment'];
			if($this->input->post('treatment') != $original_value) {
			   $is_unique =  '|is_unique[treatments.treatment]';
			} else {
			   $is_unique =  '';
			}
			
			$this->form_validation->set_rules('treatment', $this->lang->line('treatment_name'), 'trim|required|xss_clean'.$is_unique);
            $this->form_validation->set_rules('treatment_price', $this->lang->line('treatment')." ".$this->lang->line('price'), 'trim|required|xss_clean');
            $data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			if ($this->form_validation->run() === FALSE) {
				$data['treatment'] = $this->treatment_model->get_treatment($id);
				$data['tax_rates'] = $this->settings_model->get_tax_rates();    
				
				$user_id = $this->session->userdata('user_id'); 
				$clinic_id = $this->session->userdata('clinic_id'); 
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$active_modules = $this->module_model->get_active_modules();
				$header_data['active_modules'] = $active_modules;
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				if (in_array("doctor", $active_modules)) {	
					$this->load->model('doctor/doctor_model');
					$data['departments'] = $this->doctor_model->get_all_departments();
				}
				$this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('treatment_form', $data);
				$this->load->view('templates/footer');
			} else {
				$treatment_id = $this->input->post('treatment_id');
                $this->treatment_model->edit_treatment($treatment_id);
				$this->index();
            }
        }
    }
	function delete_treatment($id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->treatment_model->delete_treatment($id);
            $this->index();
        }
    }
	function treatment_report(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('from_date',$this->lang->line('from_date'), 'required');
			$this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'required');
			if ($this->form_validation->run() === FALSE) {
				$timezone = $this->settings_model->get_time_zone();
				if (function_exists('date_default_timezone_set'))
					date_default_timezone_set($timezone);
				$from_date = date('Y-m-d');
				$to_date = date('Y-m-d');
				$data['from_date'] = $from_date;
				$data['to_date'] = $to_date;
				$data['selected_doctors'] = array();
				$selected_doctors = array();
				$data['selected_treatments'] = array();
				$selected_treatments = array();
				$data['fields'] = array('name','phone_number','email','date','time','doctor','treatment','doctor_share');
			}else{
				$data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
				$from_date = date('Y-m-d',strtotime($this->input->post('from_date')));
				$data['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
				$to_date = date('Y-m-d',strtotime($this->input->post('to_date')));
				$data['fields'] = $this->input->post('field');
				$data['selected_doctors'] = $this->input->post('doctor');
				$selected_doctors = $this->input->post('doctor');
				
				$data['selected_treatments'] = $this->input->post('treatment');
				$selected_treatments = $this->input->post('treatment');
			}
			$data['def_dateformate'] = $this->settings_model->get_date_formate();
			$data['def_timeformate'] = $this->settings_model->get_time_formate();
			$data['treatments'] = $this->treatment_model->get_treatments();                
			$data['bill_details'] = $this->bill_model->get_bill_details();
			$data['doctors'] = $this->admin_model->get_doctor();
			$data['treatement_report'] = $this->treatment_model->get_treatment_report($from_date,$to_date,$selected_doctors,$selected_treatments);
			$user_id = $this->session->userdata('user_id'); 
			$clinic_id = $this->session->userdata('clinic_id'); 
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$header_data['active_modules'] = $this->module_model->get_active_modules();
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('treatment_report',$data);
			$this->load->view('templates/footer');
		}
	}
	function treatment_report_excel_export($from_date,$to_date,$fields=NULL,$selected_doctors = NULL,$selected_treatments=NULL){
		if($selected_doctors == "0"){
			$selected_doctors = "";
		}else{
			$selected_doctors = explode("__",$selected_doctors);
		}
		if($selected_treatments == "0"){
			$selected_treatments = "";
		}else{
			$selected_treatments = explode("__",$selected_treatments);
		}
		if($fields == NULL){
			$fields = array('name','phone_number','email','date','time','doctor','treatment','doctor_share');
		}else{
			$fields = explode("__",$fields);
		}
		$query = $this->treatment_model->get_treatment_report($from_date,$to_date,$selected_doctors,$selected_treatments);
		$this->export->to_excel($query, 'treatement_report'); 
	}
	function print_treatment_report($from_date,$to_date,$fields=NULL,$selected_doctors = NULL,$selected_treatments = NULL){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {	
            redirect('login/index');
        } else {
			$data['selected_doctors'] = $selected_doctors;
			if($selected_doctors == "0"){
				$selected_doctors = "";
			}else{
				$selected_doctors = explode("__",$selected_doctors);
			}
			$data['selected_treatments'] = $selected_treatments;
			if($selected_treatments == "0"){
				$selected_treatments = "";
			}else{
				$selected_treatments = explode("__",$selected_treatments);
			}
			
			
			if($fields == NULL){
				$fields = array('name','phone_number','email','date','time','doctor','treatment');
			}else{
				$fields = explode("__",$fields);
			}
			
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['fields'] = $fields;
			$data['treatement_report'] = $this->treatment_model->get_treatment_report($from_date,$to_date,$selected_doctors,$selected_treatments);
			$data['def_dateformate'] = $this->settings_model->get_date_formate();
			$data['def_timeformate'] = $this->settings_model->get_time_formate();
			
			$this->load->view('print_treatment_report', $data);
		}
	}
}

?>