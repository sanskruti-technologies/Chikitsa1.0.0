<?php 

$start_string='{';
$end_string='}';
$doctor_str="";
$doctor_str.=$start_string;
foreach($doctors as $doctor){
	$string='"'.$doctor['doctor_id'].'":{"title":"'.$doctor['name'].'"},';
	$doctor_str.=$string;
}
$doctor_str.=$end_string;



?>
	
		<meta charset="utf-8"/>
			
			<link href="<?= base_url() ?>assets/vendor/scheduler/st_scheduler.css" rel="stylesheet" />
			<!--<link href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.css" rel="stylesheet" />-->
			<script src="<?= base_url() ?>assets/vendor/scheduler/jquery-3.4.1.js" ></script>
			<script src="<?= base_url() ?>assets/vendor/scheduler/js/moment.js" ></script>
			<script src="<?= base_url() ?>assets/vendor/scheduler/st_scheduler.js" ></script>

			<!-- Page level plugins -->
			<script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
			<script src="<?= base_url() ?>assets/vendor/datatables/dataTables.responsive.min.js"></script>
			<script src="<?= base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
			<!-- Datetime Picker -->
		<link href="<?= base_url() ?>assets/vendor/datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet">
		<script src="<?= base_url() ?>assets/vendor/datetimepicker/jquery.datetimepicker.min.js"></script>
			
		<script>
			$( document ).ready(function() {
				var appointments;
				
				setInterval(function(){
					fetch_appointments();
				}, 60000);
					
					fetch_appointments();
				function fetch_appointments(){
					$.get('<?=site_url("appointment/ajax_appointments/".$appointment_date);?>', function(data, status){
					  //console.log(data);
					  appointments = JSON.parse(data);
					  var s = <?php  echo $doctor_str?>;
						var is_blocked_date="";
						//call function to check weeken or not.
						if(!is_weekend()){
							is_blocked_date = ["Weekend"];
						}
						var reason=is_exceptional_date();
						if(reason!=false){
							if(reason!="Working"){
								is_blocked_date = [is_exceptional_date()];
							}else{
								is_blocked_date="";
							}
						}
					
					  var year = <?php  echo $year?>;
					  var month = <?php  echo $month?>;
					  var day = <?php  echo $day?>;
					  var show_date = year +'-'+ month +'-' + day;
					
									
						
					  //alert(year +' '+ month +' '+ day);
					  var doctor = <?php  echo $doctor['doctor_id']?>;
					  					 
							//console.log(appointments);
							$("#scheduler").schedule({startTime:'<?php echo $start_time; ?>',
									    endTime:'<?php echo $end_time; ?>',
									    interval:'<?php echo $time_interval; ?>',
										showDate: show_date,
										//create_even_url:'<?php echo base_url(); ?>index.php/appointment/add/'+year+'/'+month+'/'+day + '/' + doctor,
										create_event_url:'<?php echo base_url(); ?>index.php/appointment/add/[show_date]/[appointment_time]/Appointment/null/[resource]',
										resources:s,
										events: appointments,
										is_blocked_date : is_blocked_date
										});
			
					});
					
					
				}
				
				$('#select_date').datetimepicker({
					timepicker:false,
					format: 'd F Y,l',
					scrollMonth:false,
					scrollTime:false,
					scrollInput:false,
					onChangeDateTime:function(dp,$input){
						var month= dp.getMonth() + 1;
						window.location='<?php echo base_url(); ?>index.php/appointment/index/'+dp.getFullYear()+'/'+month+'/'+dp.getDate();
					}
				});
						function is_weekend(){
							var working_days = <?php echo json_encode($working_days); ?>;
							new_date = new Date($('#select_date').val())
							day = new_date.getDay();
							if(day==0){
								day=7;
							}
							var ans=checkValue(day,working_days);
								console.log(ans);
								return ans;
						}
						function is_exceptional_date(){
							var date=$('#select_date').val();
							var new_date= formatDate(date);
							console.log(new_date);
							
							var exceptional_date = <?php echo json_encode($exceptional_days); ?>;
							//console.log(exceptional_date);
							var reason=false;
							var ans=false;
							var i=0;
							for(i=0;i<exceptional_date.length;i++){
								if((exceptional_date[i]['working_date']<=new_date)&&(exceptional_date[i]['end_date']>=new_date)){
									if ((exceptional_date[i]['working_date']==new_date)){
										if(exceptional_date[i]['working_status']=='Non Working'){
											reason= exceptional_date[i]['working_reason'];
										}else if(exceptional_date[i]['working_status']=='Half Day'){
											reason= exceptional_date[i]['working_reason'];
										}else{
											reason= exceptional_date[i]['working_status'];
										}
									}else{
										reason= exceptional_date[i]['working_reason'];
									}
								}
							}
							return reason;
						}
						
						/*function is_exceptional_date(){
							var date=$('#select_date').val();
							var new_date= formatDate(date);
							console.log(new_date);
							
							var exceptional_date = <?php echo json_encode($exceptional_days); ?>;
							//console.log(exceptional_date);
							var ans=false;
							var i=0;
							for(i=0;i<exceptional_date.length;i++){
								/*if(exceptional_date[i]['working_date']==new_date){
									ans=true;
									break;
								}*/
							/*	var compare_dates = function(date1,date2){
								if (date1>date2)
									return ("Date1 > Date2");
								else if (date1<date2)
									return ("Date2 > Date1");
								else
									return ("Date1 = Date2"); 
							  }
								console.log(compare_dates(new Date(new_date), new Date((exceptional_date[i]['working_date']))));	

							}
							
								
							

				
						return ans;		
						}
						*/
						function checkValue(value,arr){
							  var status = false;
							  for(var i=0; i<arr.length; i++){
								var name = arr[i];
								if(name == value){
								  status = true;
								  break;
								}
							  }
				
							  return status;
						}

						function formatDate(date) {
							var d = new Date(date),
								month = '' + (d.getMonth() + 1),
								day = '' + d.getDate(),
								year = d.getFullYear();
				
							if (month.length < 2) 
								month = '0' + month;
							if (day.length < 2) 
								day = '0' + day;

							return [year, month, day].join('-');
						}
				
				
			$("#add_inquiry_submit").click(function(event) {
					event.preventDefault();
					var first_name = $("#first_name").val();
					var middle_name = $("#middle_name").val();
					var last_name = $("#last_name").val();
					var email_id = $("#email_id").val();
					var mobile_no = $("#mobile_no").val();

					$.post( "<?php echo base_url(); ?>index.php/patient/add_inquiry",
						{first_name: first_name, middle_name: middle_name,last_name: last_name,email: email_id, phone_number:mobile_no},
						function(data,status)
						{
							alert(data);
						});
				});	
			
			});
	
	
	
		</script>


	
