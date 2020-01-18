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
	oTable = $('#item_table').dataTable({
        "aaSorting": [[ 1, "asc" ]],
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
})
</script>
<?php
	if(isset($sell_return)){
		$edit = TRUE;
		$return_date = set_value('return_date',date($def_dateformate,strtotime($sell_return['return_date'])));
		$bill_no = set_value('bill_no',$sell_return['bill_no']);
		$item_id = set_value('item_id',$sell_return['item_id']);
		$item_name = set_value('item_name',$sell_return['item_name']);
		$quantity = set_value('quantity',$sell_return['quantity']);
		$patient_id = set_value('patient_id',$sell_return['patient_id']);
		$patient_name = set_value('patient_name',$sell_return['patient_name']);
		$price = set_value('price',$sell_return['price']);
	}else{
		$edit = FALSE;
		$return_date = set_value('return_date',date($def_dateformate));
		$bill_no = set_value('bill_no',"");
		$item_id = set_value('item_id',"");
		$item_name = set_value('item_name',"");
		$quantity = set_value('quantity',"");
		$patient_id = set_value('patient_id',"");
		$patient_name = set_value('patient_name',"");
		$price = set_value('price',"");
	}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
					<?php echo $this->lang->line('sell').' '.$this->lang->line('return');?>
			</div>
			<div class="panel-body">
			<?php if($edit){ ?>
			<?php echo form_open('stock/edit_sell_return/'. $sell_return['return_id']) ?>
			<?php }else{ ?>
			<?php echo form_open('stock/add_sell_return/') ?>
			<?php } ?>

				<div class="col-md-3">
					<div class="form-group">
						<input type="hidden" name="return_id" id="return_id" value="<?=$sell_return['return_id']?>"/>
						<label for="return_date"><?php echo $this->lang->line('return_date');?></label>
						<input type="input" name="return_date" id="return_date" class="form-control" value="<?=$return_date;?>"/>
						<?php echo form_error('return_date','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="bill_no"><?php echo $this->lang->line('bill_no');?></label>
						<input type="input" name="bill_no" id="bill_no" class="form-control" value="<?=$bill_no;?>"/>
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
				<div class="col-md-3">
					<div class="form-group">
						<input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id;?>"/>
						<label for="supplier_name"><?php echo $this->lang->line('patient');?></label>
						<input type="input" name="patient_name" id="patient_name" class="form-control" value="<?=$patient_name;?>"/>
						<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="price"><?php echo $this->lang->line('price');?></label>
						<input type="input" name="price" class="form-control" value="<?=$price;?>"/>
						<?php echo form_error('price','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" /><i class="fa fa-floppy-o">&nbsp;</i><?php echo $this->lang->line('save');?></button>
						<a class="btn-sm square-btn-adjust btn btn-primary" href="<?=site_url('stock/sell_return');?>"><i class="fa fa-arrow-left">&nbsp;</i><?php echo $this->lang->line('back');?></a>

					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
