<script>
	$(window).load(function() {    
		$("#add_alert").click(function(){
			
			var alert_count = parseInt($("#alert_count").val());
			alert_count = alert_count + 1;
			$("#alert_count").val(alert_count);
			
			var alert_time = "<div class='col-md-12'><div class='col-md-1'><?=$this->lang->line("before");?></div><div class='col-md-2'><select id='days' name='days[]' class='form-control'>";
			<?php for($i=0;$i<=15;$i++){
				echo "alert_time += '<option value=\"$i\">$i</option>';\n";
			}?>
			alert_time += "</select> <?=$this->lang->line("days");?> </div><div class='col-md-2'><select id='hours' name='hours[]'  class='form-control'>";
			<?php for($i=0;$i<=23;$i++){
				echo "alert_time += '<option value=\"$i\">$i</option>';\n";
			} ?>
			alert_time += "</select> <?=$this->lang->line("hour");?> </div><div class='col-md-2'><select id='minutes' name='minutes[]'  class='form-control'>";
			<?php for($i=0;$i<=59;$i++){
				echo "alert_time += '<option value=\"$i\">$i</option>';\n";
			} ?>
			alert_time += "</select> <?=$this->lang->line("minutes");?> </div><div class='col-md-3'><?=$this->lang->line("of_appoinment_time");?></div>";
			alert_time += "<div class='col-md-2'><label></label><a href='#' id='delete_alert"+alert_count+"' class='btn btn-danger btn-sm square-btn-adjust'><?=$this->lang->line("delete");?></a></div></div>";
			$( "#all_alert_times" ).append(alert_time);	
			
			$("#delete_alert"+alert_count).click(function() {			
					$(this).parent().parent().remove();
			});	
		});
		$('#alert_time').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
		});
		$('#alert_time2').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
		});
		$('#alert_time3').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
		});
	});
</script>
<div id="page-inner"
	<div class="row">
		<div class="col-md-12">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("sms_alert_time");?>
					</div>
					<?php 
					if($alert_occur == "APPNT" ) { ?>
						<div class="panel-body" >
						<?php echo form_open('alert/save_sms_alert_time/'.$alert_name.'/'.$alert_occur); ?>    	
							<div class="form-group">
								<a href="#" id="add_alert" class="btn btn-primary"/><?=$this->lang->line("add_alerts");?></a>
								<input type="hidden" id="alert_count" value="0"/>
							</div>
							<div id="all_alert_times">
							<?php
								$days = floor($total_hours/24);
								
								$hours = floor($total_hours - ($days*24));
								
								$minutes = floor(($total_hours - floor($total_hours))*60);
								
							?>
								<div class="col-md-12">
									<div class="col-md-1">
									<?=$this->lang->line("before");?>
									</div>
									<div class="col-md-2">
										<select id="days" name="days[]" class="form-control" >
										<?php for($i=0;$i<=15;$i++){
											if($i==$days){
												echo "<option value='$i' selected>$i</option>";
											}else{
												echo "<option value='$i'>$i</option>";	
											}
										}?>
										</select>
										<?=$this->lang->line("days");?> 
									</div>
									<div class="col-md-2">
										<select id="hours" name="hours[]"  class="form-control">
										<?php for($i=0;$i<=23;$i++){
											if($i==$hours){
												echo "<option value='$i' selected>$i</option>";
											}else{
												echo "<option value='$i'>$i</option>";	
											}
										}?>
										</select>
										<?=$this->lang->line("hour");?> 
									</div>
									<div class="col-md-2">
									<select id="minutes" name="minutes[]"  class="form-control">
									<?php for($i=0;$i<=59;$i=$i+15){
										if($i==$minutes){
												echo "<option value='$i' selected>$i</option>";
											}else{
												echo "<option value='$i'>$i</option>";	
											}
									}
									?>
									</select>
									<?=$this->lang->line("minutes");?> 
									</div>
									<div class="col-md-3">
									<?=$this->lang->line("of_appoinment_time");?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
								<a class="btn btn-primary" href="<?=site_url('alert/settings');?>"  /><?=$this->lang->line("cancel");?></a>
							</div>
						<?php echo form_close(); ?>    	
					</div>
					<?php } 
					elseif ($alert_occur == "DOSE") {?>
						<?php
							$dose_time_array = explode("|",$dose_time);
							$morning_time = $dose_time_array[0];
							$afternoon_time = $dose_time_array[1];
							$night_time = $dose_time_array[2];
						?>
						<div class="panel-body" >
						<?php echo form_open('alert/save_sms_alert_time/'.$alert_name.'/'.$alert_occur); ?>    	
							<div class="form-group">
							<div class="col-md-6">
								<div class="col-md-4">
									<?=$this->lang->line("morning_time");?>
								</div>
								<div class="col-md-6">
									<input type="text" name="morning_time" id="alert_time" class="form-control" value="<?=$morning_time;?>"/>
								</div>
				
								<div class="col-md-4">
									<?=$this->lang->line("afternoon_time");?>
								</div>
								<div class="col-md-6">
									<input type="text" name="afternoon_time" id="alert_time2" class="form-control" value="<?=$afternoon_time;?>"/>
								</div>
								
								<div class="col-md-4">
									<?=$this->lang->line("night_time");?>
								</div>
								<div class="col-md-6">
									<input type="text" name="night_time" id="alert_time3" class="form-control" value="<?=$night_time;?>"/>
								</div>
							
								<div class="col-md-6">
									<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
								
									<a class="btn btn-primary" href="<?=site_url('alert/settings');?>"  /><?=$this->lang->line("cancel");?></a>
								</div>
							</div>
							<?php echo form_close(); ?>
					    </div>
					<?php } else {?>
						<div class="panel-body" >
							<?php echo form_open('alert/save_sms_alert_time/'.$alert_name.'/'.$alert_occur); ?>
								<div class="col-md-6">
									<div class="col-md-4">
										<?=$this->lang->line("alert_time");?>
									</div>
									<div class="col-md-6">
										<input type="text" name="alert_time" id="alert_time"  class="form-control" value="<?=$alert_time;?>"/>
									</div>
								</div>
								<p></p>
								<p></p>
								<div class="col-md-12">
									<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
									<a class="btn btn-primary" href="<?=site_url('alert/settings');?>"  /><?=$this->lang->line("cancel");?></a>
								</div>
								
							<?php echo form_close(); ?>
						</div>
					<?php }?>
				</div>
		</div>
	</div>
</div>