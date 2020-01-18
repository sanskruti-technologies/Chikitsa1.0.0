<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('#return_date').datetimepicker({
		timepicker:false,
		format: '<?=$def_dateformate; ?>',
		maxDate:0,
		scrollMonth:false,
		scrollTime:false,
		scrollInput:false,
	});

	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	});
	$("#item_name").autocomplete({
        source: [<?php
                $i=0;
                foreach ($items as $item){
    if ($i > 0) {
        echo ",";
    }
                    echo '{value:"' . $item['item_name'] . '",id:"' . $item['item_id'] . '"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#item_id").val(ui.item ? ui.item.id : '');
        }
    });

	$('#purchase_table').dataTable();

});

</script>

<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('opening') . ' ' .$this->lang->line('stock');?></h2></div>
				</div>
			</div>
			<div class="panel-body table-responsive-25">
				  <div class="form-group">
			   <a class="btn btn-primary square-btn-adjust btn-sm" href="<?php echo site_url("stock/add_opening_stock/" ); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add'). " ".$this->lang->line('opening_stock');?></a>
			</div>
			<div class="col-md-12">
				<?php echo form_open('stock/opening_stock'); ?>
				<div class="col-md-6">
				 <label><?php echo $this->lang->line('item');?></label>
					<select id="items" class="form-control" multiple="multiple" tabindex="4" name="items[]">
						<?php foreach ($items as $item) {
							echo "<option value='".$item['item_id']."'";
							if($selected_items != NULL){
							foreach ($selected_items as $selected_item){
								if($item['item_id'] == $selected_item){
									echo " selected ";
								}
							}}
							echo ">".$item['item_name']."</option>";
						} ?>
					</select>
					<script>jQuery('#items').chosen();</script>
				</div>
				<div class="col-md-3">
					<label>&nbsp;</label>
					<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" ><i class="fa fa-filter"></i>&nbsp;<?php echo $this->lang->line('filter');?></button>
				</div>
				<?php echo form_close(); ?>
			</div>

			<div class="col-md-12 table-responsive">
				<table class="table table-striped table-hover display responsive nowrap" id="purchase_table">
				<thead>
					<tr>
						<th><?php echo $this->lang->line('sr_no');?></th>
						<th><?php echo $this->lang->line('added_date');?></th>
						<th><?php echo $this->lang->line('item');?></th>
						<th><?php echo $this->lang->line('quantity');?></th>
						<th><?php echo $this->lang->line('price');?></th>
						<th><?php echo $this->lang->line('edit');?></th>
						<th><?php echo $this->lang->line('delete');?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; ?>
					<?php foreach ($opening_stocks as $opening_stock):  ?>
					<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
						<td><?php echo $i; ?></td>
						<td><?php echo date($def_dateformate,strtotime($opening_stock['added_date']));?></td>
						<td><?php echo $opening_stock['item_name'] ?></td>
						<td><?php echo $opening_stock['quantity'] ?></td>
						<td style="text-align:right"><?php echo currency_format($opening_stock['price']);if($currency_postfix) echo $currency_postfix; ?></td>
						<td>
						<!--a class="btn btn-primary" title="Edit" href="<?php echo site_url("stock/edit_opening_stock/" . $opening_stock['stock_id']); ?>">Edit</a-->
						<a class="btn btn-primary btn-sm square-btn-adjust editbt" title="Edit" href="<?php echo site_url("stock/edit_opening_stock/" . $opening_stock['stock_id']); ?>"><i class="fa fa-pencil"></i></a>
						</td>
						<td>
						<!--a class="btn btn-danger square-btn-adjust confirmDelete junkcss-rm" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_opening_stock/" . $opening_stock['stock_id']); ?>"><?php echo $this->lang->line('delete');?></a-->
						<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_opening_stock/" . $opening_stock['stock_id']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</td>
					</tr>
					<?php $i++; ?>
					<?php endforeach ?>
				</tbody>
				</table>
			</div>
			</div>
			</div>
		</div>
	</div>
</div>
