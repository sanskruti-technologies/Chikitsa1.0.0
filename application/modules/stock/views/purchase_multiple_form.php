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

	$('#purchase_table').dataTable();
	$('#price').change(function(){
			var price = document.getElementById("price").value;
			var quantity = document.getElementById("quantity").value;
			var amount= price * quantity;
			document.getElementById("amount").value = amount;
	});

	var flag = "false";
	//show table
	function display_table(records){

		var purchase=records[0]['purchases'];
		console.log(purchase);
		$('#purchase_items tr:gt(0)').remove()
		var total=0;
		$.each(purchase, function( index, purchase ) {
						total = Number(total) + Number(purchase.cost_price);
						var del_icon='<i  class="fa fa-trash btn btn-danger confirmDelete btn-sm square-btn-adjust" data-purchase_id='+ purchase['purchase_id']  +'  aria-hidden="true"></i>';
						var edit_icon='<i class="fa fas fa-pencil  edit btn btn-primary square-btn-adjust" id="onEdit" data-purchase_id='+ purchase['purchase_id']   +'  aria-hidden="true"></i>';
						var bill_no = '<input type="hidden" id="bill_no" value="'+ purchase['bill_no']  +'" />';
						//$('#purchase_items').append('<tr><td class="editableColumns">'+ purchase.item_name +'</td><td style="text-align:right;">' + purchase.quantity + '</td><td></td><td style="text-align:right;">'+purchase.cost_price+'</td><td>'+edit_icon+' '+del_icon+' '+bill_no+'</td></tr>');
						$('#purchase_items').append('<tr><td class="editableColumns">'+ purchase.item_name +'</td><td class="editableColumns qty" >' + purchase.quantity + '</td><td class="editableColumns"></td><td class="editableColumns cost">'+purchase.cost_price+'</td><td class="del_td">'+edit_icon+' '+del_icon+' '+bill_no+'</td></tr>');


					document.getElementById("item_id").value="";
					document.getElementById("item_name").value="";
					document.getElementById("quantity").value="";
					document.getElementById("price").value="";
					document.getElementById("amount").value="";
					});
					$('#purchase_items').append('<tr><td colspan="3" style="text-align:right;"><b>Total</b></td><td style="text-align:right;">'+(total)+'</td><td></td></tr>');

	}
	//delete perticuler Purchase item
	$(document).on("click", '.confirmDelete', function(event) {
		var p_id = $(this).data('purchase_id');
		var bill_no = document.getElementById("bill_no").value;
			$.ajax({
				type: "POST",
				url: "<?=site_url('stock/ajax_delete_purchase_item/');?>",
				data: {bill_no:bill_no,p_id:p_id},
				success: function (result) {
					var records = JSON.parse(result);
					console.log(records);
					display_table(records);

				}
			});
	});
	//add purchase_item
	$('#add_new_purchase_item').click(function(){
		var bill_no = document.getElementById("bill_no").value;
		var purchase_date = document.getElementById("purchase_date").value;
		var supplier_id = document.getElementById("supplier_id").value;
		var item_id = document.getElementById("item_id").value;
		var quantity = document.getElementById("quantity").value;
		var price = document.getElementById("price").value;
		var amount = document.getElementById("amount").value;


		$.ajax({
			type: "POST",
			url: "<?=site_url('stock/ajax_add_purchase/');?>",
			data: {	bill_no:bill_no,
						  purchase_date:purchase_date,
							supplier_id:supplier_id,
							item_id:item_id,
							quantity:quantity,
							amount:amount},
			success: function (result) {

				var records = JSON.parse(result);
				console.log(records);
				display_table(records);

			}
		});
	});
	//convert td into input element
	$(document).on("click", '.edit', function(event) {
		$(this).removeClass('fa-pencil');
		$(this).removeClass('edit');
		$(this).addClass('fa-check');
		$(this).addClass('save');

		var qty = $(this).parents('tr').find('td.qty').html();
		var cost =$(this).parents('tr').find('td.cost').html();
		var price=cost / qty;
		//alert(price);

		var txt='edit_txt_';
		var id=1;
		var readonly='readonly';
		$(this).parents('tr').find('td.editableColumns').each(function() {
			txt_id=txt + id;
			var html = $(this).html();
			var input = $('<input class="form-control" type="text"  id='+ txt_id +' />');

			if(id==4){
				var input = $('<input class="form-control" type="text"  id='+ txt_id +' '+readonly +' />');
			}
			input.val(html);
			if(id==3){
				input.val(price);
			}
			$(this).html(input);
			id=id +1;
		});
	});

	$(document).on("change", '#edit_txt_3', function(event) {
			var e_price = document.getElementById("edit_txt_3").value;
			var e_quantity = document.getElementById("edit_txt_2").value;
			var e_amount= e_price * e_quantity;
			document.getElementById("edit_txt_4").value = e_amount;
	});

	//edit purchase item
	$(document).on("click", '.save', function(event) {
		var p_id = $(this).data('purchase_id');
		var bill_no = document.getElementById("bill_no").value;

		var item_name = document.getElementById("edit_txt_1").value;
		var item_qty = document.getElementById("edit_txt_2").value;
		var item_price = document.getElementById("edit_txt_3").value;
		var item_cost_price = document.getElementById("edit_txt_4").value;

		$.ajax({
			type: "POST",
			url: "<?=site_url('stock/ajax_edit_purchase/');?>",
			data: {p_id:p_id,item_name:item_name,quantity:item_qty,amount:item_cost_price,bill_no:bill_no},
			success: function (result) {
				var records = JSON.parse(result);
				console.log(records);
				display_table(records);

			}
		});

	});

})
</script>
<?php

