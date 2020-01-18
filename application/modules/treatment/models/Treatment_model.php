<?php

class Treatment_model extends CI_Model {
	function __construct() {
        $this->load->database();
    }
	function get_treatments($doctor_id = NULL){
		
		if($doctor_id != NULL){
			$doctor = $this->doctor_model->get_doctor_doctor_id($doctor_id);
			$departments = explode(",",$doctor['department_id']);
			$where_departments = "";
			foreach($departments as $department_id){
				if($where_departments != ""){
					$where_departments .= " OR";
				}
				$where_departments .= " departments LIKE '%$department_id%'";
			}
			
			if($where_departments != ""){
				$this->db->where($where_departments);
			}
		}
		$query = $this->db->get("treatments");
		//echo $this->db->last_query();
        return $query->result_array();    
    }
	function get_treatment_name(){
		$treatments = $this->get_treatments();
		$treatment_name = array();
		foreach($treatments as $treatment){
			$treatment_name[$treatment['id']] = $treatment['treatment'];
		}
		return $treatment_name;
    }
    function add_treatment() {
        $data['treatment'] = $this->input->post('treatment');
        $data['price'] = $this->input->post('treatment_price');
		$data['share_type'] = $this->input->post('share_type');
		$data['share_amount'] = $this->input->post('share_amount');
		//$departments = implode(",",$this->input->post('department_id'));
		if($this->input->post('department_id[]')){
			$data['departments'] = implode(",",$this->input->post('department_id[]'));
		}
		//$data['departments'] = $departments;
		$data['tax_id'] = $this->input->post('treatment_rate');
        $this->db->insert('treatments',$data);
		//echo $this->db->last_query();
    }
	function add_visit_treatment($visit_id){
		$treatments = $this->input->post('treatment');
		foreach ($treatments as $treatment_id){
			$data['visit_id'] = $visit_id;
			$data['treatment_id'] = $treatment_id;
			$this->db->insert('visit_treatment_r', $data);
		}
	}
    function get_treatment($id) {    
        $this->db->where("id", $id);
        $query = $this->db->get("treatments");
        return $query->row_array();    
    }
    function edit_treatment($id){
        $data['treatment'] = $this->input->post('treatment');
        $data['price'] = $this->input->post('treatment_price');
		$data['tax_id'] = $this->input->post('treatment_rate');
		$data['sync_status'] = 0;
		$data['share_type'] = $this->input->post('share_type');
		$data['share_amount'] = $this->input->post('share_amount');
		//$departments = implode(",",$this->input->post('department_id'));
		//$data['departments'] = $departments;
		if($this->input->post('department_id[]')){
			$data['departments'] = implode(",",$this->input->post('department_id[]'));
		}
        $this->db->where('id', $id);
        $this->db->update('treatments', $data);
    }
    function delete_treatment($id) {
        $this->db->delete('treatments', array('id' => $id));
    }
    function get_visit_treatment($visit_id){
        $bill_id = patient_model::get_bill_id($visit_id);
        
        $query = $this->db->get_where('bill_detail', array('bill_id' => $bill_id));
        return $query->result_array();
    }
	function get_visit_treatments($visit_id){
		$query = $this->db->get_where('visit_treatment_r', array('visit_id' => $visit_id));
        return $query->result_array();
	}
	function get_treatment_report($from_date,$to_date,$selected_doctors,$selected_treatments){
		$from_date = date('Y-m-d',strtotime($from_date));
		$to_date = date('Y-m-d',strtotime($to_date));
		if(!empty($selected_doctors)){
			$selected_doctors = implode(",",$selected_doctors);
		}else{
			$selected_doctors = "";
		}
		$treatment_name = $this->get_treatment_name();
		$selected_treatments_list = "";
		if(!empty($selected_treatments)){
			foreach($selected_treatments as $selected_treatment){
				$selected_treatments_list .= "'".$treatment_name[$selected_treatment]."'".",";
			}
			//$selected_treatments = implode(",",$selected_treatments);
			$selected_treatments_list = trim($selected_treatments_list,",");
		}else{
			$selected_treatments = "";
		}
		$select_treatment_condition = "";
		if($selected_treatments_list != ""){
		   $select_treatment_condition = "AND particular IN ($selected_treatments_list)";	
		   		  
		}
		$where ="WHERE bill_detail.type='treatment' $select_treatment_condition ";
		$where .= " AND bill.bill_date >= '$from_date'";
		$where .= " AND bill.bill_date <= '$to_date'";
		if($selected_doctors != ''){
				$where .= " AND bill.doctor_id IN ($selected_doctors)";
			}
		/*
		$query = "SELECT patient.name,
						patient.phone_number,
						patient.email,
						bill.bill_date as date,
						bill.bill_time as time,
						doctor.name as doctor,
						bill.bill_id
				  FROM " . $this->db->dbprefix('bill_detail') ." AS bill_detail
					   INNER JOIN " . $this->db->dbprefix('view_patient') ." AS patient ON patient.patient_id = bill.patient_id 
					   LEFT OUTER JOIN " . $this->db->dbprefix('view_doctor') ." AS doctor ON doctor.doctor_id = bill.doctor_id 
					$where		
				 ORDER BY bill.bill_date, bill.bill_time, bill.doctor_id";
				 
				 */
				
				$query="SELECT DISTINCT patient.patient_name,
				 patient.phone_number,
				 patient.email, 
				 bill.bill_date as date, 
				 bill.bill_time as time, 
				 doctor.name as doctor,
				 bill.bill_id, 
				 bill_detail.type 
				 FROM " . $this->db->dbprefix('bill') ." AS bill 
				 INNER JOIN " . $this->db->dbprefix('view_patient') ."  AS patient ON patient.patient_id = bill.patient_id 
				 INNER JOIN " . $this->db->dbprefix('bill_detail') ." AS bill_detail ON bill_detail.bill_id = bill.bill_id 
				 LEFT OUTER JOIN " . $this->db->dbprefix('view_doctor') ." AS doctor ON doctor.doctor_id = bill.doctor_id 
				 $where
				
				 ORDER BY bill.bill_date, bill.bill_time, bill.doctor_id";			 
								 
		//echo $query."<br/>";
		$result = $this->db->query($query);
		$treatment_report = $result->result_array();
		$treatments = $this->get_treatments();
		$i = 0;
		//print_r($treatment_report);
		foreach($treatment_report as $row){
			$bill_id = $row['bill_id'];
			$query = $this->db->get_where('bill_detail', array('bill_id' => $bill_id));
			$bill_details = $query->result_array();
			$treatment_list = "";
			$doctor_share = 0;
			foreach ($bill_details as $bill_detail){
				if($bill_detail['type'] == 'treatment'){
					if($treatment_list != ""){
						$treatment_list .= ",";
					}
					$treatment_list .= $bill_detail['particular'];
					foreach($treatments as $treatment){
						if($bill_detail['particular'] == $treatment['treatment']){
							if($treatment['share_type'] == 'amount'){
								$doctor_share = $doctor_share + $treatment['share_amount'];
							}else{
								$doctor_share = $doctor_share + ($treatment['share_amount']*$treatment['price']/100);
							}
						}
					}
				}
			}
			$treatment_report[$i]['treatment'] = $treatment_list;
			//
			$treatment_report[$i]['doctor_share'] = $doctor_share;
			$i++;
		}

		//echo $this->db->last_query();
		return $treatment_report;
	}
}

?>
