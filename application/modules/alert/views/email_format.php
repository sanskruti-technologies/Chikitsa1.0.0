<script src='<?= base_url(); ?>assets/tinymce/js/tinymce/tinymce.min.js'></script>
<script type="text/javascript">
tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});
</script>
<div id="page-inner"
	<div class="row">
		<div class="col-md-12">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("email_format");?>
					</div>
					<div class="panel-body" >
						<?php echo form_open('alert/save_email_format/'.$alert_name); ?>    	
							<div class="form-group">
								<label><?=$this->lang->line("subject");?></label>
								<input type="text" class="form-control" name="email_subject" value="<?php echo $email_subject;?>" />
								<label><?=$this->lang->line("content");?></label>
								<textarea name="email_format">
									<?php echo $email_format;?>
								</textarea>
								<label><?=$this->lang->line("shortcode_label");?></label>
								<ul>
									<li><?=$this->lang->line("shortcode_patient_name");?></li>
									<li><?=$this->lang->line("shortcode_patient_id");?></li>
									<li><?=$this->lang->line("shortcode_clinic_name");?></li>
									<li><?=$this->lang->line("shortcode_doctor_name");?></li>
									<li><?=$this->lang->line("shortcode_dose_time");?></li>
									<li><?=$this->lang->line("shortcode_medicine_details");?></li>
									<li><?=$this->lang->line("shortcode_appointment_time");?></li>
									<li><?=$this->lang->line("shortcode_appointment_date");?></li>
									<li><?=$this->lang->line("shortcode_appointment_status");?></li>
									<li><?=$this->lang->line("shortcode_bill");?></li>
									<li><?=$this->lang->line("shortcode_appointment_reason");?></li>
									<li><?=$this->lang->line("shortcode_bill_id");?></li>
									<li><?=$this->lang->line("shortcode_patient_email");?></li>
									<li><?=$this->lang->line("shortcode_patient_phone_number");?></li>
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