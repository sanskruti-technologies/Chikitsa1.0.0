<?php
class Alert_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	public function get_patient_alert_settings($alert_name,$patient_id){
		$query = $this->db->get_where('patient_alerts',array('alert_name'=>$alert_name,'patient_id'=>$patient_id));
        //echo $this->db->last_query();
		return $query->row_array();
	}
	public function get_all_alerts(){
		$query=$this->db->get('alerts');
		$result=$query->result_array();
		return $result;
	}
	public function set_enabled_alerts(){
		$data['is_enabled'] = 0;
		$this->db->update('alerts', $data);
		$data['is_enabled'] = 1;
		$email_alert = $this->input->post('email_alert');
		foreach($email_alert as $alert){
			$this->db->update('alerts', $data,array('alert_name'=>$alert));
		}
		if($this->input->post('sms_alert')){
			$sms_alert = $this->input->post('sms_alert');
			foreach($sms_alert as $alert){
				$this->db->update('alerts', $data,array('alert_name'=>$alert));
			}
		}
		
	}
	public function get_enabled_alerts($alert_occur = NULL){
		$query=$this->db->get_where('alerts', array('alert_occur' => $alert_occur,'is_enabled'=>1));
		$result=$query->result_array();
		return $result;
	}
	public function is_alert_enabled($alert_name){
		$query=$this->db->get_where('alerts', array('alert_name' => $alert_name,'is_enabled' => 1));
		if ($query->num_rows() > 0){
			$alert = $query->row_array();
			$required_module = $alert['required_module'];
			if($required_module != NULL || $this->module_model->is_active($required_module)){
				return TRUE;
			}else{
				return FALSE;
			}			
		}else{
			return FALSE;
		}
	}
	public function sms_log($send_sms_url,$content,$current_time){
		$data['sms_url'] = $send_sms_url;
        $data['sms_response'] = $content;
		$data['sms_timestamp'] = $current_time;
        
        $this->db->insert('sms_log', $data);
		//echo $this->db->last_query();
	}
	public function email_log($alert_name,$email_id,$subject,$message,$response,$current_time,$params){
		$data['email_alert_name'] = $alert_name;
		$data['email_email_id'] = $email_id;
		$data['email_subject'] = $subject;
		$data['email_message'] = $message;
        $data['email_response'] = $response;
		$data['email_timestamp'] = $current_time;
		$data['email_param'] = $params;
        
        $this->db->insert('email_log', $data);
		//echo $this->db->last_query();
	}
	public function get_sms_log(){
		$this->db->order_by('sms_timestamp','desc');
		$query = $this->db->get_where('sms_log');
		return $query->result_array();
	}
	public function get_email_log(){
		$this->db->order_by('email_timestamp','desc');
		$query = $this->db->get_where('email_log');
		return $query->result_array();
	}
	public function get_alert_email_log($alert_name,$email_id,$date){
		$query = $this->db->get_where('email_log',array('email_alert_name'=>$alert_name,'email_email_id'=>$email_id,'DATE_FORMAT(email_timestamp,"%Y-%m-%d")'=>$date));
		return $query->result_array();
	}
	public function get_alert($alert_name){
		$query = $this->db->get_where('alerts',array('alert_name'=>$alert_name));
		return $query->row_array();
	} 
	public function get_patient_alerts($patient_id){
		$query = $this->db->get_where('patient_alerts',array('patient_id'=>$patient_id));
		return $query->result_array();
	}
	public function set_patient_alerts($patient_id){
		$email_alert = $this->input->post('email_alert');
		
		$data['is_enabled'] = 0;
		$this->db->update('patient_alerts', $data,array('patient_id'=>$patient_id));
		//echo $this->db->last_query()."<br/>";
		foreach($email_alert as $alert_name){
			//Check if alreay exists
			$query = $this->db->get_where('patient_alerts',array('alert_name'=>$alert_name,'patient_id'=>$patient_id));
			$count = $query->num_rows();
			if($count > 0){
				$row = $query->row_array();	
				$patient_alert_id = $row['patient_alert_id'];
				$data['patient_id'] = $patient_id;
				$data['alert_name'] = $alert_name;
				$data['is_enabled'] = 1;
				$this->db->update('patient_alerts', $data,array('patient_alert_id'=>$patient_alert_id));
				//echo $this->db->last_query();
			}else{
				$data['patient_id'] = $patient_id;
				$data['alert_name'] = $alert_name;
				$data['is_enabled'] = 1;
				$this->db->insert('patient_alerts', $data);
				//echo $this->db->last_query();
			}
		}
	}
}
?>
