<?php

class Menu_access_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_images($patient_id) {
        $this->db->order_by("id", "desc");
        $query = $this->db->get_where('visit_img', array('patient_id' => $patient_id));
        return $query->result_array();
    }

    function insert_image($path) {
        $patient_id = $this->input->post('patient_id');
        $visit_id = $this->input->post('visit_id');
		
        $data['patient_id'] = $patient_id;
        $data['visit_img_path'] = 'patient_images/'.$path;
        $data['visit_id'] = $visit_id;
        $data['img_name'] = date("d-m-Y");
        $this->db->insert('visit_img', $data);
		if($this->db->_error_message()){
			log_message('error',$this->db->_error_message());
		}
    }
	
	
	/*menu access-------------------------------------------------------------------------------------------*/
	public function get_mymenu() {
		$query = $this->db->get("navigation_menu");
		//$query = $this->db->get_where('navigation_menu', array('parent_name' => ''));
		//echo $this->db->last_query();
        return $query->result_array();
    }
	public function find_navigation_menu() {	
         $query = $this->db->get("navigation_menu");
        return $query->result_array();

    }
	function add_menu_access(){
		$mymenus=$this->input->post('navigation_menu');
		foreach($mymenus as $navigation_menu){
			$data['category_id'] = $this->input->post('category');
			$data['menu_id'] = $mymenu;
			$data['allow'] = 1;
			$this->db->insert('menu_access', $data);
		}			
	}
	function get_menu_access(){
		$this->db->select('*');
		$this->db->from('menu_access');
		$this->db->join('navigation_menu', 'menu_access.menu_name = navigation_menu.menu_name');
		$query = $this->db->get();
		//echo $this->db->last_query();
		//$query = $this->db->get("menu_access");
        return $query->result_array();	
	}
	function update_menu_access(){
		//$mymenus=$this->input->post('mymenu');
		$query = $this->db->get("navigation_menu");
		//$query = $this->db->get_where('navigation_menu', array('parent_name' => ''));
		
        $mymenus = $query->result_array();
		
		foreach($mymenus as $mymenu){
			$category=$this->input->post('category');
			$data['category_name'] = $category;
			$data['menu_name']= $mymenu['menu_name'];
			$menu_name = $mymenu['menu_name'];
			$menu_text = $mymenu['menu_text'];
			if($this->input->post($menu_name)){
				$data['allow'] = 1;
				$udata['allow'] = 1;
			}else{
				$data['allow'] = 0;
				$udata['allow'] = 0;
			}
			$query = $this->db->get_where('menu_access', array('menu_name' => $menu_name,'category_name' => $category));
			//echo $this->db->last_query();
			if ($query->num_rows() > 0){
				if($this->input->post($menu_name)){
				$udata['allow'] = 1;
				//echo $menu_name;
				}else{
				$udata['allow'] = 0;
				}
				$udata['sync_status'] = 0;
				$this->db->update('menu_access', $udata, array('menu_name' => $menu_name,'category_name' => $category));
				//echo $this->db->last_query();
			}
			else{
			$this->db->insert('menu_access', $data);
			//echo $this->db->last_query();
			}
		}			
	}
	
	/*category Master ---------------------------------------------------------------------------------------*/
    public function get_category($id) {
            $query = $this->db->get_where('user_categories', array('id' => $id));
            return $query->row_array();
        }
		
	 public function update_category($category_id) {
		
		$data['id'] = $category_id;
		$data['category_name'] = $this->input->post('category_name');
		$data['sync_status'] = 0;
		$this->db->update('user_categories', $data, array('id' =>  $category_id));		
	}
	function add_category() {       
        $data['category_name'] = $this->input->post('category_name');	
        $this->db->insert('user_categories', $data);	
		return $this->db->insert_id();		
    }
	function delete_category($id) {
        $this->db->delete('user_categories', array('id' => $id));
    }
	public function find_category() {	
        $query = $this->db->get("user_categories");
        return $query->result_array();

    }
	function save_special_access(){
		$this->db->delete('special_access', array('access_name' => 'back_date_visit'));
		if($this->input->post('back_date_visit')){
			$back_date_visit = $this->input->post('back_date_visit');
			$data['access_name'] = 'back_date_visit';	
			foreach($back_date_visit as $category_id => $category_name){
				$data['category_name'] = $category_name;	
				$data['allow'] = 1;	
				$this->db->insert('special_access', $data);	
			}
		}
	}
	function get_special_access(){
		$query = $this->db->get("special_access");
        return $query->result_array();
	}
	
		
	function access_granted($access_name, $category_name){
		$query = $this->db->get_where('special_access', array('access_name' => $access_name,'category_name'=>$category_name));
		$special_access = $query->row_array();
		
		$num = $query->num_rows();
		
		if($num > 0){
			if($special_access['allow'] == 1){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
}

?>
