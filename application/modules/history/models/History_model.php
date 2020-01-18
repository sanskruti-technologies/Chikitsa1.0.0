<?php

class History_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->database();
    }
    function get_sections() {
        $query = $this->db->get("patient_history_section_master");
		//echo $this->db->last_query();
        return $query->result_array();
    }
	function add_section(){
		$data['section_name'] = $this->input->post('section_name');
		$data['display_in'] = $this->input->post('display_in');
		$data['department_id'] = implode(",",$this->input->post('department_id'));
		
		$this->db->insert('patient_history_section_master', $data);
	}
	function delete_section($section_id){
		$this->db->delete('patient_history_section_master', array('section_id' => $section_id));
	}
	function get_section_by_display_in($display_in){
		$query = $this->db->get_where('patient_history_section_master', array('display_in' => $display_in));
		//echo $this->db->last_query();
        return $query->result_array();
	}
	function get_fields_by_display_in($display_in){
		$sections = $this->get_section_by_display_in($display_in);
		$all_fields = array();
		foreach($sections as $section){
			$section_id = $section['section_id'];
			$fields = $this->get_section_fields($section_id);
			$all_fields = array_merge($all_fields,$fields);
		}
		return $all_fields;
	}
	function get_conditions_by_display_in($display_in){
		$sections = $this->get_section_by_display_in($display_in);
		$all_conditions = array();
		foreach($sections as $section){
			$section_id = $section['section_id'];
			$conditions = $this->get_section_conditions($section_id);
			$all_conditions = array_merge($all_conditions,$conditions);
		}
		return $all_conditions;
	}
	function get_section($section_id){
		$query = $this->db->get_where('patient_history_section_master', array('section_id' => $section_id));
        $row = $query->row_array();
        return $row;
	}
	function get_section_fields($section_id){
		$this->db->where('section_id',$section_id);
		$this->db->order_by("field_order", "asc");

		$query = $this->db->get('patient_history_field_master');
		//echo $this->db->last_query();
        return $query->result_array();
	}
	function get_field_names(){
		$query = $this->db->get('patient_history_field_master');
        $fields = $query->result_array();
		
		$field_names = array();
		foreach($fields as $field){
			$field_names[$field['field_id']] = $field['field_name'];
		}
		return $field_names; 
	}
	function get_section_conditions($section_id){
		$this->db->where('section_id',$section_id);

		$query = $this->db->get('patient_history_field_condition');
		//echo $this->db->last_query();
        return $query->result_array();
	}
	function get_section_condition($condition_id){
		$this->db->where('condition_id',$condition_id);

		$query = $this->db->get('patient_history_field_condition');
		//echo $this->db->last_query();
        return $query->row_array();
	}
	function get_section_field_options($section_id){
		$field_options = array();
		$fields = $this->get_section_fields($section_id);
		foreach($fields as $field){
			$field_id = $field['field_id'];
			$query = $this->db->get_where('patient_history_field_options_master', array('field_id' => $field_id));
			$options =  $query->result_array();
			$option_array = array();
			foreach($options as $option){
				$option_array[] = $option['option_label'];
			}
			$field_options[$field_id] = implode("|",$option_array);
		}
		return $field_options;
	}
	function edit_section($section_id){
		$data['section_name'] = $this->input->post('section_name');
		$data['display_in'] = $this->input->post('display_in');
		$data['department_id'] = implode(",",$this->input->post('department_id'));
		$data['sync_status'] = 0;
		$this->db->update('patient_history_section_master', $data, array('section_id' => $section_id));
	}
	function edit_field_section($section_id){
		//For all fields
		$field_names = $this->input->post('field_name');
		$field_labels = $this->input->post('field_label');
		$field_types = $this->input->post('field_type');
		$field_width = $this->input->post('field_width');
		$field_order = $this->input->post('field_order');
		$field_status = $this->input->post('field_status');
		$field_ids = $this->input->post('field_id');
		$field_options = $this->input->post('field_options');
		
		$field_repeat = $this->input->post('field_repeat');
		$in_group = $this->input->post('in_group');
		
		$group_name = $this->input->post('group_name');
			
		//echo "<br/>";
		//print_r ($field_repeat);
		
		$i = 0;
		//remove fields
        $fields = $this->get_section_fields($section_id);
		foreach($fields as $field){
			$field_exists = FALSE;
			foreach($field_ids as $field_id){
				if($field_id == $field['field_id']){
					$field_exists = TRUE;
				}
			}
			if(!$field_exists){
				$this->db->delete('patient_history_field_master', array('section_id' => $section_id,'field_id'=>$field['field_id']));
				$this->db->delete('patient_history_field_options_master', array('field_id'=>$field['field_id']));
			}
		}
		//print_r($field_names);
		//print_r($field_ids);
		//insert - update fields
		foreach($field_names as $field_name){
			//echo $i."<br/>";
			$data = array();
			$field_id = $field_ids[$i];
			//echo $field_id."<br/>";
			$data['section_id'] = $section_id;
			$data['field_name'] = $field_name;
			$data['field_label'] = $field_labels[$i];
			$data['field_type'] = $field_types[$i];
			$data['field_width'] = $field_width[$i];
			$data['field_order'] = $field_order[$i];
			$data['field_status'] = $field_status[$i];
			
			$data['is_repeat']=0;
			if(is_array($field_repeat) && in_array($i,$field_repeat)){
				$data['is_repeat']=1;
			}
			$data['in_group']=0;
			if(in_array($i,$in_group)){
				$data['in_group']=1;
			}
			
			$data['group_name']=$group_name[$i];
			
			
			$field_option = $field_options[$i];
			$field_option_array = explode("|",$field_option);

			$this->db->where('field_id',$field_id);
			$query = $this->db->get('patient_history_field_master');
			if ($query->num_rows() > 0){
				//if the field id exists 
				$data['sync_status'] = 0;
				$this->db->update('patient_history_field_master', $data, array('field_id' => $field_id));
				//echo $this->db->last_query();
				$this->db->delete('patient_history_field_options_master', array('field_id'=>$field_id));
				//echo $this->db->last_query();
				foreach($field_option_array as $field_opt){
					$opt_data['field_id']=$field_id;
					$opt_data['option_label']=$field_opt;
					$opt_data['option_value']=slugify($field_opt);
					//print_r($opt_data);
					$this->db->insert('patient_history_field_options_master', $opt_data);
					//echo $this->db->last_query();
				}
			}
			else{
				//if the field id does not exists 
				$this->db->insert('patient_history_field_master', $data);
				//echo $this->db->last_query();
				$field_id = $this->db->insert_id();
				foreach($field_option_array as $field_opt){
					$opt_data['field_id']=$field_id;
					$opt_data['option_label']=$field_opt;
					$opt_data['option_value']=slugify($field_opt);
					//print_r($opt_data);
					$this->db->insert('patient_history_field_options_master', $opt_data);
					//echo $this->db->last_query();
				}
			}
			$i++;
		}
		
	}
	function edit_section_conditions($section_id){
		$change_status_of_fields = $this->input->post('change_status_of_field');
		$change_status_to = $this->input->post('change_status_to');
		$field_name = $this->input->post('field_name');
		$condition_type = $this->input->post('condition_type');
		print_r($condition_type);
		$field_has_value = $this->input->post('field_has_value');
		$condition_ids = $this->input->post('condition_id');
		
		
		$i = 0;
		//remove conditions
		$conditions = $this->get_section_conditions($section_id);
		foreach($conditions as $condition){
			$condition_exists = FALSE;
			foreach($condition_ids as $condition_id){
				if($condition_id == $condition['condition_id']){
					$condition_exists = TRUE;
				}
			}
			if(!$condition_exists){
				$this->db->delete('patient_history_field_condition', array('section_id' => $section_id,'condition_id'=>$condition['condition_id']));
				echo $this->db->last_query();
			}
		}
		
		$i = 0;
		foreach($change_status_of_fields as $change_status_of_field){
			$condition_id = $condition_ids[$i];
			$data = array();
			$data['section_id'] = $section_id;
			$data['change_status_of_field'] = $change_status_of_field;
			$data['change_status_to'] = $change_status_to[$i];
			$data['field_name'] = $field_name[$i];
			$data['condition_type'] = $condition_type[$i];
			$data['field_has_value'] = $field_has_value[$i];
			if($condition_type[$i] == 'is_checked'){
				$data['field_is_checked'] = 1;
			}elseif($condition_type[$i] == 'is_unchecked'){
				$data['field_is_checked'] = 0;
			}
			
			$this->db->where('condition_id',$condition_id);
			$query = $this->db->get('patient_history_field_condition');
			if ($query->num_rows() > 0){
				//$data['sync_status'] = 0;
				$this->db->update('patient_history_field_condition', $data, array('condition_id' => $condition_id));
				echo $this->db->last_query();
			}else{
				$this->db->insert('patient_history_field_condition', $data);
				echo $this->db->last_query();
			}
			
			//echo $this->db->last_query();
			$i++;
		}
		
		
	}
	function add_patient_history_details($patient_id){
		$fields = $this->input->post();
		//print_r($fields);
		foreach($fields as $field_name => $field_value){
			if(strpos($field_name,"history_") !== FALSE){
				$field_id = str_replace("history_","",$field_name);
				$data['patient_id'] = $patient_id;
				$data['field_id'] = $field_id;
				if(is_array($field_value)){
					$data['field_value'] = implode(",",$field_value);
				}else{
					$data['field_value'] = $field_value;
				}
				
				$this->db->insert('patient_visit_history_details', $data);
				//echo $this->db->last_query();
			}
		}
	}
	function get_next_id(){
		$next_id = 0;
		$patient_visit_history_details = $this->db->dbprefix('patient_visit_history_details');
		$row = $this->db->query("SELECT MAX(history_id) AS next_id FROM $patient_visit_history_details")->row();
		if ($row) {
			$next_id = $row->next_id; 
		}
		return $next_id+1;
	}
	function add_visit_history_details($visit_id){
		$fields = $this->input->post();
		//print_r($fields);
		foreach($fields as $field_name => $field_value){
			if(strpos($field_name,"history_") !== FALSE){
				$field_name = str_replace("history_","",$field_name);
				$data['visit_id'] = $visit_id;
				$data['field_name'] = $field_name;
				if(is_array($field_value)){
					$data['field_value'] = implode(",",$field_value);
				}else{
					$data['field_value'] = $field_value;
				}
				
				$this->db->insert('patient_visit_history_details', $data);
				//echo $this->db->last_query();
			}
		}
	}
	function update_patient_history_details($patient_id){
		$fields = $this->input->post();
		$query = $this->db->get_where('patient_visit_history_details', array('patient_id' => $patient_id));
		$history = $query->result_array();
		foreach($fields as $field_name => $field_value){
			$found = FALSE;
			if(strpos($field_name,"history_") !== FALSE){
				$field_id = str_replace("history_","",$field_name);
				if(is_array($field_value)){
					$field_value = implode(",",$field_value);
				}
				$data['patient_id'] = $patient_id;
				$data['field_id'] = $field_id;
				$data['field_value'] = $field_value;
				foreach($history as $h){
					$found = FALSE;
					if($h['field_id'] == $field_id ){
						$found = TRUE;
						$this->db->update('patient_visit_history_details', $data, array('field_id' => $field_id,'patient_id' => $patient_id));
						//echo $this->db->last_query()."<br/>";
					}
				}
				if(!$found){
					$this->db->insert('patient_visit_history_details', $data);
					//echo $this->db->last_query();
				}
			}
		}
		foreach($history as $h){
			$found = FALSE;
			foreach($fields as $field_name => $field_value){
				$field_id = str_replace("history_","",$field_name);
				
				if($h['field_id'] == $field_id ){
					$found = TRUE;
				}
			}
			if(!$found){
				$this->db->delete('patient_visit_history_details',  array('field_name' => $h['field_name'],'patient_id' => $patient_id));
				//echo $this->db->last_query();
			}
		}
	}
	function update_visit_history_details($visit_id){
		$fields = $this->input->post();
		
		$query = $this->db->get_where('patient_visit_history_details', array('visit_id' => $visit_id));
		$history = $query->result_array();
		//print_r($fields);
		foreach($fields as $field_name => $field_value){
			
			if(strpos($field_name,"history_") !== FALSE){
				$field_id = str_replace("history_","",$field_name);
				if(is_array($field_value)){
					$field_value = implode(",",$field_value);
				}
				$data['visit_id'] = $visit_id;
				$data['field_id'] = $field_id;
				$data['field_value'] = $field_value;
				$found = FALSE;
				foreach($history as $h){
					$found = FALSE;
					if($h['field_id'] == $field_id ){
						$found = TRUE;
						$this->db->update('patient_visit_history_details', $data, array('field_id' => $field_id,'visit_id' => $visit_id));
						//echo $this->db->last_query()."<br/>";
					}
				}
				if(!$found){
					$this->db->insert('patient_visit_history_details', $data);
					//echo $this->db->last_query();
				}
			}
		}
		foreach($history as $h){
			$found = FALSE;
			foreach($fields as $field_name => $field_value){
				$field_id = str_replace("history_","",$field_name);
				
				if($h['field_id'] == $field_id ){
					$found = TRUE;
				}
			}
			if(!$found){
				$this->db->delete('patient_visit_history_details',  array('field_name' => $h['field_name'],'visit_id' => $visit_id));
				//echo $this->db->last_query();
			}
		}
		/*foreach($fields as $field_name => $field_value){
			if(strpos($field_name,"history_") !== FALSE){
				$field_id = str_replace("history_","",$field_name);
				$data['visit_id'] = $visit_id;
				$data['field_name'] = $field_id;
				$this->db->delete('patient_visit_history_details', $data);
				echo $this->db->last_query();
				if(is_array($field_value)){
					$field_value = implode(",",$field_value);
				}
				$data['field_value'] = $field_value;
				$this->db->insert('patient_visit_history_details', $data);
				echo $this->db->last_query();
			}
		}*/
	}
	function update_visit_history_file_details($visit_id,$file_name,$file_upload){
		$field_id = str_replace("history_","",$file_name);
		print_r( $file_upload);
		$field_value = $file_upload['file_name'];
		
		$data['visit_id'] = $visit_id;
		$data['field_id'] = $field_id;
		$data['field_value'] = $field_value;
		
		$query = $this->db->get_where('patient_visit_history_details', array('visit_id' => $visit_id,'field_id' => $field_id));
		$count = $query->num_rows();
		if($count > 0 ){
			$this->db->update('patient_visit_history_details', $data, array('field_id' => $field_id,'visit_id' => $visit_id));
			echo $this->db->last_query();
		}else{
			$this->db->insert('patient_visit_history_details', $data);
			echo $this->db->last_query();
		}
		
	}
	function update_patient_history_file_details($patient_id,$file_name,$file_upload){
		$field_id = str_replace("history_","",$file_name);
		$field_value = $file_upload['file_name'];
		
		$data['patient_id'] = $patient_id;
		$data['field_id'] = $field_id;
		$data['field_value'] = $field_value;
		
		$query = $this->db->get_where('patient_visit_history_details', array('patient_id' => $patient_id,'field_id' => $field_id));
		$count = $query->num_rows();
		if($count > 0 ){
			$this->db->update('patient_visit_history_details', $data, array('field_id' => $field_id,'patient_id' => $patient_id));
			echo $this->db->last_query();
		}else{
			$this->db->insert('patient_visit_history_details', $data);
			echo $this->db->last_query();
		}
		
	}
	function delete_patient_history_detail($patient_id){
		$this->db->delete('patient_visit_history_details', array('patient_id'=>$patient_id));
	}
	function get_patient_history_details($patient_id){
		$query = $this->db->get_where('patient_visit_history_details', array('patient_id' => $patient_id));
        //echo $this->db->last_query();
		$result = $query->result_array();
		$field_values = array();
		foreach($result as $row){
			$field_values[$row['field_id']]['value'] = $row['field_value'];
			$field_values[$row['field_id']]['history_id'] = $row['history_id'];
		}
		return $field_values;
	}
	function get_visit_history_details($visit_id){
		$query = $this->db->get_where('patient_visit_history_details', array('visit_id' => $visit_id));
		$result = $query->result_array();
		$field_values = array();
		foreach($result as $row){
			$field_values[$row['field_id']]['value'] = $row['field_value'];
			$field_values[$row['field_id']]['history_id'] = $row['history_id'];
		}
		return $field_values;
	}
	function remove_patient_value($history_id,$index){
		$query = $this->db->get_where('patient_visit_history_details', array('history_id' => $history_id));
        $row = $query->row_array();
		$value = $row['field_value'];
		$values = explode(",",$value);
		unset($values[$index]);
		$value = implode(",",$values);
		$data['field_value'] = $value;
		$this->db->update('patient_visit_history_details', $data, array('history_id' => $history_id));
						
	}
	
	function get_field_options_by_display_in($display_in){
		$sections = $this->get_section_by_display_in($display_in);
		$all_field_options = array();
		foreach($sections as $section){
			$section_id = $section['section_id'];
			$fields = $this->get_section_fields($section_id);
			foreach($fields as $field){
				$field_id = $field['field_id'];
				$query = $this->db->get_where('patient_history_field_options_master', array('field_id' => $field_id));
				$options = $query->result_array();
				$all_field_options = array_merge($all_field_options,$options);
			}
		}
		return $all_field_options;
	}
}
?>