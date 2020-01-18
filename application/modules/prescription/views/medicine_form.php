<?php
	if(isset($medicine)){
		$medicine_id = $medicine['medicine_id'];
		$medicine_name = $medicine['medicine_name'];
	}else{
		$medicine_name = "";
	}
?>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('medicines');?></h1>
				<?php if(isset($medicine)){?>
				<?php echo form_open('prescription/edit_medicine/'.$medicine_id) ?>
				<input type="hidden" name="medicine_id" value="<?=$medicine_id?>"/>
				<?php }else{ ?>
				<?php echo form_open('prescription/insert_medicine/') ?>
				<?php } ?>
						<div class="form-group">
							
							<label for="medicine_name"><?php echo $this->lang->line('medicine_name');?></label> 
							<input type="input" name="medicine_name" value="<?=$medicine_name?>" class="form-control"/>		
							<?php echo form_error('medicine_name','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" /><?php echo $this->lang->line('save');?></button>
							<a href="<?=site_url('prescription/medicine');?>" class="btn btn-info square-btn-adjust btn-sm"><?php echo $this->lang->line('back');?></a>
						</div>
				<?php echo form_close(); ?>
			</div>
			</div>
		</div>
	</div>
</div>