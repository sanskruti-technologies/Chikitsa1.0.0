<!-- JQUERY SCRIPTS -->
<script src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<!-- JQUERY UI SCRIPTS -->
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>		
<!-- BOOTSTRAP SCRIPTS -->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="<?= base_url() ?>assets/js/jquery.metisMenu.min.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="<?= base_url() ?>assets/js/custom.min.js"></script>
 <!-- DATA TABLE SCRIPTS -->
<script src="<?= base_url() ?>assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/js/dataTables/dataTables.bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/dataTables/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/dataTables/datetime-moment.min.js"></script>
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
		$(".expand-collapse-header").click(function () {
			if($(this).find("i").hasClass("fa-arrow-circle-down"))
			{
				$(this).find("i").removeClass("fa-arrow-circle-down");
				$(this).find("i").addClass("fa-arrow-circle-up");
			}else{
				$(this).find("i").removeClass("fa-arrow-circle-up");
				$(this).find("i").addClass("fa-arrow-circle-down");
			}
			
			$content = $(this).next('.expand-collapse-content');
			$content.slideToggle(500);

		});
		
	});	
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading expand-collapse-header"><i class="fa fa-arrow-circle-down"></i>
					<?php echo $this->lang->line('patient_details');?> <?php echo $this->lang->line('clickto_toggle_display');?>
				</div>
				<div class="panel-body expand-collapse-content collapsed">
					<div class="col-md-9">
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('id');?> :</label>
								<span><?php echo $patient['display_id']; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('ssn_id');?> :</label>
								<span><?php echo $patient['ssn_id']; ?></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label><?php echo $this->lang->line('name');?> :</label>
								<span><?php echo $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name']; ?></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label><?php echo $this->lang->line('display_name');?>:</label>
								<span><?php echo $patient['display_name']; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('reference_by');?> :</label>
								<?php
									$reference_by = $patient['reference_by'];
									if($patient['reference_by_detail'] != NULL){
										$reference_by .= $reference_by . $patient['reference_by_detail'];
									}
								?>
								<span><?php echo $reference_by; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('dob');?> :</label>
								<?php if($patient['dob'] != NULL) { ?>
								<span><?php echo date($def_dateformate,strtotime($patient['dob'])); ?></span>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('gender');?> :</label>
								<span><?= $patient['gender']; ?></span>
							</div>
						</div>
						<?php 
						$contacts = "";
						foreach($contact_details as $contact_detail){
							if($contact_detail['contact_id'] == $patient['contact_id']){
								if($contacts == ""){
									$contacts .= $contact_detail['detail'];
								}else{
									$contacts .= ",".$contact_detail['detail'];
								}
							}
						}
						?>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('mobile');?> :</label>
								<span><?= $contacts; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?php echo $this->lang->line('email');?> :</label>
								<span><?= $addresses['email']; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label style="display:table-cell;"><?php echo $this->lang->line('address');?> :</label>
								<span><strong>(<?=$addresses['type']; ?>)</strong><br/>
									   <?=$addresses['address_line_1'];?><br/>
									   <?=$addresses['address_line_2'];?><br/>
									   <?=$addresses['area'];?><br/>
									   <?=$addresses['city'] . "," . $addresses['state'] . "," . $addresses['postal_code'] . "," . $addresses['country']; ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php if(isset($addresses['contact_image']) && $addresses['contact_image'] != ""){ ?>
								<img src="<?php echo base_url() . $addresses['contact_image']; ?>" height="150" width="150"/>	
							<?php }else{ ?>
								<img src="<?php echo base_url() . "/uploads/images/Profile.png" ?>" height="150" width="150"/>	
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('tests');?> 
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('lab/upload_report_files/'.$visit_id);?>
								
					<table class="table table-striped table-bordered table-hover" >
						<thead>
							<tr>
								<th><?php echo $this->lang->line("sr_no");?></th>
								<th><?php echo $this->lang->line("test")." ".$this->lang->line("name");?></th>
								<th><?php echo $this->lang->line("report");?></th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; ?>
							<?php $show_viewed = TRUE; ?>
							<?php $show_completed = TRUE; ?>
								<?php foreach($visit_test as $test){ ?>
									<tr>
										<td><?=$i;?></td>
										<td><?=$test['test_name'];?></td>
										<td>
											<input type="hidden" name="file_name[]" value="<?=$test['visit_test_id'];?>"/>
											<?php if($test['file_name'] != NULL){ 
												if($test['status'] == 'complete' || $test['status'] == 'viewed'){ 
													$show_completed = FALSE;
												}
											?>
												<a target="_blank" href="<?=base_url('uploads/reports/'.$test['file_name']);?>" ><?php echo $this->lang->line('view')." ".$this->lang->line('report');?></a>
											<?php }else{
												$show_completed = FALSE;
											}
											if($test['status'] == 'pending'){ ?>
												<?php $show_viewed = FALSE; ?>
												<input type="file" name="<?=$test['visit_test_id'];?>"/>
											<?php } ?>
											
										</td>
									</tr>
									<?php $i++; ?>
								<?php }?>
								
						</tbody>
					</table>
					<?php if($show_viewed){ ?>
						<button type="submit" name="submit" value="viewed" class="btn btn-primary btn-sm square-btn-adjust"><?php echo $this->lang->line('viewed');?></button>
					<?php }else{ ?>
						<button type="submit" name="submit" value="save" class="btn btn-primary btn-sm square-btn-adjust"><?php echo $this->lang->line('save');?></button>
					<?php }
					if($show_completed){ ?>	
						<button type="submit" name="submit" value="save_complete" class="btn btn-primary btn-sm square-btn-adjust"><?php echo $this->lang->line('save_complete');?></button>
					<?php } ?>	
					<a href="<?=site_url('lab/view_lab_tests');?>" class="btn btn-primary btn-sm square-btn-adjust"><?php echo $this->lang->line("back");?></a>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</div>