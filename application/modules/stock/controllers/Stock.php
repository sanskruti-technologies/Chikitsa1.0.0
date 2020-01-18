<?php

class Stock extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('currency_helper');
        $this->load->helper('form');
        $this->load->helper('header');

		$this->load->library('form_validation');
        $this->load->library('session');

		$this->load->model('stock_model');
		$this->load->model('menu_model');
        $this->load->model('patient/patient_model');
		$this->load->model('contact/contact_model');
        $this->load->model('settings/settings_model');
		$this->load->model('module/module_model');
		$this->load->model('admin/admin_model');

		$this->lang->load('main');
    }
	function ajax_prescribed_medicine($patient_id){

		$this->load->model('prescription/prescription_model');
		$prescriptions = $this->prescription_model->get_last_prescription($patient_id);
		$ajax_data = array();
		foreach($prescriptions as $prescription){
			$row = array();
			$available_quantity=$this->stock_model->get_available_medicine($prescription['medicine_id']);
			$medicine = $this->prescription_model->get_medicine($prescription['medicine_id']);
			$quantity = ($prescription['freq_morning']+$prescription['freq_afternoon']+$prescription['freq_night']) * $prescription['for_days'];

			$row['available_quantity']=$available_quantity;
			$row['medicine_id'] = $prescription['medicine_id'];
			$row['medicine_name'] = $medicine['medicine_name'];
			$row['quantity'] = $quantity;
			$row['freq_morning'] = $prescription['freq_morning'];
			$row['freq_afternoon'] = $prescription['freq_afternoon'];
			$row['freq_night'] = $prescription['freq_night'];
			$row['for_days'] = $prescription['for_days'];
			$ajax_data[] = $row;
		}

		echo json_encode($ajax_data);
	}
	function ajax_select_medicine(){
		$medicines=$this->stock_model->get_medicines_not_link();
		$ajax_data = array();
		foreach($medicines as $medicine){
			$row = array();
			$row['medicine_id'] = $medicine['medicine_id'];
			$row['medicine_name'] = $medicine['medicine_name'];
			$ajax_data[] = $row;
		}

		echo json_encode($ajax_data);
	}
	function change_link_medicine(){
		$medicine_id=$this->input->post('select_medicine');
		$item_id=$this->input->post('item_id');
		$this->stock_model->update_item_medicine_id($medicine_id,$item_id);
		 $this->item();
	}
	function remove_link_medicine($item_id){
		$this->stock_model->update_item_medicine_id(NULL,$item_id);
		 $this->item();
	}
	/**Stock Item*/
    public function item($message = NULL) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $data['items'] = $this->stock_model->get_items();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();

      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('item', $data);
            $this->load->view('templates/footer');
        }
    }
	public function add_item() {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			//$this->form_validation->set_rules('item_name', $this->lang->line('item_name'), 'required');
			$this->form_validation->set_rules('item_name', $this->lang->line('item_name'), 'trim|required|is_unique[item.item_name]');
			$this->form_validation->set_rules('barcode', $this->lang->line('barcode'), 'trim|required|is_unique[item.barcode]');
			$this->form_validation->set_rules('desired_stock', $this->lang->line('desired_stock'), 'required|greater_than[0]');
			$this->form_validation->set_rules('mrp', $this->lang->line('mrp'), 'required|greater_than[0]');
			$data['items'] = $this->stock_model->get_items();
			$active_modules = $this->module_model->get_active_modules();
			$data['active_modules'] = $active_modules;
			$data['medicine_name'] = "";
			$data['medicine_id'] = "";
        	if ($this->form_validation->run() === FALSE) {
				if (in_array("prescription", $active_modules)){
					$this->load->model('prescription/prescription_model');
					$data['medicines'] = $this->prescription_model->get_medicines();
				}
                $header_data = get_header_data();
                $this->load->view('templates/header',$header_data);
                $this->load->view('templates/menu');
                $this->load->view('stock/item_form',$data);
                $this->load->view('templates/footer');
            } else {
                $item_id = $this->stock_model->insert_item();
				if($this->input->post('add_as_medicine') == 'add_as_medicine'){
					if (in_array("prescription", $active_modules)) {
						$this->load->model('prescription/prescription_model');
						$medicine_name = $this->input->post('item_name');
						$medicine_id = $this->prescription_model->add_medicine($medicine_name);
						$this->stock_model->link_medicine_to_item($item_id,$medicine_id);
					}
				}elseif($this->input->post('medicine_id')){
					$medicine_id = $this->input->post('medicine_id');
					$this->stock_model->link_medicine_to_item($item_id,$medicine_id);
				}
                $this->item();
            }
        }
    }
	public function ajax_check_item_in_use() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
		} else {
			$item_id=$this->input->post('item_id');
           $message= $this->stock_model->is_item_inuse($item_id);
		   echo json_encode($message);
		}
	}
    public function delete_item() {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$item_id=$this->input->post('item_id');
          	$this->stock_model->delete_item($item_id);
			//$this->item();

        }
    }
    public function edit_item($item_id = NULL) {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('item_name', $this->lang->line('item_name'), 'required');
			$this->form_validation->set_rules('desired_stock',  $this->lang->line('desired_stock'), 'required|greater_than[0]');
			$this->form_validation->set_rules('mrp', $this->lang->line('mrp'), 'required|greater_than[0]');

            if ($this->form_validation->run() === FALSE) {
                $data['item'] = $this->stock_model->get_item($item_id);
				$data['medicine_id']=$this->stock_model->get_medicine_id($item_id);
				$active_modules = $this->module_model->get_active_modules();
				$data['active_modules'] = $active_modules;
				if (in_array("prescription", $active_modules)){
					$this->load->model('prescription/prescription_model');
					$data['medicines'] = $this->prescription_model->get_medicines();
					$data['medicine_name']=	$this->prescription_model->get_medicine_name($data['medicine_id']);
				}
                $header_data = get_header_data();
                $this->load->view('templates/header',$header_data);
                $this->load->view('templates/menu');
                $this->load->view('stock/item_form', $data);
                $this->load->view('templates/footer');
            } else {
				$this->stock_model->update_item();
				$active_modules = $this->module_model->get_active_modules();
				if (in_array("prescription", $active_modules)) {
					$this->load->model('prescription/prescription_model');
					if($this->input->post('add_as_medicine') == 'add_as_medicine'){
						$this->load->model('prescription/prescription_model');
						$medicine_name = $this->input->post('item_name');
						$medicine_id = $this->prescription_model->add_medicine($medicine_name);
						$this->stock_model->link_medicine_to_item($item_id,$medicine_id);
					}else{
						$medicine_id = $this->input->post('medicine_id');
						$this->stock_model->link_medicine_to_item($item_id,$medicine_id);
					}
				}
                $this->item();
            }
        }
    }
	/**Stock Supplier*/
    public function supplier() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['contact_details'] = $this->contact_model->get_all_contact_details();
			$data['suppliers'] = $this->stock_model->get_suppliers();
            $header_data = get_header_data();
            $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('stock/supplier', $data);
            $this->load->view('templates/footer');
        }
    }
	public function add_supplier() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('first_name', $this->lang->line('first_name'), 'callback_validate_name');
            $this->form_validation->set_rules('last_name', $this->lang->line('last_name'), 'callback_validate_name');
			$this->form_validation->set_rules('phone_number', $this->lang->line('phone_number'), 'required');

            if ($this->form_validation->run() === FALSE) {
              $header_data = get_header_data();
              $this->load->view('templates/header',$header_data);
				      $this->load->view('templates/menu');
				$this->load->view('stock/supplier_form');
				$this->load->view('templates/footer');
			}else{
				$contact_id = $this->contact_model->insert_contact();
				$this->stock_model->insert_supplier($contact_id);
				$this->supplier();
			}
		}
	}
    public function edit_supplier($supplier_id = NULL) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
          $this->form_validation->set_rules('first_name', $this->lang->line('first_name'), 'callback_validate_name');
            $this->form_validation->set_rules('last_name', $this->lang->line('last_name'), 'callback_validate_name');
			$this->form_validation->set_rules('phone_number', $this->lang->line('phone_number'), 'required');

            if ($this->form_validation->run() === FALSE) {
				$data['supplier_id'] = $supplier_id;
                $data['supplier'] = $this->stock_model->get_supplier($supplier_id);

                $header_data = get_header_data();
                $this->load->view('templates/header',$header_data);
                $this->load->view('templates/menu');
                $this->load->view('stock/supplier_form', $data);
                $this->load->view('templates/footer');
            } else {
				$this->contact_model->update_contact();
                $this->supplier();
            }
        }
    }
	public function validate_name(){
	   if($this->input->post('first_name') || $this->input->post('last_name')){
			return TRUE;
	   }else{
	        $this->form_validation->set_message('validate_name', $this->lang->line('first_or_last'));
			return FALSE;
	   }
	}
    public function delete_supplier($supplier_id = NULL) {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->stock_model->delete_supplier($supplier_id);
            $this->supplier();
        }
    }
	/**Purchase Register*/
    public function purchase($items = 0) {

		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
       		if($this->input->post('from_date')){
				$data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
			}else{
				$data['from_date'] = NULL;
			}
			if($this->input->post('to_date')){
				$data['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
			}else{
				$data['to_date'] = NULL;
			}
			if($this->input->post('items')){
				$items = $this->input->post('items');
				$selected_items = $items;
				$items = implode(",",$items);
				$data['items_csv'] = str_replace(",","_",$items);
			}else{
				$data['items_csv'] = $items;
				$items = str_replace("_",",",$items);
				$selected_items = explode(",",$items);
			}
			$data['selected_items'] = $selected_items;

			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
            $data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            $data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['suppliers'] = $this->stock_model->get_suppliers();
			$data['items'] = $this->stock_model->get_items();
			$data['purchases'] = $this->stock_model->get_purchases($items,$from_date,$to_date);
			//print_r($data['purchases']);

      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('purchase', $data);
            $this->load->view('templates/footer');

        }
    }
	public function add_purchase(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('purchase_date', $this->lang->line('purchase_date'), 'required');
            $this->form_validation->set_rules('bill_no', $this->lang->line('bill')." ". $this->lang->line('no'), 'required');
			$this->form_validation->set_rules('item_name', $this->lang->line('item_name'), 'required');
            $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
            $this->form_validation->set_rules('supplier_name',$this->lang->line('supplier_name'), 'required');
            $this->form_validation->set_rules('cost_price', $this->lang->line('cost_price'), 'required|numeric');

            if ($this->form_validation->run() === FALSE) {
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['suppliers'] = $this->stock_model->get_suppliers();
				$data['new_bill_no'] = $this->stock_model->get_new_bill_no();
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
				//$this->load->view('stock/purchase_form', $data);
				$this->load->view('stock/purchase_multiple_form', $data);
				$this->load->view('templates/footer');
            } else {
				$this->stock_model->add_purchase();
				$this->purchase();
            }

        }
	}
	public function ajax_add_purchase(){
		$data['purchase_date'] = date("Y-m-d",strtotime($this->input->post('purchase_date')));
		$data['bill_no'] = $this->input->post('bill_no');
		$data['quantity'] =$this->input->post('quantity');
		$data['remain_quantity'] = $this->input->post('quantity');
		$data['supplier_id'] = $this->input->post('supplier_id');
		$data['cost_price'] = $this->input->post('amount');
		$data['available_purchase_quantity'] = $this->input->post('quantity');
		$data['item_id']  = $this->input->post('item_id');

		$this->stock_model->add_purchase($data);
		$d['purchases'] = $this->stock_model->get_purchases(null,null,null,$data['bill_no']);
		$a_data[] = $d;
		echo json_encode($a_data);
	}
	public function ajax_edit_purchase(){
		$bill_no =$this->input->post('bill_no');
		$data['quantity'] =$this->input->post('quantity');
		$data['remain_quantity'] = $this->input->post('quantity');
		$data['cost_price'] = $this->input->post('amount');
		$data['available_purchase_quantity'] = $this->input->post('quantity');
		$data['purchase_id'] = $this->input->post('p_id');
		$item_name  = $this->input->post('item_name');
		$items=$this->stock_model->get_item_detail($item_name);
		$data['item_id']=$items['item_id'];
		$this->stock_model->update_purchase($data);
		$d['purchases'] = $this->stock_model->get_purchases(null,null,null,$bill_no);
		$a_data[] = $d;
		echo json_encode($a_data);
	}
	public function ajax_delete_purchase_item() {
		//Check if user has logged in
			$data['bill_no'] = $this->input->post('bill_no');
			$purchase_id = $this->input->post('p_id');

            $this->stock_model->delete_purchase($purchase_id);

			$d['purchases'] = $this->stock_model->get_purchases(null,null,null,$data['bill_no']);
			$a_data[] = $d;
			echo json_encode($a_data);
	}

	public function print_purchase_report($items = NULL,$from_date = NULL,$to_date = NULL){
		$data['from_date']= $from_date;
		$data['to_date'] = $to_date;
		$items = str_replace("_",",",$items);
		$data['items'] = $items;
		$data['item_name'] = $this->stock_model->get_item_name();
		$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
		$data['def_dateformate']=$this->settings_model->get_date_formate();
		$data['purchases'] = $this->stock_model->get_purchases($items,$from_date,$to_date);
		$data['purchase_totals'] = $this->stock_model->get_purchase_total($items,$from_date,$to_date);
		$this->load->view('print_purchase_report', $data);
	}
	public function edit_purchase($purchase_id = NULL) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('purchase_date', $this->lang->line('purchase_date'), 'required');
            $this->form_validation->set_rules('bill_no', $this->lang->line('bill')." ". $this->lang->line('no'), 'required');
			$this->form_validation->set_rules('item_name', $this->lang->line('item_name'), 'required');
            $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
            $this->form_validation->set_rules('supplier_id',$this->lang->line('supplier_name'), 'required');
            $this->form_validation->set_rules('cost_price', $this->lang->line('cost_price'), 'required|numeric');

            if ($this->form_validation->run() === FALSE) {
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['purchase'] = $this->stock_model->get_purchase($purchase_id);
				$data['suppliers'] = $this->stock_model->get_suppliers();
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
				$this->load->view('stock/purchase_form', $data);
				$this->load->view('templates/footer');
            } else {
				$this->stock_model->update_purchase();
				$this->purchase();
            }

        }
    }
    public function delete_purchase($purchase_id = NULL) {
        if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->stock_model->delete_purchase($purchase_id);
            $this->purchase();
        }
    }
	/**Purchase Return*/
	public function purchase_return($items = 0) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['suppliers'] = $this->stock_model->get_suppliers();
			$data['items'] = $this->stock_model->get_items();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			if($this->input->post('from_date')){
				$data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
			}else{
				$data['from_date'] = NULL;
			}
			if($this->input->post('to_date')){
				$data['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
			}else{
				$data['to_date'] = NULL;
			}
			if($this->input->post('items')){
				$items = $this->input->post('items');
				$selected_items = $items;
				$items = implode(",",$items);
				$data['items_csv'] = str_replace(",","_",$items);
			}else{
				$data['items_csv'] = $items;
				$items = str_replace("_",",",$items);
				$selected_items = explode(",",$items);
			}
			$data['selected_items'] = $selected_items;

			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
            $data['purchase_returns'] = $this->stock_model->get_purchase_returns($items,$from_date,$to_date);

            $header_data = get_header_data();
            $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('purchase_return', $data);
            $this->load->view('templates/footer');
        }
    }
	public function print_purchase_return_report($items = NULL,$from_date = NULL,$to_date = NULL){
		$data['from_date']= $from_date;
		$data['to_date'] = $to_date;
		$items = str_replace("_",",",$items);
		$data['items'] = $items;

		$data['item_name'] = $this->stock_model->get_item_name();
		$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
		$data['def_dateformate']=$this->settings_model->get_date_formate();
		$data['purchase_returns'] = $this->stock_model->get_purchase_returns($items,$from_date,$to_date);
		$data['purchase_return_totals'] = $this->stock_model->get_purchase_returns_total($items,$from_date,$to_date);
		$this->load->view('print_purchase_return_report', $data);
	}
	public function add_purchase_return() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('return_date', $this->lang->line('return_date'), 'required');
            $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
            //$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
			$bill_no=$this->input->post('bill_no');
			$item_id = $this->input->post('item_id');
			$row=$this->stock_model->get_purchase_id($bill_no,$item_id);
			$purchase_id=$row['purchase_id'];
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_purchased_quantity['.$purchase_id.']');

			$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'required');
            $this->form_validation->set_rules('price', $this->lang->line('price'), 'required|numeric');
            $this->form_validation->set_rules('bill_no', $this->lang->line('bill_no'), 'required|numeric');

            if ($this->form_validation->run() === FALSE) {
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['suppliers'] = $this->stock_model->get_suppliers();
				$data['purchases'] = $this->stock_model->get_purchases();
        $header_data = get_header_data();
        $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/purchase_return_form', $data);
				$this->load->view('templates/footer');
            } else {
				$this->stock_model->calculate_available_purchased_quantity($purchase_id);
                $this->stock_model->save_purchase_return();
				$this->purchase_return();
            }
        }
    }
	public function edit_purchase_return($return_id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('return_date', $this->lang->line('return_date'), 'required');
            $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
			//$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
			$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'required');
            $this->form_validation->set_rules('price', $this->lang->line('price'), 'required|numeric');
			$bill_no=$this->input->post('bill_no');
			$item_id = $this->input->post('item_id');
			$row=$this->stock_model->get_purchase_id($bill_no,$item_id);
			$purchase_id=$row['purchase_id'];
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_purchased_quantity['.$purchase_id.']');


            if ($this->form_validation->run() === FALSE) {
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['purchase_return'] = $this->stock_model->get_purchase_return($return_id);
				$data['suppliers'] = $this->stock_model->get_suppliers();
					//get quantity of purchased
				$bill_no=$data['purchase_return']['bill_no'];
				$item_id = $data['purchase_return']['item_id'];
				$row=$this->stock_model->get_purchase_id($bill_no,$item_id);
				$purchase_id=$row['purchase_id'];
				$purchase_detail = $this->stock_model->get_purchase($purchase_id);
				$qty=$purchase_detail['quantity'];
					//set deafault quantity to avilable quentity
				$this->stock_model->update_purchase_quantity_to_default($qty,$purchase_id);

        $header_data = get_header_data();
        $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/purchase_return_form', $data);
				$this->load->view('templates/footer');
            } else {
				$this->stock_model->calculate_available_purchased_quantity($purchase_id);
                $this->stock_model->update_purchase_return();
				$this->purchase_return();
            }
        }
    }
	public function delete_purchase_return($return_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['purchase_return'] = $this->stock_model->get_purchase_return($return_id);
			$bill_no=$data['purchase_return']['bill_no'];
			$item_id = $data['purchase_return']['item_id'];
			$row=$this->stock_model->get_purchase_id($bill_no,$item_id);
			$purchase_id=$row['purchase_id'];
			$purchase_detail = $this->stock_model->get_purchase($purchase_id);
			$qty=$purchase_detail['quantity'];
				//set deafault quantity to avilable quentity
			$this->stock_model->update_purchase_quantity_to_default($qty,$purchase_id);
            $this->stock_model->delete_return_purchase($return_id);
            $this->purchase_return();
        }
	}
	/**Sell*/
	public function check_available_stock($required_stock, $item_id) {
		$item_detail = $this->stock_model->get_item($item_id);
		$available_quantity = $item_detail['available_quantity'];
		if ($available_quantity < $required_stock) {
			$this->form_validation->set_message('check_available_stock', $this->lang->line('required_quantity') . $required_stock . ' exceeds Available Stock (' . $available_quantity . ') for Item ' . $item_detail['item_name']);
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function check_sold_quantity($required_stock,$sell_detail_id) {
		$sell_detail = $this->stock_model->get_sold_quantity($sell_detail_id);
		$available_sold_quantity = $sell_detail['available_sold_quantity'];
		if ($available_sold_quantity < $required_stock) {
		$this->form_validation->set_message('check_sold_quantity', $this->lang->line('required_quantity') . $required_stock . ' exceeds Available Stock (' . $available_sold_quantity . ') for Item ' . $sell_detail['item_name']);

			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function check_purchased_quantity($required_stock,$purchase_id) {
		$purchase_detail = $this->stock_model->get_purchase($purchase_id);
		$available_purchase_quantity = $purchase_detail['available_purchase_quantity'];
		if ($available_purchase_quantity < $required_stock) {
		$this->form_validation->set_message('check_purchased_quantity', $this->lang->line('required_quantity') . $required_stock . ' exceeds Available Stock (' . $available_purchase_quantity . ') for Item ' . $purchase_detail['item_name']);

			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function sell($sell_id = NULL) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('sell_date',$this->lang->line('sell_date'), 'required');
			$this->form_validation->set_rules('patient_id', $this->lang->line('patient_name'), 'required');
			$this->form_validation->set_rules('sell_no', $this->lang->line('sell')." ".$this->lang->line('no'), 'required');

			if($this->input->post('item_id')){
				$this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
				$item_id = $this->input->post('item_id');
				$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_available_stock['.$item_id.']');
				$this->form_validation->set_rules('sell_price', $this->lang->line('price'), 'required');
			}else{
				$this->form_validation->set_rules('discount', $this->lang->line('discount'), 'required');
			}

            if ($this->form_validation->run() === FALSE) {

            } else {

				if ($sell_id == NULL){
					$sell_id = $this->stock_model->insert_sell();
					}else{
					$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_available_stock['.$item_id.']');
					$this->stock_model->update_sell($sell_id);
				}
				$this->stock_model->insert_sell_detail($sell_id);
	          }
			if ($sell_id != NULL){
				$data['sell'] = $this->stock_model->get_sell($sell_id);
				$data['sell_details'] = $this->stock_model->get_sell_details($sell_id);

			}
			$active_modules = $this->module_model->get_active_modules();
			$data['active_modules'] = $active_modules;
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['def_timeformate']=$this->settings_model->get_time_formate();
			$timezone = $this->settings_model->get_time_zone();
			if (function_exists('date_default_timezone_set'))
				date_default_timezone_set($timezone);
			$data['patients'] = $this->patient_model->get_patient();
			$data['items'] = $this->stock_model->get_items();
			$data['new_sell_no'] = $this->stock_model->get_new_sell_no();

      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('stock/sell', $data);
			$this->load->view('templates/footer');


        }
    }
	public function ajax_add_sell() {

			$flag = $this->input->post('flag');
			$sell_no = $this->input->post('sell_no');
			$sell_date = $this->input->post('sell_date');
			$patient_id = $this->input->post('patient_id');
			$discount = $this->input->post('discount');
			$item_id = $this->input->post('item_id');
			$quantity = $this->input->post('quantity');
			$sell_price = $this->input->post('sell_price');

			if($flag==true){
				$row=$this->stock_model->get_sell_id($sell_no);
				$sell_id=$row['sell_id'];
			}else{
				$sell_id='';
			}

			if ($sell_id == NULL){
				$sell_id = $this->stock_model->insert_sell($sell_no,$sell_date,$patient_id,$discount);
			}else{
				//$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_available_stock['.$item_id.']');
				$this->stock_model->update_sell($sell_id,$sell_no,$sell_date,$patient_id,$discount);
			}

			$this->stock_model->insert_sell_detail($sell_id,$item_id,$quantity,$sell_price);
			if ($sell_id != NULL){
				$data['sell'] = $this->stock_model->get_sell($sell_id);
				$data['sell_details'] = $this->stock_model->get_sell_details($sell_id);
			}
			$a_data[] = $data;
			echo json_encode($a_data);

	}
	public function ajax_delete_sell_detail() {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$sell_id = $this->input->post('sell_id');
			$sell_detail_id = $this->input->post('sell_detail_id');

            $this->stock_model->delete_sell_detail($sell_detail_id);

			$data['sell'] = $this->stock_model->get_sell($sell_id);
			$data['sell_details'] = $this->stock_model->get_sell_details($sell_id);
			$a_data[] = $data;

			echo json_encode($a_data);

        }
    }
	/*public function delete_sell_detail($sell_detail_id = NULL, $sell_id = NULL) {
		//Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->stock_model->delete_sell_detail($sell_detail_id);
            $this->sell($sell_id);
        }
    }*/
    public function stock_report() {
        //Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $data['stock_report'] = $this->stock_model->get_stock_report();
            $data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            $header_data = get_header_data();
            $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('stock_report', $data);
            $this->load->view('templates/footer');
        }
    }
    public function print_stock_report(){
		$data['stock_report'] = $this->stock_model->get_stock_report();
		$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
		$this->load->view('print_stock_report', $data);
	}
	public function all_sell() {
        //Check if user has logged in
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $data['sells'] = $this->stock_model->get_sells();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            $header_data = get_header_data();
            $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('stock/all_sell', $data);
            $this->load->view('templates/footer');
        }
    }
	public function print_receipt($sell_id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index/');
        } else {
			$receipt_template = $this->stock_model->get_sell_receipt_template();
			$template = $receipt_template['template'];

			//Clinic Details
			$clinic = $this->settings_model->get_clinic_settings();
			$clinic_array = array('clinic_name','tag_line','clinic_address','landline','mobile','email');
			foreach($clinic_array as $clinic_detail){
				$template = str_replace("[$clinic_detail]", $clinic[$clinic_detail], $template);
			}
			//Sell Details
			$sell = $this->stock_model->get_sell($sell_id);
			$sell_details = $this->stock_model->get_sell_details($sell_id);

			//Bill ID
			$bill_id = $sell['sell_id'];
			$template = str_replace("[bill_id]", $bill_id, $template);
			//Bill Date
			$def_dateformate=$this->settings_model->get_date_formate();
			$def_timeformate=$this->settings_model->get_time_formate();
			$bill_date = date($def_dateformate.' '.$def_timeformate,strtotime($sell['sell_date']));
			$template = str_replace("[bill_date]", $bill_date, $template);

			//Patient Details
			$patient_id = $sell['patient_id'];
			$patient = $this->patient_model->get_patient_detail($patient_id);
			$patient_name = $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'];
			$template = str_replace("[patient_name]", $patient_name, $template);

			//Bill Columns
			$start_pos = strpos($template, '[col:');
			if ($start_pos !== false) {
				$end_pos = strpos($template, ']',$start_pos);
				$length = abs($end_pos - $start_pos);
				$col_string = substr($template, $start_pos, $length+1);

				$columns = str_replace("[col:", "", $col_string);
				$columns = str_replace("]", "", $columns);
				$cols = explode("|",$columns);
				$table = "";
				foreach($sell_details as $sell_detail){

						$table .= "<tr>";
						foreach($cols as $col){
							if($col == "sell_price"||$col == "sell_amount"){
								$table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
								$table .= currency_format($sell_detail[$col])."</td>";
							}elseif($col == "quantity"){
								$table .= "<td style='text-align:right;padding:5px;border:1px solid black;'>";
								$table .= $sell_detail[$col]."</td>";
							}else{
								$table .= "<td style='text-align:left;padding:5px;border:1px solid black;'>";
								$table .= $sell_detail[$col]."</td>";
							}
						}
						$table .= "</tr>";
				}
				$template = str_replace("$col_string",$table, $template);
			}
			//Discount
			$discount = currency_format($sell['discount']);
			$template = str_replace("[discount]",$discount, $template);
			//Total Amount
			$total = currency_format($sell['sell_amount'] - $sell['discount']);
			$template = str_replace("[total]",$total, $template);

			$template .="<input type='button' value='Print' id='print_button' onclick='window.print()'>
			<style>
				@media print{
					#print_button{
						display:none;
					}
				}
			</style>";

			echo $template;
		}
	}
	public function sell_report(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			if($this->input->post('from_date')){
				$data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
			}else{
				$data['from_date'] = date('Y-m-d');
			}
			if($this->input->post('to_date')){
				$data['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
			}else{
				$data['to_date'] = date('Y-m-d');
			}
			if($this->input->post('item')){
				$data['selected_items'] = $this->input->post('item');
			}else{
				$data['selected_items'] = array();
			}

			if($this->input->post('group_by')){
				$data['group_by'] = $this->input->post('group_by');
			}else{
				$data['group_by'] = "none";
			}

			$from_date = $data['from_date'];
			$to_date = $data['to_date'];
			$selected_items = $data['selected_items'];
			$group_by = $data['group_by'];


			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$data['sell_report'] =  $this->stock_model->get_sell_report($from_date,$to_date,$selected_items,$group_by);
			$data['items'] = $this->stock_model->get_items();
      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('stock/sell_report', $data);
			$this->load->view('templates/footer');
		}

	}
	public function sell_return($items = 0){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			if($this->input->post('from_date')){
				$data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
			}else{
				$data['from_date'] = NULL;
			}
			if($this->input->post('to_date')){
				$data['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
			}else{
				$data['to_date'] = NULL;
			}
			if($this->input->post('items')){
				$items = $this->input->post('items');
				$selected_items = $items;
				$items = implode(",",$items);
				$data['items_csv'] = str_replace(",","_",$items);
			}else{
				$data['items_csv'] = $items;
				$items = str_replace("_",",",$items);
				$selected_items = explode(",",$items);
			}
			$data['selected_items'] = $selected_items;

			$from_date = $data['from_date'];
			$to_date = $data['to_date'];

   			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['patients'] = $this->patient_model->get_patient();
			$data['items'] = $this->stock_model->get_items();
			$data['sell_returns'] = $this->stock_model->get_sell_returns($items,$from_date,$to_date);
            $header_data = get_header_data();
            $this->load->view('templates/header',$header_data);
            $this->load->view('templates/menu');
            $this->load->view('sell_return', $data);
            $this->load->view('templates/footer');

        }
	}
	public function print_sell_return_report($items = NULL,$from_date = NULL,$to_date = NULL){
		$data['from_date']= $from_date;
		$data['to_date'] = $to_date;
		$items = str_replace("_",",",$items);
		$data['items'] = $items;

		$data['item_name'] = $this->stock_model->get_item_name();
		$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
		$data['def_dateformate']=$this->settings_model->get_date_formate();
		$data['sell_returns'] = $this->stock_model->get_sell_returns($items,$from_date,$to_date);
		$data['sell_return_totals'] = $this->stock_model->get_sell_returns_total($items,$from_date,$to_date);
		$this->load->view('print_sell_return_report', $data);
	}
	public function add_sell_return() {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('return_date', $this->lang->line('return_date'), 'required');
            $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
           //$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
			$sell_no = $this->input->post('bill_no');
			$item_id = $this->input->post('item_id');
			$row=$this->stock_model->get_sell_id($sell_no);
			$sell_id=$row['sell_id'];
			$row=$this->stock_model->get_sell_detail_id_row($sell_id,$item_id);
			$sell_detail_id=$row['sell_detail_id'];
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_sold_quantity['.$sell_detail_id.']');
			$this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'required');
            $this->form_validation->set_rules('price', $this->lang->line('price'), 'required|numeric');

            if ($this->form_validation->run() === FALSE) {

				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				//$data['sell_return'] = $this->stock_model->get_sell_return($return_id);
				$data['patients'] = $this->patient_model->get_patient();
				$data['sells'] = $this->stock_model->get_sells();
        $header_data = get_header_data();
        $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/sell_return_form', $data);
				$this->load->view('templates/footer');
            } else {

				$this->stock_model->calculate_available_sold_quantity($sell_detail_id);
                $this->stock_model->save_sell_return();
				$this->sell_return();
            }

        }
    }
	public function edit_sell_return($return_id) {
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('return_date', $this->lang->line('return_date'), 'required');
            $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
           // $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
            $sell_no = $this->input->post('bill_no');
            $item_id = $this->input->post('item_id');
			$row=$this->stock_model->get_sell_id($sell_no);
			$sell_id=$row['sell_id'];
			$row=$this->stock_model->get_sell_detail_id_row($sell_id,$item_id);
			$sell_detail_id=$row['sell_detail_id'];
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|callback_check_sold_quantity['.$sell_id.']');
			$this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'required');
            $this->form_validation->set_rules('price', $this->lang->line('price'), 'required|numeric');

            if ($this->form_validation->run() === FALSE) {
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['sell_return'] = $this->stock_model->get_sell_return($return_id);
				$data['patients'] = $this->patient_model->get_patient();
				//print_r($data['sell_return']);
				$sell_no = $data['sell_return']['bill_no'];
				$item_id = $data['sell_return']['item_id'];
				$row=$this->stock_model->get_sell_id($sell_no);
				$sell_id=$row['sell_id'];
				$row=$this->stock_model->get_sell_detail_id_row($sell_id,$item_id);
				$sell_detail_id=$row['sell_detail_id'];
				$qty=$row['quantity'];
					//update avilable quantity befor update  values
					$this->stock_model->update_sold_quantity_to_default($qty,$sell_detail_id);

          $header_data = get_header_data();
          $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/sell_return_form', $data);
				$this->load->view('templates/footer');
            } else {
                $this->stock_model->update_sell_return();
				$this->stock_model->calculate_available_sold_quantity($sell_detail_id);
				$this->sell_return();
            }

        }
    }
	public function delete_sell_return($return_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$data['sell_return'] = $this->stock_model->get_sell_return($return_id);
			$sell_no = $data['sell_return']['bill_no'];
			$item_id = $data['sell_return']['item_id'];
			$row=$this->stock_model->get_sell_id($sell_no);
			$sell_id=$row['sell_id'];
			$row=$this->stock_model->get_sell_detail_id_row($sell_id,$item_id);
			$sell_detail_id=$row['sell_detail_id'];
			$qty=$row['quantity'];
			//update avilable quantity befor delete  sell return
				$this->stock_model->update_sold_quantity_to_default($qty,$sell_detail_id);;
            $this->stock_model->delete_sell_return($return_id);
            $this->sell_return();
        }
	}
	public function opening_stock($items = NULL){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {

			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['items'] = $this->stock_model->get_items();

			$medicine_name = $this->input->post('item_name');
			if($this->input->post('items')){
				$items = $this->input->post('items');
				$selected_items = $items;
				$items = implode(",",$items);
			}else{
				$items = str_replace("_",",",$items);
				$selected_items = explode(",",$items);
			}
			$data['selected_items'] = $selected_items;

			$data['opening_stocks'] = $this->stock_model->get_opening_stocks($items);
      $header_data = get_header_data();
      $this->load->view('templates/header',$header_data);
			$this->load->view('templates/menu');
			$this->load->view('stock/opening_stock',$data);
			$this->load->view('templates/footer');

        }
	}
	public function add_opening_stock(){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('added_date', $this->lang->line('added_date'), 'required');
			//$this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
			$this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required|is_unique[opening_stock.item_id]');
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required');
			$this->form_validation->set_rules('price', $this->lang->line('average_price'), 'required');

            if ($this->form_validation->run() === FALSE) {
				$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
        $header_data = get_header_data();
        $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/opening_stock_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->stock_model->add_opening_stock();
				$this->opening_stock();
			}
		}
	}
	public function edit_opening_stock($stock_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('added_date', $this->lang->line('added_date'), 'required');
			$this->form_validation->set_rules('item_id', $this->lang->line('item'), 'required');
			$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required');
			$this->form_validation->set_rules('price', $this->lang->line('average_price'), 'required');

            if ($this->form_validation->run() === FALSE) {
				$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
				$data['def_dateformate']=$this->settings_model->get_date_formate();
				$data['items'] = $this->stock_model->get_items();
				$data['opening_stock'] = $this->stock_model->get_opening_stock($stock_id);
        $header_data = get_header_data();
        $this->load->view('templates/header',$header_data);
				$this->load->view('templates/menu');
				$this->load->view('stock/opening_stock_form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->stock_model->update_opening_stock($stock_id);
				$this->opening_stock();
			}
		}
	}
	public function delete_opening_stock($stock_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
            $this->stock_model->delete_opening_stock($stock_id);
            $this->opening_stock();
        }
	}
	public function delete_sell_discount($sell_id){
		if (!$this->session->userdata('user_name') || $this->session->userdata('user_name') == '') {
            redirect('login/index');
        } else {
			$this->stock_model->delete_sell_discount($sell_id);

			$this->sell($sell_id);
		}
	}
	public function insert_sell(){
		$sell_id = $this->stock_model->insert_sell();
		$array = array('sell_id' => $sell_id);
		echo json_encode($array);
	}
	public function add_medicine(){
		$medicines = $_POST['medicine_id'];
		$quantity = $_POST['quantity'];
		$sell_id = $_POST['sell_id'];
		if($sell_id == ""){
			$sell_id = $this->stock_model->insert_sell();
		}
		$i = 0;
		foreach($medicines as $medicine_id){
			echo $medicine_id." ".$quantity[$i];
			$this->stock_model->add_medicine($sell_id,$medicine_id,$quantity[$i]);
			$i++;
		}
		redirect('stock/sell/'.$sell_id);
	}
}

?>
