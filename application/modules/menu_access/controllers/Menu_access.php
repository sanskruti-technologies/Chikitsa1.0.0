<?php

class Menu_access extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('patient/patient_model');
        $this->load->model('menu_access_model');
		$this->load->model('admin/admin_model');
		$this->load->model('menu_model');

		$this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('header');

		$this->load->library('form_validation');
		$this->load->library('session');

		$this->lang->load('main');
    }
	/*menu access ------------------------------------------------------------------------------------*/
	public function index(){
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['categories'] = $this->menu_access_model->find_category();
			$data['mymenus'] = $this->menu_access_model->get_mymenu();
			$data['menu_accesss'] = $this->menu_access_model->get_menu_access();
			$data['level'] = $this->session->userdata('category');
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('menu_access', $data);
			$this->load->view('templates/footer');
		}
	}
	public function add_menu_access() {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
			redirect('login/index');
        } else {
			$this->form_validation->set_rules('category', $this->lang->line('category'), 'required');
			if ($this->form_validation->run() === FALSE) {
				$this->index();
			}
			else
			{
				$this->menu_access_model->add_menu_access();
				$this->index();
			}
		}
	}
	public function edit_menu_access($id = NULL) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
			redirect('login/index');
        } else {

            $this->form_validation->set_rules('category', $this->lang->line('category'), 'required');

            if ($this->form_validation->run() === FALSE) {
				$data['categories'] = $this->menu_access_model->find_category();
				$data['menus'] = $this->menu_access_model->get_mymenu();
				$data['category'] = $this->menu_access_model->get_category($id);
				$data['menu_accesses'] = $this->menu_access_model->get_menu_access();

                $this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('menu_access/edit_menu_access', $data);
				$this->load->view('templates/footer');
            } else {
				$this->menu_access_model->update_menu_access();
                $this->index();
            }
      }
   }
	public function category(){
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
			redirect('login/index');
        } else {
			$data['categories'] = $this->menu_access_model->find_category();
      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('category', $data);
            $this->load->view('templates/footer');
		}
	}
	public function add_category() {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('category_name', $this->lang->line('category'), 'required|callback_alpha_dash_space');

			if ($this->form_validation->run() === FALSE) {
				$this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('category_form');
                $this->load->view('templates/footer');
			}else{
				$this->menu_access_model->add_category();
				$this->category();
			}
		}
	}
	function delete_category($id) {
		$this->menu_access_model->delete_category($id);
		$this->category();
	}
	public function edit_category($id = NULL) {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('category_name', $this->lang->line('category'), 'required|callback_alpha_dash_space');

            if ($this->form_validation->run() === FALSE) {
                $data['category'] = $this->menu_access_model->get_category($id);
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('category_form', $data);
                $this->load->view('templates/footer');
            } else {
                $this->menu_access_model->update_category($id);
                $this->category();
            }
        }
    }
	function alpha_dash_space($str){
		if (! preg_match('/^[a-zA-Z\s]+$/', $str)) {
			$this->form_validation->set_message('alpha_dash_space', $this->lang->line('aplha_space'));
			return FALSE;
		} else {
			return TRUE;
		}
	}
	function special_access(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['categories'] = $this->menu_access_model->find_category();
			$data['special_access'] = $this->menu_access_model->get_special_access();
      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('menu_access/special_access',$data);
			$this->load->view('templates/footer');
		}
	}
	function save_special_access(){
		$this->menu_access_model->save_special_access();
		$this->special_access();
	}

}

?>
