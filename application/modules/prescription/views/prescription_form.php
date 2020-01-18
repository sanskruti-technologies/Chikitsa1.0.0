<script>
	$(window).load(function() {
		var medicine_array = [<?php
					$i=0;
					foreach ($medicines as $medicine){
						if ($i>0) {echo ",";}
						echo '{value:"' . $medicine['medicine_name'] . '",id:"' . $medicine['medicine_id'] . '"}';
						$i++;
					}
				?>];

		$( "#add_medicine" ).click(function() {

			var medicine_count = parseInt( $( "#medicine_count" ).val());
			medicine_count = medicine_count + 1;
			$( "#medicine_count" ).val(medicine_count);

			var medicine = "<div><div class='col-md-2'><label for='medicine' style='display:block;text-align:left;'>Medicine</label><input type='text' name='medicine_name[]' id='medicine_name"+medicine_count+"' class='form-control'/><input type='hidden' name='medicine_id[]' id='medicine_id"+medicine_count+"' class='form-control'/></div>";
			medicine += "<div class='col-md-4'><label for='frequency' style='display:block;text-align:left;'>Frequency</label><div class='col-md-2'>M</div><div class='col-md-2'><input type='text' name='freq_morning[]' id='freq_morning' class='form-control'/></div><div class='col-md-2'>A</div><div class='col-md-2'><input type='text' name='freq_afternoon[]' id='freq_afternoon' class='form-control'/></div><div class='col-md-2'>N</div><div class='col-md-2'><input type='text' name='freq_evening[]' id='freq_evening' class='form-control'/></div></div>";
			medicine += "<div class='col-md-2'><label for='days' style='display:block;text-align:left;'>Days</label><input type='text' name='days[]' id='days' class='form-control'/></div>";
			medicine += "<div class='col-md-3'><label for='prescription_notes' style='display:block;text-align:left;'>Instructions</label><input type='text' name='prescription_notes[]' id='prescription_notes' class='form-control'/></div>";
			medicine += "<div class='col-md-1'><label></label><a href='#' id='delete_medicine"+medicine_count+"' class='btn btn-danger btn-sm btn-sm deletbtn'><i class='fa fa-trash-o'></i></a></div></div>";
			$( "#prescription_list" ).append(medicine);

			$("#delete_medicine"+medicine_count).click(function() {
				$(this).parent().parent().remove();
			});
			$("#medicine_name"+medicine_count).autocomplete({
				source: medicine_array,
				minLength: 1,//search after one characters
				select: function(event,ui){
					//do something
					$("#medicine_id"+medicine_count).val(ui.item ? ui.item.id : '');
				},
				change: function(event, ui) {
					 if (ui.item == null) {
						$("#medicine_name"+medicine_count).val('');
						}
				},
			});

		});
		$("#add_medicine_submit").click(function(event){
			event.preventDefault();
			var medicine_name = $("#add_medicine_name").val();
			$.post("<?php echo site_url('prescription/add_medicine');?>",
				{medicine_name: medicine_name},
				function(data,status){
					data = JSON.parse(data);
					medicine_array.push(data);
					$( "#medicine_name" ).autocomplete('option', 'source', medicine_array);
				});
		});
	});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo  $this->lang->line('prescription');?></h1>
					
					<?php echo form_open('prescription/edit_prescription/' . $visit_id); ?>
					<div id="prescription_list">
						<div class="row">
						<div class="col-md-12">
							<a href="#" id="add_medicine" class="btn btn-primary btn-sm btn-sm"><i class="fa fa-plus"></i>&nbsp;<?php echo  $this->lang->line('add_another_medicine');?></a>
							<input type="hidden" id="medicine_count" value="0"/>
                    	</div>
                    	</div>
						<div class="row">
						<?php foreach($prescriptions as $medicine){
								if($medicine['medicine_id'] == 0){
									$medicine_id = "";
									$medicine_name = "";
								}else{
									$medicine_id = $medicine['medicine_id'];
									$medicine_name = $medicine_array[$medicine['medicine_id']];
								}

							?>
							<div class="col-md-2">
								<label for="medicine" style="display:block;text-align:left;"><?=$this->lang->line('medicine');?></label>
								<input type="text" name="medicine_name[]" id="medicine_name" value="<?=$medicine_name;?>" class="form-control medicine_name"/>
								<input type="hidden" name="medicine_id[]" id="medicine_id" value="<?=$medicine_id;?>" class="form-control"/>
							</div>
							<div class="col-md-4">
								<label for="frequency" style="display:block;text-align:left;"><?=$this->lang->line('frequency');?></label>
								<div class="row">
								<div class="col-md-2">
									<?=$this->lang->line('morning');?>
								</div>
								<div class="col-md-2">
									<input type="text" name="freq_morning[]" id="freq_morning"  value="<?=$medicine['freq_morning'];?>" class="form-control"/>
								</div>
								<div class="col-md-2">
									<?=$this->lang->line('afternoon');?>
								</div>
								<div class="col-md-2">
									<input type="text" name="freq_afternoon[]" id="freq_afternoon" value="<?=$medicine['freq_afternoon'];?>" class="form-control"/>
								</div>
								<div class="col-md-2">
									<?=$this->lang->line('night');?>
								</div>
								<div class="col-md-2">
								<input type="text" name="freq_evening[]" id="freq_evening" value="<?=$medicine['freq_night'];?>" class="form-control"/>
								</div>
								</div>
							</div>
							<div class="col-md-2">
								<label for="days" style="display:block;text-align:left;"><?=$this->lang->line('days');?></label>
								<input type="text" name="days[]" id="days" value="<?=$medicine['for_days'];?>" class="form-control"/>
							</div>
							<div class="col-md-3">
								<label for="prescription_notes" style="display:block;text-align:left;"><?=$this->lang->line('instructions');?></label>
								<input type="text" name="prescription_notes[]" value="<?=$medicine['instructions'];?>" id="prescription_notes" class="form-control"/>
							</div>
							<div class="col-md-1">
								<br/>
								<a href='#' id='delete_medicine"+medicine_count+"' class='btn btn-danger btn-sm btn-sm deletbtn'><?=$this->lang->line('delete');?></a>
							</div>
						<?php }?>
					</div>
					</div>
					<div class="row">
                    <div class="col-md-12"><hr/></div></div>
					<div class="row">
					<div class="col-md-12">
					<button class="btn btn-primary btn-sm" type="submit" name="submit" /><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $this->lang->line('save');?></button>
					<a class="btn btn-primary btn-sm" href="<?=site_url('patient/visit/' . $patient_id);?>"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;<?php echo $this->lang->line('back');?></a>
					</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Medicine</h4>
			</div>
			<?php echo form_open(); ?>
			<div class="modal-body">
					<div class="col-md-12"><label><?=$this->lang->line('medicine_name');?>:</label></div>
					<div class="col-md-12"><input type="text" id="add_medicine_name" name="medicine_name" class="form-control"/></div>
			</div>
			<div class="modal-footer">
					<input id="add_medicine_submit" type="submit" name="submit" value="Save" class="btn btn-primary" data-dismiss="modal"/>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close');?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>