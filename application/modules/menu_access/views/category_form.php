<?php
 if(isset($category)){
	 $edit = TRUE;
	 $category_name = $category['category_name'];
	 $category_id = $category['id'];
 }else{
	 $edit = FALSE;
	 $category_name = "";
 }
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php if($edit){ ?>
					<?php echo $this->lang->line("edit")."  ". $this->lang->line('category');?>
					<?php }else{ ?>
					<?php echo $this->lang->line("add")."  ". $this->lang->line('category');?>
					<?php } ?>
				</div>
				<div class="panel-body">
						<?php if($edit){ ?>
						<?php echo form_open_multipart('menu_access/edit_category/'.$category_id) ?>						
						<?php }else{ ?>
						<?php echo form_open_multipart('menu_access/add_category/') ?>						
						<?php } ?>					
						<div class="col-md-6">
							<div class="form-group">
							<input type="hidden" name="id" class="inline" value="<?=$categories['id']?>"/>
								<label for="category_name"><?php echo $this->lang->line('category');?></label>
								<input type="input" name="category_name" class="form-control" value="<?=$category_name;?>" required/>
								<?php echo form_error('category_name','<div class="alert alert-danger">','</div>'); ?>
							</div>							
							<div class="form-group">
								<button class="btn btn-primary square-btn-adjust btn-sm" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
								<a href="<?=site_url('menu_access/category');?>" class="btn btn-info btn-sm square-btn-adjust"><?php echo $this->lang->line('back');?></a>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		
		</div>
	</div>
</div>