<div class="col-md-12">
	<div class="col-md-4">
			<input type="text" id="select_date" name="select_date" class="btn btn-success" value="<?=date('d F Y, l', strtotime($day . "-" . $month . "-" . $year));?>"/>
	</div>
				
	
	<?php if ($level == 'Doctor') {?>
	<!--------------------------- Display Doctor's Screen  ------------------------------->
			<div class="col-md-4">				
				<a href="<?=site_url('appointment/add/'.date('Y-m-d',strtotime($appointment_date)));?>" class="btn square-btn-adjust btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;<?=$this->lang->line('add_appointment');?></a>
				<a href="#" class="btn square-btn-adjust btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;<?=$this->lang->line('add_inquiry');?></a>
			</div>

	<?php }else { ?>
		<!--------------------------- Display Administration's Screen / Staff Scrren  ------------------------------->
		<div class="col-md-4">
			<a href="<?=site_url('appointment/add/'.date('Y-m-d',strtotime($appointment_date)));?>" class="btn square-btn-adjust btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;<?=$this->lang->line('add_appointment');?></a>
			<a href="#" class="btn square-btn-adjust btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;<?=$this->lang->line('add_inquiry');?></a>
		</div>
	<?php } ?>
	<!--  calender -->
	<div class="col-md-12">
	<div id="scheduler"></div>
	</div><br/>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-2">
			<span class="btn square-btn-adjust btn-primary"><?=$this->lang->line('appointment');?></span>
		</div>
		<div class="col-md-2">
			<span class="btn square-btn-adjust btn-danger"><?=$this->lang->line('consultation');?></span>
		</div>
		<div class="col-md-3">
			<span class="btn square-btn-adjust btn-success"><?=$this->lang->line('complete') .' '. $this->lang->line('appointment');?></span>
		</div>
		<div class="col-md-3">
			<span class="btn square-btn-adjust btn-info"><?=$this->lang->line('cancelled') .' '. $this->lang->line('appointment');?></span>
		</div>
		<div class="col-md-2">
			<span class="btn square-btn-adjust btn-warning"><?=$this->lang->line('waiting');?></span>
		</div>
		<div class="col-md-2">
			<span class="btn square-btn-adjust btn-grey"><?=$this->lang->line('not_available');?></span>
		</div>
		<!--div class="col-md-2">
			<span class="btn square-btn-adjust btn_pending"><?=$this->lang->line('pending');?></span>
		</div-->
	</div>
	<br/>
