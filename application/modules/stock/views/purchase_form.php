<script type="text/javascript" charset="utf-8">
$(window).load(function() { 
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	})
	$( "#purchase_date" ).datetimepicker({
		timepicker:false,
		format: '<?=$def_dateformate; ?>'
	});
  $("#item_name").autocomplete({
        source: [<?php
                $i=0;
                foreach ($items as $item){
                    if ($i>0) {echo ",";}
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
	
	$("#supplier_name").autocomplete({
        source: [<?php
                $i=0;
                foreach ($suppliers as $supplier){
                    if ($i>0) {echo ",";}
                    echo '{value:"' . $supplier['first_name'].' '.$supplier['middle_name'].' '.$supplier['last_name'] . '",id:"' . $supplier['supplier_id'] . '"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#supplier_id").val(ui.item ? ui.item.id : '');

        }
    });
	oTable = $('#item_table').dataTable({
        "aaSorting": [[ 1, "asc" ]],
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    }); 	
})
</script>
<?php 
	if(isset($purchase)){
		$purchase_date = date($def_dateformate,strtotime($purchase['purchase_date']));
		$bill_no = set_value('bill_no',$purchase['bill_no']);
		$item_id = set_value('item_id',$purchase['item_id']);	
		$item_name = set_value('item_name',$purchase['item_name']);
		$quantity = set_value('quantity',$purchase['quantity']);
		$supplier_id = set_value('supplier_id',$purchase['supplier_id']);
		$supplier_name = set_value('supplier_name',$purchase['supplier_name']);
		$cost_price = set_value('cost_price',$purchase['cost_price']);
	}else{
		$purchase_date = date($def_dateformate);
		$bill_no = set_value('bill_no',"");
		$item_id = set_value('item_id',"");
		$item_name = set_value('item_name',"");
		$quantity = set_value('quantity',"");
		$supplier_id = set_value('supplier_id',"");
		$supplier_name = set_value('supplier_name',"");
		$cost_price = set_value('cost_price',"");
	}
	
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
					<?php echo $this->lang->line('purchase');?>
			</div>
			<div class="panel-body">
			<?php if(isset($purchase)){?>
			<?php echo form_open('stock/edit_purchase/'. $purchase['purchase_id']) ?>
			<input type="hidden" name="purchase_id" id="purchase_id" value="<?=$purchase['purchase_id']?>"/>
			<?php }else{ ?>
			<?php echo form_open('stock/add_purchase/') ?>
			<?php } ?>
				<div class="col-md-3">
					<div class="form-group">
						
						<label for="purchase_date"><?php echo $this->lang->line('purchase_date');?></label>
						<input type="input" name="purchase_date" id="purchase_date" class="form-control" value="<?=$purchase_date;?>"/>																	
						<?php echo form_error('purchase_date','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="bill_no"><?php echo $this->lang->line('bill_no');?></label>
						<input type="input" name="bill_no" id="bill_no" class="form-control" value="<?=$bill_no?>"/>						
						<?php echo form_error('bill_no','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">

						<label for="item_name"><?php echo $this->lang->line('item_name');?></label> 
						<input type="input"  id="item_name" name="item_name" value="<?=$item_name?>" class="form-control"/>		
						<?php echo form_error('item_name','<div class="alert alert-danger">','</div>'); ?>
						<input type="hidden" name="item_id" id="item_id" value="<?=$item_id;?>"/>
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
						<input type="hidden" name="supplier_id" id="supplier_id" value="<?=$supplier_id;?>"/>
						<label for="supplier_name"><?php echo $this->lang->line('supplier');?></label> 
						<input type="input" name="supplier_name" id="supplier_name" class="form-control" value="<?=$supplier_name;?>"/>
						<?php echo form_error('supplier_id','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="cost_price"><?php echo $this->lang->line('cost_price');?></label> 
						<input type="input" name="cost_price" class="form-control" value="<?=$cost_price;?>"/>
						<?php echo form_error('cost_price','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" /><?php echo $this->lang->line('save');?></button>
						<a class="btn-sm square-btn-adjust btn btn-primary" href="<?=site_url('stock/purchase');?>"><?php echo $this->lang->line('back');?></a>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>