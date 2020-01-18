<script type="text/javascript" charset="utf-8">
	$(window).load(function() {
		$("#from_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
			scrollInput:false, 
			scrollMonth:false,
			scrollTime:false,
			maxDate: 0,
		});
		$("#to_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
			scrollInput:false, 
			scrollMonth:false,
			scrollTime:false,
			maxDate: 0,
		});
		
    });
	$(document).ready(function () {
		$("#select_all").click(function () {
			$(".field_checkbox").prop('checked', $(this).prop('checked'));
		});
	});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('treatment')." ".$this->lang->line('report');?>
				</div>
				<div class="panel-body">
					<?php echo form_open('treatment/treatment_report'); ?>
					<div class="col-md-12">
					<div class="col-md-3">
						<?php echo $this->lang->line('from_date');?>
						<input type="text" name="from_date" id="from_date" class="form-control" value="<?=date($def_dateformate,strtotime($from_date));?>" />
					</div>
					<div class="col-md-3">
						<?php echo $this->lang->line('to_date');?>
						<input type="text" name="to_date" id="to_date" class="form-control" value="<?=date($def_dateformate,strtotime($to_date));?>" />
					</div>
					<div class="col-md-3">	
						<label for="treatment" style="display:block;text-align:left;"><?php echo $this->lang->line('treatment');?></label>
						<select id="treatment" class="form-control" multiple="multiple" style="width:350px;" tabindex="4" name="treatment[]">
							<?php foreach ($treatments as $treatment) { ?>
								<option value="<?php echo $treatment['id']?>" <?php if(!empty($selected_treatments)){if(in_array($treatment['id'], $selected_treatments)) {echo "selected";}} ?>><?= $treatment['treatment']; ?></option>
							<?php } ?>
						</select>
						<script>jQuery('#treatment').chosen();</script>
					</div>
					<div class="col-md-3">	
						<label for="doctor" style="display:block;text-align:left;"><?php echo $this->lang->line('doctor');?></label>
						<select name="doctor[]" id="doctor" class="form-control" multiple="multiple">
							<option></option>
							<?php foreach ($doctors as $doctor) {?>
								<option value="<?=$doctor['doctor_id'];?>" <?php if(!empty($selected_doctors)){if(in_array($doctor['doctor_id'], $selected_doctors)) {echo "selected";}} ?>><?= $doctor['name'];?></option>
							<?php } ?>
							<input type="hidden" name="doctor_id" id="doctor_id" value="" />
						</select>
						<script>jQuery('#doctor').chosen();</script>
					</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-3">
							<label><?php echo $this->lang->line('select_fields');?></label>
							<div class="checkbox">
								<label>
									<input type="checkbox" id="select_all" value=""><?php echo $this->lang->line('select_all');?>
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-3">
							<label><?php echo $this->lang->line('patient');?></label>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("name", $fields)) {echo "checked";} ?> value="name"><?=$this->lang->line('name');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("phone_number", $fields)) {echo "checked";} ?> value="phone_number"><?=$this->lang->line('phone_number');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("email", $fields)) {echo "checked";} ?> value="email"><?=$this->lang->line('email');?>
								</label>
							</div>
						</div>
						<div class="col-md-3">
							<label><?php echo $this->lang->line('appointment');?></label>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("date", $fields)) {echo "checked";} ?> value="date"><?=$this->lang->line('date');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("time", $fields)) {echo "checked";} ?> value="time"><?=$this->lang->line('time');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("doctor", $fields)) {echo "checked";} ?>  value="doctor"><?=$this->lang->line('doctor');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("treatment", $fields)) {echo "checked";} ?> value="treatment"><?=$this->lang->line('treatment');?>
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" class="field_checkbox" name="field[]" <?php if(in_array("treatment", $fields)) {echo "checked";} ?> value="doctor_share"><?=$this->lang->line('doctor_share');?>
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
					<div class="col-md-3">
						<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('go');?></button>
						<?php 
							$selected_doctors_str = "0";
							if(!empty($selected_doctors)){
								$selected_doctors_str = implode("__",$selected_doctors);
							}
							$selected_treatments_str = "0";
							if(!empty($selected_treatments)){
								$selected_treatments_str = implode("__",$selected_treatments);
							}
							$fields_str = "";
							if(!empty($fields)){
								$fields_str = implode("__",$fields);
							}
						?>
						<a href="<?php echo site_url('treatment/treatment_report_excel_export/'.$from_date.'/'.$to_date.'/'.$fields_str.'/'.$selected_doctors_str.'/'.$selected_treatments_str);?>" name="excel_export" class="btn btn-primary"><?php echo $this->lang->line('export_to_excel');?></a>
						<a href="<?php echo site_url('treatment/print_treatment_report/'.$from_date.'/'.$to_date.'/'.$fields_str.'/'.$selected_doctors_str.'/'.$selected_treatments_str);?>" class="btn btn-primary"><?php echo $this->lang->line('print_report');?></a>
					</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>	
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('treatment')." ".$this->lang->line('report');?>
				</div>
				<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="treatment_report" >
						<thead>
							<tr>
								<?php foreach($fields as $field){?>
								<th><?php echo $this->lang->line($field);?></th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($treatement_report as $treatement){ ?>
							<tr> 
								<?php foreach($fields as $field){?>
								<?php if($field == 'date'){?>
								<td><?php echo date($def_dateformate,strtotime($treatement[$field]));?></td>
								<?php }elseif($field == 'time'){?>
									<td><?php echo date($def_timeformate,strtotime($treatement[$field]));?></td>
								<?php }elseif($field == 'doctor_share'){?>
									<td style="text-align:right;"><?php echo currency_format($treatement[$field]);?></td>
								<?php }elseif($field == 'name'){?>
									<td style="text-align:right;"><?php echo ($treatement['patient_name']);?></td>
								<?php }else{?>
									<td><?php echo $treatement[$field];?></td>
								<?php }?>
								<?php }?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>