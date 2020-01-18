<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("<?php echo $this->lang->line('areyousure_delete');?>");
		});
		
    $('#treatments').dataTable();	
	$( "#share_amount" ).hide();
	$( "#share_type" ).change(function() {
			if($( "#share_type" ).val() == 'percentage' || $( "#share_type" ).val() == 'amount'){
				$( "#share_amount" ).show();
			}else{
				$( "#share_amount" ).hide();
			}
		});
	
} )
</script>
<?php 
	$treatment = set_value('treatment','');
	$treatment_price = set_value('treatment_price','');
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('treatments');?>
				</div>
				<div class="panel-body">
					<a class="btn btn-primary square-btn-adjust" href="<?php echo site_url("treatment/add_treatment/"); ?>"><?php echo $this->lang->line('add');?></a>
					<?php if ($treatments) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="treatments" >
						<thead>
							<tr>
								<th><?php echo $this->lang->line('no');?></th>
								<th><?php echo $this->lang->line('treatment_name');?></th>
								<th><?php echo $this->lang->line('treatment_charges');?></th>
								<?php if (in_array("doctor", $active_modules)) {	?>
								<th><?php echo $this->lang->line("department");?></th>
								<?php } ?>
								<th><?php echo $this->lang->line('tax_rate');?></th>
								<th><?php echo $this->lang->line('share_type');?></th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1;
						$tax_rate_name[0] = "";
						$tax_rate_array[0] = "0";
						?>
						<?php foreach ($treatments as $treatment):
							if( $treatment['tax_id'] != NULL){
								$tax_rate =" (". $tax_rate_array[$treatment['tax_id']]."%)";
								$tax_name = $tax_rate_name[$treatment['tax_id']];
							}else{
								$tax_rate = "";
								$tax_name = "";
							}
							if($treatment['share_type'] == 'percentage'){
								$share_amount = $treatment['share_amount'] . "%";
							}else{
								$share_amount = currency_format($treatment['share_amount']);
							}
						?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $j; ?></td>
							<td><?php echo $treatment['treatment']; ?></td>
							<td class="right"><?php echo currency_format($treatment['price']);if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></td>                
							<?php if (in_array("doctor", $active_modules)) {	?>
							<td>
								<?php $treatment_departments = explode(",",$treatment['departments']); ?>
								<?php foreach ($departments as $department):  ?>
									<?php if(in_array($department['department_id'],$treatment_departments)){ ?>
										<?php echo $department['department_name'].",";?>
									<?php } ?>
								<?php endforeach ?>
							</td>
							<?php } ?>
							<td><?php echo $tax_name.$tax_rate; ?></td>
							<td><?php echo ucfirst($treatment['share_type']) . " : " .$share_amount; ?></td>
							<td><a class="btn btn-primary square-btn-adjust" title="Visit" href="<?php echo site_url("treatment/edit_treatment/" . $treatment['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger square-btn-adjust confirmDelete" title="<?php echo $this->lang->line('delete_treatment')." : " . $treatment['treatment'] ?>" href="<?php echo site_url("treatment/delete_treatment/" . $treatment['id']); ?>"><?php echo $this->lang->line('delete');?></a></td>
            </tr>
            <?php $i++; $j++;?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>  
<?php }else{ ?>
	<?php echo $this->lang->line('no_treatment_added_add_treatment');?>
<?php } ?>				
				</div>