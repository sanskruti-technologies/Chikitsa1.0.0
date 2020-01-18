<?php
	$field_type_options = array(
        'text'  	=> 'Text Box',
        'textarea'  => 'Text Area',
        'date'  	=> 'Date',
        'combo' 	=> 'Select Box',
        'checkbox' 	=> 'Check Box',
		'radio' 	=> 'Radio Buttons',
		'header' 	=> 'Header',
		'file' 		=> 'File',
		'group'		=>	'Group',
	);
	
	$field_width_options = array(
        '1'	  		=> '1/12',
        '2'		  	=> '1/6',
        '3' 		=> '1/4',
        '4'		 	=> '1/3',
		'5'		 	=> '5/12',
		'6'		 	=> '1/2',
		'7'		 	=> '7/12',
		'8'		 	=> '2/3',
		'9'		 	=> '3/4',
		'10'		=> '5/6',
		'11'		=> '11/12',
		'12'		=> '100%',
	);
	$field_status_options = array(
		'enabled'	=> 'Enabled',
		'hidden'	=> 'Hidden',
		'disabled'	=> 'Disabled',
	);
?>
<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	});
    $("#sections_table").dataTable({
		"pageLength": 50
	});
	$("#field_options").parent().hide();
	$( "#field_type" ).change(function() {
		if($("#field_type" ).val() == "combo" || $("#field_type" ).val() == "checkbox" ||$("#field_type" ).val() == "radio" ){
			$("#field_options").parent().show();
		}else{
			$("#field_options").parent().hide();
		}
	});
	$("#delete_field").click(function() {			
		$(this).parent().parent().remove();
	});			
	$( "#add_field" ).click(function() {
		var field_count = parseInt( $( "#field_count" ).val());
		field_count = field_count + 1;
		$( "#field_count" ).val(field_count);
		
		var field = "<div class='col-md-12 panel panel-primary'><div class='col-md-3'><label for='field_name'>Field Name</label><input type='hidden' class='form-control' name='field_id[]' value=''/><input type='input' class='form-control' name='field_name[]' value=''/></div>";
		field += "<div class='col-md-3'><label for='field_label'>Field Label</label><input type='input' class='form-control' name='field_label[]' value=''/></div>";
		field += "<div class='col-md-2'><label for='field_type'>Field Type</label><select name='field_type[]' id='field_type"+field_count+"' class='form-control'>";
		<?php foreach($field_type_options as $field_type_value => $field_type_label){ ?>
		field += "<option value='<?=$field_type_value;?>'><?=$field_type_label;?></option>";
		<?php } ?>
		field += "</select></div>";
		field += "<div class='col-md-3'><label for='field_options'>Field Options</label><textarea class='form-control' name='field_options[]' id='field_options"+field_count+"'></textarea><small>separate options by |</small></div>";
		field += "<div class='col-md-2'><label for='field_width'>Field Width</label><select class='form-control' name='field_width[]' id='field_width"+field_count+" class='form-control'>";
		<?php foreach($field_width_options as $field_width_value => $field_width_label){ ?>
		field += "<option value='<?=$field_width_value;?>'><?=$field_width_label;?></option>";
		<?php } ?>
		field += "</select></div>";
		field += "<div class='col-md-2'><label for='field_status'>Field Status</label><select class='form-control' name='field_status[]' id='field_status"+field_count+"'>"; 
		<?php foreach($field_status_options as $field_status_value => $field_status_label){ ?>
		field += "<option value='<?=$field_status_value;?>'><?=$field_status_label;?></option>";
		<?php } ?>
		field += "</select></div>";
		field += "<div class='col-md-2'><label for='field_order'>Field Order</label><input type='input' class='form-control' name='field_order[]' value=''/></div>";
		field += "<div class='col-md-2'><label for='field_repeat'>Field Repeat</label><input type='checkbox' class='form-control' name='field_repeat[]' value='1' checked='checked'/></div>";
		field += "<div class='col-md-2'><label for='in_group'>In Group</label><input type='checkbox' class='form-control' name='in_group[]' value='1' checked='checked'/></div>";
		field += "<div class='col-md-3'><label for='group_name'>Group Name</label><input type='input' class='form-control' name='group_name[]' value=''/></div>";
	
		
		field += "<div class='col-md-1'><label></label><a href='#' id='delete_field"+field_count+"' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a></div></div>";
		
		$( "#field_list" ).append(field);
		
		$("#field_options"+field_count).parent().hide();
		$( "#field_type"+field_count ).change(function() {
			if($("#field_type"+field_count ).val() == "combo" || $("#field_type"+field_count ).val() == "checkbox" ||$("#field_type"+field_count ).val() == "radio" ){
				$("#field_options"+field_count).parent().show();
			}else{
				$("#field_options"+field_count).parent().hide();
			}
		});
		$("#delete_field"+field_count).click(function() {			
			$(this).parent().parent().remove();
		});			
		/*$("#medicine_name"+medicine_count).autocomplete({
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
		});	*/										
	});
});
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					Edit Section
				</h1>
					<div>
						<?php echo form_open('history/edit_section_fields/'.$section['section_id']) ?>
						<input type="hidden" name="section_id" value="<?=$section['section_id'];?>"/>
						<div class="col-md-12">	
						<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

						<div class="col-md-3">
							<a href="#" id="add_field" class="btn btn-primary square-btn-adjust">Add Field</a>
						</div>	
							<div id="field_list">
							<?php 
								if(isset($fields)){
									$i=0;
									?>
									<input type="hidden" id="field_count" value="<?=count($fields);?>"/>	
									<?php foreach($fields as $field){ ?>
										<div class="col-md-12 panel panel-primary">
											<input type="hidden" class="form-control" name="field_id[]" value="<?=$field['field_id'];?>"/>
											<div class="col-md-3">	
												<label for="field_name">Field Name</label> 
												<input type="input" class="form-control" name="field_name[]" value="<?=$field['field_name'];?>"/>
											</div>
											<div class="col-md-3">	
												<label for="field_label">Field Label</label> 
												<input type="input" class="form-control" name="field_label[]" value="<?=$field['field_label'];?>"/>
											</div>
											<div class="col-md-2">	
												<label for="field_type">Field Type</label> 
												<?php echo form_dropdown('field_type[]', $field_type_options, $field['field_type'],"id='field_type".$i."' class='form-control'"); ?>
											</div>	
											<div class="col-md-3">	
												<label for="field_options">Field Options</label> 
												<textarea class="form-control" name="field_options[]" id="field_options<?=$i;?>"><?=$field_options[$field['field_id']];?></textarea>
												<small>separate options by |</small>
											</div>	
											<div class="col-md-2">	
												<label for="field_width">Field Width</label> 
												<?php echo form_dropdown('field_width[]', $field_width_options, $field['field_width'],"id='field_width".$i."' class='form-control'"); ?>
											</div>
											<div class="col-md-2">	
												<label for="field_width">Field Status</label> 
												<?php echo form_dropdown('field_status[]', $field_status_options, $field['field_status'],"id='field_status".$i."' class='form-control'"); ?>
											</div>
											<div class="col-md-2">	
												<label for="field_order">Field Order</label> 
												<input type="input" class="form-control" name="field_order[]" value="<?=$field['field_order'];?>"/>
											</div>

											<div class="col-md-2">	
												<label for="field_repeat"> Allow Repeat</label> 
												<?php 
												$checked = "";
												if($field['is_repeat'] == 1){
													$checked = "checked";
												}			
												?>
												<input type="checkbox" class="form-control" id="<?=$field['field_id'];?>" name="field_repeat[]" value="<?=$i;?>" <?=$checked;?> />
											
											</div>
										
										<div class="col-md-2">	
												<label for="field_repeat">In Group</label> 
												<?php 
												$checked = "";
												if($field['in_group'] == 1){
													$checked = "checked";
												}			
												?>
												<input type="checkbox" class="form-control" id="<?=$field['field_id'];?>" name="in_group[]" value="<?=$i;?>" <?=$checked;?> />
											</div>
											
											<div class="col-md-3">	
												<label for="group_name">Group Name</label> 
												<input type="input" class="form-control" name="group_name[]" value="<?=$field['group_name'];?>"/>
											</div>
											
											<div class='col-md-1'>
												<label></label>
												<a href='#' id='delete_field<?=$i;?>' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a>
											</div>
										</div>
										<script>
											if($("#field_type<?=$i;?>").val() == "combo" || $("#field_type<?=$i;?>" ).val() == "checkbox" ||$("#field_type<?=$i;?>").val() == "radio" ){
												$("#field_options<?=$i;?>").parent().show();
											}else{
												$("#field_options<?=$i;?>").parent().hide();
											}
											$( "#field_type<?=$i;?>" ).change(function() {
												if($("#field_type<?=$i;?>").val() == "combo" || $("#field_type<?=$i;?>" ).val() == "checkbox" ||$("#field_type<?=$i;?>").val() == "radio" ){
													$("#field_options<?=$i;?>").parent().show();
												}else{
													$("#field_options<?=$i;?>").parent().hide();
												}
											});
											$("#delete_field<?=$i;?>").click(function() {			
												$(this).parent().parent().remove();
											});			
										</script>
									<?php 
										$i++;
									} } ?>
							</div>   
						</div>   
						  
						<div class="form-group">
								<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" /><?php echo $this->lang->line('save');?></button>
								<a href="<?=site_url('history/sections');?>" class="btn btn-primary square-btn-adjust" >Back</a>
							</div>							
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

