<?php

class Prescription extends CI_Controller {

	function __construct() {
        parent::__construct();
        
		$this->load->model('menu_model');
		$this->load->model('admin/admin_model');
		$this->load->model('module/module_model');
		$this->load->model('prescription_model');
		$this->load->model('patient/patient_model');
		$this->load->model('settings/settings_model');
		
		$this->lang->load('main');
		
		$this->load->helper('form');
		$this->load->helper('mainpage_helper');
		
		$this->load->library('form_validation');
		$this->load->library('session');
		
		
		$this->load->database();
    }
	
	function medicine(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
			redirect('login/index');
        } else {

			$data['medicines'] = $this->prescription_model->get_medicines();
			$data['active_modules'] = $this->module_model->get_active_modules();
			
			$header_data['level'] = $this->session->userdata('category');
			$clinic_id = $this->session->userdata('clinic_id');
			$user_id = $this->session->userdata('user_id');
			$header_data['clinic_id'] = $clinic_id;
			$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
			$header_data['active_modules'] = $this->module_model->get_active_modules();
			$header_data['user_id'] = $user_id;
			$header_data['user'] = $this->admin_model->get_user($user_id);
			$header_data['login_page'] = get_main_page();
			$header_data['software_name']= $this->settings_model->get_data_value("software_name");
		
			
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('medicine', $data);
			$this->load->view('templates/footer');
		}
	}
	function insert_medicine() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {				
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('medicine_name', $this->lang->line('medicine_name'), 'required');

            if ($this->form_validation->run() === FALSE) {
                $header_data['level'] = $this->session->userdata('category');
				$clinic_id = $this->session->userdata('clinic_id');
				$user_id = $this->session->userdata('user_id');
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$header_data['active_modules'] = $this->module_model->get_active_modules();
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				$header_data['software_name']= $this->settings_model->get_data_value("software_name");
			
				
				$this->load->view('templates/header',$header_data);
                $this->load->view('templates/menu');
                $this->load->view('prescription/medicine_form');
                $this->load->view('templates/footer');
            } else {
                $this->prescription_model->save_medicine();
                $this->medicine();
            }
        }
    }
	function add_medicine(){
		$medicine_name =  $this->input->post('medicine_name');
		$medicine_id = $this->prescription_model->add_medicine($medicine_name);
		$medicine = $this->prescription_model->get_medicine($medicine_id);
		echo '{"value":"' . $medicine['medicine_name'] . '","id":"' . $medicine['medicine_id'] . '"}';
	}
	function edit_medicine($medicine_id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {				
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('medicine_name', $this->lang->line('medicine_name'), 'required');

            if ($this->form_validation->run() === FALSE) {
                $data['medicine'] = $this->prescription_model->get_medicine($medicine_id);
                $header_data['level'] = $this->session->userdata('category');
				$clinic_id = $this->session->userdata('clinic_id');
				$user_id = $this->session->userdata('user_id');
				$header_data['clinic_id'] = $clinic_id;
				$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
				$header_data['active_modules'] = $this->module_model->get_active_modules();
				$header_data['user_id'] = $user_id;
				$header_data['user'] = $this->admin_model->get_user($user_id);
				$header_data['login_page'] = get_main_page();
				$header_data['software_name']= $this->settings_model->get_data_value("software_name");
			
				
				$this->load->view('templates/header',$header_data);
                $this->load->view('templates/menu');
                $this->load->view('prescription/medicine_form', $data);
                $this->load->view('templates/footer');
            } else {
                $this->prescription_model->update_medicine();
                $this->medicine();
            }
        }
    }
	function delete_medicine($medicine_id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {				
            redirect('login/index');
        } else {
            $this->prescription_model->delete_medicine($medicine_id);
            $this->medicine();
        }
    }
	function print_view_prescription($patient_id){
		$data['patient'] = $this->patient_model->get_patient_detail($patient_id);
		$data['prescriptions'] = $this->prescription_model->get_last_prescription($patient_id);
		$data['medicine_name'] = $this->prescription_model->get_medicine_array();
		$this->load->view('print_view_prescription',$data);
	}
	function print_prescription($visit_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index/');
        } else {
			$active_modules = $this->module_model->get_active_modules();
			$data['active_modules'] = $active_modules;
			
            $receipt_template = $this->patient_model->get_template('prescription');
			$template = $receipt_template['template'];
			
			$def_dateformate = $this->settings_model->get_date_formate();
			$def_timeformate = $this->settings_model->get_time_formate();
			
			//Clinic Details
			$clinic = $this->settings_model->get_clinic_settings();
			$clinic_array = array('clinic_name','tag_line','clinic_address','landline','mobile','email');
			foreach($clinic_array as $clinic_detail){
				$template = str_replace("[$clinic_detail]", $clinic[$clinic_detail], $template);
			}
			
			//Visit Details
			$visit = $this->patient_model->get_visit_data($visit_id);
			$visit_date = date($def_dateformate, strtotime($visit['visit_date']));
			$patient_id = $visit['patient_id'];
			$doctor_id = $visit['doctor_id'];
			$patient_notes = $visit['patient_notes'];
			$doctor = $this->admin_model->get_doctor($doctor_id);
			 
			$template = str_replace("[visit_date]", $visit_date, $template);
			$template = str_replace("[doctor_name]", $doctor['name'], $template);
			$template = str_replace("[patient_notes]", $patient_notes, $template);
			
			//Patient Details
			$patient = $this->patient_model->get_patient_detail($patient_id);
			$dob = $patient['dob'];
			if(!empty($dob)){
				$birthdate = strtotime($dob);
				$today = strtotime(date('Y-m-d'));
				$age_in_seconds = $today - $birthdate;
				//$age = $age_in_seconds;
				$age = floor($age_in_seconds / 60 / 60 / 24 / 365);
			}else{
				$age = "-";
			}

			$patient_name = $patient['first_name']." ".$patient['middle_name']." ".$patient['last_name'];
			$template = str_replace("[patient_name]",$patient_name, $template);
			$template = str_replace("[patient_id]",$patient['display_id'], $template);
			$template = str_replace("[sex]",strtoupper(substr($patient['gender'],0,1)), $template);
			$template = str_replace("[age]",$age, $template);
			
			
			$medicines = $this->prescription_model->get_all_medicines($visit_id);
			$dose_method = array(
											'OD'       => 'Once (OD)',
											'BD'       => 'Twice (BD)',
											'TDS'      => 'Three Time (TDS)',
											'HS'       => 'Night (HS)',
											'QID'      => 'Four Time (QID)',
									);
			//Medicine Columns
			$start_pos = strpos($template, '[col:');
			if ($start_pos !== false) {
				
				$end_pos= strpos($template, ']',$start_pos);
				$length = abs($end_pos - $start_pos);
				$col_string = substr($template, $start_pos, $length+1);
				$columns = str_replace("[col:", "", $col_string);
				$columns = str_replace("]", "", $columns);
				$cols = explode("|",$columns);
				$table = "";
				foreach($medicines as $medicine){
					
					$table .= "<tr>";
					foreach($cols as $col){
						if($col == 'medicine_name'){
							$medicine_id = $medicine['medicine_id'];
							$medicine_name = $this->prescription_model->get_medicine_name($medicine_id);
							$table .= "<td style='padding:5px;border:1px solid black;'>".$medicine_name."</td>";
						}elseif($col == 'dosage'){
							$table .= "<td style='padding:5px;border:1px solid black;'>".$medicine['freq_morning']."-".$medicine['freq_afternoon']."-".$medicine['freq_night']." for ".$medicine['for_days']." days</td>";
						}elseif($col == 'quantity'){
							$quantity = ($medicine['freq_morning']+$medicine['freq_afternoon']+$medicine['freq_night']) * $medicine['for_days'];
							$table .= "<td style='padding:5px;border:1px solid black;'>".$quantity." Nos.</td>";
						}elseif($col == 'instructions'){
							$table .= "<td style='padding:5px;border:1px solid black;'>".$medicine['instructions']."</td>";
						}
					}
					$table .= "</tr>";
				
				}
				$template = str_replace("$col_string",$table, $template);
			}
			$template .="<input type='button' value='Print' id='print_button' onclick='window.print()'>
			<style>
				@media print{
					#print_button{
						display:none;
					}
					
				}
			</style>";
			
			$data['receipt_template'] = $template;
			
            $this->load->view('patient/receipt_template/receipt', $data);
        }
	}
	function edit_prescription($visit_id){
		$patient_id = $this->patient_model->get_patient_id($visit_id);
		$this->form_validation->set_rules('medicine_name[]', $this->lang->line('medicine_name'), 'required');
		if ($this->form_validation->run() === FALSE) {
			
		}else{
			
			$this->prescription_model->update_prescription($visit_id,$patient_id);
		}
		$data['prescriptions'] = $this->prescription_model->get_all_medicines($visit_id);
		$data['medicines'] = $this->prescription_model->get_medicines();
		$data['medicine_array'] = $this->prescription_model->get_medicine_array();
		$data['visit_id'] = $visit_id;
		$data['patient_id'] = $patient_id;
		
		$header_data['level'] = $this->session->userdata('category');
		$clinic_id = $this->session->userdata('clinic_id');
		$user_id = $this->session->userdata('user_id');
		$header_data['clinic_id'] = $clinic_id;
		$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
		$header_data['active_modules'] = $this->module_model->get_active_modules();
		$header_data['user_id'] = $user_id;
		$header_data['user'] = $this->admin_model->get_user($user_id);
		$header_data['login_page'] = get_main_page();
		$header_data['software_name']= $this->settings_model->get_data_value("software_name");
	
		
		$this->load->view('templates/header',$header_data);
		$this->load->view('templates/menu');
		$this->load->view('prescription_form', $data);
		$this->load->view('templates/footer');
	}
	function add_prescription($visit_id){
		$patient_id = $this->patient_model->get_patient_id($visit_id);
		$this->form_validation->set_rules('medicine_name[]', $this->lang->line('medicine_name'), 'required');
		if ($this->form_validation->run() === FALSE) {
			
		}else{
			
			$this->prescription_model->update_prescription($visit_id,$patient_id);
		}
		$data['prescriptions'] = $this->prescription_model->get_all_medicines($visit_id);
		$data['medicines'] = $this->prescription_model->get_medicines();
		$data['medicine_array'] = $this->prescription_model->get_medicine_array();
		$data['visit_id'] = $visit_id;
		$data['patient_id'] = $patient_id;
		
		$header_data['level'] = $this->session->userdata('category');
		$clinic_id = $this->session->userdata('clinic_id');
		$user_id = $this->session->userdata('user_id');
		$header_data['clinic_id'] = $clinic_id;
		$header_data['clinic'] = $this->settings_model->get_clinic($clinic_id);
		$header_data['active_modules'] = $this->module_model->get_active_modules();
		$header_data['user_id'] = $user_id;
		$header_data['user'] = $this->admin_model->get_user($user_id);
		$header_data['login_page'] = get_main_page();
		$header_data['software_name']= $this->settings_model->get_data_value("software_name");
	
		
		$this->load->view('templates/header',$header_data);
		$this->load->view('templates/menu');
		$this->load->view('prescription_form', $data);
		$this->load->view('templates/footer');
	}
	public function delete_prescription_medicine($visit_id,$medicine_id){
		$this->prescription_model->delete_prescription_medicine($visit_id,$medicine_id);
		$this->edit_prescription($visit_id);
	}
	public function view_prescription(){
		$data['patients'] = $this->patient_model->get_patient();
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('view_prescription',$data);
		$this->load->view('templates/footer');
	}
}
?>