<div class="col-md-4">
	<!--------------------------- Display Follow-Up  ------------------------------->
	<div class="panel panel-primary">
		<div class="panel-heading"><?=$this->lang->line('follow_ups');?></div>
		<div class="panel-body"  style="overflow:scroll;height:250px;padding:0;">

				<table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer" id="followup_table">
					<thead>
						<th><?= $this->lang->line('follow_up') .' '. $this->lang->line('date');?></th>
						<th><?= $this->lang->line('doctor');?></th>
						<th><?= $this->lang->line('patient');?></th>
					</thead>
					<tbody>
					<?php
						if ($followups) {
						$i = 0;
						foreach ($followups as $followup) {
							foreach ($patients as $patient) {
								if ($followup['patient_id'] == $patient['patient_id']) {
									if ($followup['patient_id'] == $patient['patient_id']) {
										foreach ($doctors as $doctor) {
											if ($followup['doctor_id'] == $doctor['doctor_id']) {
												$followup_date = $followup['followup_date'];
												$patient_name = $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'];
												?>
												<tr>
													<td><?= date($def_dateformate, strtotime($followup_date));?></td>
													<td><?=$doctor['name'];?></td>
													<td><a href='<?= base_url() . "index.php/patient/followup/" . $patient['patient_id'] ;?>' ><?=$patient_name;?></a></td>
												</tr>
									<?php
											}
										}
									}
									break;
								}
							}
						} ?>
						<?php }	?>
					</tbody>
				</table>

		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="panel panel-primary">
		<div class="panel-heading"><?=$this->lang->line('tasks');?></div>
		<div class="panel-body">
		<!--------------------------- Display To Do  ------------------------------->
		<?php echo form_open('appointment/todos'); ?>
			<div class="input-group">
				<input type="text" name="task"  class="form-control">
				<span class="form-group input-group-btn">
					<input type="submit" class="btn btn-primary" value='<?=$this->lang->line('submit');?>' />
				</span>
			</div>
		<?php echo form_close(); ?>
		<?php foreach ($todos as $todo) { ?>
			<div class="checkbox">
				<label class="<?php if ($todo['done'] == 1) {echo 'done';} else {echo 'not_done';} ?>">
					<input type="checkbox" class="todo" name='todo' <?php if ($todo['done'] == 1) {echo 'checked="checked"';} ?> value="<?=$todo['id_num'];?>" /><?=$todo['todo'];?>
				</label>
				<a class='todo_img' href='<?=base_url() . "index.php/appointment/delete_todo/" . $todo['id_num'];?>'><i class='fa fa-remove'></i></a>
			</div>
		<?php } ?>
		</div>
	</div>
	<!--------------------------- Display To Do  ------------------------------->
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_inquiry');?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<?php echo form_open(); ?>
			<div class="modal-body">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12"><label><?=$this->lang->line('name');?>:</label></div>
						<div class="col-md-4"><input type="text" id="first_name" name="first_name" class="form-control" placeholder="first name"/></div>
						<div class="col-md-4"><input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="middle name"/></div>
						<div class="col-md-4"><input type="text" id="last_name" name="last_name" class="form-control" placeholder="last name"/></div>
					</div>
				</div>
				<div class="col-md-12"><label><?=$this->lang->line('email_id');?>:</label></div>
				<div class="col-md-12"><input type="text" id="email_id" name="email_id" class="form-control"/></div>


				<div class="col-md-12"><label><?=$this->lang->line('mobile_no');?>:</label></div>
				<div class="col-md-12"><input type="text" id="mobile_no" name="mobile_no" class="form-control"/></div>

			</div>
			<div class="modal-footer">
					<input id="add_inquiry_submit" type="submit" name="submit" value="Save" class="btn btn-primary" data-dismiss="modal"/>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close');?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


