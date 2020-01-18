<script type="text/javascript" charset="utf-8">
$(document).ready(function () {

    <?php if (in_array("prescription", $active_modules)) { ?>

		var medicine_array = [<?php
				$i=0;
				foreach ($medicines as $medicine){
					if ($i>0) {echo ",";}
					echo '{value:"' . $medicine['medicine_name'] . '",id:"' . $medicine['medicine_id'] . '"}';
					$i++;
				}
			?>];
		$("#medicine_name").autocomplete({
			source: medicine_array,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#medicine_id").val(ui.item ? ui.item.id : '');
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#medicine_name").val('');
		     	}
			},
		});
	<?php } ?>


	$('#add_as_medicine').change(function() {
		if(this.checked) {
			$('#medicine_name').parent().hide();
		}else{
			$('#medicine_name').parent().show();
		}
	});

	$.get("<?=site_url('stock/ajax_select_medicine');?>", function(data,status){
				var medicines = $.parseJSON(data);
				//console.log(medicines);
				select="<select name='select_medicine' class='form-control' id='select_medicine'>";
				select=select+"<option value='<?php echo $medicine_id;?>'><?php echo $medicine_name;?></option>";
				jQuery.each( medicines, function( key, value ) {
					var i=1;
					var mi=null;
					var id;
					var option;
						jQuery.each( value, function( k, v ) {
							//console.log(v);
							if(i%2==0){
								mi=v;
							}else{
								id=v;
							}
							i=i+1;
							console.log(id,mi)
							if(mi!=null){
								select=select+"<option value="+id+">"+mi+"</option>";
							}
						});
				});
				select=select+"</select>";
				console.log(select);
			$('#medicine_table').html(select);
	});

	$("#change_medicine").click(function(){
		$("#medicine_Popup").modal({show:true});
	});
});

</script>
<?php

	$item_name = set_value('item_name',"");
	$desired_stock = set_value('desired_stock',"");
	$mrp = set_value('mrp',"");
	$barcode = set_value('barcode',"");
	if(!isset($medicine_name)){
		$medicine_name = set_value('medicine_name',"");
	}
	$add_as_medicine = set_value('add_as_medicine',"");
	if( $add_as_medicine != ""){
		$add_as_medicine = "checked";
		?>
		<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			$('#medicine_name').parent().hide();
		});
		</script>
		<?php
	}

	$checked="";
	$medicine_id = "";
	$item_id = 0;
	if(isset($item)){
		$item_id =set_value('item_id',$item['item_id']);
		$item_name = set_value('item_name',$item['item_name']);
		$desired_stock = set_value('desired_stock',$item['desired_stock']);
		$mrp =set_value('mrp',$item['mrp']);
		$barcode =set_value('barcode',$item['barcode']);
		$medicine_id = set_value('medicine_id',$item['medicine_id']);
		if($medicine_id!=NULL){
			$checked="checked";
		}else{
			$checked="";
		}

	}
?>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					<?php echo $this->lang->line('items');?>
			</h1>
					<?php if(isset($item)){ ?>
					<?php echo form_open('stock/edit_item/'.$item['item_id']) ?>
					<input type="hidden" name="item_id" value="<?=$item['item_id']?>"/>
					<?php }else{ ?>
					<?php echo form_open('stock/add_item/') ?>
					<?php } ?>
					<div class="form-group">

						<label for="item_name"><?php echo $this->lang->line('item_name');?></label>
						<input type="input" name="item_name" value="<?=$item_name?>" class="form-control"/>
						<?php echo form_error('item_name','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="desired_stock"><?php echo $this->lang->line('desired_stock');?></label>
						<input type="input" name="desired_stock" value="<?=$desired_stock;?>" class="form-control"/>
						<?php echo form_error('desired_stock','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="mrp"><?php echo $this->lang->line('sell_price');?></label>
						<input type="input" name="mrp" value="<?=$mrp;?>" class="form-control"/>
						<?php echo form_error('mrp','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="barcode"><?php echo $this->lang->line('barcode');?></label>
						<input type="input" name="barcode" value="<?=$barcode;?>" class="form-control"/>
						<?php echo form_error('barcode','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<?php if (in_array("prescription", $active_modules)){ ?>
					<div class="form-group">
						<label for="medicine_id">Linked Medicine</label><br/>
						<label><input type="checkbox" id="add_as_medicine" name="add_as_medicine" value="add_as_medicine" <?=$add_as_medicine;?>> Add as New Medicine</label><br/>
						<div>
							<label for="medicine_name">Or Attach Existing Medicine</label><br/>
							<input type="hidden" id="medicine_id" name="medicine_id" value="<?=$medicine_id;?>" class="form-control"/>
							<input type="text" name="medicine_name" id="medicine_name" value="<?=$medicine_name;?>" class="form-control"/>
						</div>
						<?php if($medicine_id != 0){ ?>
						<a  href="<?php echo site_url("stock/remove_link_medicine/". $item_id); ?>"><?php echo $this->lang->line('remove')." ".$this->lang->line('medicine')." ". $this->lang->line('link');?></a>
						<?php } ?>
						<!--a href="#" id="change_medicine" ><h4><?=$medicine_name;?><h4></a-->


					</div>
					<?php } ?>

					<div class="form-group">
						<button type="submit" name="submit" class="btn-sm square-btn-adjust btn btn-primary" /><?php echo $this->lang->line('save');?></button>
						<a class="btn btn-primary btn-sm square-btn-adjust" title="back" href="<?php echo site_url("stock/item/"); ?>"><?php echo $this->lang->line('back');?></a>
					</div>
			<?php echo form_close(); ?>
			</div>
			
