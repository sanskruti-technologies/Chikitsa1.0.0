<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
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

	$("#supplier_name").autocomplete({
        source: [<?php
                $i=0;
                foreach ($suppliers as $supplier){
    if ($i > 0) {
        echo ",";
    }
                    echo '{value:"' . $supplier['first_name'] . ' ' .$supplier['middle_name'] .' '.$supplier['last_name'] .  '",id:"' . $supplier['supplier_id'] . '"}';
                    $i++;
                }
            ?>],
        minLength: 1,//search after one characters
        select: function(event,ui){
            //do something
            $("#supplier_id").val(ui.item ? ui.item.id : '');

        }
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
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('purchase') . ' ' .$this->lang->line('return');?></h1>

					<div class="form-group">

					   <a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("stock/add_purchase_return/"); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add') . ' ' .$this->lang->line('purchase') . ' ' .$this->lang->line('return');?></a>
					</div>
					<div class="form-group">
						<?php echo form_open('stock/purchase_return'); ?>
						<div class="row">
						<div class="col-md-3">
							<label><?php echo $this->lang->line('from_date');?></label>
							<input type="text" name="from_date" id="from_date" class="form-control" value="<?=$from_date;?>" />
						</div>
						<div class="col-md-3">
							<label><?php echo $this->lang->line('to_date');?></label>
							<input type="text" name="to_date" id="to_date" class="form-control" value="<?=$to_date;?>" />
						</div>
						<div class="col-md-3">
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
							<script>jQuery('#items').chosen();</script></div>
						<div class="col-md-3">
							<label>&nbsp;</label>
							<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm"><i class="fa fa-filter"></i>&nbsp;<?php echo $this->lang->line('filter');?></button>
							<a target="_blank" href="<?= site_url('stock/print_purchase_return_report/'.$items_csv.'/'.$from_date.'/'.$to_date);?>" class="btn btn-primary square-btn-adjust btn-sm" /><i class="fa fa-print"></i>&nbsp;<?= $this->lang->line('print');?></a>
						</div>
					</div>
						<?php echo form_close(); ?>
						<div class="col-md-12 table-responsive-25">
					<table class="table table-striped table-hover display responsive nowrap" id="purchase_table" style="width:100%;">
					<thead>
						<tr>
							<th><?php echo $this->lang->line('sr_no');?></th>
							<th><?php echo $this->lang->line('return_date');?></th>
							<th><?php echo $this->lang->line('bill_no');?></th>
							<th><?php echo $this->lang->line('item');?></th>
							<th><?php echo $this->lang->line('quantity');?></th>
							<th><?php echo $this->lang->line('supplier');?></th>
							<th><?php echo $this->lang->line('price');?></th>
							<th><?php echo $this->lang->line('edit');?></th>
							<th><?php echo $this->lang->line('delete');?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; ?>
						<?php foreach ($purchase_returns as $purchase_return):  ?>
						<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
							<td><?php echo $i; ?></td>
							<td><?php echo date($def_dateformate,strtotime($purchase_return['return_date']));?></td>
							<td><?php echo $purchase_return['bill_no'] ?></td>
							<td><?php echo $purchase_return['item_name'] ?></td>
							<td style="text-align:right"><?php echo $purchase_return['quantity'] ?></td>
							<td><?php echo $purchase_return['supplier_name'] ?></td>
							<td style="text-align:right"><?php echo currency_format($purchase_return['price']);if($currency_postfix) echo $currency_postfix; ?></td>
							<td>
							<!--a class="btn btn-primary btn-sm square-btn-adjust" title="Edit" href="<?php echo site_url("stock/edit_purchase_return/" . $purchase_return['return_id']); ?>">Edit</a-->
							<a class="btn btn-primary btn-sm square-btn-adjust editbt" title="Edit" href="<?php echo site_url("stock/edit_purchase_return/" . $purchase_return['return_id']); ?>"><i class="fa fa-pencil"></i></a>
							</td>
							<td>
							<!--a class="btn btn-danger  btn-sm square-btn-adjust confirmDelete junkcss-rm" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_purchase_return/" . $purchase_return['return_id']); ?>"><?php echo $this->lang->line('delete');?></a-->
							<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_purchase_return/" . $purchase_return['return_id']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
							</td>
						</tr>
						<?php $i++; ?>
						<?php endforeach ?>
					</tbody>
					</table>
				</div>
			</div>
			</div>
