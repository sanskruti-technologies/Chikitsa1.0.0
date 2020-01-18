<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	
	<?php if($treatment['share_type']=="amount" || $treatment['share_type']=="percentage" ){ ?>
		$( "#share_amount" ).show();
	<?php } ?>	
	$( "#share_type" ).change(function() {
		if($( "#share_type" ).val() == 'percentage' || $( "#share_type" ).val() == 'amount'){
			$( "#share_amount" ).show();
		}else{
			$( "#share_amount" ).hide();
		}
	});
})
</script>
<?php
	$treatment_name = set_value('treatment',"");
	$treatment_price = set_value('treatment_price',"");
	$treatment_share_amount = set_value('share_amount',"");
	$treatment_tax_id = set_value('treatment_rate',"");
	$treatment_share_type = set_value('share_type',"");
	$treatment_departments = set_value('department_id',"");
	if(isset($treatment)){
		$treatment_name = set_value('treatment',$treatment['treatment']);
		$treatment_price = set_value('treatment_price',$treatment['price']);
		$treatment_share_amount = set_value('share_amount',$treatment['share_amount']);
		$treatment_tax_id = set_value('treatment_rate',$treatment['tax_id']);
		$treatment_share_type = set_value('share_type',$treatment['share_type']);
		$treatment_departments = set_value('department_id',$treatment['departments']);
	}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				<?php if(isset($treatment)){ ?>
				<?php echo $this->lang->line('edit_treatment');?>
				<?php }else{ ?>
				<?php echo $this->lang->line('add_treatment');?>
				<?php } ?>
			</div>
			<div class="panel-body">
				<div class="col-md-6"> 
				<?php if(isset($treatment)){ ?>
				<?php echo form_open('treatment/edit_treatment/'.$treatment['id']) ?>
				<input type="hidden" name="treatment_id" id="treatment_id" value="<?php echo $treatment['id']; ?>" class="form-control"/>	
					
				<?php }else{?>
				<?php echo form_open('treatment/add_treatment/') ?>
				<?php } ?>
					<div class="form-group">
						<label for="treatment"><?php echo $this->lang->line('treatment');?></label> 
						<input type="text" name="treatment" id="treatment" value="<?php echo $treatment_name; ?>" class="form-control"/>
						<?php echo form_error('treatment','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="treatment_price"><?php echo $this->lang->line('charges_fees');?></label>
						<input type="text" name="treatment_price" id="treatment_price" value="<?php echo $treatment_price; ?>" class="form-control"/>
						<?php echo form_error('treatment_price','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<?php if (in_array("doctor", $active_modules)) { ?>
						<?php $department_array = explode(",",$treatment_departments); ?>
						<div class="form-group">
							<label for="department_id"><?=$this->lang->line("department");?></label>
							<select id="department_id" name="department_id[]" multiple="multiple" class="form-control">  <option></option>
								<?php if(isset($departments)) { ?>
									<?php  foreach ($departments as $department) { ?>
									<?php $selected = ""; ?>
									<?php if(in_array($department['department_id'],$department_array)) {$selected = "selected";} ?>
									<option value="<?=$department['department_id'] ?>" <?=$selected;?> ><?= $department['department_name']; ?> </option>
									<?php } ?>
								<?php } ?>
							</select>								
							<?php echo form_error('department_id','<div class="alert alert-danger">','</div>'); ?>
							<script>jQuery('#department_id').chosen();</script>
						</div>
					<?php } ?>
					<div class="form-group">
						<label for="treatment_rate"><?php echo $this->lang->line('tax_rate');?></label>
						<select type="text" class="form-control"  name="treatment_rate" id="treatment_rate">
							<option value="0"><?php echo $this->lang->line('select_tax_rate');?></option>
							<?php foreach($tax_rates as $tax_rate){ 
							$selected = "";
							if($tax_rate['tax_id'] == $treatment_tax_id) {
								$selected = "selected";
							}?>
							<option value="<?= $tax_rate['tax_id']; ?>" <?=$selected;?>><?= $tax_rate['tax_rate_name']; ?></option>
							<?php } ?>
						</select>
						<?php echo form_error('treatment_rate','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="share_type"><?= $this->lang->line('share_type');?></label>
							<select id="share_type" name="share_type" class="form-control">
								<option value="0"><?= $this->lang->line('select');?></option>
								<option value="amount"<?php if($treatment_share_type =="amount"){ ?> selected="selected" <?php } ?>><?= $this->lang->line('amount');?></option>
								<option value="percentage" <?php if($treatment_share_type="percentage"){ ?> selected="selected" <?php } ?>><?= $this->lang->line('percentage');?></option>
							</select>
						<?php echo form_error('share_type','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<input type="text" class="form-control"  name="share_amount" id="share_amount" value="<?= $treatment_share_amount ?>"/>
						<?php echo form_error('share_amount','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" /><?php echo $this->lang->line('save');?></button>
						<a href="<?=site_url('treatment/index');?>" class="btn btn-primary square-btn-adjust btn-sm"><?= $this->lang->line('back');?></a>
					</div>
				<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>