<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("sms_format");?>
					</div>
					<div class="panel-body" >
						<?php echo form_open('alert/save_sms_format/'.$alert_name); ?>    	
							<div class="form-group">
								<label><?=$this->lang->line("sms_template_id");?></label>
								<input type="text" class="form-control" name="sms_template_id" value="<?php echo $sms_template_id;?>" />
								<label><?=$this->lang->line("sms_format");?></label>
								<textarea class="form-control" name="sms_format"><?=$sms_format;?></textarea>
								<label><?=$this->lang->line("shortcode_label");?></label>
								<ul>
									<li><?=$this->lang->line("shortcode_patient_name");?></li>
									<li><?=$this->lang->line("shortcode_patient_id");?></li>
									<li><?=$this->lang->line("shortcode_clinic_name");?></li>
									<li><?=$this->lang->line("shortcode_doctor_name");?></li>
									<li><?=$this->lang->line("shortcode_dose_time");?></li>
									<li><?=$this->lang->line("short	code_sms_medicine_details");?></li>
									<li><?=$this->lang->line("shortcode_appointment_time");?></li>
									<li><?=$this->lang->line("shortcode_appointment_date");?></li>
									<li><?=$this->lang->line("shortcode_appointment_status");?></li>
								</ul>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
								<a class="btn btn-primary" href="<?=site_url('alert/settings');?>"  /><?=$this->lang->line("back");?></a>
							</div>
						<?php echo form_close(); ?>    	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>