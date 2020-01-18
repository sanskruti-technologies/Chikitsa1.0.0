<?php
if(!isset($message_medicine)){
$message_medicine=NULL;
}
if(!isset($message_doctor)){
$message_doctor=NULL;
}
if(!isset($message)){
$message=NULL;
}
if(!isset($doctor_count)){
$doctor_count=0;
}
if(!isset($patient_count)){
$patient_count=0;
}
if(!isset($account_count)){
$account_count=0;
}
if(!isset($appointment_count)){
$appointment_count=0;
}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				Data Imported!
			</div>
			<div class="panel-body">
				<?php 
				if($message!=NULL){//for item import?>
					<a href="<?=site_url('stock/item');?>" ><?php echo $message;?></a><br/>
				<?php }
				if($message_medicine){?>
					<a href="<?=site_url('prescription/medicine');?>" ><?php echo $message_medicine;?></a><br/>
				<?php }
				if($message_doctor){?>
					<a href="<?=site_url('doctor/doctor_schedule');?>" ><?php echo $message_doctor;?></a><br/>
				<?php }
				if($doctor_count > 0){
					foreach($doctor_import_list as  $doctor_import){
						echo $doctor_import."<br/>";
					}
					echo "<br/>";?>
					<a href="<?=site_url('doctor/index');?>" ><?php echo "Total $doctor_count Doctors Imported"."<br/>";?></a><br/>
				<?php }
				if($patient_count > 0){
					foreach($patient_import_list as  $patient_import){
						echo $patient_import."<br/>";
					}
					echo "<br/>";?>
					<a href="<?=site_url('patient/index');?>" ><?php echo "Total $patient_count Patients Imported"."<br/>";?></a><br/>
				<?php }
				
				if($account_count > 0){
					foreach($account_import_list as  $account_import){
						echo $account_import."<br/>";
					}
					echo "<br/>";?>
					<a href="<?=site_url('patient/index');?>" ><?php echo "Total $account_count Accounts Imported"."<br/>";?></a><br/>
					<?php }
				if($appointment_count > 0){?>
					<a href="" ><?php echo "Total $appointment_count Appointments Imported"."<br/>";?></a><br/>
				<?php }
				?>
			</div>
		</div>
	</div>
</div>
		