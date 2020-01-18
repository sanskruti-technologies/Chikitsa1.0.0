<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	
	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

    $("#sections_table").dataTable({
		"pageLength": 50
	});
});
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					Sections</h1>
					<a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("history/add_section/");?>">Add Section</a>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="sections_table">
							<thead>
								<tr>
									<th>No</th>
									<th>Section</th>
									<th>Display In</th>
									<th><?php echo $this->lang->line("fields");?></th>
									<th><?php echo $this->lang->line("conditions");?></th>
									<th><?php echo $this->lang->line("actions");?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($sections as $section){  ?>
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
									<td><?php echo $i; ?></td>
									<td><?php echo $section['section_name']; ?></td>
									<td><?php echo ucwords(str_replace("_"," ",$section['display_in'])); ?></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("history/edit_section_fields/" . $section['section_id']); ?>"><?php echo $this->lang->line("fields");?></a></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("history/edit_section_conditions/" . $section['section_id']); ?>"><?php echo $this->lang->line("conditions");?></a></td>
									<td><a class="btn btn-warning btn-sm square-btn-adjust" title="Edit Section :<?php echo $section['section_name'];?>" href="<?php echo site_url("history/edit_section/" . $section['section_id']); ?>"><?php echo $this->lang->line("edit");?></a>
										<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="Delete Section :<?php echo $section['section_name'];?>" href="<?php echo site_url("history/delete_section/" . $section['section_id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
									
								</tr>
								<?php $i++; ?>
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

