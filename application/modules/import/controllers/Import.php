<?php

class Import extends CI_Controller {
	var $patient_import_list;
	var $doctor_import_list;
	var $account_import_list;
	var $patient_count;
	var $appointment_count;
	var $account_count;
	var $item_count;

    function __construct() {
        parent::__construct();

		$this->patient_import_list = array();
		$this->doctor_import_list = array();
		$this->patient_count = 0;
		$this->appointment_count = 0;

		$this->load->library('session');
		$this->load->library('CSVReader');

		$this->load->helper('form');
		$this->load->helper('my_string');
		$this->load->helper('header');

		$this->load->model('admin/admin_model');
		$this->load->model('module/module_model');
		$this->load->model('contact/contact_model');
		$this->load->model('doctor/doctor_model');
		$this->load->model('patient/patient_model');
		$this->load->model('appointment/appointment_model');
		$this->load->model('import/import_model');

		$this->load->model('menu_model');

		$this->lang->load('main');
    }
	/**
	**  This functions import data from csv file
	**/
	function index(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['active_modules'] = $this->module_model->get_active_modules();
			$header_data = get_header_data();
			$this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('import',$data);
			$this->load->view('templates/footer');
		}
	}
	function do_upload() {

        $config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size'] = '4096';
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('csv_file')) {
			$error = array('error' => $this->upload->display_errors());
			return $error;
		} else {
			$data = array('upload_data' => $this->upload->data());
			return $data['upload_data'];
		}
    }
	function upload_csv(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$select_import = $this->input->post('select_import');
			$data['select_import'] = $this->input->post('select_import');
			$data['active_modules'] = $this->module_model->get_active_modules();
			$file_upload = $this->do_upload('csv_file');
			if(isset($file_upload['error'])){
				$data['error'] = $file_upload['error'];
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('import',$data);
				$this->load->view('templates/footer');
			}elseif($file_upload['file_ext']!='.csv'){
				$data['error'] = "The file you are trying to upload is not a .csv file. Please try again.";
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('import',$data);
				$this->load->view('templates/footer');
			}else{
				//Read CSV File
				$file_path = './uploads/'.$file_upload['file_name'];
				$result = $this->csvreader->parse_file($file_path);
				$headers = array_keys($result[1]);
				$row_headers = array();
				foreach($headers as $header){
					array_push($row_headers,slugify($header));
				}
				$data['headers'] = $headers;
				$data['row_headers'] = $row_headers;
				$i=0;
				foreach($result as $row){
					foreach($headers as $header){
						$rows[$i][slugify($header)] = $row[$header];
					}
					$i++;
				}
				$data['file_path'] = $file_path;
				$data['update_existing'] = $this->input->post('update_existing');
				$data['select_import'] = $select_import;
				/*if($select_import == 'appointment_import'){
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper_appointment',$data);
					$this->load->view('templates/footer');
				}elseif($select_import == 'patient_import'){
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper_patient',$data);
					$this->load->view('templates/footer');
				}elseif($select_import == 'account_import'){
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper_account',$data);
					$this->load->view('templates/footer');
				}elseif($select_import == 'medicine_import'){
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper_medicine',$data);
					$this->load->view('templates/footer');
				}elseif($select_import == 'doctor_schedule_import'){
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper_doctor_schedule',$data);
					$this->load->view('templates/footer');
				}else{
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('key_mapper',$data);
					$this->load->view('templates/footer');
				}*/

				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('key_mapper',$data);
				$this->load->view('templates/footer');

			}
		}
	}
	function key_mapper_appointment(){
		$update_existing = $this->input->post('update_existing');
		$file_path = $this->input->post('file_path');

		$result = $this->csvreader->parse_file($file_path);

		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}

		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;

		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}

		$row_headers = $this->input->post('row_headers');
		//print_r($row_headers);
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}
		//print_r($key_map);
		foreach($rows as $row){
			/********Patient************/
			$patient_full_name = "";
			$patient_phone_number = "";
			$patient_id = "";
			$doctor_full_name = "";
			$doctor_id = "";
			$appointment_date = "";
			$appointment_start_time = "";
			$appointment_end_time = "";
			$visit_notes = "";
			$user_id="";
			if(array_key_exists('patient_full_name', $key_map)){
				$patient_full_name = $row[$key_map['patient_full_name']];
			}
			if(array_key_exists('patient_phone_number', $key_map)){
				$patient_phone_number = $row[$key_map['patient_phone_number']];
			}
			if(array_key_exists('patient_id', $key_map)){
				//$patient_id = $this->get_patient($patient_full_name,$patient_phone_number);
				$patient_id=$row[$key_map['patient_id']];
				//echo "<br/>$patient_id<br/>";
			}else{
				$patient_id = $this->get_patient($patient_full_name,$patient_phone_number);
			}
			/********Doctor************/
			if(array_key_exists('doctor_full_name', $key_map)){
				$doctor_full_name = $row[$key_map['doctor_full_name']];
				$doctor_id =  $this->get_doctor($doctor_full_name);
			}
			/********Appointment************/
			if(array_key_exists('appointment_date', $key_map)){
				$appointment_date = date('Y-m-d',strtotime($row[$key_map['appointment_date']]));
			}
			if(array_key_exists('appointment_start_time', $key_map)){
				$appointment_start_time = $row[$key_map['appointment_start_time']];
			}
			if(array_key_exists('appointment_end_time', $key_map)){
				$appointment_end_time = $row[$key_map['appointment_end_time']];
			}
			if(array_key_exists('visit_notes', $key_map)){
				$visit_notes = $row[$key_map['visit_notes']];
			}

			$appointment_id = $this->get_appointment($patient_id,$doctor_id,$appointment_date,$appointment_start_time,$appointment_end_time,$user_id);
			/********Visit************/

			$visit_id = $this->get_visit($appointment_id,$visit_notes);
		}
		$data['patient_import_list']=$this->patient_import_list;
		$data['doctor_import_list']=$this->doctor_import_list;
		$data['appointment_count']=$this->appointment_count;
		$data['patient_count']= $this->patient_count;
		$data['doctor_count']= 0;
		$data['account_count']= 0;
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');

	}
	function get_patient($patient_full_name,$patient_phone_number){
		//Separate Full Name in First Name, Middle Name and Last Name
		$parts = explode(" ",$patient_full_name);
		$first_name = "";
		$middle_name = "";
		$last_name = "";
		//Last Name
		if(sizeof($parts) == 1){
			$last_name = $parts[0];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Middle Name Last Name
		if(sizeof($parts) == 3){
			$first_name = $parts[0];
			$middle_name = $parts[1];
			$last_name = $parts[2];
		}

		//check if patient exists
		$patient_id = $this->patient_model->find_patient_by_name($first_name,$middle_name,$last_name);
		if($patient_id > 0){
			return $patient_id;
		}else{
			$this->patient_import_list[] = "New Patient $patient_full_name added.";
			$contact_id = $this->contact_model->insert_new_contact($first_name,$middle_name,$last_name,$patient_phone_number);
			$patient_id = $this->patient_model->insert_new_patient($contact_id);
			return $patient_id;
		}
	}
	function get_doctor($doctor_full_name){
		$parts = explode(" ",$doctor_full_name);
		$first_name = "";
		$middle_name = "";
		$last_name = "";
		//Last Name
		if(sizeof($parts) == 1){
			$last_name = $parts[0];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Middle Name Last Name
		if(sizeof($parts) == 3){
			$first_name = $parts[0];
			$middle_name = $parts[1];
			$last_name = $parts[2];
		}
		//check if doctor exists
		$doctor_id = $this->doctor_model->find_doctor_by_name($first_name,$middle_name,$last_name);
		if($doctor_id > 0){
			return $doctor_id;
		}else{
			$this->doctor_import_list[] = "New Doctor $doctor_full_name added.";
			$doctor_user_name = slugify($doctor_full_name);

			$contact_id = $this->contact_model->insert_new_contact($first_name,$middle_name,$last_name,"");
			$doctor_id = $this->doctor_model->insert_doctors_full($doctor_full_name,$doctor_user_name,$contact_id);
			return $doctor_id;
		}
	}
	function get_appointment($patient_id,$doctor_id,$appointment_date,$appointment_start_time,$appointment_end_time,$user_id){
		$this->appointment_count++;
		$appointment_id = $this->import_model->insert_new_appointment($patient_id,$doctor_id,$appointment_date,$appointment_start_time,$appointment_end_time,$user_id);
		return $appointment_id;
	}
	function get_visit($appointment_id,$visit_notes){
		$visit_id = $this->appointment_model->insert_new_visit($appointment_id,$visit_notes);
		return $visit_id;
	}
	function key_mapper(){
		$update_existing = $this->input->post('update_existing');
		$file_path = $this->input->post('file_path');

		$result = $this->csvreader->parse_file($file_path);

		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}

		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;

		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}
		$row_headers = $this->input->post('row_headers');
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}
		$this->item_count++;
		$i_count=0;
		foreach($rows as $row){
			$this->import_item($row,$key_map);
			$i_count++;
		}
		//$data['message'] = "Total ".$this->item_count." Items Imported";
		$data['message'] = "Total ".$i_count." Items Imported";

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');

	}
	function key_mapper_doctor(){

		$update_existing = $this->input->post('update_existing');
		$file_path = $this->input->post('file_path');

		$result = $this->csvreader->parse_file($file_path);

		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}

		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;

		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}
		$row_headers = $this->input->post('row_headers');
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}
		$this->item_count++;
		$i_count=0;
		foreach($rows as $row){
			$this->import_doctor($row,$key_map);
			$i_count++;
		}
		//$data['message'] = "Total ".$this->item_count." Items Imported";
		$data['message_doctor'] = "Total ".$i_count." Doctor Schedule Imported";

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');

	}
	function key_mapper_medicine(){
		$update_existing = $this->input->post('update_existing');
		$file_path = $this->input->post('file_path');

		$result = $this->csvreader->parse_file($file_path);

		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}

		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;

		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}
		$row_headers = $this->input->post('row_headers');
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}
		$this->item_count++;
		$i_count=0;
		foreach($rows as $row){
			$this->import_medicine($row,$key_map);
			$i_count++;
		}
		//$data['message'] = "Total ".$this->item_count." Items Imported";
		$data['message_medicine'] = "Total ".$i_count." medicine Imported";

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');

	}
	function key_mapper_patient(){
		$update_existing = $this->input->post('update_existing');

		$file_path = $this->input->post('file_path');

		$result = $this->csvreader->parse_file($file_path);

		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}

		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;

		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}

		$row_headers = $this->input->post('row_headers');
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}

		foreach($rows as $row){
			$display_id = "";
			$patient_full_name = "";
			$display_name = "";
			$gender = "";
			$address_type = "";
			$address_line_1 = "";
			$address_line_2 = "";
			$area = "";
			$city = "";
			$state = "";
			$area_postal_code = "";
			$country = "";
			$dob = "";
			$reference = "";
			$phone_number = "";
			$second_number = "";
			$email = "";
			if(array_key_exists('display_id', $key_map)){
				$display_id = $row[$key_map['display_id']];
			}
			if(array_key_exists('patient_full_name', $key_map)){
				$patient_full_name = $row[$key_map['patient_full_name']];
			}
			if(array_key_exists('display_name', $key_map)){
				$display_name = $row[$key_map['display_name']];
			}
			if(array_key_exists('gender', $key_map)){
				$gender = $row[$key_map['gender']];
			}
			if(array_key_exists('address_type', $key_map)){
				$address_type = $row[$key_map['address_type']];
			}
			if(array_key_exists('address_line_1', $key_map)){
				$address_line_1 = $row[$key_map['address_line_1']];
			}
			if(array_key_exists('address_line_2', $key_map)){
				$address_line_2 = $row[$key_map['address_line_2']];
			}
			if(array_key_exists('area', $key_map)){
				$area = $row[$key_map['area']];
			}
			if(array_key_exists('city', $key_map)){
				$city = $row[$key_map['city']];
			}
			if(array_key_exists('state', $key_map)){
				$state = $row[$key_map['state']];
			}
			if(array_key_exists('area_postal_code', $key_map)){
				$area_postal_code = $row[$key_map['area_postal_code']];
			}
			if(array_key_exists('country', $key_map)){
				$country = $row[$key_map['country']];
			}
			if(array_key_exists('dob', $key_map)){
				$dob = $row[$key_map['dob']];
			}
			if(array_key_exists('reference', $key_map)){
				$reference = $row[$key_map['reference']];
			}
			if(array_key_exists('phone_number', $key_map)){
				$phone_number = $row[$key_map['phone_number']];
			}
			if(array_key_exists('second_number', $key_map)){
				$second_number = $row[$key_map['second_number']];
			}
			if(array_key_exists('email', $key_map)){
				$email = $row[$key_map['email']];
			}
			$patient_id = $this->get_patient_full($display_id,$patient_full_name,$display_name,$gender,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country,$dob,$reference,$phone_number,$second_number,$email,$update_existing);
		}
		$data['patient_import_list'] = $this->patient_import_list;
		$data['doctor_import_list'] = array();
		$data['patient_count']= $this->patient_count;
		$data['appointment_count']= 0;
		$data['doctor_count']= 0;
		$data['account_count']= 0;
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');
	}
	function key_mapper_account(){

		$update_existing = $this->input->post('update_existing');

		//$rows = $this->input->post('rows');

		$file_path = $this->input->post('file_path');
		$result = $this->csvreader->parse_file($file_path);
		$headers = array_keys($result[1]);
		$row_headers = array();
		foreach($headers as $header){
			array_push($row_headers,slugify($header));
		}
		$data['headers'] = $headers;
		$data['row_headers'] = $row_headers;
		$i=0;
		foreach($result as $row){
			foreach($headers as $header){
				$rows[$i][slugify($header)] = $row[$header];
			}
			$i++;
		}

		$row_headers = $this->input->post('row_headers');
		foreach($row_headers as $header){
			$key_map[$this->input->post($header)]=$header;
		}

		foreach($rows as $row){
			$account_group = "";
			$title = "";
			$full_name = "";
			$first_name = "";
			$middle_name = "";
			$last_name = "";
			$address_type = "";
			$address_line_1 = "";
			$address_line_2 = "";
			$area = "";
			$city = "";
			$state = "";
			$area_postal_code = "";
			$country = "";
			$phone_number = "";
			$residence_number = "";
			$email = "";
			$tax_id = "";
			$opening_balance_crdr = "";
			$opening_balance_amount = "";
			$opening_balance_as_on = "";
			$clinic_name = "";
			$clinic_number = "";
			$affiliation_date = "";

			if(array_key_exists('account_group', $key_map)){
				$account_group = $row[$key_map['account_group']];
			}
			if(array_key_exists('title', $key_map)){
				$title = $row[$key_map['title']];
			}
			if(array_key_exists('full_name', $key_map)){
				$full_name = $row[$key_map['full_name']];
			}
			if(array_key_exists('first_name', $key_map)){
				$first_name = $row[$key_map['first_name']];
			}
			if(array_key_exists('middle_name', $key_map)){
				$middle_name = $row[$key_map['middle_name']];
			}
			if(array_key_exists('last_name', $key_map)){
				$last_name = $row[$key_map['last_name']];
			}
			if(array_key_exists('address_type', $key_map)){
				$address_type = $row[$key_map['address_type']];
			}
			if(array_key_exists('address_line_1', $key_map)){
				$address_line_1 = $row[$key_map['address_line_1']];
			}
			if(array_key_exists('address_line_2', $key_map)){
				$address_line_2 = $row[$key_map['address_line_2']];
			}
			if(array_key_exists('area', $key_map)){
				$area = $row[$key_map['area']];
			}
			if(array_key_exists('city', $key_map)){
				$city = $row[$key_map['city']];
			}
			if(array_key_exists('state', $key_map)){
				$state = $row[$key_map['state']];
			}
			if(array_key_exists('area_postal_code', $key_map)){
				$area_postal_code = $row[$key_map['area_postal_code']];
			}
			if(array_key_exists('country', $key_map)){
				$country = $row[$key_map['country']];
			}
			if(array_key_exists('phone_number', $key_map)){
				$phone_number = $row[$key_map['phone_number']];
			}
			if(array_key_exists('residence_number', $key_map)){
				$residence_number = $row[$key_map['residence_number']];
			}
			if(array_key_exists('email', $key_map)){
				$email = $row[$key_map['email']];
			}
			if(array_key_exists('tax_id', $key_map)){
				$tax_id = $row[$key_map['tax_id']];
			}
			if(array_key_exists('clinic_name', $key_map)){
				$clinic_name = $row[$key_map['clinic_name']];
			}
			if(array_key_exists('clinic_number', $key_map)){
				$clinic_number = $row[$key_map['clinic_number']];
			}
			if(array_key_exists('affiliation_date', $key_map)){
				$affiliation_date = $row[$key_map['affiliation_date']];
			}
			$account_id = $this->set_account($account_group,$title,$full_name,$first_name,$middle_name,$last_name,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country,$phone_number,$residence_number,$email,$tax_id,$opening_balance_crdr,$opening_balance_amount,$opening_balance_as_on,$update_existing);
			$this->set_account_detail($account_id,'clinic_name',$clinic_name);
			$this->set_account_detail($account_id,'clinic_number',$clinic_number);
			$this->set_account_detail($account_id,'affiliation_date',$affiliation_date);
		}
		$data['account_import_list'] = $this->account_import_list;
		$data['doctor_import_list'] = array();
		$data['patient_import_list'] = array();
		$data['account_count']= $this->account_count;
		$data['doctor_count']= 0;
		$data['patient_count']= 0;
		$data['appointment_count']= 0;
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('final_view',$data);
		$this->load->view('templates/footer');
	}
	function set_account($account_group,$title,$full_name,$first_name,$middle_name,$last_name,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country,$phone_number,$residence_number,$email,$tax_id,$opening_balance_crdr,$opening_balance_amount,$opening_balance_as_on,$update_existing){
		$this->load->model('account/account_model');
		//Separate Full Name in First Name, Middle Name and Last Name
		if($full_name != ""){
			$parts = explode(" ",$full_name);
			$first_name = "";
			$middle_name = "";
			$last_name = "";
			//Last Name
			if(sizeof($parts) == 1){
				$last_name = $parts[0];
			}
			//First Name Last Name
			if(sizeof($parts) == 2){
				$first_name = $parts[0];
				$last_name = $parts[1];
			}
			//First Name Middle Name Last Name
			if(sizeof($parts) == 3){
				$first_name = $parts[0];
				$middle_name = $parts[1];
				$last_name = $parts[2];
			}
			//First Name Middle Name Last Name
			if(sizeof($parts) == 4){
				$title = $parts[0];
				$first_name = $parts[1];
				if($parts[2] != ""){
					$middle_name = $parts[2];
					$last_name = $parts[3];
				}else{
					$last_name = $parts[2];
				}
			}
			if(sizeof($parts) == 5){
				$title = $parts[0];
				$first_name = $parts[1];
				$middle_name = $parts[2];
				$last_name = $parts[3];

			}
		}else{
			$full_name = $first_name." ".$middle_name." ".$last_name;
		}

		if($tax_id != NULL){
			$account_id = $this->account_model->find_account_by_tax_id($tax_id);
			if($account_id != 0){
				if($update_existing == 1){
					//Update Account
					$this->account_import_list[] = "Account <strong>$full_name</strong> updated.";
					$this->account_count++;
				}
				return $account_id;
			}
		}



		$second_number = "";
		$display_name = $full_name;
		$account_group = $this->account_model->get_account_group_by_name($account_group);
		if(isset($account_group)){
			$this->account_import_list[] = "New Account <strong>$full_name</strong> added.";
			$this->account_count++;
			$account_group_id = $account_group['account_group_id'];
			$contact_id = $this->contact_model->insert_contact_full($first_name,$middle_name,$last_name,$phone_number,$second_number,$display_name,$email,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country);
			$this->contact_model->insert_contact_details_full($contact_id,'mobile',$phone_number,1);
			$this->contact_model->insert_contact_details_full($contact_id,'landline',$residence_number,0);
			$account_id = $this->account_model->insert_account_full($contact_id,$account_group_id,$tax_id,$opening_balance_crdr,$opening_balance_amount,$opening_balance_as_on);
			return $account_id;
		}
	}
	function set_account_detail($account_id,$field_name,$field_value){
		$this->account_model->insert_account_details_full($account_id,$field_name,$field_value);
	}
	function get_patient_full($display_id,$patient_full_name,$display_name,$gender,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country,$dob,$reference,$phone_number,$second_number,$email,$update_existing){
		$this->patient_count++;

		//Separate Full Name in First Name, Middle Name and Last Name
		$parts = explode(" ",$patient_full_name);
		$first_name = "";
		$middle_name = "";
		$last_name = "";
		//Last Name
		if(sizeof($parts) == 1){
			$last_name = $parts[0];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Last Name
		if(sizeof($parts) == 2){
			$first_name = $parts[0];
			$last_name = $parts[1];
		}
		//First Name Middle Name Last Name
		if(sizeof($parts) == 3){
			$first_name = $parts[0];
			$middle_name = $parts[1];
			$last_name = $parts[2];
		}
		if($display_id != NULL){
			$patient_id = $this->patient_model->find_patient_by_display_id($display_id);
			if($patient_id != 0){
				if($update_existing == 1){
					//Update Patient
					$patient = $this->patient_model->get_patient_detail($patient_id);
					$contact_id = $patient['contact_id'];
					$this->contact_model->update_contact_full($contact_id,$first_name,$middle_name,$last_name,$phone_number,$second_number,$display_name,$email,$address_type,$address_line_1,$address_line_2,$city,$state,$area_postal_code,$country);
					$this->patient_model->update_patient_full($patient_id,$display_id,$reference,$gender,$dob);
				}
				return $patient_id;
			}
		}



		//check if patient exists
		$patient_id = $this->patient_model->find_patient_by_name($first_name,$middle_name,$last_name);
		if($patient_id != 0 && $display_id == NULL){
			if($update_existing == 1){
				//Update Patient
				$patient = $this->patient_model->get_patient_detail($patient_id);
				$contact_id = $patient['contact_id'];

				$this->contact_model->update_contact_full($contact_id,$first_name,$middle_name,$last_name,$phone_number,$second_number,$display_name,$email,$address_type,$address_line_1,$address_line_2,$city,$state,$area_postal_code,$country);
				$this->patient_model->update_patient_full($patient_id,$display_id,$reference,$gender,$dob);
			}
			return $patient_id;
		}else{
			$this->patient_import_list[] = "New Patient $patient_full_name added.";
			$contact_id = $this->contact_model->insert_contact_full($first_name,$middle_name,$last_name,$phone_number,$second_number,$display_name,$email,$address_type,$address_line_1,$address_line_2,$area,$city,$state,$area_postal_code,$country);
			$this->contact_model->insert_contact_details_full($contact_id,'mobile',$phone_number,1);
			$this->contact_model->insert_contact_details_full($contact_id,'mobile',$second_number,0);
			$patient_id = $this->patient_model->insert_patient_full($contact_id,$display_id,$reference,$gender,$dob);
			return $patient_id;
		}
	}
	function import_item($row,$key_map){
		$item_name = "";
		$desired_stock = "";
		$mrp = "";
		if(array_key_exists('item_name', $key_map)){
			$item_name = $row[$key_map['item_name']];
		}
		if(array_key_exists('desired_stock', $key_map)){
			$desired_stock = $row[$key_map['desired_stock']];
		}
		if(array_key_exists('mrp', $key_map)){
			$mrp = $row[$key_map['mrp']];
		}
		$item_id = $this->import_model->insert_item_full($item_name,$desired_stock,$mrp);
		return $item_id;
	}
	function import_medicine($row,$key_map){
		$medicine_name = "";
		if(array_key_exists('medicine_name', $key_map)){
			$medicine_name = $row[$key_map['medicine_name']];
		}
		$medicine_id = $this->import_model->insert_medicine_full($medicine_name);
		return $medicine_id;
	}
	function import_doctor($row,$key_map){
		$doctor_name = "";
		$schedule_day = "";
		$schedule_date = "";
		$from_time = "";
		$to_time = "";
		if(array_key_exists('doctor_name', $key_map)){
			$doctor_name = $row[$key_map['doctor_name']];
			if (strpos($doctor_name, 'Dr.') !== false)
			{
				//echo 'yes';
				$doctor_name=str_replace("Dr. ","",$doctor_name);
			}
			//echo $doctor_name;
			$doctor_id =  $this->get_doctor($doctor_name);
			//echo $doctor_id;
		}
		if(array_key_exists('schedule_day', $key_map)){
			$schedule_day = $row[$key_map['schedule_day']];
		}
		if(array_key_exists('schedule_date', $key_map)){
			$schedule_date=date('Y-m-d',strtotime($row[$key_map['schedule_date']]));
			//echo $schedule_date;
		}
		if(array_key_exists('from_time', $key_map)){
			$from_time = $row[$key_map['from_time']];
		}
		if(array_key_exists('to_time', $key_map)){
			$to_time = $row[$key_map['to_time']];
		}
		$schedule_id = $this->import_model->insert_doctor_full($doctor_id,$schedule_day,$schedule_date,$from_time,$to_time);
		return $schedule_id;
	}
}

?>
