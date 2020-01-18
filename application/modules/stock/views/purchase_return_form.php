<script type="text/javascript" charset="utf-8">
$(window).load(function() {

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
                    echo '{value:"' . $supplier['first_name'].' '.$supplier['middle_name'] .' '.$supplier['last_name'] .'",id:"' . $supplier['supplier_id'] . '"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#supplier_id").val(ui.item ? ui.item.id : '');

        },
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#supplier_id").val('');
			}
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
	if(isset($purchase_return)){
		$purchase_return_id = set_value('return_id',$purchase_return['return_id']);
		$purchase_return_date = set_value('return_date',date($def_dateformate,strtotime($purchase_return['return_date'])));
		$purchase_bill_no = set_value('bill_no',$purchase_return['bill_no']);
		$item_id = set_value('item_id',$purchase_return['item_id']);
		$item_name = set_value('item_name',$purchase_return['item_name']);
		$quantity = set_value('quantity',$purchase_return['quantity']);
		$supplier_id = set_value('supplier_id',$purchase_return['supplier_id']);
		$supplier_name = set_value('supplier_name',$purchase_return['supplier_name']);
		$price = set_value('price',$purchase_return['price']);
	}else{
		$purchase_return_date = set_value('return_date',date($def_dateformate));
		$purchase_bill_no = set_value('bill_no',"");
		$item_id = set_value('item_id',"");
		$item_name = set_value('item_name',"");
		$quantity = set_value('quantity',"");
		$supplier_id = set_value('supplier_id',"");
		$supplier_name = set_value('supplier_name',"");
		$price = set_value('price', "");
	}
?>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('purchase').' '.$this->lang->line('return');?></h1>

			<?php if(isset($purchase_return)){ ?>
			<?php echo form_open('stock/edit_purchase_return/'. $purchase_return_id) ?>
			<input type="hidden" name="return_id" id="return_id" value="<?=$purchase_return_id;?>"/>
			<?php }else{ ?>
			<?php echo form_open('stock/add_purchase_return/') ?>
			<?php } ?>
				<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label for="return_date"><?php echo $this->lang->line('return_date');?></label>
						<input type="input" name="return_date" id="return_date" class="form-control" value="<?=$purchase_return_date;?>"/>
						<?php echo form_error('return_date','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="bill_no"><?php echo $this->lang->line('bill_no');?></label>
						<input type="input" name="bill_no" id="bill_no" class="form-control" value="<?=$purchase_bill_no;?>"/>
						<?php echo form_error('bill_no','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<input type="hidden" name="item_id" id="item_id" value="<?=$item_id?>"/>
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
				</div>
				<div class="row">
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
						<label for="price"><?php echo $this->lang->line('price');?></label>
						<input type="input" name="price" class="form-control" value="<?=$price;?>"/>
						<?php echo form_error('price','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				</div>
				<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm" /><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $this->lang->line('save');?></button>
						<a class="btn-sm square-btn-adjust btn btn-primary" href="<?=site_url('stock/purchase_return');?>"><?php echo $this->lang->line('back');?></a>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
			</div>
