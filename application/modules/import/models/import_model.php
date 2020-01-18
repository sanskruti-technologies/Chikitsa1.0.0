<?php

class Import_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->database();
    }
	public function insert_item_full($item_name,$desired_stock,$mrp){
		$data['item_name'] = $item_name;
        $data['desired_stock'] = $desired_stock;
        $data['mrp'] = $mrp;
        $this->db->insert('item',$data);
		return $this->db->insert_id();
	}
	public function insert_medicine_full($medicine_name){
		$data['medicine_name'] = $medicine_name;
        $this->db->insert('medicines',$data);
		return $this->db->insert_id();
	}
	public function insert_doctor_full($doctor_id,$schedule_day,$schedule_date,$from_time,$to_time){
		$data['doctor_id'] = $doctor_id;
		$data['schedule_day'] = $schedule_day;
		$data['schedule_date'] = $schedule_date;
		$data['from_time'] = $from_time;
		$data['to_time'] = $to_time;
        $this->db->insert('doctor_schedule',$data);
		return $this->db->insert_id();
	}
	function insert_new_appointment($patient_id,$doctor_id,$appointment_date,$appointment_start_time,$appointment_end_time){
		$data['appointment_date'] = $appointment_date;
        $data['start_time'] = $appointment_start_time;
        $data['end_time'] = $appointment_end_time;
		$data['visit_id'] = 0;
		//$data['status'] = 'Complete';
		$data['status'] = 'Appointment';
		$data['title']=$this->appointment_model->get_patient_name($patient_id);
		$data['patient_id'] = $patient_id;
		$data['doctor_id'] = $doctor_id;
		$userid = $this->appointment_model->get_doctor_user_id($doctor_id);
		$data['userid'] = $userid;
		$data['clinic_code'] = $this->session->userdata('clinic_code');

		$this->db->insert('appointments', $data);
		//echo $this->db->last_query();
		$appointment_id = $this->db->insert_id();
		return $appointment_id;
	}
}
