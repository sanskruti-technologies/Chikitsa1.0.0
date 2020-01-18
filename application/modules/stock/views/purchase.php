<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	});
	$('#purchase_table').dataTable();

		$("#from_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
			onShow:function( ct ){
				//var ToDate = $.datepicker.formatDate('yy/mm/dd', new Date($('#to_date').val()));
				var ToDate = $('#to_date').val();
				this.setOptions({
					maxDate:ToDate?ToDate:false,
					formatDate:'<?=$def_dateformate; ?>'
				})
			}
		});
		$("#to_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
			onShow:function( ct ){
				//var FromDate = $.datepicker.formatDate('yy/mm/dd', new Date($('#from_date').val()));
				var FromDate = $('#from_date').val();
				this.setOptions({
					minDate:FromDate?FromDate:false,
					formatDate:'<?=$def_dateformate; ?>'
				})
			}
		});

    });

</script>
<?php
	if($from_date == NULL){
		$from_date = "";
	}else{
		$from_date = date($def_dateformate,strtotime($from_date));
	}

	if($to_date == NULL){
		$to_date = "";
	}else{
		$to_date = date($def_dateformate,strtotime($to_date));
	}

?>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('purchase');?></h1>
			<a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("stock/add_purchase/"); ?>"><?= $this->lang->line('add')." ".$this->lang->line('purchase');?></a>
			<div class="col-md-12">
				<?php echo form_open('stock/purchase'); ?>
				<div class="col-md-3">
					<label><?php echo $this->lang->line('from_date');?></label>
					<input type="text" name="from_date" id="from_date" class="form-control" value="<?=$from_date;?>" />
				</div>
				<div class="col-md-3">
					<label><?php echo $this->lang->line('to_date');?></label>
					<input type="text" name="to_date" id="to_date" class="form-control" value="<?=$to_date;?>" />
				</div>
				<!--<div class="col-md-3">
					<label><?php echo $this->lang->line('item');?></label>
					<select id="items" class="form-control" multiple="multiple" tabindex="4" name="items[]">
						<?php foreach ($items as $item) {
							echo "<option value='".$item['item_id']."'";
							foreach ($selected_items as $selected_item){
								if($item['item_id'] == $selected_item){
									echo " selected ";
								}
							}
							echo ">".$item['item_name']."</option>";
						} ?>
					</select>
					<script>jQuery('#items').chosen();</script>
				</div>-->
				<div class="col-md-3">
					<label>&nbsp;</label>
					<input type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" value="<?php echo $this->lang->line('filter');?>"></input>
					<a target="_blank" href="<?= site_url('stock/print_purchase_report/'.$items_csv.'/'.$from_date.'/'.$to_date);?>" class="btn btn-primary square-btn-adjust btn-sm" /><?= $this->lang->line('print');?></a>
				</div>
				<?php echo form_close(); ?>
			</div>
			<div class="col-md-12">

			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="purchase_table">
				<thead>
					<tr>
						<th><?php echo $this->lang->line('sr_no');?></th>
						<th><?php echo $this->lang->line('purchase_date');?></th>
						<th><?php echo $this->lang->line('bill_no');?></th>
						<th><?php echo $this->lang->line('item');?></th>
						<th><?php echo $this->lang->line('supplier');?></th>
					</tr>
				</thead>
				<tbody>
				<?php //print_r($purchases);
				$pre_bill="";
				$pre_item="";
				$item_name;
				$j=0;
				$i=1;
					foreach ($purchases as $purchase){
						$item_name=$purchases[$j]['item_name'];
						if($j>0){
							$pre_bill=$purchases[$j-1]['bill_no'];
						}

						if($pre_bill==$purchases[$j]['bill_no']){
							//echo "<br/>$item_name";
							$item_name=$purchases[$j-1]['item_name'].",".$item_name;
							?>
							<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
							<td><?php echo $i; ?></td>
							<td><?php echo date($def_dateformate,strtotime($purchase['purchase_date']));?></td>
							<td><?php echo $purchase['bill_no'] ?></td>
							<td><?php echo $item_name ?></td>
							<td><?php echo $purchase['supplier_name'] ?></td>
							</tr>
							<?php $i++;
						}elseif($purchases[$j]['bill_no']==$purchases[$j+1]['bill_no']){
							$item_name="";
						}else{

							?>
							<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
							<td><?php echo $i; ?></td>
							<td><?php echo date($def_dateformate,strtotime($purchase['purchase_date']));?></td>
							<td><?php echo $purchase['bill_no'] ?></td>
							<td><?php echo $item_name ?></td>
							<td><?php echo $purchase['supplier_name'] ?></td>
							</tr>
						<?php $i++;
						}
						$j++;

					}

				 ?>
			</table>
			</div>



		</div>
	</div>
	</div>
	</div>
</div>
