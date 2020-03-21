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
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('tests');?></h2></div>
					</div>
				</div>
				<div class="panel-body table-responsive-25">
							<a href="<?= site_url("lab/insert_test/");?>" class="btn btn-primary square-btn-adjust"><?php echo $this->lang->line("add")." ".$this->lang->line("test");?></a>
					<div class="table-responsive">
						<table class="table table-striped table-hover display responsive nowrap" id="tax_rate_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php echo $this->lang->line("test")." ".$this->lang->line("name");?></th>
									<th><?php echo $this->lang->line("test")." ".$this->lang->line("charges");?></th>
									<th><?php echo $this->lang->line("action");?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach($tests as $test){ ?>
									<tr>
										<td><?=$i;?></td>
										<td><?=$test['test_name'];?></td>
										<td><?=$test['test_charges'];?></td>
										<td>
											<!--a class="btn btn-primary square-btn-adjust" href="<?=site_url('lab/edit_test/'.$test['test_id']);?>">Edit</a-->
											<a class="btn btn-primary btn-sm square-btn-adjust editbt" href="<?=site_url('lab/edit_test/'.$test['test_id']);?>"><i class="fa fa-pencil"></i></a>

											<!--a class="btn btn-danger square-btn-adjust confirmDelete" href="<?=site_url('lab/delete_test/'.$test['test_id']);?>">Delete</a-->
											<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" href="<?=site_url('lab/delete_test/'.$test['test_id']);?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</td>
									</tr>
									<?php $i++; ?>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	
	$('.confirmDelete').click(function(){
		return confirm(<?=$this->lang->line('areyousure_delete');?>);
	})

    $("#tax_rate_table").dataTable({
		"pageLength": 50
	});
});
</script>
