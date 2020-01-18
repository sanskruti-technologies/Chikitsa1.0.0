<?php
	if($select_import == 'appointment_import'){
		$appinments_fields = array('skip' => $this->lang->line('skip'),
							'option_group_1' => $this->lang->line('patient'),
							'patient_id' => $this->lang->line('patient_id'),
							'patient_full_name' => $this->lang->line('patient')." ".$this->lang->line('full')." ".$this->lang->line('name'),
							'user_id' => $this->lang->line('user')." ".$this->lang->line('id'),
							'patient_phone_number' => $this->lang->line('patient')." ".$this->lang->line('phone')." ".$this->lang->line('number'),
							'option_group_2' => $this->lang->line('doctor'),
							'doctor_full_name' => $this->lang->line('doctor')." ".$this->lang->line('full')." ".$this->lang->line('name'),
							'option_group_3' => $this->lang->line('appointments'),
							'appointment_date' => $this->lang->line('appointment')." ".$this->lang->line('date'),
							'appointment_statr_time' => $this->lang->line('appointment')." ".$this->lang->line('start_time'),
							'appointment_end_time' => $this->lang->line('appointment')." ".$this->lang->line('end_time'),
							'option_group_4' => $this->lang->line('visit'),
							'visit_note' => $this->lang->line('visit')." ".$this->lang->line('notes')
							);
		$fields = $appinments_fields;
		$location='import/key_mapper_appointment';
		
	}elseif($select_import == 'patient_import'){
		$patient_fields = array('skip' => $this->lang->line('skip'),
							'patient_id' => $this->lang->line('patient_id'),
							'patient_full_name' => $this->lang->line('patient')." ".$this->lang->line('full')." ".$this->lang->line('name'),
							'display_name' => $this->lang->line('display')." ".$this->lang->line('name'),
							'gender' => $this->lang->line('gender'),
							'option_group_5' => $this->lang->line('address'),
							'address_type' => $this->lang->line('address')." ".$this->lang->line('type'),
							'address_line_1' => $this->lang->line('address_line_1'),
							'address_line_2' => $this->lang->line('address_line_2'),
							'city' => $this->lang->line('city'),
							'state' => $this->lang->line('state'),
							'postal_code' => $this->lang->line('postal_code'),
							'country' => $this->lang->line('country'),
							'dob' => $this->lang->line('dob'),
							'reference_by' => $this->lang->line('reference_by'),
							'phone_number' => $this->lang->line('phone')." ".$this->lang->line('number'),
							'second_number' => $this->lang->line('second')." ".$this->lang->line('number'),
							'email' => $this->lang->line('email')
							);
		$fields = $patient_fields;
		$location='import/key_mapper_patient';
		
	}elseif($select_import == 'account_import'){
		$account_fields = array('skip' => $this->lang->line('skip'),
							'account_group' => $this->lang->line('account')." ".$this->lang->line('group'),
							'full_name' => $this->lang->line('full')." ".$this->lang->line('name'),
							'option_group_6' => $this->lang->line('name'),
							'title' => $this->lang->line('title'),
							'first_name' => $this->lang->line('first_name'),
							'middle_name' => $this->lang->line('middle_name'),
							'last_name' => $this->lang->line('last_name'),
							'option_group_7' => $this->lang->line('address'),
							'area' => $this->lang->line('area'),
							'address_type' => $this->lang->line('address')." ".$this->lang->line('type'),
							'address_line_1' => $this->lang->line('address_line_1'),
							'address_line_2' => $this->lang->line('address_line_2'),
							'city' => $this->lang->line('city'),
							'state' => $this->lang->line('state'),
							'postal_code' => $this->lang->line('postal_code'),
							'country' => $this->lang->line('country'),
							'mobile_number' => $this->lang->line('mobile')." ".$this->lang->line('number'),
							'residence_number' => $this->lang->line('residence')." ".$this->lang->line('number'),
							'email' => $this->lang->line('email'),
							'tax_id' => $this->lang->line('tax')." ".$this->lang->line('id'),
							'option_group_8' => $this->lang->line('opening')." ".$this->lang->line('balance'),
							'opening_balnce' => $this->lang->line('opening')." ".$this->lang->line('balance')."Cr/Dr/",
							'opening_balnce_amount' => $this->lang->line('opening')." ".$this->lang->line('balance')." ".$this->lang->line('amount'),
							'opening_balnce_as_on' => $this->lang->line('opening')." ".$this->lang->line('balance')." ".$this->lang->line('as')." ".$this->lang->line('on'),
							'option_group_9' => $this->lang->line('additional_detail'),
							'clinic_name' => $this->lang->line('clinic')." ".$this->lang->line('name'),
							'clinic_number' => $this->lang->line('clinic')." ".$this->lang->line('number'),
							'affliation_date' => $this->lang->line('affliation')." ".$this->lang->line('date')

							);
		$fields = $account_fields;
		$location='import/key_mapper_account';
		
	}elseif($select_import == 'medicine_import'){
		$medicine_fields = array('medicine_name' => $this->lang->line('medicine_name'));
		$fields = $medicine_fields;
		$location='import/key_mapper_medicine';
		
	}elseif($select_import == 'doctor_schedule_import'){
		$doctor_schedule_fields = array('doctor_name' => $this->lang->line('doctor_name'),
						'schedule_day' => $this->lang->line('schedule')." ".$this->lang->line('day'),
						'schedule_date' => $this->lang->line('schedule')." ".$this->lang->line('date'),
						'from_time' => $this->lang->line('from_time'),
						'to_time' => $this->lang->line('to_time'));
		$fields = $doctor_schedule_fields;
		$location='import/key_mapper_doctor';
		
	}elseif($select_import == 'items_import'){
		$item_fields = array('item_name' => $this->lang->line('item_name'),
							'desired_stock' => $this->lang->line('desired_stock'),
							'mrp' => $this->lang->line('mrp'));
		$fields = $item_fields;
		$location='import/key_mapper';
	}
	
		
?>
<?php
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}  
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				Map Keys
			</div>
			<div class="panel-body">
				<?php echo form_open($location) ?>
				<input type='hidden' name='update_existing' value='<?=$update_existing;?>' />
				<input type='hidden' name='file_path' value='<?=$file_path;?>' />				
				<?php $i=0; ?>
				<?php foreach($headers as $header){ ?>
					
					<?php echo '<input type="hidden" name="row_headers[]" value="'. $row_headers[$i]. '">'; ?>
					<div class="col-md-4">
						<div class="panel panel-primary">
							<div class="panel-heading"><?php echo $header;?></div>
						</div>
						<div class="panel-body">
							<span>Map CSV File Column <?php echo $header;?> To </span>
							<select name="<?php echo $row_headers[$i];?>" class="form-control">
								<?php foreach($fields as $key => $value) { 
									if(startsWith($key,"option_group")){?>
										<option value="<?=$key;?>" style="color:#000;font-weight :bold;" disabled><?=$value;?></option>
									<?php }else{ ?>
										<option value="<?=$key;?>" <?php if(trim($header) == $value) {echo "selected";} ?>><?=$value;?></option>
									<?php  } ?>
									
								<?php } ?>
							</select>
						</div>
					</div>
					<?php $i=$i+1; ?>
				<?php } ?>
				<div class="col-md-12">
					<div class="form-group">
						<button class="btn btn-primary" type="submit" name="submit" />Submit</button>
					
						<a class="btn btn-primary" href="<?php echo site_url("import/index"); ?>">Back</a>
					</div>
				</div>
				<?php echo form_close() ?>    	
			</div>
		</div>
	</div>
</div>
		