<script type="text/javascript" charset="utf-8">
$(window).load(function() {
    $('#medicine_table').dataTable();
	$('.confirmDelete').click(function(){
		return confirm('<?=$this->lang->line('areyousure_delete');?>');
	})
});
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('medicines');?></h1>
					
				<a class="btn btn-primary square-btn-adjust btn-sm" title="Edit" href="<?php echo site_url("prescription/insert_medicine/"); ?>"><?php echo $this->lang->line('add');?>&nbsp;<?php echo $this->lang->line('medicine');?></a>
				<div class="table-responsive">
					<table class="table table-striped table-hover display responsive nowrap" id="medicine_table">
					<thead>
						<tr>
							<th><?php echo $this->lang->line('sr_no');?></th>
							<th><?php echo $this->lang->line('medicine');?></th>
							<th><?php echo $this->lang->line('edit');?></th>
							<th><?php echo $this->lang->line('delete');?></th>
						</tr>									
					</thead>
					<tbody>
					<?php $i=1; ?>
					<?php foreach ($medicines as $medicine): ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $medicine['medicine_name'] ?></td>
						<td><!--a class="btn btn-primary square-btn-adjust btn-sm" title="Edit" href="<?php echo site_url("prescription/edit_medicine/" . $medicine['medicine_id']); ?>"><?php echo $this->lang->line('edit');?></a-->
						<a class="btn btn-primary btn-sm square-btn-adjust editbt" title="Edit" title="Edit" href="<?php echo site_url("prescription/edit_medicine/" . $medicine['medicine_id']); ?>"><?php echo $this->lang->line('edit');?></a>
						</td>
						
						
						<td><!--a class="btn btn-danger square-btn-adjust btn-sm confirmDelete junkcss-rm" title="<?php echo  $this->lang->line('delete_item')." :" . $medicine['medicine_name']?>" href="<?php echo site_url("prescription/delete_medicine/" . $medicine['medicine_id']); ?>"><?php echo $this->lang->line('delete');?></a-->
						
						<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="delete" href="<?php echo site_url("prescription/delete_medicine/" . $medicine['medicine_id']); ?>"><?php echo $this->lang->line('delete');?></a>
						</td>
					</tr>
					 <?php $i++; ?>
					<?php endforeach ?>
					</tbody>
					
					</table>
				</div>		
			</div>
			