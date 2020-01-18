<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line("areyousure_delete");?>");
	})

    $("#doctor_table").dataTable();
});
</script>
<?php $category = $this->session->userdata('category');?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line("doctor");?>
				</div>
				<div class="panel-body">
					<?php if($category != 'Doctor') {?>
					<a title="<?php echo $this->lang->line("add")." ".$this->lang->line("doctor");?>" href="<?php echo base_url()."index.php/doctor/doctor_detail/" ?>" class="btn btn-primary square-btn-adjust"><?php echo $this->lang->line("add")." ".$this->lang->line("doctor");?></a>
					<?php }?>
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="doctor_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("sr_no");?></th>
									<th><?php echo $this->lang->line("name");?></th>
									<th><?php echo $this->lang->line("department");?></th>
									<th><?php echo $this->lang->line("email");?></th> 
									<th><?php echo $this->lang->line("phone_number");?></th>
									<th><?php echo $this->lang->line("fees");?></th>
									<th><?php echo $this->lang->line("preferences");?></th>
									<th><?php echo $this->lang->line("schedule");?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php $display = TRUE; ?>
								<?php if(isset($doctors)){ ?>
								<?php foreach ($doctors as $doctor):  ?>
								<?php if($category == 'Doctor') {?>
									<?php if($doctor['userid'] == $this->session->userdata('id')) {?>
										<?php $display = TRUE; ?>
									<?php }else{ ?>
										<?php $display = FALSE; ?>
									<?php } ?>
								<?php } ?>
								<?php if($display) {?>
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >								
									<td><?php echo $i; ?></td>
									<td><a class="btn btn-info btn-sm square-btn-adjust" href="<?php echo site_url("doctor/doctor_detail/" . $doctor['doctor_id']); ?>"><?php echo  $doctor['title'] . " " . $doctor['first_name'] . " " . $doctor['middle_name'] . " " . $doctor['last_name']; ?></a></td>
									<td>
									<?php $doctor_departments = explode(",",$doctor['department_id']);  ?>
									<?php $display_departments = "";  
										foreach ($departments as $department): 
											if(in_array($department['department_id'],$doctor_departments)){
											
												if($display_departments != "") {
													$display_departments .= ",";
												}
												$display_departments .=  $department['department_name'];
											} ?>
									<?php endforeach ?>
									<?= $display_departments; ?> 
									</td>
									<td><?php echo $doctor['email'];?></td> 
									<td><?php echo $doctor['phone_number'];?></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("doctor/fees/" . $doctor['doctor_id']); ?>"><?php echo $this->lang->line("fees");?></a></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("doctor/edit_preference/" . $doctor['doctor_id']); ?>"><?php echo $this->lang->line("preferences");?></a></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("doctor/doctor_schedule/" . $doctor['doctor_id']); ?>"><?php echo $this->lang->line("schedule");?></a></td>
								</tr>
								<?php } ?>
								<?php $i++; ?>
								
								<?php endforeach ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>
