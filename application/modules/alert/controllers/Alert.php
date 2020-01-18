<?php
class Alert extends CI_Controller {
	function __construct() {
        parent::__construct();
     
		$this->load->model('menu_model');
		$this->load->model('alert_model');
		$this->load->model('module/module_model');
		$this->load->model('doctor/doctor_model');
		$this->load->model('settings/settings_model');
		$this->load->model('appointment/appointment_model');
		$this->load->model('payment/payment_model');
		
		$this->load->model('contact/contact_model');
		$this->load->model('patient/patient_model');
		
		$this->load->model('admin/admin_model');
		
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('email');
		
		$this->load->helper('form');
		$this->load->helper('currency');
		
		$this->lang->load('main');
    }
	public function index(){
		// this controller can only be called from the command line
        //if (!$this->input->is_cli_request()) show_error('Direct access is not allowed');
	
		$timezone = $this->settings_model->get_time_zone();
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$now = date("Y-m-d H:i:s");
		$todate = date("Y-m-d");
		$current_time = date("H:i");
		
		//Check if alerts extension is enabled
		$active_modules = $this->module_model->get_active_modules();
		if (in_array("alert", $active_modules)) {
			$this->load->model('alert/alert_model');
			/*********************************Appointments*****************************************/
			//All Enabled appointment reminders 
			$alerts = $this->alert_model->get_enabled_alerts('APPNT');
			foreach($alerts as $alert){
				//All Future Appointments
				$future_appointments = $this->appointment_model->get_future_appointments($now);
				
				foreach($future_appointments as $future_appointment){
					
					//Calculate Remaining Time
					$appointment_time = $future_appointment['appointment_date'] .' '. $future_appointment['start_time'];
					$hours_to_appointment = round((strtotime($appointment_time) - strtotime($now) ) / 60 / 60,2);
					
					//Time to send the reminder
					$alert_time = str_replace('email_alert','email_alert_time',$alert['alert_name']);
					$alert_time = str_replace('sms_alert','sms_alert_time',$alert_time);

					$time_for_reminder = $this->settings_model->get_data_value($alert_time);
					
					
					if (($hours_to_appointment >= ($time_for_reminder - 0.25)) && ($hours_to_appointment <= ($time_for_reminder + 0.25))){
						//Call Alert	
						$alert_name = str_replace('email_alert_','',$alert['alert_name']);
						$alert_name = str_replace('sms_alert_','',$alert_name);
						$alert_name = str_replace('_to_patient','',$alert_name);
						$alert_name = str_replace('_to_doctor','',$alert_name);
						
						$this->send($alert_name,$future_appointment['patient_id'],$future_appointment['userid'],$future_appointment['appointment_id'],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
					}
				}
			}
			/*********************************Prescription*****************************************/
			//All Enabled prescription reminders 
			$alerts = $this->alert_model->get_enabled_alerts('DOSE');
			foreach($alerts as $alert){
				
				//Time to send the reminder
				$alert_time = str_replace('email_alert','email_alert_time',$alert['alert_name']);
				$alert_time = str_replace('sms_alert','sms_alert_time',$alert_time);
				
				$time_for_reminder = $this->settings_model->get_data_value($alert_time);
				$time_for_reminder_array = explode("|",$time_for_reminder);
				
				$alert_name = str_replace('email_alert_','',$alert['alert_name']);
				$alert_name = str_replace('sms_alert_','',$alert_name);
				$alert_name = str_replace('_to_patient','',$alert_name);
				$alert_name = str_replace('_to_doctor','',$alert_name);
				
				if (in_array("prescription", $active_modules)) {
					$this->load->model('prescription/prescription_model');
					$future_doses = $this->prescription_model->get_future_doses($todate);
					foreach($future_doses as $doses){
						foreach($time_for_reminder_array as $time => $dose_time){
							if ((strtotime($current_time) >= (strtotime($dose_time) - 900)) && (strtotime($current_time) <= (strtotime($dose_time) + 900))){
								$this->send($alert_name,$doses['patient_id'],NULL,NULL,NULL,NULL,$time,NULL,NULL,NULL,NULL,NULL);	
							}
						}
					}
				}
			}
			/*********************************Birthdays*****************************************/
			
			$alerts = $this->alert_model->get_enabled_alerts('BRTH');
			foreach($alerts as $alert){
			
				$current_time = date("H:i:s");
				//Time to send the reminder
				$alert_time = str_replace('email_alert','email_alert_time',$alert['alert_name']);
				$alert_time = str_replace('sms_alert','sms_alert_time',$alert_time);
				
				$time_for_reminder = $this->settings_model->get_data_value($alert_time);
				//Patient
				if ((strtotime($current_time) >= (strtotime($time_for_reminder) - 900)) && (strtotime($current_time) < (strtotime($time_for_reminder) + 900))){
					$alert_name = str_replace('email_alert_','',$alert['alert_name']);
					$alert_name = str_replace('sms_alert_','',$alert_name);
					$alert_name = str_replace('_to_patient','',$alert_name);
					$alert_name = str_replace('_to_doctor','',$alert_name);
					// All patients whose birthday is today
					$patients = $this->patient_model->get_today_birthdays($todate);
					foreach($patients as $patient){
						$this->send($alert_name,$patient['patient_id'],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);	
					}
					// All doctors whose birthday is today
					if (in_array("doctor", $active_modules)) {
						$this->load->model('doctor/doctor_model');
						$doctors = $this->doctor_model->get_today_birthdays($todate);
						foreach($doctors as $doctor){
							$this->send($alert_name,NULL,$doctor['userid'],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);	
						}
					}
				}
			}
			
			/*********************************Followups*****************************************/
			$alerts = $this->alert_model->get_enabled_alerts('FLWUP');
			foreach($alerts as $alert){
				
				//Time to send the reminder
				$alert_time = str_replace('email_alert','email_alert_time',$alert['alert_name']);
				$alert_time = str_replace('sms_alert','sms_alert_time',$alert_time);
				
				$time_for_reminder = $this->settings_model->get_data_value($alert_time);

				$alert_name = str_replace('email_alert_','',$alert['alert_name']);
				$alert_name = str_replace('sms_alert_','',$alert_name);
				$alert_name = str_replace('_to_patient','',$alert_name);
				$alert_name = str_replace('_to_doctor','',$alert_name);
				//All Future Appointments
				$future_followups = $this->appointment_model->get_future_followups($todate);
				foreach($future_followups as $future_followup){
					//Calculate Remaining Time
					$followup_date = $future_followup['followup_date'];

					if(strtotime($followup_date) == strtotime($todate)){
						if ((strtotime($current_time) > (strtotime($time_for_reminder) - 900)) && (strtotime($current_time) <= (strtotime($time_for_reminder) + 900))){
							$this->send($alert_name,$future_followup['patient_id'],$future_followup['userid'],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
						}
					}
				}
			}
		}
    }
	/**Settings*/
    public function settings() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {				
            redirect('login/index');
        } else {
			$data['alerts'] = $this->alert_model->get_all_alerts();
			$data['sms_api_username'] = $this->settings_model->get_data_value('sms_api_username');
			$data['sms_api_password'] = $this->settings_model->get_data_value('sms_api_password');
			$data['send_sms_url'] = $this->settings_model->get_data_value('send_sms_url');
			$data['country_code'] = $this->settings_model->get_data_value('country_code');
			$data['senderid'] = $this->settings_model->get_data_value('senderid');
			$data['from_email'] = $this->settings_model->get_data_value('from_email');
			$data['from_name'] = $this->settings_model->get_data_value('from_name');
			$data['email_password'] = $this->settings_model->get_data_value('email_password');
			$data['smtp_host'] = $this->settings_model->get_data_value('smtp_host');
			$data['smtp_port'] = $this->settings_model->get_data_value('smtp_port');
			$data['active_modules'] = $this->module_model->get_active_modules();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('settings',$data);
			$this->load->view('templates/footer');
        }
    }
	public function save_settings(){
		$this->alert_model->set_enabled_alerts();
		$this->settings();
	}
	public function sms_settings(){
		$sms_api_username = $this->input->post('username');
		$this->settings_model->set_data_value('sms_api_username', $sms_api_username);
		$sms_api_password = $this->input->post('password');
		$this->settings_model->set_data_value('sms_api_password', $sms_api_password);
		$send_sms_url = $this->input->post('send_sms_url');
		$this->settings_model->set_data_value('send_sms_url', $send_sms_url);
		$senderid = $this->input->post('senderid');
		$this->settings_model->set_data_value('senderid', $senderid);
		$country_code = $this->input->post('country_code');
		$this->settings_model->set_data_value('country_code', $country_code);
		$this->settings();
	}
    public function email_settings(){
		if($this->input->post('from_email')){
			$from_email = $this->input->post('from_email');
			$this->settings_model->set_data_value('from_email', $from_email);
		}
		if($this->input->post('email_password')){
			$email_password = $this->input->post('email_password');
			$this->settings_model->set_data_value('email_password', $email_password);
		}
		if($this->input->post('from_name')){
			$from_name = $this->input->post('from_name');
			$this->settings_model->set_data_value('from_name', $from_name);
		}
		$smtp_host = $this->input->post('smtp_host');
		$this->settings_model->set_data_value('smtp_host', $smtp_host);
		$smtp_port = $this->input->post('smtp_port');
		$this->settings_model->set_data_value('smtp_port', $smtp_port);
		$this->settings();
	}
	public function sms_format($alert_name){
		$data['alert_name'] = $alert_name;
		$sms_format_name = 'sms_format_'.$alert_name;
		$data['sms_format'] = $this->settings_model->get_data_value($sms_format_name);
		$alert_name = str_replace('_to_patient','',$alert_name);
		$alert_name = str_replace('_to_doctor','',$alert_name);
		$sms_template_id = 'sms_template_'.$alert_name;
		$data['sms_template_id'] = $this->settings_model->get_data_value($sms_template_id);
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('sms_format',$data);
		$this->load->view('templates/footer');
	}
	public function email_format($alert_name){
		$data['alert_name'] = $alert_name;
		$email_format_name = 'email_format_'.$alert_name;
		$email_subject_name = 'email_subject_'.$alert_name;
		$data['email_format'] = $this->settings_model->get_data_value($email_format_name);
		$data['email_subject'] = $this->settings_model->get_data_value($email_subject_name);
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('email_format',$data);
		$this->load->view('templates/footer');
	}
	public function email_alert_time($alert_name,$alert_occur){
		$data['alert_name'] = $alert_name;
		$data['alert_occur'] = $alert_occur;
		$email_alert_time = 'email_alert_time_'.$alert_name;
		if($alert_occur == "APPNT" ){
			$data['total_hours'] = $this->settings_model->get_data_value($email_alert_time);
		}elseif($alert_occur == "DOSE"){
			$data['dose_time'] = $this->settings_model->get_data_value($email_alert_time);
		}elseif($alert_occur == "BRTH" || $alert_occur == "FLWUP"){
			$data['alert_time'] = $this->settings_model->get_data_value($email_alert_time);
		}
		
		
		$data['time_interval'] = $this->settings_model->get_time_interval();
		$data['def_timeformate'] = $this->settings_model->get_time_formate();
		$data['clinic_start_time'] = $this->settings_model->get_clinic_start_time();
		$data['clinic_end_time'] = $this->settings_model->get_clinic_end_time();
		
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('email_alert_time',$data);
		$this->load->view('templates/footer');
	}
	public function sms_alert_time($alert_name,$alert_occur){
		$data['alert_name'] = $alert_name;
		$data['alert_occur'] = $alert_occur;
		$sms_alert_time = 'sms_alert_time_'.$alert_name;
		if($alert_occur == "APPNT" ){
			$data['total_hours'] = $this->settings_model->get_data_value($sms_alert_time);
		}elseif($alert_occur == "DOSE"){
			$data['dose_time'] = $this->settings_model->get_data_value($sms_alert_time);
		}elseif($alert_occur == "BRTH" || $alert_occur == "FLWUP"){
			$data['alert_time'] = $this->settings_model->get_data_value($sms_alert_time);
		}
		
		$data['time_interval'] = $this->settings_model->get_time_interval();
		$data['def_timeformate'] = $this->settings_model->get_time_formate();
		$data['clinic_start_time'] = $this->settings_model->get_clinic_start_time();
		$data['clinic_end_time'] = $this->settings_model->get_clinic_end_time();
		
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('sms_alert_time',$data);
		$this->load->view('templates/footer');
	}
	public function save_sms_format($alert_name){
		$sms_format = $this->input->post('sms_format');
		$sms_format_name = 'sms_format_'.$alert_name;
		$this->settings_model->set_data_value($sms_format_name, $sms_format);
		
		$sms_template_id = $this->input->post('sms_template_id');
		$alert_name = str_replace('_to_patient','',$alert_name);
		$alert_name = str_replace('_to_doctor','',$alert_name);
		$sms_template = 'sms_template_'.$alert_name;
		$this->settings_model->set_data_value($sms_template, $sms_template_id);
		
		$this->settings();
	}
	public function save_email_format($alert_name){
		$email_format = $this->input->post('email_format');
		$email_subject = $this->input->post('email_subject');
		$email_format_name = 'email_format_'.$alert_name;
		$this->settings_model->set_data_value($email_format_name, $email_format);
		$email_subject_name = 'email_subject_'.$alert_name;
		$this->settings_model->set_data_value($email_subject_name, $email_subject);
		$this->settings();
	}
	public function save_email_alert_time($alert_name,$alert_occur){
		if($alert_occur == "APPNT" ){
			$days = $this->input->post('days');
			$hours = $this->input->post('hours');
			$minutes = $this->input->post('minutes');
			$i = 0;
			foreach($days as $day){
				$total_hours[$i] = ($days[$i]*24)  + $hours[$i] + ($minutes[$i]/60);
				$i++;
			}
			$total_hours_str = implode("|",$total_hours);
			$email_alert_time = 'email_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $total_hours_str);
		}elseif($alert_occur == "DOSE"){
			$morning_time = $this->input->post('morning_time');
			$afternoon_time = $this->input->post('afternoon_time');
			$night_time = $this->input->post('night_time');
			$dose[] = $morning_time;
			$dose[] = $afternoon_time;
			$dose[] = $night_time;
			$dose_str = implode("|",$dose);
			$email_alert_time = 'email_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $dose_str);
		}elseif($alert_occur == "BRTH" || $alert_occur == "FLWUP"){
			$alert_time = $this->input->post('alert_time');
			$email_alert_time = 'email_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $alert_time);
		}
		
		
		$this->settings();
	}
	public function save_sms_alert_time($alert_name,$alert_occur){
		if($alert_occur == "APPNT" ){
			$days = $this->input->post('days');
			$hours = $this->input->post('hours');
			$minutes = $this->input->post('minutes');
			$i = 0;
			foreach($days as $day){
				$total_hours[$i] = ($days[$i]*24)  + $hours[$i] + ($minutes[$i]/60);
				$i++;
			}
			$total_hours_str = implode("|",$total_hours);
			$email_alert_time = 'sms_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $total_hours_str);
		}elseif($alert_occur == "DOSE"){
			$morning_time = $this->input->post('morning_time');
			$afternoon_time = $this->input->post('afternoon_time');
			$night_time = $this->input->post('night_time');
			$dose[] = $morning_time;
			$dose[] = $afternoon_time;
			$dose[] = $night_time;
			$dose_str = implode("|",$dose);
			$email_alert_time = 'sms_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $dose_str);
		}elseif($alert_occur == "BRTH" || $alert_occur == "FLWUP"){
			$alert_time = $this->input->post('alert_time');
			$email_alert_time = 'sms_alert_time_'.$alert_name;
			$this->settings_model->set_data_value($email_alert_time, $alert_time);
		}
		
		
		$this->settings();
	}
	public function email_already_sent($alert_name,$email_id,$params){
		$patient_id = 0;
		$doctor_id = 0;
		$appointment_id = 0;
		$visit_id = 0;
		$payment_id = 0;
		$dose_time = '';
		
		$params_array = explode(",",$params);
		foreach($params_array as $p){
			list($key, $val) = explode('=', $p);
			$val = str_replace("'","",$val);
			switch($key){
					case "patient_id" : $patient_id = (int)$val; break;
					case "doctor_id" : $doctor_id = (int)$val; break;
					case "appointment_id" : $appointment_id = (int)$val; break;
					case "visit_id" : $visit_id = (int)$val; break;
					case "payment_id" : $payment_id = (int)$val; break;
					case "dose_time" : $dose_time = $val; break;
					default: break;
			}
		}
		//Check Alert Occur
		$alert = $this->alert_model->get_alert($alert_name);
		if($alert['alert_occur'] == 'BRTH' || $alert['alert_occur'] == 'APPNT'){
			//Check if Email is sent already
			$today = date('Y-m-d');
			$email_log = $this->alert_model->get_alert_email_log($alert_name,$email_id,$today);
			$is_email_sent = FALSE;
			foreach($email_log as $log){
				if($log['email_param'] != NULL){
					$saved_patient_id = 0;
					$saved_doctor_id = 0;
					$saved_appointment_id = 0;
					$saved_visit_id = 0;
					$saved_payment_id = 0;
					$saved_dose_time = '';
					
					$params_array = explode(",",$log['email_param']);
					foreach($params_array as $p){
						if($p != NULL){
							list($key, $val) = explode('=', $p);
							$val = str_replace("'","",$val);
							switch($key){
									case "patient_id" : $saved_patient_id = (int)$val; break;
									case "doctor_id" : $saved_doctor_id = (int)$val; break;
									case "appointment_id" : $saved_appointment_id = (int)$val; break;
									case "visit_id" : $saved_visit_id = (int)$val; break;
									case "payment_id" : $saved_payment_id = (int)$val; break;
									case "dose_time" : $saved_dose_time = $val; break;
									default: break;
							}
						}
					}
					if(($patient_id == $saved_patient_id) && ($doctor_id == $saved_doctor_id) && ($appointment_id == $saved_appointment_id)){
						//if($log['email_response'] == "OK"){
							$is_email_sent = TRUE;
							break;
						//}
					}
				}
			}
			return $is_email_sent;
		}else{
			return FALSE;
		}
	}
	public function send_to_patient($alert_name,$patient_id){
		$patient_alert_settings = $this->alert_model->get_patient_alert_settings($alert_name,$patient_id);
		//print_r($patient_alert_settings);
		if($patient_alert_settings == NULL){
			return TRUE; // Send to this patient
		}else{
			if($patient_alert_settings['is_enabled'] == 0){
				return FALSE; // Do NOT Send to this patient
			}else{
				return TRUE; // Send to this patient
			}
		}
	}
	public function send($alert_name,$patient_id,$doctor_id,$appointment_id,$visit_id,$payment_id,$dose_time,$redirect_controller,$redirect_function,$redirect_para1,$redirect_para2,$redirect_para3){
		
		$data['patient_id'] = $patient_id;
		$data['doctor_id'] = $doctor_id;
		$data['appointment_id'] = $appointment_id;
		$data['visit_id'] = $visit_id;
		$data['payment_id'] = $payment_id;
		$data['dose_time'] = $dose_time;

		if($appointment_id != 0){
			$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
			$patient_id = $appointment['patient_id'];
			$doctor_id = $appointment['doctor_id'];
			$data['doctor_id'] = $doctor_id;
			$data['patient_id'] = $patient_id;
		}
		if($visit_id != 0){
			$visit = $this->patient_model->get_visit_data($visit_id);
			$patient_id = $visit['patient_id'];
			$data['patient_id'] = $patient_id;
		}
		
		$parameter_string = http_build_query($data);
		//Check if SMS Alert is enabled ?
		$sms_alert_name = 'sms_alert_'.$alert_name;
		
		if($this->alert_model->is_alert_enabled($sms_alert_name)){
			//To Patient
			$sms_alert_name_to_patient = $sms_alert_name.'_to_patient';
			if($this->alert_model->is_alert_enabled($sms_alert_name_to_patient)){
				if($patient_id != NULL){
					if($this->send_to_patient($sms_alert_name_to_patient,$patient_id)){
						$contact_id = $this->patient_model->get_contact_id($patient_id);
						$mobile_number = $this->contact_model->get_contact_number($contact_id);
						if($mobile_number != ''){
							//Message Template
							$sms_format_name = 'sms_format_'.$alert_name.'_to_patient';
							$template = $this->settings_model->get_data_value($sms_format_name);
							$message = $this->generate_message($template,$parameter_string);
							$this->send_sms($sms_alert_name_to_patient,$mobile_number,$message);
						}
					}
				}
			}
			
			//To Doctor
			$sms_alert_name_to_doctor = $sms_alert_name.'_to_doctor';
			if($this->alert_model->is_alert_enabled($sms_alert_name_to_doctor)){
				if($doctor_id != NULL){
					$active_modules = $this->module_model->get_active_modules();
					if (in_array("doctor", $active_modules)) {
						$this->load->model('doctor/doctor_model');	
						$doctor = $this->doctor_model->get_doctor_user_id($doctor_id);
						$contact_id = $doctor['contact_id'];
						$mobile_number = $this->contact_model->get_contact_number($contact_id);
						if($mobile_number != ''){
							//Message Template
							$sms_format_name = 'sms_format_'.$alert_name.'_to_doctor';
							$template = $this->settings_model->get_data_value($sms_format_name);
							$message = $this->generate_message($template,$parameter_string);
							$this->send_sms($alert_name,$mobile_number,$message);
						}
					}
				}
			}
		}
		
		$email_alert_name = 'email_alert_'.$alert_name;
		if($this->alert_model->is_alert_enabled($email_alert_name)){
			//To Patient
			$email_alert_name_to_patient = $email_alert_name.'_to_patient';	
			if($this->alert_model->is_alert_enabled($email_alert_name_to_patient)){
				if($patient_id != NULL){
					if($this->send_to_patient($email_alert_name_to_patient,$patient_id)){
						
						$contact_id = $this->patient_model->get_contact_id($patient_id);
						$email_id = $this->contact_model->get_contact_email($contact_id);
			
						if($email_id != ''){
							//Message Template
							$email_format_name = 'email_format_'.$alert_name.'_to_patient';
							$email_subject_format_name = 'email_subject_'.$alert_name.'_to_patient';
							
							$template = $this->settings_model->get_data_value($email_format_name);
							$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
							$message = $this->generate_message($template,$parameter_string);
							
							$subject = $this->generate_message($subject_template,$parameter_string);
							if(!$this->email_already_sent($email_alert_name_to_patient,$email_id,$parameter_string)){
								$this->send_email($email_alert_name_to_patient,$email_id,$subject,$message,$parameter_string);
							}
						}
					}
				}
			}
			
			//To Doctor
			$email_alert_name_to_doctor = $email_alert_name.'_to_doctor';
			if($this->alert_model->is_alert_enabled($email_alert_name_to_doctor)){
				if($doctor_id != NULL){
					$active_modules = $this->module_model->get_active_modules();
					if (in_array("doctor", $active_modules)) {
						$this->load->model('doctor/doctor_model');	
						$doctor = $this->doctor_model->get_doctor_doctor_id($doctor_id);
						//print_r($doctor);
						$contact_id = $doctor['contact_id'];
						$email_id = $this->contact_model->get_contact_email($contact_id);
						if($email_id != ''){
							//Message Template
							$email_format_name = 'email_format_'.$alert_name.'_to_doctor';
							$email_subject_format_name = 'email_subject_'.$alert_name.'_to_doctor';
							
							$template = $this->settings_model->get_data_value($email_format_name);
							$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
							$message = $this->generate_message($template,$parameter_string);
							
							$subject = $this->generate_message($subject_template,$parameter_string);
							if(!$this->email_already_sent($email_alert_name_to_patient,$email_id,$parameter_string)){
								$this->send_email($email_alert_name_to_patient,$email_id,$subject,$message,$parameter_string);
							}
						}
					}
				}
			}
			
			//To Clinic Email
			$email_alert_name_to_clinic = $email_alert_name.'_to_clinic';	
			
			if($this->alert_model->is_alert_enabled($email_alert_name_to_clinic)){
				$clinic = $this->settings_model->get_clinic();
				$email_id = $clinic['email'];
				if($email_id != ''){
					//Message Template
					$email_format_name = 'email_format_'.$alert_name.'_to_clinic';
					$email_subject_format_name = 'email_subject_'.$alert_name.'_to_clinic';
					$template = $this->settings_model->get_data_value($email_format_name);
					$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
					$message = $this->generate_message($template,$parameter_string);
					$subject = $this->generate_message($subject_template,$parameter_string);
					$this->send_email($alert_name,$email_id,$subject,$message);
				}
			}
		}
		//Redirect Back to Original Place
		if($redirect_controller != NULL){
			redirect($redirect_controller.'/'.$redirect_function.'/'.$redirect_para1.'/'.$redirect_para2.'/'.$redirect_para3);
		}
	}
		
	function send_alert($alert_name,$parameter_string,$redirect_controller,$redirect_function,$redirect_para1,$redirect_para2,$redirect_para3){
		//Retrive Parameters from Parameter String
		parse_str($parameter_string, $output);
		
		$patient_id = NULL;
		$doctor_id = NULL;
		$appointment_id = 0;
		$visit_id = 0;
		$payment_id = NULL;
		$dose_time = NULL;
		$email = NULL;
		
		if(isset($output['patient_id'])) { $patient_id = $output['patient_id'];}
		if(isset($output['doctor_id'])) { $doctor_id = $output['doctor_id'];}
		if(isset($output['appointment_id'])) { $appointment_id = $output['appointment_id'];}
		if(isset($output['visit_id'])) { $visit_id = $output['visit_id'];}
		if(isset($output['payment_id'])) { $payment_id = $output['payment_id'];}
		if(isset($output['dose_time'])) { $dose_time = $output['dose_time'];}
		if(isset($output['email'])) { $email = $output['email'];}
		
		if($appointment_id != 0){
			$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
			$patient_id = $appointment['patient_id'];
			$doctor_id = $appointment['doctor_id'];
		}
		if($visit_id != 0){
			$visit = $this->patient_model->get_visit_data($visit_id);
			$patient_id = $visit['patient_id'];
		}
		//Check if SMS Alert is enabled ?
		$sms_alert_name = 'sms_alert_'.$alert_name;
		if($this->alert_model->is_alert_enabled($sms_alert_name)){
			//To Patient
			$sms_alert_name_to_patient = $sms_alert_name.'_to_patient';
			if($this->alert_model->is_alert_enabled($sms_alert_name_to_patient)){
				if($patient_id != NULL){
					$contact_id = $this->patient_model->get_contact_id($patient_id);
					$mobile_number = $this->contact_model->get_contact_number($contact_id);
					if($mobile_number != ''){
						//Message Template
						$sms_format_name = 'sms_format_'.$alert_name.'_to_patient';
						$template = $this->settings_model->get_data_value($sms_format_name);
						$message = $this->generate_message($template,$parameter_string);
						$this->send_sms($alert_name,$mobile_number,$message);
					}
				}
			}
			
			//To Doctor
			$sms_alert_name_to_doctor = $sms_alert_name.'_to_doctor';
			if($this->alert_model->is_alert_enabled($sms_alert_name_to_doctor)){
				if($doctor_id != NULL){
					$active_modules = $this->module_model->get_active_modules();
					if (in_array("doctor", $active_modules)) {
						$this->load->model('doctor/doctor_model');	
						$doctor = $this->doctor_model->get_doctor_user_id($doctor_id);
						$contact_id = $doctor['contact_id'];
						$mobile_number = $this->contact_model->get_contact_number($contact_id);
						if($mobile_number != ''){
							//Message Template
							$sms_format_name = 'sms_format_'.$alert_name.'_to_doctor';
							$template = $this->settings_model->get_data_value($sms_format_name);
							$message = $this->generate_message($template,$parameter_string);
							$this->send_sms($alert_name,$mobile_number,$message);
						}
					}
				}
			}
		}
		$email_alert_name = 'email_alert_'.$alert_name;
		if($this->alert_model->is_alert_enabled($email_alert_name)){
			//To Patient
			$email_alert_name_to_patient = $email_alert_name.'_to_patient';	
			
			if($this->alert_model->is_alert_enabled($email_alert_name_to_patient)){
				
				if($patient_id != NULL){
					$contact_id = $this->patient_model->get_contact_id($patient_id);
					$email_id = $this->contact_model->get_contact_email($contact_id);
					
					if($email_id != ''){
						//Message Template
						$email_format_name = 'email_format_'.$alert_name.'_to_patient';
						$email_subject_format_name = 'email_subject_'.$alert_name.'_to_patient';
						
						$template = $this->settings_model->get_data_value($email_format_name);
						
						$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
						$message = $this->generate_message($template,$parameter_string);
						
						$subject = $this->generate_message($subject_template,$parameter_string);
						
						$this->send_email($alert_name,$email_id,$subject,$message);
					}
				}
			}
			//To Doctor
			$email_alert_name_to_doctor = $email_alert_name.'_to_doctor';	
			
			if($this->alert_model->is_alert_enabled($email_alert_name_to_doctor)){
				if($doctor_id != NULL){
					$active_modules = $this->module_model->get_active_modules();
					if (in_array("doctor", $active_modules)) {
						$this->load->model('doctor/doctor_model');	
						$doctor = $this->doctor_model->get_doctor_user_id($doctor_id);
						$contact_id = $doctor['contact_id'];
						$email_id = $this->contact_model->get_contact_email($contact_id);
						if($email_id != ''){
							//Message Template
							$email_format_name = 'email_format_'.$alert_name.'_to_doctor';
							$email_subject_format_name = 'email_subject_'.$alert_name.'_to_doctor';
							$template = $this->settings_model->get_data_value($email_format_name);
							$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
							$message = $this->generate_message($template,$parameter_string);
							$subject = $this->generate_message($subject_template,$parameter_string);
							if(!$this->email_already_sent($email_alert_name_to_doctor,$email_id,$params)){
								$this->send_email($email_alert_name_to_doctor,$email_id,$subject,$message,$params);
							}
						}
					}
				}
			}
			//To Clinic Email
			$email_alert_name_to_clinic = $email_alert_name.'_to_clinic';	
			
			if($this->alert_model->is_alert_enabled($email_alert_name_to_clinic)){
				$clinic = $this->settings_model->get_clinic();
				$email_id = $clinic['email'];
				if($email_id != ''){
					//Message Template
					$email_format_name = 'email_format_'.$alert_name.'_to_clinic';
					$email_subject_format_name = 'email_subject_'.$alert_name.'_to_clinic';
					$template = $this->settings_model->get_data_value($email_format_name);
					$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
					$message = $this->generate_message($template,$parameter_string);
					$subject = $this->generate_message($subject_template,$parameter_string);
					if(!$this->email_already_sent($email_alert_name_to_clinic,$email_id,$params)){
						$this->send_email($email_alert_name_to_clinic,$email_id,$subject,$message,$params);
					}
				}
			}
			//To UserName
			$email_alert_name_to_user = $email_alert_name.'_to_user';
			if($this->alert_model->is_alert_enabled($email_alert_name_to_user)){
				if($email != ''){
					$email_format_name = 'email_format_'.$alert_name.'_to_user';
					$email_subject_format_name = 'email_subject_'.$alert_name.'_to_user';
					$template = $this->settings_model->get_data_value($email_format_name);
					$subject_template = $this->settings_model->get_data_value($email_subject_format_name);
					$message = $this->generate_message($template,$parameter_string);
					$subject = $this->generate_message($subject_template,$parameter_string);
					$this->send_email($alert_name,$email,$subject,$message);
				}
			}
		}
		//Redirect Back to Original Place
		if($redirect_controller != NULL){
			redirect($redirect_controller.'/'.$redirect_function.'/'.$redirect_para1.'/'.$redirect_para2.'/'.$redirect_para3);
		}
	}
	function generate_message($template,$parameter_string){
		parse_str($parameter_string, $output);
		
		$patient_id = NULL;
		$doctor_id = NULL;
		$appointment_id = 0;
		$visit_id = 0;
		$payment_id = NULL;
		$dose_time = NULL;
		$email = NULL;
		
		if(isset($output['patient_id'])) { $patient_id = $output['patient_id'];}
		if(isset($output['doctor_id'])) { $doctor_id = $output['doctor_id'];}
		if(isset($output['appointment_id'])) { $appointment_id = $output['appointment_id'];}
		if(isset($output['visit_id'])) { $visit_id = $output['visit_id'];}
		if(isset($output['payment_id'])) { $payment_id = $output['payment_id'];}
		if(isset($output['dose_time'])) { $dose_time = $output['dose_time'];}
		if(isset($output['email'])) { $email = $output['email'];}
		
		//Clinic Name
		$clinic_name = $this->settings_model->get_clinic_name();
		$template = str_replace("[clinic_name]", $clinic_name, $template);
		if($patient_id != NULL){
			//Patient Name
			$patient = $this->patient_model->get_patient_detail($patient_id);
			$patient_name = $patient['first_name']." ".$patient['middle_name']." ".$patient['last_name'];
			$template = str_replace("[patient_name]", $patient_name, $template);
			//Patient ID
			$display_id = $patient['display_id'];
			$template = str_replace("[patient_id]", $display_id, $template);
			//Contact ID
			$contact_id = $this->patient_model->get_contact_id($patient_id);
			$user_detail = $this->admin_model->get_user_detail_by_contact_id($contact_id);
			
			//UserName
			$username = $user_detail['username'];
			$template = str_replace("[username]", $username, $template);
			$password = $user_detail['password'];
			$password = base64_decode($password);
			$template = str_replace("[password]", $password, $template);
			//Patient email
			$email = $patient['email'];
			$template = str_replace("[patient_email]", $email, $template);
			//Patient Mobile
			$phone_number = $patient['phone_number'];
			$template = str_replace("[patient_phone_number]", $phone_number, $template);
			
			$timezone = $this->settings_model->get_time_zone();
			if (function_exists('date_default_timezone_set'))
				date_default_timezone_set($timezone);
			
			$current_time = date('Y-m-d H:i:s');
			$verification_code = $this->admin_model->get_active_verification_code($email,$current_time);
			$template = str_replace("[verification_code]", $verification_code, $template);
			
			$email_changed = str_replace("@","__",$email);
			$verification_link = site_url('frontend/verify_email_code')."/".$email_changed."/".$verification_code;
			$template = str_replace("[verification_link]", $verification_link, $template);
			
		}
		if($doctor_id != NULL){
			//Doctor Name
			$doctor = $this->doctor_model->get_doctor_doctor_id($doctor_id);
			$doctor_name = $doctor['title']." ".$doctor['first_name']." ".$doctor['middle_name']." ".$doctor['last_name'];
			$template = str_replace("[doctor_name]", $doctor_name, $template);
		}
		if($appointment_id != NULL){
			//Appointment Time
			$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
			$appointment_time = $appointment['start_time'];
			$template = str_replace("[appointment_time]", $appointment_time, $template);
			//Appointment Date
			$appointment_date = $appointment['appointment_date'];
			$appointment_date = date('d-m-Y',strtotime($appointment_date));
			$template = str_replace("[appointment_date]", $appointment_date, $template);
			//Appointment Status
			$appointment_status = $appointment['status'];
			if($appointment_status == "Appointments"){
				$appointment_status = "Booked";
			}
			$template = str_replace("[appointment_status]", $appointment_status, $template);
			//appointment reason
			$appointment_reason = $appointment['appointment_reason'];
			$template = str_replace("[appointment_reason]", $appointment_reason, $template);
			
		}
		if($visit_id != NULL){
			$bill_copy = $this->get_print_receipt($visit_id);
			$template = str_replace("[bill]", $bill_copy, $template);	
			$bill_id = $this->patient_model->get_bill_id($visit_id);
			$invoice = $this->settings_model->get_invoice_settings();
			$bill_id = $invoice['static_prefix'] . sprintf("%0" . $invoice['left_pad'] . "d", $bill_id);
			$template = str_replace("[bill_id]", $bill_id, $template);
		}
		
		if($payment_id != 0){
			$payment = $this->payment_model->get_payment($payment_id);
			$payment_amount = currency_format($payment['pay_amount']);
			$template = str_replace("[payment_amount]", $payment_amount, $template);	
			$payment_bills = $this->payment_model->get_bills_for_payment($payment_id);
			$payment_bill_ids = "";
			foreach($payment_bills as $payment_bill){
				$payment_bill_ids = $payment_bill_ids . $invoice['static_prefix'] . sprintf("%0" . $invoice['left_pad'] . "d", $payment_bill['bill_id'] ). " , ";
			}
			$payment_bill_ids = rtrim($payment_bill_ids, ",");
			$template = str_replace("[payment_bill_ids]", $payment_bill_ids, $template);	
		}
		$my_account_link = site_url('frontend/my_account');
		$template = str_replace("[my_account_link]", $my_account_link, $template);	
		
		$template = str_replace("[website_url]", base_url(), $template);	
		$template = str_replace("[user_email]", $email, $template);	
		
		$email_str = str_replace("@","__",$email);
		$key = hash('md5', $email.$clinic_name);
		$reset_password_link = site_url('login/reset_password/'.$email_str.'/'.$key);
		$template = str_replace("[reset_password_link]", $reset_password_link, $template);	
		
		$active_modules = $this->module_model->get_active_modules();
		//Prescription/
		if (in_array("prescription", $active_modules)) {
			if($dose_time!=NULL){
				$dose_time_array[0] = "Morning";
				$dose_time_array[1] = "Afternoon";
				$dose_time_array[2] = "Night";
				$template = str_replace("[dose_time]", $dose_time_array[$dose_time], $template);	
			
				$todate = date("Y-m-d");
				$this->load->model('prescription/prescription_model');
				$future_dose = $this->prescription_model->get_future_doses($todate,$patient_id);

				$medicine_details = "<table style='border-collapase:collapse;'>";
				$medicine_details .= "<tr>";
				$medicine_details .= "<th style='text-align:left;padding:5px;'>Medicine</th>";
				$medicine_details .= "<th style='text-align:left;padding:5px;'>Quantity</th>";
				$medicine_details .= "<th style='text-align:left;padding:5px;'>Instructions</th>";
				$medicine_details .= "</tr>";
				foreach($future_dose as $dose){
					$medicine_details .= "<tr>";
					if($dose_time_array[$dose_time] == "Morning"){
						if($dose['freq_morning'] > 0){
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['medicine_name']."</td>";
							$medicine_details .= "<td style='text-align:right;padding:5px;'>".$dose['freq_morning']."</td>";
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['instructions']."</td>";
						}
					}elseif($dose_time_array[$dose_time] == "Afternoon"){
						if($dose['freq_afternoon'] > 0){
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['medicine_name']."</td>";
							$medicine_details .= "<td style='text-align:right;padding:5px;'>".$dose['freq_afternoon']."</td>";
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['instructions']."</td>";
						}
					}elseif($dose_time_array[$dose_time] == "Night"){
						if($dose['freq_night'] > 0){
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['medicine_name']."</td>";
							$medicine_details .= "<td style='text-align:right;padding:5px;'>".$dose['freq_night']."</td>";
							$medicine_details .= "<td style='text-align:left;padding:5px;'>".$dose['instructions']."</td>";
						}
					}
					$medicine_details .= "</tr>";
				}
				$medicine_details .= "</table>";
				$template = str_replace("[medicine_details]", $medicine_details, $template);	
				
				$sms_medicine_details = "";
				foreach($future_dose as $dose){
					if($dose_time_array[$dose_time] == "Morning"){
						if($dose['freq_morning'] > 0){
							$sms_medicine_details .= $dose['medicine_name']." - ";
							$sms_medicine_details .= $dose['freq_morning']." - ";
							$sms_medicine_details .= $dose['instructions']."\r\n";
						}
					}elseif($dose_time_array[$dose_time] == "Afternoon"){
						if($dose['freq_afternoon'] > 0){
							$sms_medicine_details .= $dose['medicine_name']." - ";
							$sms_medicine_details .= $dose['freq_afternoon']." - ";
							$sms_medicine_details .= $dose['instructions']."\r\n";
						}
					}elseif($dose_time_array[$dose_time] == "Night"){
						if($dose['freq_night'] > 0){
							$sms_medicine_details .= $dose['medicine_name']." - ";
							$sms_medicine_details .= $dose['freq_night']." - ";
							$sms_medicine_details .= $dose['instructions']."\r\n";
						}
					}
				}
				$sms_medicine_details .= "</table>";
				$template = str_replace("[sms_medicine_details]", $sms_medicine_details, $template);	
			}
		}
		return $template;
	}
	function send_sms($alert_name,$mobile_number,$message){ 
		//Get Username
		$sms_api_username = $this->settings_model->get_data_value('sms_api_username');
		//Get Password
		$sms_api_password = $this->settings_model->get_data_value('sms_api_password');
		//Get Sender ID
		$senderid = $this->settings_model->get_data_value('senderid');

		$sms_template_id = 'sms_template_'.$alert_name;
		$template_id = $this->settings_model->get_data_value($sms_template_id);

		$country_code = $this->input->post('country_code');
		//Get API URL
		$send_sms_url = $this->settings_model->get_data_value('send_sms_url');
		$send_sms_url = str_replace("[username]", $sms_api_username, $send_sms_url);
		$send_sms_url = str_replace("[password]", $sms_api_password, $send_sms_url);
		$send_sms_url = str_replace("[senderid]", $senderid, $send_sms_url);
		$send_sms_url = str_replace("[mobileno]", $country_code.$mobile_number, $send_sms_url);
		$send_sms_url = str_replace("[template_id]", $template_id, $send_sms_url);
		//Message
		$message = urlencode($message);
		$send_sms_url = str_replace("[message]", $message, $send_sms_url);
		
		$response = file_get_contents($send_sms_url);

		$timezone = $this->settings_model->get_time_zone();
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_time = date("Y-m-d H:i" );           
		
		$this->alert_model->sms_log($send_sms_url,$response,$current_time);
	}
	function get_print_receipt($visit_id){
		
		$currency_postfix = $this->settings_model->get_currency_postfix();
		$def_dateformate = $this->settings_model->get_date_formate();
		$def_timeformate = $this->settings_model->get_time_formate();
		$invoice = $this->settings_model->get_invoice_settings();
				
		$active_modules = $this->module_model->get_active_modules();
		$bill_id = $this->patient_model->get_bill_id($visit_id);
		
		$particular_total = $this->patient_model->get_particular_total($visit_id);
		if (in_array("treatment", $active_modules)) {
			$treatment_total = $this->patient_model->get_treatment_total($visit_id);
		}
		if (in_array("doctor", $active_modules)) {
			$fees_total = $this->patient_model->get_fee_total($visit_id);
		}
		$item_total = $this->patient_model->get_item_total($visit_id);
		
		$paid_amount = $this->payment_model->get_paid_amount($bill_id);
		
		$receipt_template = $this->patient_model->get_template();
		
		$template = $receipt_template['template'];
		
		$clinic = $this->settings_model->get_clinic_settings();
		//Clinic Details
		$clinic_array = array('clinic_name','tag_line','clinic_address','landline','mobile','email');
		foreach($clinic_array as $clinic_detail){
			$template = str_replace("[$clinic_detail]", $clinic[$clinic_detail], $template);
		}
				
		//Bill Details
		$bill_array = array('bill_date','bill_id','bill_time');
		$bill = $this->patient_model->get_bill($visit_id);
		$patient_id = $bill['patient_id'];
		$bill_details = $this->patient_model->get_bill_detail($visit_id);
		foreach($bill_array as $bill_detail){
			if($bill_detail == 'bill_date'){
				$bill_date = date($def_dateformate, strtotime($bill['bill_date']));
				$template = str_replace("[bill_date]", $bill_date, $template);
			}elseif($bill_detail == 'bill_time'){
				$bill_time = date($def_timeformate, strtotime($bill['bill_time']));
				$template = str_replace("[bill_time]", $bill_time, $template);
			}elseif($bill_detail == 'bill_id'){
				$bill_id = $invoice['static_prefix'] . sprintf("%0" . $invoice['left_pad'] . "d", $bill['bill_id']);
				$template = str_replace("[bill_id]", $bill_id, $template);
			}else{
				$template = str_replace("[$bill_detail]", $bill[$bill_detail], $template);
			}
		}
		//Tax Details for Bill type 
		$tax_amount = 0;
		$bill_tax_amount = 0;
		$data['tax_type']=$this->settings_model->get_data_value('tax_type');
		if($data['tax_type'] == "bill"){
			$tax_details = "</td>";
			foreach($bill_details as $bill_detail){
				
				if($bill_detail['type']=='tax'){
					$tax_details .= "<td colspan='2' style='padding:5px;border:1px solid black;'>".$bill_detail['particular']."</td>";
					$tax_details .= "<td style='padding:5px;border:1px solid black;text-align:right;'><strong>".currency_format($bill_detail['amount'])."</strong></td>";
					$bill_tax_amount = $bill_tax_amount + $bill_detail['amount'];
					$tax_details .= "</tr><tr><td>";
				}
			}
			
		}else{
			$tax_details = "</td><td></td><td></td><td>";
		}
		$template = str_replace("[tax_details]", $tax_details, $template);
		//Patient Details
		$patient = $this->patient_model->get_patient_detail($patient_id);
		$patient_array = array('patient_name');
		foreach($patient_array as $patient_detail){
			if($patient_detail == 'patient_name'){
				$patient_name = $patient['first_name']." ".$patient['middle_name']." ".$patient['last_name'];
				$template = str_replace("[patient_name]",$patient_name, $template);
			}else{
				$template = str_replace("[$patient_detail]", $patient[$patient_detail], $template);
			}
		}
				
		//Bill Columns
		$start_pos = strpos($template, '[col:');
		$item_table = "";
		$particular_table = "";
		$treatment_table = "";
		$fees_table = "";
		$col_string = "";
		
		$particular_amount = 0;
		$item_amount = 0;
		$treatment_amount = 0;
		$fees_amount = 0;
		if ($start_pos !== false) {
			
			$end_pos= strpos($template, ']',$start_pos);
			$length = abs($end_pos - $start_pos);
			$col_string = substr($template, $start_pos, $length+1);
			$columns = str_replace("[col:", "", $col_string);
			$columns = str_replace("]", "", $columns);
			$cols = explode("|",$columns);
			
			
			foreach($bill_details as $bill_detail){
				if($bill_detail['type']=='particular'){
					$particular_table .= "<tr>";
					foreach($cols as $col){
						if($col =='mrp' || $col =='amount'){
							$particular_table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
							$particular_table .= currency_format($bill_detail[$col])."</td>";
						}else{
							$particular_table .= "<td style='padding:5px;border:1px solid black;'>";
							$particular_table .= $bill_detail[$col]."</td>";
						}
					}
					$particular_table .= "</tr>";
					$particular_amount = $particular_amount + $bill_detail['amount'];
				}elseif($bill_detail['type']=='item'){
					$item_table .= "<tr>";
					foreach($cols as $col){
						if($col =='mrp' || $col =='amount'){
							$item_table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
							$item_table .= currency_format($bill_detail[$col])."</td>";
						}else{
							$item_table .= "<td style='padding:5px;border:1px solid black;'>";
							$item_table .= $bill_detail[$col]."</td>";
						}
						
					}
					$item_table .= "</tr>";
					$item_amount = $item_amount + $bill_detail['amount'];
				}elseif($bill_detail['type']=='treatment'){
					$treatment_table .= "<tr>";
					foreach($cols as $col){
						if($col =='mrp' || $col =='amount'){
							$treatment_table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
							$treatment_table .= currency_format($bill_detail[$col])."</td>";
						}else{
							$treatment_table .= "<td style='padding:5px;border:1px solid black;'>";
							$treatment_table .= $bill_detail[$col]."</td>";
						}
						
					}
					$treatment_table .= "</tr>";
					$treatment_amount = $treatment_amount + $bill_detail['amount'];
				}elseif($bill_detail['type']=='fees'){
					$fees_table .= "<tr>";
					foreach($cols as $col){
						if($col =='mrp' || $col =='amount'){
							$fees_table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
							$fees_table .= currency_format($bill_detail[$col])."</td>";
						}else{
							$fees_table .= "<td style='padding:5px;border:1px solid black;'>";
							$fees_table .= $bill_detail[$col]."</td>";
						}
						
					}
					$fees_table .= "</tr>";
					$fees_amount = $fees_amount + $bill_detail['amount'];
				}
			}
			if($particular_table != ""){	
				$particular_table .= "<tr><td colspan='3' style='padding:5px;border:1px solid black;'><strong>Sub Total - Particular</strong></td><td style='text-align:right;padding:5px;border:1px solid black;'><strong>".currency_format($particular_amount)."</strong></td></tr>";
			}
			if($item_table != ""){
				$item_table .= "<tr><td colspan='3' style='padding:5px;border:1px solid black;'><strong>Sub Total - Items</strong></td><td style='text-align:right;padding:5px;border:1px solid black;'><strong>".currency_format($item_amount)."</strong></td></tr>";
			}
			if($treatment_table != ""){	
				$treatment_table .= "<tr><td colspan='3' style='padding:5px;border:1px solid black;'><strong>Sub Total - Treatment</strong></td><td style='text-align:right;padding:5px;border:1px solid black;'><strong>".currency_format($treatment_amount)."</strong></td></tr>";
			}
			if($fees_table != ""){	
				$fees_table .= "<tr><td colspan='3' style='padding:5px;border:1px solid black;'><strong>Sub Total - Fees</strong></td><td style='text-align:right;padding:5px;border:1px solid black;'><strong>".currency_format($fees_amount)."</strong></td></tr>";
			}
		}
		$table = $particular_table . $item_table . $treatment_table .$fees_table;
		$template = str_replace("$col_string",$table, $template);
		
		$balance = $this->patient_model->get_balance_amount($bill['bill_id']);
		$balance = currency_format($balance);
		$template = str_replace("[previous_due]",$balance, $template);
		
		$paid_amount = $this->payment_model->get_paid_amount($bill['bill_id']);
		$paid_amount = currency_format($paid_amount);
		$template = str_replace("[paid_amount]",$paid_amount, $template);
		
		$discount_amount = $this->patient_model->get_discount_amount($bill['bill_id']);
		$discount = currency_format($discount_amount);
		$template = str_replace("[discount]",$discount, $template);
		
		$total_amount = $particular_amount + $item_amount + $treatment_amount + $fees_amount - $discount_amount;
		$total_amount = currency_format($total_amount);
		$template = str_replace("[total]",$total_amount, $template);
		return $template;
	}
	function sms_log(){
		$data['sms_log'] = $this->alert_model->get_sms_log();
		$data['def_dateformate'] = $this->settings_model->get_date_formate();
		$data['def_timeformate'] = $this->settings_model->get_time_formate();
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('sms_log', $data);
		$this->load->view('templates/footer');
	}
	function email_log(){
		$data['email_log'] = $this->alert_model->get_email_log();
		$data['def_dateformate'] = $this->settings_model->get_date_formate();
		$data['def_timeformate'] = $this->settings_model->get_time_formate();
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('email_log', $data);
		$this->load->view('templates/footer');
	}
	function send_email($alert_name,$email_id,$subject,$message,$params=NULL){
	    
		if($alert_name == "" || $email_id == "" ||$message == ""||$subject == ""){
			return;
		}
		$timezone = $this->settings_model->get_time_zone();
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		
		$current_time = date("Y-m-d H:i" );
		
		$from_name = $this->settings_model->get_data_value('from_name');
		$from_email = $this->settings_model->get_data_value('from_email');
		$password = $this->settings_model->get_data_value('email_password');
		$smtp_host = $this->settings_model->get_data_value('smtp_host');
		$smtp_port = $this->settings_model->get_data_value('smtp_port');
		
		if($smtp_host != NULL || $smtp_host != ""){
		
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $smtp_host;
			$config['smtp_port'] = $smtp_port;
			$config['smtp_user'] = $from_email;
			$config['smtp_from_name'] = $from_name;
			$config['smtp_pass'] = $password;
			
			$config['mailtype'] = "html";
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['starttls'] = TRUE;
			$config['newline'] = "\r\n";
			
			$this->email->initialize($config);    
			
			$this->email->from($from_email, $from_name);
			$this->email->to($email_id);
			 
			$this->email->subject($subject);
			$this->email->message($message);
			
			$this->email->send(FALSE);
			
			$response = $this->email->print_debugger();
		}else{
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: '.$from_email. "\r\n";
			if(mail($email_id,$subject,$message,$headers)){
				$response = "OK";
			}else{
				$response = "Some Error Occurred when sending email\r\n";
				$response .= "Email From $from_email\r\n";
				$response .= "Email To $email_id\r\n";
				$response .= "Header $headers\r\n";
				$response .= "Subject $subject\r\n";
				$response .= "Message $message\r\n";
			}
		}
		
		
		$this->alert_model->email_log($alert_name,$email_id,$subject,$message,$response,$current_time,$params);
		
		return;
	}

	
	
}

?>