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
<?php 
	if(isset($opening_stock)){
		$edit = TRUE;
		$added_date = set_value('added_date',date($def_dateformate,strtotime($opening_stock['added_date'])));
		$stock_id = set_value('stock_id',$opening_stock['stock_id']);
		$item_id = set_value('item_id',$opening_stock['item_id']);
		$item_name = set_value('item_name',$opening_stock['item_name']);
		$quantity = set_value('quantity',$opening_stock['quantity']);
		$price = set_value('price',$opening_stock['price']);
	}else{
		$edit = FALSE;
		$added_date = set_value('added_date',date($def_dateformate));
		$stock_id = set_value('stock_id',"");
		$item_id = set_value('item_id',"");
		$item_name = set_value('item_name',"");
		$quantity = set_value('quantity',"");
		$price = set_value('price',"");
	}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				<?php echo $this->lang->line('opening') . ' ' .$this->lang->line('stock');?>
			</div>
			<div class="panel-body">
			<?php if($edit){ ?>
			<?php echo form_open('stock/edit_opening_stock/'.$opening_stock['stock_id']) ?>
			<?php }else{ ?>
			<?php echo form_open('stock/add_opening_stock/') ?>
			<?php } ?>
				<?php $today = date('Y-m-d'); ?>
				<div class="col-md-3">
					<div class="form-group">
						<label for="added_date"><?php echo $this->lang->line('added_date');?></label> 
						<input type="text" name="added_date" id="added_date" value="<?=date($def_dateformate,strtotime($added_date));?>" class="form-control"/>					
						<?php echo form_error('added_date','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<input type="hidden" name="stock_id" id="stock_id" value="<?=$stock_id;?>"/>
				<input type="hidden" name="item_id" id="item_id" value="<?=$item_id;?>"/>
				<div class="col-md-3">
					<div class="form-group">
						<label for="item_name"><?php echo $this->lang->line('item');?></label> 
						<input type="input" name="item_name" id="item_name" class="form-control" value="<?=$item_name;?>"/>
						<?php echo form_error('item_id','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="quantity"><?php echo $this->lang->line('quantity');?></label> 
						<input type="input" name="quantity" class="form-control" value="<?=$quantity;?>"/>
						<?php echo form_error('quantity','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label for="price"><?php echo $this->lang->line('average_price');?></label> 
						<input type="input" name="price" class="form-control" value="<?=$price;?>"/>
						<?php echo form_error('price','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" /><?php echo $this->lang->line('save');?></button>
						<a class="btn-sm square-btn-adjust btn btn-primary" href="<?=site_url('stock/opening_stock');?>"><?php echo $this->lang->line('back');?></a>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
