<?php
/*
	This file is part of Chikitsa.

    Chikitsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Chikitsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Chikitsa.  If not, see <https://www.gnu.org/licenses/>.
*/
	$total = ($particular_total + $fees_total + $treatment_total + $item_total + $session_total);
	$time_interval = $time_interval*60;
	$patient_name = "";
	$ssn_id = "";
	$display_id = "";
	$phone_number = "";
	if(isset($patient)){
		$patient_name = $patient['title']." ".$patient['first_name']." ".$patient['middle_name']." ".$patient['last_name'];
		$ssn_id = $patient['ssn_id'];
		$display_id = $patient['display_id'];
		$phone_number = $patient['phone_number'];
	}
	if(isset($doctor)){
		$doctor_name = $doctor['title']." ".$doctor['first_name']." ".$doctor['middle_name']." ".$doctor['last_name'];
		$doctor_department = $doctor['department_id'];
	}else{
		$doctor_name="";
		$doctor_department="";
	}
?>
<script type="text/javascript" charset="utf-8">
$(window).load(function(){
	$('.confirmDelete').click(function(){
		return confirm('<?=$this->lang->line("areyousure_delete");?>');
	});
	$('#foc').change(function() {
		if($(this).is(":checked")){
			$('#discount').val('<?=$total-$discount;?>');
		}else{
			$('#discount').val('');
		}
	});
	var tax_rate = $('option:selected', $("#bill_tax_rate")).attr('tax_rate');
	$("#bill_tax_amount").val(tax_rate);
	$( "#bill_tax_rate" ).change(function(){
		var tax_rate = $('option:selected', $("#bill_tax_rate")).attr('tax_rate');
		$("#bill_tax_amount").val(tax_rate);
	});

	<?php if (in_array("stock",$active_modules)) { ?>
		var items_list=[<?php $i = 0;
		foreach ($items as $item) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $item['item_name'] . '",amount:"' . $item['mrp'] . '",available_quantity:"'.$item['available_quantity'].'",item_id:"'.$item['item_id'].'"}';
			$i++;
		}?>];
		$("#item_name").autocomplete({
			autoFocus: true,
			source: items_list,
			minLength: 1,//search after one characters

			select: function(event,ui){
				//do something
				$("#item_amount").val(ui.item ? ui.item.amount : '');
				$("#available_quantity").val(ui.item ? ui.item.available_quantity : '');
				$("#item_id").val(ui.item ? ui.item.item_id : '');
				$("#item_quantity").val(1);

			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#item_id").val('');
					$("#item_amount").val('');
					$("#item_name").val('');
					$("#available_quantity").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#item_id").val('');
					$("#item_amount").val('');
					$("#item_name").val('');
					$("#available_quantity").val('');
				}
			}
		});
	<?php } ?>
	var list_fees=[];

	<?php if(isset($fees)){
		$list_fees_array = array();
		foreach ($doctors as $doc) {
			$list_fees_array[$doc['doctor_id']] = array();
		}
		foreach ($fees as $fee) {
			$fee_array = array('value'=>$fee['detail'],'amount' => $fee['fees']);
			$list_fees_array[$fee['doctor_id']][] = $fee_array;
		}
		echo "list_fees = ".json_encode($list_fees_array).";";
	?>

	$("#fees_detail").autocomplete({
		autoFocus: true,
		source: list_fees["3"],
		minLength: 1,//search after one characters

		select: function(event,ui){
			//do something
			$("#fees_amount").val(ui.item ? ui.item.amount : '');

		},
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#fees_amount").val('');
				$("#fees_detail").val('');
			}
		},
		response: function(event, ui) {
			if (ui.content.length === 0)
			{
				$("#fees_amount").val('');
				$("#fees_detail").val('');
			}
		}
	});

	<?php }	?>
	<?php
	$tax_rate_name[0] = "";
	$tax_rate_array[0] = 0;
	if (in_array("treatment",$active_modules)) {?>
		var list_treatment=[<?php $i = 0;
			foreach ($treatments as $treatment) {
				if ($i > 0) { echo ",";}
				echo '{value:"' . $treatment['treatment'] . '",amount:"' . $treatment['price'] .'",tax_rate_name:"' . $tax_rate_name[$treatment['tax_id']] .'",treatment_rate:"' . ($treatment['price']*$tax_rate_array[$treatment['tax_id']]/100) . '",departments:"'.$treatment['departments'].'"}';
				$i++;
			}?>];
		$("#treatment").autocomplete({
			autoFocus: true,
			source: list_treatment,
			minLength: 1,//search after one characters

			select: function(event,ui){
				//do something
				$("#treatment_price").val(ui.item ? ui.item.amount : '');
				$("#treatment_rate_name").val(ui.item ? ui.item.tax_rate_name : '');
				$("#treatment_rate").val(ui.item ? ui.item.treatment_rate : '');

			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#treatment_price").val('');
					$("#treatment").val('');
					$("#treatment_rate_name").val('');
					$("#treatment_rate").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#treatment_price").val('');
					$("#treatment").val('');
					$("#treatment_rate_name").val('');
					$("#treatment_rate").val('');
				}
			}
		});
	<?php }else{ ?>
		var list_treatment=[];
	<?php } ?>
	    var patient=[<?php $i = 0;
			foreach ($patients as $patient) {
				if ($i > 0) { echo ",";}
				echo '{value:"' . $patient['first_name'] .' '.$patient['middle_name'].' '.$patient['last_name']. '",num:"' . $patient['phone_number'] . '",ssn_id:"' . $patient['ssn_id'] . '",id:"' . $patient['patient_id'].'",display:"' . $patient['display_id'] . '"}';
				$i++;
			}?>];
		$("#patient_name").autocomplete({
			autoFocus: true,
			source: patient,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#phone_number").val(ui.item ? ui.item.num : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#patient_name").val('');
					$("#patient_id").val('');
					$("#display_id").val('');
					$("#phone_number").val('');
					$("#ssn_id").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					$("#patient_name").val('');
					$("#patient_id").val('');
					$("#display_id").val('');
					$("#phone_number").val('');
					$("#ssn_id").val('');
				}
			}
		});

		var searcharrdispname=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['display_id'] . '",id:"' . $patient['patient_id'] . '",num:"' . $patient['phone_number'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",ssn_id:"' . $patient['ssn_id'] . '"}';
			$i++;
		}?>];
		$("#display_id").autocomplete({
			autoFocus: true,
			source: searcharrdispname,
			minLength: 1,//search after one characters
			select: function(event,ui)
			{
				//do something
			   $("#patient_id").val(ui.item ? ui.item.id : '');
			   $("#patient_name").val(ui.item ? ui.item.patient : '');
			   $("#phone_number").val(ui.item ? ui.item.num : '');
			   	$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
			},
			change: function(event, ui)
			{
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#email_id").val('');
				}
			},
			response: function(event, ui)
			{
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#email_id").val('');
				}
			}
		});
		var search_ssn_id=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['ssn_id'] . '",id:"' . $patient['patient_id'] . '",num:"' . $patient['phone_number'] . '",display:"' . $patient['display_id'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",email:"' . $patient['email'] . '"}';
			$i++;
		}?>];
		$("#ssn_id").autocomplete({
			autoFocus: true,
			source: search_ssn_id,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#phone_number").val(ui.item ? ui.item.num : '');
				$("#patient_name").val(ui.item ? ui.item.patient : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			}
		});
		var searcharrmob=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['phone_number'] . '",ssn_id:"' . $patient['ssn_id'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '"}';
			$i++;
		}?>];
		$("#phone_number").autocomplete({
			autoFocus: true,
			source: searcharrmob,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#patient_name").val(ui.item ? ui.item.patient : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			}
		});

		//Doctor AutoComplete
		var doctor=[<?php $i = 0;
			foreach ($doctors as $doc) {
				if ($i > 0) { echo ",";}
				echo '{value:"' . $doc['title']." ".$doc['first_name']." ".$doc['middle_name']." ".$doc['last_name']. '",department:"'.$doc['department_id'].'",id:"' . $doc['doctor_id'].'"}';
				$i++;
			}?>];
		$("#doctor_name").autocomplete({
			autoFocus: true,
			source: doctor,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#doctor_id").val(ui.item ? ui.item.id : '');
				$("#doctor_department").val(ui.item ? ui.item.department : '');
				$("#fees_section").show();
				var doctor_id = $('#doctor_id').val();
				$( "#fees_detail" ).autocomplete('option', 'source', list_fees[doctor_id]);

				var new_treatment_list = [];
				var doctor_department = $("#doctor_department").val();
				$.each(list_treatment , function(index, treatment) {
				  var treatement_departments = treatment.departments.split(',');
				  var doctor_departments = doctor_department.split(',');
				  $.each(doctor_departments , function(i, doctor_department) {
					if(treatement_departments.indexOf(doctor_department) > -1){
						found = false;
						if(new_treatment_list.length > 0){
							$.each(new_treatment_list , function(index, new_treatment) {
								if (new_treatment.value == treatment.value){
									found = true;
								}
							});
						}
						if(!found){
							new_treatment_list.push(treatment);
						}
					}
				  });
				});
				$( "#treatment" ).autocomplete('option', 'source', new_treatment_list);

			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#doctor_name").val('');
					$("#doctor_id").val('');
					$("#doctor_department").val('');
					$("#fees_section").hide();
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0){
					$("#doctor_name").val('');
					$("#doctor_id").val('');
					$("#doctor_department").val('');
					$("#fees_section").hide();
				}
			}
		});
	<?php if(!isset($doctor_id) || $doctor_id == NULL){ ?>
		$("#fees_section").hide();
	<?php }else{ ?>
		var doctor_id = $('#doctor_id').val();
		$( "#fees_detail" ).autocomplete('option', 'source', list_fees[doctor_id]);
	<?php }?>
	<?php 	if(isset($visit_id)){ ?>
				$("#fees_section").show();
				var doctor_id = $('#doctor_id').val();
				$( "#fees_detail" ).autocomplete('option', 'source', list_fees[doctor_id]);
	<?php 	}?>

	$('#bill_date').datetimepicker({
		timepicker:false,
		format: '<?=$def_dateformate; ?>',
		scrollMonth:false,
		scrollTime:false,
		scrollInput:false,
	});
	$('#bill_time').datetimepicker({
		datepicker:false,
		step:<?=$time_interval;?>,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>',
		<?php if($clinic_start_time != '00:00' && $clinic_end_time !='24:00'){?>
		minTime:'<?=date($def_timeformate,strtotime($clinic_start_time));?>',
		maxTime:'<?=date($def_timeformate,strtotime($clinic_end_time));?>',
		<?php } ?>
		scrollMonth:false,
		scrollTime:false,
		scrollInput:false,
	});
	<?php if (in_array("lab",$active_modules)) { ?>
		var list_lab_test=[<?php $i = 0;
			foreach ($lab_tests as $lab_test) {
				if ($i > 0) { echo ",";}
				echo '{value:"' . $lab_test['test_name'] . '",amount:"' . $lab_test['test_charges'] .'",test_id:"'.$lab_test['test_id'].'"}';
				$i++;
			}?>];
		$("#lab_test").autocomplete({
			autoFocus: true,
			source: list_lab_test,
			minLength: 1,//search after one characters

			select: function(event,ui){
				//do something
				$("#test_price").val(ui.item ? ui.item.amount : '');
				$("#lab_test_id").val(ui.item ? ui.item.test_id : '');
			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#test_price").val('');
					$("#lab_test_id").val('');
					$("#lab_test").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#test_price").val('');
					$("#lab_test").val('');
					$("#lab_test_id").val('');
				}
			}
		});
	<?php } ?>
});
</script>

