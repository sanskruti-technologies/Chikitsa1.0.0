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
?>
<script type="text/javascript" charset="utf-8">
	$(window).load(function(){
		$('#time').hide();
		var w_status=document.getElementsByName("working_status");
		for (i = 0; i < w_status.length; i++) {
			//alert(w_status[i].value)
			status=w_status[i].value;
		}
		if(status=='Half Day'){
			$('#time').show();
		}

		$('#working_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false
		});
		$('#end_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false
		});
		$('#start_time').datetimepicker({
			datepicker:false,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
		});
		$('#end_time').datetimepicker({
			datepicker:false,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
		});

		$('#working_date').change(function () {
			var w_date=this.value;
			document.getElementById("end_date").value = w_date;
        });
		$( ".working_status" ).change(function() {
			//console.log($(this).val());
					if ($(this).val() == 'Half Day') {
					$('#time').show();
					}
					else{
					$('#time').hide();
					}

			});

	});
</script>
<?php
$working_date = set_value('working_date',date('Y/m/d'));
$working_status = set_value('working_status','');
$working_reason = set_value('working_reason','');
$end_date = set_value('end_date',date('Y/m/d'));
$start_time = set_value('start_time','');
$end_time = set_value('end_time','');
if(isset($exceptional)){
	$working_date = set_value('working_date',$exceptional['working_date']);
	$working_status = set_value('working_status',$exceptional['working_status']);
	$working_reason = set_value('working_reason',$exceptional['working_reason']);
	$end_date = set_value('end_date',$exceptional['end_date']);
	$start_time = set_value('start_time',$exceptional['start_time']);
	$start_time = date($def_timeformate,strtotime($start_time));
	$end_time = set_value('end_time',$exceptional['end_time']);
	$end_time = date($def_timeformate,strtotime($end_time));
}
?>
<!-- Begin Page Content -->
    <div class="container-fluid">
	<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line("exceptional_days");?></h1>
		<?php 	if(!isset($exceptional)){
					echo form_open('settings/save_exceptional_days');
				}else{
					echo form_open('settings/update_exceptional_days');
				}
		?>
		<div class="col-md-12">
			<div class="row"> 
				<input type="hidden" id="uid" name="uid" class="form-control" value="<?=$exceptional['uid'];?>">
				<div class="col-md-3">
					<label><?php echo $this->lang->line("start_date");?></label>
					<input type="text" id="working_date" name="working_date" class="form-control" value="<?= date($def_dateformate,strtotime($working_date));?>" >
					<?php echo form_error('working_date','<div class="alert alert-danger">','</div>'); ?>
				</div>
				<div class="col-md-3">
					<label><?php echo $this->lang->line("end_date");?></label>
					<input type="text" id="end_date" name="end_date" class="form-control" value="<?= date($def_dateformate,strtotime($end_date));?>">
					<?php echo form_error('end_date','<div class="alert alert-danger">','</div>'); ?>
				</div>

				<div class="col-md-3">
					<label><?php echo $this->lang->line("status");?></label>
					<?php
					$option = array('Working'=>$this->lang->line('working'),
									'Non Working'=>$this->lang->line('non_working'),
									'Half Day'=>$this->lang->line('half_day'));
					$attr = 'class="form-control working_status"';
					echo form_dropdown("working_status",$option,$working_status,$attr);
					?>
					<?php echo form_error('working_status','<div class="alert alert-danger">','</div>'); ?>
				</div>
				<div class="col-md-3">
					<label><?php echo $this->lang->line("reason");?></label>
					<input type="text" id="working_reason" name="working_reason" value="<?=$working_reason;?>" class="form-control">
					<?php echo form_error('working_reason','<div class="alert alert-danger">','</div>'); ?>
				</div> <br/> &nbsp 
				
				<div class="col-md-12">
					<div id="time" class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="start_time"><?php echo $this->lang->line('start_time');?></label>
								<input type="input" name="start_time" id="start_time" value="<?=$start_time; ?>" class="form-control"/>
								<?php echo form_error('start_time','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="end_time"><?=$this->lang->line('end_time');?></label>
								<input type="input" name="end_time" id="end_time" value="<?=$end_time; ?>" class="form-control"/>
								<?php echo form_error('end_time','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>			
		<div class="col-md-12">
			<input type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" value="<?=$this->lang->line('save');?>">
			<a href="<?=site_url('settings/working_days/');?>" class="btn btn-primary square-btn-adjust btn-sm" ><?php echo $this->lang->line('back');?></a>
		</div>			
	<?php echo form_close(); ?>
</div>
