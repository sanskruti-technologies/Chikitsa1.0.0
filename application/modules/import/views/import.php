<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=select_import]').click(function(){
			if($('input[name=select_import]').is(':checked')){
				if($('input[name=select_import]:checked').val() == 'appointment_import') {
					$('#update_existing').parent().hide();
				}else{
					$('#update_existing').parent().show();
				}
			}
        });
    });
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Import Data</h1>
				<?php echo form_open_multipart('import/upload_csv/') ?>
					<div class="form-group">
						<label for="csv_file">Upload CSV file</label>
						<br/>
						<select class="form-control" name="select_import">
							<option value="appointment_import" >Appointment Import</option>
							<option value="patient_import" >Patient Import</option>
							<?php if (in_array("account", $active_modules)) {	?>
							<option value="account_import" >Account Import</option>
							<?php }	?>
							<?php if (in_array("stock", $active_modules)) {	?>
							<option value="items_import" >Items Import</option>
							<?php }	?>
							<?php if (in_array("prescription", $active_modules)) {	?>
							<option value="medicine_import" >Medicine Import</option>
							<?php }	?>
							<?php if (in_array("doctor", $active_modules)) {	?>
							<option value="doctor_schedule_import" >Doctor Schedule Import</option>
							<?php }	?>
						</select>

						<input type="file" id="csv_file" name="csv_file" class="form-control" size="20" />
						<p>Please Keep following points in mind while creating CSV
						<ul>
							<li>First Row Must be header</li>
							<li>Keep Dates in Format YYYY-MM-DD</li>
							<li>Make sure that if Name of a Patient or Doctor is Repeated, it is exactly the same. Otherwise they will be considered as different Patients/Doctors</li>
							<li> While adding days of Week, make sure they are spelled in Full - Monday, Tuesday, Wednesday, Thursday, Friday, Saturday or Sunday</li>
						</ul>
						</p>
						<label><input type="checkbox" value='1' id="update_existing" name="update_existing" /> Update Existing</label>
					</div>
					<div class="form-group">
						<button class="btn btn-primary btn-sm square-btn-adjust" type="submit" name="submit" />Upload</button>
					</div>
				<?php echo form_close(); ?>
			</div>
