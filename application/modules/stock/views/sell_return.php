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
            $("#patient_id").val(ui.item ? ui.item.id : '');

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
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="row">
        	<div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('sell_return');?></h2></div>
		</div>
	</div>
	<div class="panel-body table-responsive-25">
	<div class="form-group">
		<a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("stock/add_sell_return/"); ?>"><i class="fa fa-plus"></i>&nbsp;Add Sell Return</a>
	</div>
	<div class="col-md-12">
			<?php echo form_open('stock/sell_return'); ?>
			<div class="col-md-3">
				<div class="form-group">
				<label><?php echo $this->lang->line('from_date');?></label>
				<input type="text" name="from_date" id="from_date" class="form-control" value="<?=$from_date;?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
				<label><?php echo $this->lang->line('to_date');?></label>
				<input type="text" name="to_date" id="to_date" class="form-control" value="<?=$to_date;?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">

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
					</div>
			<div class="col-md-3">
				<div class="form-group">
				<label>&nbsp;</label>
				<button type="submit" name="submit" class="btn btn-primary square-btn-adjust btn-sm"><i class="fa fa-filter"></i>&nbsp;<?php echo $this->lang->line('filter');?></button>
				<a target="_blank" href="<?= site_url('stock/print_sell_return_report/'.$items_csv.'/'.$from_date.'/'.$to_date);?>" class="btn btn-primary square-btn-adjust btn-sm" /><i class="fa fa-print"></i>&nbsp;<?= $this->lang->line('print');?></a>
			</div>
			</div>
			<?php echo form_close(); ?>
		</div>
		<div class="col-md-12">
			<div class="panel-body table-responsive-25">

		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="purchase_table">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('sr_no');?></th>
					<th><?php echo $this->lang->line('return_date');?></th>
					<th><?php echo $this->lang->line('bill_no');?></th>
					<th><?php echo $this->lang->line('item');?></th>
					<th><?php echo $this->lang->line('quantity');?></th>
					<th><?php echo $this->lang->line('patient');?></th>
					<th><?php echo $this->lang->line('price');?></th>
					<th><?php echo $this->lang->line('edit');?></th>
					<th><?php echo $this->lang->line('delete');?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; ?>
				<?php foreach ($sell_returns as $sell_return):  ?>
				<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
					<td><?php echo $i; ?></td>
                    <td><?php echo date($def_dateformate,strtotime($sell_return['return_date']));?></td>
                    <td><?php echo $sell_return['bill_no'] ?></td>
					<td><?php echo $sell_return['item_name'] ?></td>
					<td><?php echo $sell_return['quantity'] ?></td>
					<td><?php echo $sell_return['patient_name'] ?></td>
					<td style="text-align:right"><?php echo currency_format($sell_return['price']);if($currency_postfix) echo $currency_postfix; ?></td>
					<td>
					<!--a class="btn btn-primary" title="Edit" href="<?php echo site_url("stock/edit_sell_return/" . $sell_return['return_id']); ?>">Edit</a-->
					<a class="btn btn-primary btn-sm square-btn-adjust editbt" title="Edit" href="<?php echo site_url("stock/edit_sell_return/" . $sell_return['return_id']); ?>"><i class="fa fa-pencil"></i></a>
					</td>
					<td>
					<!--a class="btn btn-danger confirmDelete junkcss-rm" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_sell_return/" . $sell_return['return_id']); ?>"><?php echo $this->lang->line('delete');?></a-->
					<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="<?php echo "Delete Purchase";?>" href="<?php echo site_url("stock/delete_sell_return/" . $sell_return['return_id']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
	</div>
</div>
