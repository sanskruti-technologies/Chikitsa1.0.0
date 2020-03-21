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
<?php
	$test_name = "";
	$test_charges = "";
	
	if(isset($test)){
		$test_name = $test['test_name'];
		$test_charges = $test['test_charges'];
	}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
			<div class="row"><h2>
				<?php if(isset($test)){ ?>
				<?php echo $this->lang->line('edit_test');?>
				<?php }else{ ?>
				<?php echo $this->lang->line('add_test');?>
				<?php } ?></h2>
			</div>
			</div>
			<div class="panel-body table-responsive-25">
				<div class="col-md-6"> 
				<?php if(isset($test)){ ?>
				<?php echo form_open('lab/edit_test/'.$test['test_id']) ?>
				<input type="hidden" name="test_id" id="test_id" value="<?php echo $test['test_id']; ?>" class="form-control"/>	
					
				<?php }else{?>
				<?php echo form_open('lab/insert_test/') ?>
				<?php } ?>
					<div class="form-group">
						<label for="test_name"><?php echo $this->lang->line('test_name');?></label> 
						<input type="text" name="test_name" id="test_name" value="<?php echo $test_name; ?>" class="form-control"/>
						<?php echo form_error('test_name','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="test_charges"><?php echo $this->lang->line('charges_fees');?></label>
						<input type="text" name="test_charges" id="test_charges" value="<?php echo $test_charges; ?>" class="form-control"/>
						<?php echo form_error('test_charges','<div class="alert alert-danger">','</div>'); ?>
					</div>
					
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" /><?php echo $this->lang->line('save');?></button>
						<a href="<?=site_url('lab/insert_test');?>" class="btn btn-info btn-sm square-btn-adjust"><?php echo $this->lang->line('back');?></a>
					</div>
				<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>