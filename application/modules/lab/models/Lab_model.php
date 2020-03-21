<?php
class Lab_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
	public function get_tests(){
		$query=$this->db->get('tests');
		$tests = $query->result_array();

		return $tests;
	}
	public function get_test_name_array(){
		$tests = $this->get_tests();
		$test_name_array = array();
		foreach($tests as $test){
			$test_name_array[$test['test_id']] = $test['test_name'];
		}
		return $test_name_array;
	}
	public function get_test($test_id){
		$this->db->where('test_id', $test_id);

		$query=$this->db->get('tests');
		$test = $query->row_array();

		return $test;
	}
	public function insert_test(){
		$data['test_name'] = $this->input->post('test_name');
		$data['test_charges'] = $this->input->post('test_charges');
		$this->db->insert('tests', $data);
	}
	public function edit_test($test_id){
		$data['test_name'] = $this->input->post('test_name');
		$data['test_charges'] = $this->input->post('test_charges');
		$this->db->where('test_id', $test_id);
		$this->db->update('tests', $data);
	}
	public function delete_test($test_id){
		$this->db->delete('tests', array('test_id' => $test_id));
	}
	public function is_test_added($visit_id, $test_id){
		$this->db->where('visit_id', $visit_id);
		$this->db->where('test_id', $test_id);
		$query=$this->db->get('visit_lab_r');
		//echo $this->db->last_query()."<br/>";
		//echo $query->num_rows();
		if($query->num_rows() <= 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function get_bill_detail_id($bill_id,$test_id){

		$query = $this->db->get_where('bill_detail', array('bill_id ' => $bill_id,'test_id' => $test_id));
		$row = $query->row_array();
		return $row['bill_detail_id'];
	}
  public function add_test_visit_r($test_id,$status,$visit_id = NULL,$bill_id = NULL){
    $data['bill_id'] = $bill_id;
    $data['visit_id'] = $visit_id;
    $data['test_id'] = $test_id;
    $data['status'] = $status;
    $this->db->insert('visit_lab_r', $data);
    echo $this->db->last_query()."<br/>";
  }
	public function add_test_visit($visit_id){
		$lab_tests = $this->input->post('lab_test[]');
		//Add Tests
		foreach($lab_tests as $test_id){
			$data['visit_id'] = $visit_id;
			$data['test_id'] = $test_id;
			if(!$this->is_test_added($visit_id, $test_id)){
				$this->db->insert('visit_lab_r', $data);
			}
		}
		//Remove tests
		$this->db->where('visit_id', $visit_id);
		$query=$this->db->get('visit_lab_r');
		$visit_tests = $query->result_array();
		foreach($visit_tests as $visit_test){
			$found = FALSE;
			foreach($lab_tests as $test_id){
				if($visit_test['test_id'] == $test_id){
					$found = TRUE;
				}
			}
			if(!$found){
				$this->db->delete('visit_lab_r', array('test_id' => $visit_test['test_id'],'visit_id'=> $visit_id));

			}
		}
	}
	public function add_single_test_visit($visit_id,$test_id){
		$data['visit_id'] = $visit_id;
		$data['test_id'] = $test_id;
		$this->db->insert('visit_lab_r', $data);
	}
	public function get_visit_tests($visit_id){
		$this->db->where('visit_id', $visit_id);
		$query=$this->db->get('visit_lab_r');
		$tests = $query->result_array();
		
		return $tests;
	}
	public function get_all_visit_tests(){
		$query=$this->db->get('visit_lab_r');
		return $query->result_array();
	}
	public function get_lab_tests($status='pending'){
		if($status == 'pending'){
			$this->db->order_by("date", "asc");
			$this->db->order_by("time", "asc");
		}else{
			$this->db->order_by("report_date", "asc");
		}

		$this->db->where('status', $status);
		$query = $this->db->get('view_lab_tests');
		//echo $this->db->last_query()."<br/>";
		return $query->result_array();
	}
	public function get_visit_test( $visit_test_id){
		$this->db->where('visit_test_id', $visit_test_id);
		$query = $this->db->get('view_lab_tests');
    $visit_test = $query->row_array();
    //print_r($visit_test);
    if($visit_test['visit_id'] != NULL){
      $visit_id = $visit_test['visit_id'];
      $this->db->where('visit_id', $visit_id);
      $query = $this->db->get('view_lab_tests');
    }elseif($visit_test['bill_id'] != NULL){
      $bill_id = $visit_test['bill_id'];
      $this->db->where('bill_id', $bill_id);
      $query = $this->db->get('view_lab_tests');
    }
    //echo $this->db->last_query()."<br/>";
		return $query->result_array();
	}
	public function save_report_file($file_name,$visit_test_id){
		$data['file_name'] = $file_name;
		$data['report_date'] = date('Y-m-d');
		$this->db->where('visit_test_id', $visit_test_id);
		$this->db->update('visit_lab_r', $data);
	}
	public function change_status($visit_test_id,$status){
		$data['status'] = $status;
		$this->db->where('visit_test_id', $visit_test_id);
		$this->db->update('visit_lab_r', $data);
	}
	public function get_new_lab_tests(){
		$query=$this->db->get_where('visit_lab_r',array('status'=>'pending'));
		//echo $this->db->last_query();
		return $query->num_rows();
	}
}
?>