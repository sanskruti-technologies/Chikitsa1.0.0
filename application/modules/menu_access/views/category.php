<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm('<?php echo $this->lang->line("areyousure_delete");?>');
	})

    $("#category_table").dataTable();
});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
	<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('category');?></h1>

					<a class="btn btn-primary square-btn-adjust " href="<?php echo site_url("menu_access/add_category/"); ?>"><?php echo $this->lang->line("add_category")?></a></td>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="doctor_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('category');?></th>
									<th><?php echo $this->lang->line("edit");?></th>
									<th><?php echo $this->lang->line("delete");?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($categories as $category):  ?>
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
									<td><?php echo $category['category_name']; ?></td>
									<?php $default_categories = array("System Administrator","Administrator","Nurse","Doctor","Receptionist"); ?>
									<?php if(!(in_array($category['category_name'],$default_categories))){?>
										<td><a class="btn btn-info square-btn-adjust " title="<?php echo $this->lang->line('edit').' category : ' . $category['id'] ?>" href="<?php echo site_url("menu_access/edit_category/" . $category['id']); ?>"><?php echo $this->lang->line("edit");?></a></td>
										<td><a class="btn btn-danger square-btn-adjust confirmDelete" title="<?php echo $this->lang->line('delete').' category : ' . $category['id']?>" href="<?php echo site_url("menu_access/delete_category/" . $category['id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
									<?php } else{?>
										<td></td>
										<td></td>
									<?php }	?>
								</tr>
								<?php $i++; ?>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			
