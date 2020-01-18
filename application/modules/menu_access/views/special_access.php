<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$(".back_date_visit_parent").change(function(){
		$('.back_date_visit').prop('checked', this.checked);
	});
	if ($('.back_date_visit:checked').length == $('.back_date_visit').length) {
	   $('.back_date_visit_parent').prop('checked', true);
	   $(".back_date_visit_parent").prop("indeterminate", false);
	}else {
		if($('.back_date_visit:checked').length == 0){
			$('.back_date_visit_parent').prop('checked', false);
		}else{
			$(".back_date_visit_parent").prop("indeterminate", true);
		}
	}
	$(".back_date_visit").change(function(){
		if ($('.back_date_visit:checked').length == $('.back_date_visit').length) {
		   $('.back_date_visit_parent').prop('checked', true);
		   $(".back_date_visit_parent").prop("indeterminate", false);
		}else {
			if($('.back_date_visit:checked').length == 0){
				$('.back_date_visit_parent').prop('checked', false);
			}else{
				$(".back_date_visit_parent").prop("indeterminate", true);
			}
		}
	});
});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
	<!-- Page Heading -->

			<?php echo form_open('menu_access/save_special_access/') ?>
				<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="back_date_visit_all" class="back_date_visit_parent" value="all" />Allow to add or edit Visit in Back Date
								<?php foreach($categories as $category){?>
									<?php $checked = ""; ?>
									<?php foreach($special_access as $access){?>
										<?php if(($access['access_name'] == 'back_date_visit') && ($access['category_name'] == $category['category_name'])){ ?>
											<?php $checked = "checked"; ?>
										<?php } ?>
									<?php } ?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="back_date_visit[]" class="back_date_visit" value="<?=$category['category_name'];?>" <?php echo $checked;?> ><?=$category['category_name'];?>
										</label>
									</div>
								<?php } ?>
							</label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<input class="btn btn-sm btn-primary" type="submit" value="Save" name="submit">
				</div>
				<?php echo form_close(); ?>

			</div>
			</div>
