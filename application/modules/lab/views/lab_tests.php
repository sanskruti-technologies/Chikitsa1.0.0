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
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/responsive.dataTables.min.css">
<!--script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/js/dataTables.responsive.min.js"></script-->

<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	var status = $("#status").val();
	var lab_tests_table =  $('#lab_tests_table').DataTable({
			"ajax": {"url": "<?=site_url('lab/ajax_lab_tests/');?>"+status},
			"columns": [
								{ "data": "sr_no" },
								{ "data": "<?=$this->lang->line('patient')." ".$this->lang->line('name');?>" },
								{ "data": "<?=$this->lang->line('test')." ".$this->lang->line('name');?>" },
								{ "data": "<?=$this->lang->line('status');?>" },
								{ "data": "<?=$this->lang->line('action');?>" },
						],
			"pageLength": 50
		});


	setInterval(function(){
		lab_tests_table.ajax.reload();
	}, 60000);
});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<h2><?php echo $this->lang->line('tests');?></h2>
					</div>
				</div>
				<div class="panel-body table-responsive-25">
					<?php echo form_open('lab/view_lab_tests'); ?>
					<div class="col-md-3">
						<label for="status" style="display:block;text-align:left;"><?=$this->lang->line('status');?></label>
						<select name="status" id="status" class="form-control" >
							<option value="pending" <?php if($status == 'pending') echo 'selected'; ?>>Pending</option>
							<option value="complete" <?php if($status == 'complete') echo 'selected'; ?>>Complete</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="status" style="display:block;text-align:left;">&nbsp;</label>
						<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('filter');?></button>
						<br><br>
					</div>
					<?php echo form_close(); ?>
					<div class="row">
					<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-hover display responsive nowrap" id="lab_tests_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("sr_no");?></th>
									<th><?php echo $this->lang->line("patient")." ".$this->lang->line("name");?></th>
									<th><?php echo $this->lang->line("test")." ".$this->lang->line("name");?></th>
									<th><?php echo $this->lang->line("status");?></th>
									<th><?php echo $this->lang->line("action");?></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
					</div>
					</div>
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>
