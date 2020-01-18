<?php 
	$section_name = "";
	$section_display_in = "";
	$section_department_id = "";
	$edit = FALSE;
	if(isset($section)){
		$edit = TRUE;
		$section_name = $section['section_name'];
		$section_display_in = $section['display_in'];
		$section_id = $section['section_id'];
		$section_department_id = $section['department_id'];
	}
?>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					<?php if($edit){?>
						Edit Section 
					<?php }else{ ?>
						Add Section
					<?php } ?>
				</h1>
					<div>
						<?php if($edit){?>
						<?php echo form_open('history/edit_section/'.$section_id) ?>
						<?php }else{ ?>
						<?php echo form_open('history/add_section/') ?>
						<?php } ?>
						
						<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label for="section_name">Section Name</label> 
								<input type="input" class="form-control" name="section_name" value="<?=$section_name;?>"/>
								<?php echo form_error('section_name','<div class="alert alert-danger">','</div>'); ?>
							</div>   
						</div>   
						<div class="col-md-3">
							<div class="form-group">
								<label for="display_in">Display In</label> 
								<select id="display_in" name="display_in" class="form-control">
									<option value="patient_detail" <?php if($section_display_in == "patient_detail") echo "selected";?>>Patient Detail</option>
									<option value="visits" <?php if($section_display_in == "visits") echo "selected";?>>Visits</option>
								</select>
								<?php echo form_error('display_in','<div class="alert alert-danger">','</div>'); ?>
							</div>   
						</div>   
						<?php if (in_array("doctor", $active_modules)) { ?>
						<div class="col-md-3">
							<div class="form-group">
							    <?php $section_departments = explode(",",$section_department_id);?>
								<label for="department_id"><?=$this->lang->line("department");?></label>
								<select id="department_id" name="department_id[]" multiple="multiple" class="form-control">  <option></option>
									<?php if(isset($departments)) { ?>
										<?php  foreach ($departments as $department) { ?>
										    <?php $selected = ""; ?>
											<?php if(in_array($department['department_id'],$section_departments)){ ?>
											    <?php $selected = "selected"; ?>
											<?php } ?>
										<option value="<?=$department['department_id'] ?>" <?=$selected;?>><?= $department['department_name']; ?> </option>
										<?php } ?>
									<?php } ?>
								</select>								
								<?php echo form_error('department_id','<div class="alert alert-danger">','</div>'); ?>
								<script>jQuery('#department_id').chosen();</script>
							</div>
						</div>	
						<?php } ?>
   					</div>
					<div class="form-group">
						<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" /><?php echo $this->lang->line('save');?></button>
						<a href="<?=site_url('history/sections');?>" class="btn btn-primary square-btn-adjust" >Back</a>
					</div>							
					<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