<!-- Begin Page Content -->
    <div class="container-fluid">
		<!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800"><?=$this->lang->line("new")." ".$this->lang->line("bill");?></h1>
			<?php echo form_open('bill/edit/'.$bill_id); ?>
			<div class="form-group">
				<?php if($bill_id != 0){?>
				<a class="btn btn-primary btn-sm" target="_blank" href="<?php echo site_url("bill/print_receipt/" . $bill_id); ?>"><?php echo $this->lang->line("receipt");?></a>
				<a class="btn btn-primary btn-sm" href="<?php echo site_url("payment/insert/" .$patient_id . "/bill"); ?>"><?php echo $this->lang->line("bill")." ".$this->lang->line("payment");?></a>
				<a class="btn btn-primary btn-sm" href="<?php echo site_url("patient/visit/" .$patient_id); ?>">Back to Visit</a>
				<a class="btn btn-primary btn-sm" href="<?php echo site_url("bill/index"); ?>">Back to Bills</a>
				<?php if (in_array("alert", $active_modules)) {	?>
					<a class="btn btn-primary btn-sm" href="<?php echo site_url("patient/email_bill/" . $bill_id."/".$patient_id ); ?>">Email Bill</a>
				<?php } ?>
				<?php

					$bill_date = date($def_dateformate,strtotime($bill['bill_date']));
					$bill_time = date($def_timeformate,strtotime($bill['bill_time']));
				}else{
					$bill_date = date($def_dateformate);
					$bill_time = date($def_timeformate);
				}?>
				<span class="alert-danger"><?php echo validation_errors(); ?></span>
			</div>				
			<input type="hidden" name="bill_id" value="<?=$bill_id?>"/>		
			<div class="panel panel-default">
					<div class="panel-heading">
						<?= $this->lang->line('search')." ".$this->lang->line('patient');?>
					</div>
					<div class="row">
						<div class="col-md-3">
						<label for="display_id"><?php echo $this->lang->line('patient_id');?></label>
						<input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id; ?>">
							<?php if(isset($visit_id)){ ?>
									<input type="text" name="display_id" id="display_id" value="<?=$display_id; ?>" class="form-control" readonly/>
								<?php	}else { ?>
									<input type="text" name="display_id" id="display_id" value="<?=$display_id; ?>" class="form-control"/>
								<?php } ?>
						</div>
						<div class="col-md-3">
							<label for="ssn_id"><?php echo $this->lang->line('ssn_id');?></label>
							<?php if(isset($visit_id)){ ?>
									<input type="text" name="ssn_id" id="ssn_id" value="<?=$ssn_id; ?>" class="form-control" readonly/>
								<?php }else { ?>
									<input type="text" name="ssn_id" id="ssn_id" value="<?=$ssn_id; ?>" class="form-control"/>
								<?php } ?>
						</div>
						<div class="col-md-3">
							<label for="patient"><?php echo $this->lang->line('patient_name');?></label>
							<?php if(isset($visit_id))
									{ ?>
									<input type="text" name="patient_name" id="patient_name" value="<?=$patient_name; ?>" class="form-control" readonly/>
								<?php	}else { ?>
									<input type="text" name="patient_name" id="patient_name" value="<?=$patient_name; ?>" class="form-control"/>
								<?php } ?>
							<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-3">
							<label for="phone"><?php echo $this->lang->line('mobile');?></label>
							<?php if(isset($visit_id))
									{ ?>
									<input type="text" name="phone_number" id="phone_number" value="<?=$phone_number; ?>" class="form-control" readonly/>
								<?php	}else { ?>
									<input type="text" name="phone_number" id="phone_number" value="<?=$phone_number; ?>" class="form-control"/>
								<?php } ?>
						</div>
					</div>
			</div>					
			<div class="row">
				<div class="col-md-6">
					<label for="patient_name"><?php echo $this->lang->line("doctor");?></label>
					<?php if(isset($visit_id)){
							 $doctor_id=$doctor['doctor_id'];?>
							<input type="text" name="doctor_name" id="doctor_name" value="<?=$doctor_name; ?>" class="form-control" readonly/>
							<input type="hidden" name="doctor_id" id="doctor_id" value="<?= $doctor_id ?>"/>
							<input type="hidden" name="doctor_department" id="doctor_department" value="<?=$doctor_department;?>" />
						<?php	}else { ?>
							<input type="text" name="doctor_name" id="doctor_name" value="<?=$doctor_name; ?>" class="form-control"/>
							<input type="hidden" name="doctor_id" id="doctor_id" value="<?= @$doctor_id ?>"/>
							<input type="hidden" name="doctor_department" id="doctor_department" value="<?=$doctor_department;?>" />
						<?php } ?>
					<input type="hidden" name="appointment_id" id="appointment_id" value="<?=@$appointment_id ?>"/>
					<?php echo form_error('doctor_id','<div class="alert alert-danger">','</div>'); ?>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="bill_date"><?php echo $this->lang->line("date");?></label>
					<?php if(isset($visit_id)){
							 ?>
							<input type="text" name="bill_date" id="" value="<?=$bill_date; ?>" class="form-control" readonly/>
						<?php	}else { ?>
							<input type="text" name="bill_date" id="bill_date" value="<?=$bill_date; ?>" class="form-control"/>
						<?php } ?>

					<?php echo form_error('bill_date','<div class="alert alert-danger">','</div>'); ?>
				</div>
				<div class="form-group col-md-6">
					<label for="bill_time"><?php echo $this->lang->line("time");?></label>
					<?php if(isset($visit_id))
							{ ?>
							<input type="text" name="bill_time" id="" value="<?=$bill_time; ?>" class="form-control" readonly/>
						<?php	}else { ?>
							<input type="text" name="bill_time" id="bill_time" value="<?=$bill_time; ?>" class="form-control"/>
						<?php } ?>

					<?php echo form_error('bill_time','<div class="alert alert-danger">','</div>'); ?>
				</div>
			</div>            
			<div class="panel panel-primary">
				<div class="panel-body table-responsive-60">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("particular");?>
									<input type="hidden" name="action" value="particular">
									<input name="particular" id="particular" class="form-control" value=""/>
								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("amount");?>
									<input type="text" name="particular_amount" id="particular_amount" class="form-control" id="amount"/>
								</div>
								<?php if($tax_type == "item"){?>
								<div class="col-md-2">
									<?php echo $this->lang->line("tax");?>
									<select name="tax_id" class="form-control" id="bill_tax_rate">
										<?php foreach($tax_rates as $tax_rate){?>
											<option tax_rate="<?=$tax_rate['tax_rate'];?>" value="<?=$tax_rate['tax_id'];?>"><?=$tax_rate['tax_rate_name'];?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-3">
									<?php echo $this->lang->line("rate");?>
									<input type="text" style="text-align:right;" name="tax_amount" id="tax_amount" class="form-control" readonly />
								</div>
								<?php } ?>
								<div class="col-md-2">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="particular" /><?php echo $this->lang->line("add");?></button>

								</div>
							</div>
						</div>
						<?php if (in_array("stock",$active_modules)) { ?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("item");?>
									<input type="hidden" name="action" value="item">
									<input type="hidden" name="item_id" id="item_id" value="">
									<input name="item_name" id="item_name" class="form-control" value=""/>
								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("quantity");?>
									<input type="text" name="item_quantity" id="item_quantity" class="form-control" />
								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("available");?>
									<input type="text" name="available_quantity" id="available_quantity" class="form-control" readonly="readonly" />

								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("amount");?>
									<input type="text" name="item_amount" id="item_amount" class="form-control" />

								</div>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="item" /><?php echo $this->lang->line("add");?></button>

								</div>
							</div>
						</div>
						<?php }?>
						<?php if (in_array("doctor",$active_modules)) {?>
						<div id="fees_section">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("fees");?>
									<input type="hidden" name="action" value="fees">
									<input name="fees_detail" id="fees_detail" class="form-control" value=""/>
								</div>
								<div class="col-md-3">
									<?php echo $this->lang->line("amount");?>
									<input type="text" name="fees_amount" id="fees_amount" class="form-control" id="amount"/>

								</div>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary  square-btn-adjust" type="submit" name="submit" value="fees" /><?php echo $this->lang->line("add");?></button>
								</div>
							</div>
						</div>

						</div>

						<?php }?>
						<?php if (in_array("treatment",$active_modules)) {?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("treatment");?>
									<input type="hidden" name="action" value="treatment">
									<input name="treatment" id="treatment" class="form-control" value=""/>

								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("amount");?>
									<input type="text" name="treatment_price" id="treatment_price" class="form-control"/>

								</div>
								<?php if($tax_type == "item"){?>
								<div class="col-md-2">
									<?php echo $this->lang->line("tax_rate_name");?>
									<input type="text" name="treatment_rate_name" readonly id="treatment_rate_name" class="form-control" />

								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("rate");?>
									<input type="text" style="text-align:right;" name="treatment_rate" id="treatment_rate" readonly class="form-control"  />

								</div>
								<?php } ?>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="treatment" /><?php echo $this->lang->line("add");?></button>

								</div>
							</div>
						</div>

						<?php }?>
						<?php if (in_array("lab",$active_modules)) {?>
						<div class="form-group">
							<div class="col-md-12">
								<div class="col-md-3">
									<?php echo $this->lang->line("lab_test");?>
									<input type="hidden" name="action" value="lab_test">
									<input type="hidden" id="lab_test_id" name="lab_test_id" class="form-control" value=""/>
									<input name="lab_test" id="lab_test" class="form-control" value=""/>

								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("amount");?>
									<input type="text" name="test_price" id="test_price" class="form-control"/>

								</div>
								<?php if($tax_type == "item"){?>
								<div class="col-md-2">
									<?php echo $this->lang->line("tax_rate_name");?>
									<input type="text" name="lab_test_rate_name" readonly id="lab_test_rate_name" class="form-control" />

								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("rate");?>
									<input type="text" style="text-align:right;" name="lab_test_rate" id="lab_test_rate" readonly class="form-control"  />

								</div>
								<?php } ?>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="lab_test" /><?php echo $this->lang->line("add");?></button>

								</div>
							</div>
						</div>

						<?php } ?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("discount");?>
									<input name="discount" id="discount" class="form-control" value=""/>
								</div>
								<div class="col-md-2">
									<?php if (in_array("doctor", $active_modules)) { ?>
									<?php echo $this->lang->line("foc");?>
									<input type="checkbox" name="foc" id="foc" class="form-control" value=""/>
									<?php } ?>
								</div>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="discount" /><?php echo $this->lang->line("add");?></button>
								</div>
							</div>
						</div>
						<?php if($tax_type == "bill"){?>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<?php echo $this->lang->line("tax");?>
									<select name="bill_tax_rate" class="form-control" id="bill_tax_rate">
										<?php foreach($tax_rates as $tax_rate){?>
											<option value="<?=$tax_rate['tax_id'];?>" tax_rate="<?=$tax_rate['tax_rate'];?>"><?=$tax_rate['tax_rate_name'];?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2">
									<?php echo $this->lang->line("percentage");?>
									<input type="text" style="text-align:right;" name="bill_tax_amount" id="bill_tax_amount" class="form-control" readonly />
								</div>
								<div class="col-md-3">
									<br/>
									<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" value="tax" /><?php echo $this->lang->line("add");?></button>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body table-responsive-25">
						<?php $this->load->view('bill/bill_table'); ?>

				</div>
			</div>
	</div>

