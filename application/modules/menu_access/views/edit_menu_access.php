<script type="text/javascript" charset="utf-8">
	$(window).load(function(){
		$('.menu_access').change(function() {	
			if($(this).prop("checked")){
				$(this).parent().parent().siblings().prop('checked', true);
				$(this).parent().parent().parent().parent().siblings().prop('checked', true);
			}
		});
		
	});
</script>
<?php 
function has_access($category_name,$menu_name,$menu_accesses){
	foreach($menu_accesses as $menu_access){
		if($menu_access['menu_name'] == $menu_name && $menu_access['category_name'] == $category_name){
			if($menu_access['allow'] == 1){
				echo "checked"; 
			}else{
				echo "";
			}
		}
	}
	echo "";
}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $this->lang->line("access_on_menu");?>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('menu_access/edit_menu_access/'.$category['id']) ?>						
											
						<div class="col-md-6">
							<div class="form-group">
								<label for="category"><?php echo $this->lang->line('category')." ".$this->lang->line('name');?></label>
									<?php  foreach ($categories as $cat) {
									if($category['id']==$cat['id']){ ?>
										<?php $category_name = $cat['category_name']; ?>
										<input type="hidden" name="category" value="<?php echo $cat['category_name'];?>">
										<input type="text"  value=<?php  echo $cat['category_name']; ?> readonly  class="form-control">
									<?php }} ?>
							</div>
							<?php 
				
							foreach ($menus as $menu) { ?>
							<div class="form-group">
							<?php if ($menu['parent_name']==""){?>
								<div class="checkbox">
									<label>	
										<input class="menu_access" type="checkbox" name="<?php echo $menu['menu_name']; ?>" id="<?php echo $menu['menu_name']; ?>" value="<?php echo $menu['menu_text']; ?>" <?=has_access($category_name,$menu['menu_name'],$menu_accesses);?>>
										<?php echo $this->lang->line($menu['menu_text']);?>
									
									<?php
									foreach ($menus as $menu_sub) {  
										if(($menu_sub['parent_name']==$menu['menu_name'])) { ?>
										<div class="checkbox" style="margin-left:25px;display:block;">
											<label>	
												<input type="checkbox" class="menu_access <?=$menu_sub['parent_name'];?>" name="<?php echo $menu_sub['menu_name']; ?>" id="<?php echo $menu_sub['menu_name']; ?>" value="<?php echo $menu_sub['menu_text']; ?>" <?=has_access($category_name,$menu_sub['menu_name'],$menu_accesses);?> >
												<?php echo $this->lang->line($menu_sub['menu_text']);?>
												
										<?php
											foreach ($menus as $menu_sub2) {  
												if(($menu_sub2['parent_name']==$menu_sub['menu_name'])) { ?>
												<div class="checkbox" style="margin-left:50px;display:block;">
													<label>	
														<input type="checkbox" class="menu_access <?=$menu_sub2['parent_name'];?>" name="<?php echo $menu_sub2['menu_name']; ?>" id="<?php echo $menu_sub2['menu_name']; ?>" value="<?php echo $menu_sub2['menu_text']; ?>" <?=has_access($category_name,$menu_sub2['menu_name'],$menu_accesses);?> >
														<?php echo $this->lang->line($menu_sub2['menu_text']);?>
													</label>
												</div>	
											<?php
												}
											}
											?>
											</label>
										</div>
											<?php 
										}
										
										
									}
									?>
								</label>
								</div>
							<?php } ?>
								
							</div>
							<?php //} 
							} ?>
							<div class="form-group">
								<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
								<a href="<?=site_url('menu_access/index');?>" class="btn btn-info btn-sm square-btn-adjust"><?php echo $this->lang->line('back');?></a>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>