//set bill_no

		$purchase_date = date($def_dateformate);
		$bill_no = set_value('bill_no',"");
		$item_id = set_value('item_id',"");
		$item_name = set_value('item_name',"");
		$quantity = set_value('quantity',"");
		$supplier_id = set_value('supplier_id',"");
		$supplier_name = set_value('supplier_name',"");
		$cost_price = set_value('cost_price',"");


?>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('purchase');?></h1>
				<a class="btn btn-success btn-sm square-btn-adjust" title="" href="<?php echo site_url("stock/add_purchase/"); ?>"><?= $this->lang->line('new')." ".$this->lang->line('purchase');?></a>
				<a class="btn btn-primary btn-sm square-btn-adjust" title="" href="<?php echo site_url("stock/purchase/"); ?>"><?= $this->lang->line('back');?></a>
					<br/>
					<br/>
			<?php echo form_open('stock/add_purchase/') ?>
			<div class="row">
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
						<input type="hidden" name="supplier_id" id="supplier_id" value="<?=$supplier_id;?>"/>
						<label for="supplier_name"><?php echo $this->lang->line('supplier');?></label>
						<input type="input" name="supplier_name" id="supplier_name" class="form-control" value="<?=$supplier_name;?>"/>
						<?php echo form_error('supplier_id','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="purchase_table">
							<thead>
								<tr>
									<th><?= $this->lang->line('item');?></th>
									<th><?= $this->lang->line('quantity');?></th>
									<th><?= $this->lang->line('price');?> </th>
									<th><?= $this->lang->line('amount');?> </th>
									<th><?= $this->lang->line('action');?></th>
								</tr>
							</thead>
							<tbody id="purchase_items">
								<tr id="new_purchase_item">
									<td><input type="text" name="item_name" id="item_name" class="form-control" placeholder="<?= $this->lang->line('item')." ".$this->lang->line('name');?>"/>
									<input type="hidden" name="item_id" id="item_id" class="form-control"/>	</td>
									<td><input type="input" name="quantity" id="quantity" class="form-control" placeholder="<?= $this->lang->line('quantity');?>"/>	</td>
									<td><input type="input" name="price" id="price" class="form-control" placeholder="<?= $this->lang->line('price');?>"/></td>
									<td><input type="input" name="amount" id="amount" value="" readonly class="form-control" placeholder="<?= $this->lang->line('amount');?>"/></td>
									<td><a id="add_new_purchase_item" class="btn btn-primary square-btn-adjust btn-sm" href="#"><i class="fa fa-plus" aria-hidden="true"></i></a></td>

								</tr>

							</tbody>
						</table>
					</div>
			<?php echo form_close(); ?>
			</div>
		</div>
