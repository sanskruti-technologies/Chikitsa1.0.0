<script type="text/javascript" charset="utf-8">
$(window).load(function() {

	<?php if (in_array("prescription", $active_modules)) { ?>
		var patient_id = $('#patient_id').val();
		if(patient_id != ""){
			$("#from_prescription_div").html('<a id="from_prescription" data-patient_id="'+patient_id+'" href="<?=site_url('stock/fetch_from_prescriotion/');?>'+patient_id+'" class="btn btn-success btn-sm square-btn-adjust">Fetch From Prescription</a>');
		}
		else{
			$("#from_prescription_div").html('<label><?php echo $this->lang->line('no_prescription');?> </label>');
		}
	<?php } ?>
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	});
	$( "#sell_date" ).datetimepicker({
		timepicker:false,
		format: '<?=$def_dateformate; ?>',
		scrollMonth:false,
		scrollTime:false,
		scrollInput:false,
	});
	$("#patient_name").autocomplete({
		autoFocus: true,
        source: [<?php
                $i=0;
                foreach ($patients as $patient){
                    if ($i>0) {echo ",";}
                    echo '{value:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " .$patient['last_name'] . '",id:"' . $patient['patient_id'] . '"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
			var patient_id = ui.item ? ui.item.id : '';
            $("#patient_id").val(patient_id);
			<?php if (in_array("prescription", $active_modules)) { ?>
			$("#from_prescription_div").html('<a id="from_prescription" data-patient_id="'+patient_id+'" href="<?=site_url('stock/fetch_from_prescriotion/');?>'+patient_id+'" class="btn btn-success btn-sm square-btn-adjust">Fetch From Prescription</a>');
			<?php }else{?>
			$("#from_prescription_div").html('<label><?php echo $this->lang->line('no_prescription');?> </label>');
			<?php } ?>
		},
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#patient_name").val('');
				}
		},
    });
	$("#item_name").autocomplete({
        source: [<?php
                $i=0;
                foreach ($items as $item){
                    if ($i>0) {echo ",";}
                    echo '{value:"' . $item['item_name'] . '",id:"' . $item['item_id'] . '",mrp:"'.$item['mrp'].'",available_quantity:"'.$item['available_quantity'].'",barcode:"'.$item['barcode'].'"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#item_id").val(ui.item ? ui.item.id : '');
			$("#available_quantity").val(ui.item ? ui.item.available_quantity : '');
			$("#barcode").val(ui.item ? ui.item.barcode : '');
			$("#sell_price").val(ui.item ? ui.item.mrp : '');

        },
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#item_name").val('');
				}
		},
    });
	$("#barcode").autocomplete({
        source: [<?php
                $i=0;
                foreach ($items as $item){
                    if ($i>0) {echo ",";}
                    echo '{value:"' . $item['barcode'] . '",id:"' . $item['item_id'] . '",mrp:"'.$item['mrp'].'",available_quantity:"'.$item['available_quantity'].'",item_name:"'.$item['item_name'].'"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#item_id").val(ui.item ? ui.item.id : '');
			$("#available_quantity").val(ui.item ? ui.item.available_quantity : '');
			$("#item_name").val(ui.item ? ui.item.item_name : '');
			$("#sell_price").val(ui.item ? ui.item.mrp : '');

        },
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#barcode").val('');
				}
		},
    });
    $('#sell_table').dataTable();

	$(document).on('click','#from_prescription',function(event) {
		event.preventDefault();
		var patient_id = $(this).data( "patient_id" );
		$.get("<?=site_url('stock/ajax_prescribed_medicine/');?>"+patient_id, function(data, status){
			var prescription = $.parseJSON(data);
			var medicine_table = "<table class='table table-striped table-bordered table-hover dataTable'>";
			if(prescription==""){
				medicine_table = medicine_table + "<td><?php echo $this->lang->line('no_prescription');?></td>";
			$('#add_medicine').hide();
			}else{
				medicine_table = medicine_table + "<tr><th><?php echo $this->lang->line('add');?></th>";
				medicine_table = medicine_table + "<th><?php echo $this->lang->line('prescription');?></th>";
				medicine_table = medicine_table + "<th><?php echo $this->lang->line('available');?>";
				medicine_table = medicine_table + "<?php echo ' ';?>";
				medicine_table = medicine_table +"<?php echo $this->lang->line('quantity');?></th>";
				medicine_table = medicine_table + "<th><?php echo $this->lang->line('quantity');?></th><tr/>";
				$('#add_medicine').show();
					jQuery.each( prescription, function( key, value ) {
						medicine_table = medicine_table + "<tr>";
						jQuery.each( value, function( k, v ) {
							if(k=='medicine_id'){
								medicine_id = v;
							}else if(k=='medicine_name'){
								medicine_name = v;
							}else if(k=='quantity'){
								quantity = v;
							}else if(k=='available_quantity'){
								available_quantity = v;
							}
						});
						checkbox = "<input  type='checkbox' name='medicine_id[]'  checked value='"+medicine_id+"'/>";
						medicine_table = medicine_table + "<td>"+checkbox+"</td>";
						medicine_table = medicine_table + "<td>"+medicine_name+"</td>";
						medicine_table = medicine_table + "<td>"+available_quantity+"</td>";
						medicine_table = medicine_table + "<td><input type='hidden' name='quantity[]' value='"+quantity+"'/>"+quantity+"</td>";
						medicine_table = medicine_table + "</tr>";
				});
			}


			medicine_table = medicine_table + "</table>";
			console.log(medicine_table);
			if(prescription=='')
				medicine_table = 'No prescription found';
			$('#prescribed_medicine_table').html(medicine_table);
		});
		$('#prescribed_patient_id').val(patient_id);

		$('#prescribed_medicine').modal({show:true});
	});

	$(document).on('click','#add_medicine',function(event) {
		var sell_no = $('#sell_no').val();
		var sell_id = $('#sell_id').val();
		var sell_date = $('#sell_date').val();
		var patient_id = $('#patient_id').val();
		if(sell_id != ""){
			$('#medicine_from_prescription_sell_id').val(sell_id);
		}
		$('#medicine_from_prescription_sell_date').val(sell_date);
		$('#medicine_from_prescription_sell_no').val(sell_no);
		$('#medicine_from_prescription_patient_id').val(patient_id);
		$( "#add_medicine_from_prescription" ).submit();
	});
	 $('#sell_data_table').dataTable();
	 $('#quantity').change(function(){
		var sell_price = document.getElementById("sell_price").value;
		var quantity = document.getElementById("quantity").value;
		var sell_amount= sell_price * quantity;
		document.getElementById("sell_amount").value = sell_amount;
	});

	var flag = "false";
	//show table
	function display_table(records){
		var sell=records[0]['sell'];
					var sell_deatils=records[0]['sell_details'];
					var discount=0;
					if(sell!=''){
						flag = "true";
						discount=sell['discount'];
					}else{
						discount=0;
					}
					$('#sell_items tr:gt(0)').remove()
					var total=0;
					$.each(sell_deatils, function( index, sell_deatil ) {
						total = Number(total) + Number(sell_deatil.sell_amount);
						var del_icon='<i class="fa fa-trash  btn btn-danger confirmDelete btn-sm square-btn-adjust" data-sell_detail_id='+ sell_deatil['sell_detail_id']  +'  aria-hidden="true"></i>';
						//var anchor = '<input type="button" name="delete_item_sell" data-sell_detail_id='+ sell_deatil['sell_detail_id']  +' id="delete_item_sell" class="item_delete btn btn-danger confirmDelete btn-sm square-btn-adjust"  value="Delete"/>';
						var sell_id = '<input type="hidden" id="id" value="'+ sell['sell_id']  +'" />';
						$('#sell_items').append('<tr><td>'+ sell_deatil.item_name +'</td><td></td><td style="text-align:right;">' + sell_deatil.quantity + '</td><td style="text-align:right;">'+sell_deatil.sell_price+'</td><td style="text-align:right;">'+sell_deatil.sell_amount+'</td><td></td><td>'+del_icon+' '+sell_id+'</td></tr>');

					document.getElementById("available_quantity").value="";
					document.getElementById("item_id").value="";
					document.getElementById("discount").value="";
					document.getElementById("item_name").value="";
					document.getElementById("quantity").value="";
					document.getElementById("sell_price").value="";
					document.getElementById("sell_amount").value="";
					document.getElementById("barcode").value="";
					});

					$('#sell_items').append('<tr><td colspan="5" style="text-align:right;"><b>Discount</b></td><tdstyle="text-align:right;">'+discount +'</td><td></td></tr>');
					$('#sell_items').append('<tr><td colspan="5" style="text-align:right;"><b>Total</b></td><td style="text-align:right;">'+(total-discount)+'</td><td></td></tr>');

	}
	//delete perticuler sell item
	$(document).on("click", '.confirmDelete', function(event) {
		var sell_id = document.getElementById("id").value;
		var sell_detail_id = $(this).data('sell_detail_id');

			$.ajax({
				type: "POST",
				url: "<?=site_url('stock/ajax_delete_sell_detail/');?>",
				data: {sell_id:sell_id,sell_detail_id:sell_detail_id},
				success: function (result) {
					var records = JSON.parse(result);
					console.log(records);
					display_table(records);

				}
			});
	});
		//add sell_item
	$('#add_new_sell_item').click(function(){
		var sell_no = document.getElementById("sell_no").value;
		var sell_date = document.getElementById("sell_date").value;
		var patient_id = document.getElementById("patient_id").value;
		var discount = document.getElementById("discount").value;
		var item_id = document.getElementById("item_id").value;
		var quantity = document.getElementById("quantity").value;
		var sell_price = document.getElementById("sell_price").value;

		$.ajax({
			type: "POST",
			url: "<?=site_url('stock/ajax_add_sell/');?>",
			data: {flag:flag,sell_no:sell_no,sell_date:sell_date,patient_id:patient_id,discount:discount,item_id:item_id,quantity:quantity,sell_price:sell_price},
			success: function (result) {

				var records = JSON.parse(result);
				console.log(records);
				display_table(records);

			}
		});
	});


});
</script>
<div class="modal fade" id="prescribed_medicine" tabindex="-1" role="dialog" aria-labelledby="prescribedLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
				<h4 class="modal-title" id="paymentLabel"><?= $this->lang->line('prescribed_medicine');?></h4>
			</div>
			<?php $attributes = array('class' => 'email', 'id' => 'add_medicine_from_prescription');?>
			<?php echo form_open('stock/add_medicine',$attributes); ?>
			<input name="sell_id" id="medicine_from_prescription_sell_id" value="" type="hidden"/>
			<input name="patient_id" id="medicine_from_prescription_patient_id" value="" type="hidden"/>
			<input name="sell_no" id="medicine_from_prescription_sell_no" value="" type="hidden"/>
			<input name="sell_date" id="medicine_from_prescription_sell_date" value="" type="hidden"/>
			<div class="modal-body">
				<div id="prescribed_medicine_table"></div>
			</div>
			<div class="modal-footer">
				<a id="add_medicine" class="btn btn-primary btn-sm square-btn-adjust"><?= $this->lang->line('add');?></a>
				<button type="button" class="btn btn-default btn-sm square-btn-adjust" data-dismiss="modal"><?= $this->lang->line('close');?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					<?php echo $this->lang->line('sell');?></h1>
					<a class="btn btn-success btn-sm square-btn-adjust" title="" href="<?php echo site_url("stock/sell/"); ?>"><?= $this->lang->line('new')." ".$this->lang->line('sell');?></a>
					<?php

						if (isset($sell)){
							$patient_name = $sell['first_name'] . " " . $sell['middle_name'] . " " .$sell['last_name'];
							$sell_date = date($def_dateformate ,strtotime($sell['sell_date']));
							$patient_id = $sell['patient_id'];
							$sell_id = $sell['sell_id'];
							$sell_no = $sell['sell_no'];
							$discount = $sell['discount'];
							$edit = TRUE;
						}else{
							$patient_name = "";
							$patient_id = "";
							$sell_date = date($def_dateformate);
							$sell_id = "";
							$sell_no = $new_sell_no;
							$discount = 0;
							$edit = FALSE;
						}
						?>
					<input type="hidden" name="sell_id" id="sell_id" value="<?=$sell_id;?>" readonly />
					<div class="row">
					<div class="col-md-3">
						<label for="sell_id"><?= $this->lang->line('sell')." ".$this->lang->line('no');?> </label>
						<input type="text" name="sell_no" id="sell_no" value="<?=$sell_no;?>" class="form-control"/>
						<?php echo form_error('sell_no','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sell_date"><?= $this->lang->line('date');?></label>
							<input type="text" name="sell_date" id="sell_date" value="<?=$sell_date; ?>" class="form-control"/>
							<?php echo form_error('sell_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="patient"><?= $this->lang->line('patient');?></label>
							<input type="input" name="patient_name" id="patient_name" value="<?=$patient_name;?>" class="form-control" />
							<input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id;?>" class="form-control"/>
							<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<?php if (in_array("prescription", $active_modules)) { ?>
					<div class="col-md-3">
						<label for="patient">&nbsp;</label>
						<div id="from_prescription_div"></div>
					</div>
					<?php } ?>
					</div>
					<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="sell_table">
							<thead>
								<tr>
										<th><?= $this->lang->line('item');?></th>
									<!--<th><?= $this->lang->line('barcode');?></th>-->
										<th><?= $this->lang->line('available')." ".$this->lang->line('quantity');?></th>
										<th><?= $this->lang->line('quantity');?></th>
										<th><?= $this->lang->line('sell')." ".$this->lang->line('price');?> </th>
										<th><?= $this->lang->line('sell')." ".$this->lang->line('amount');?> </th>
										<th><?= $this->lang->line('discount');?> </th>
										<th><?= $this->lang->line('action');?></th>
								</tr>
							</thead>
							<tbody id="sell_items">
								<tr id="new_sell_item">
									<td>
										<input type="text" name="barcode" id="barcode" class="form-control"  placeholder="<?= $this->lang->line('barcode');?> "/>
										<input type="text" name="item_name" id="item_name" class="form-control"  placeholder="<?= $this->lang->line('item')." ".$this->lang->line('name');?>"/>
										<input type="hidden" name="item_id" id="item_id" class="form-control"/>
									</td>
									<!--<td><input type="text" name="barcode" id="barcode" class="form-control" style="width: 80%;" placeholder="<?= $this->lang->line('barcode');?> "/>-->
									<td><input type="input" name="available_quantity" id="available_quantity" placeholder="<?= $this->lang->line('available')." ".$this->lang->line('quantity');?>"  class="form-control" readonly /></td>
									<td><input type="input" name="quantity" id="quantity" class="form-control" placeholder="<?= $this->lang->line('quantity');?>"/>	</td>
									<td><input type="input" name="sell_price" id="sell_price" class="form-control"  placeholder="<?= $this->lang->line('price');?>"/></td>
									<td><input type="input" name="sell_amount" id="sell_amount" value=""  readonly class="form-control" placeholder="<?= $this->lang->line('sell_amount');?>"/></td>
									<td><input type="input" name="discount" id="discount" value="" class="form-control"  placeholder="<?= $this->lang->line('discount');?>"/></td>
									<td><a id="add_new_sell_item" class="btn btn-primary square-btn-adjust btn-sm" href="#"><i class="fa fa-plus" aria-hidden="true"></i></a></td>

								</tr>

							</tbody>
						</table>
					</div>




				</div>

			</div>
		</div>

	</div>
</div>
