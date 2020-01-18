<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	
	$('.confirmDelete').click(function(){
		return confirm('<?php echo $this->lang->line("areyousure_delete");?>');
	})

    $("#category_table").dataTable();
});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			
			<a class="btn btn-primary square-btn-adjust" title="<?php echo $this->lang->line('add')." ".$this->lang->line('category') ?>" href="<?php echo site_url("menu_access/category"); ?>"><?php echo $this->lang->line("add")." ". $this->lang->line("category");?></a>
			<br/><br/>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line("access_on_menu");?>
				</div>
				<div class="panel-body">
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="category_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('category');?></th>
									<th><?php echo $this->lang->line('allow_menu');?></th>									
									<th><?php echo $this->lang->line('edit')?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($categories as $category):  
								if ($category['category_name']!="System Administrator" || $level == "System Administrator"){
								?>   
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >									
								<td><?php echo $category['category_name']; ?></td>
								 
								<td>
								
								<?php foreach ($menu_accesss as $menu_access):?>
							
									<?php if(($category['category_name']==$menu_access['category_name']) && ($menu_access['allow']==1)){ 										 						
										 foreach ($mymenus as $mymenu):
										 
											if($menu_access['menu_text']==$mymenu['menu_text']){
												 echo $this->lang->line($mymenu['menu_text']);
												 echo ", ";
											}
										endforeach; 
										
										 
										 }
										 ?>
								<?php endforeach ?>
								</td>
								<td><a class="btn btn-info btn-sm square-btn-adjust" title="<?php echo $this->lang->line('edit').' menu_access : ' . $category['id'] ?>" href="<?php echo site_url("menu_access/edit_menu_access/" . $category['id']); ?>"><?php echo $this->lang->line("edit");?></a></td>
								</tr>
								<?php $i++; ?>
								<?php } ?>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>
