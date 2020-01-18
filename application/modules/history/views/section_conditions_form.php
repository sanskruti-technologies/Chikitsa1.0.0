<?php
	$field_type_options = array(
        'text'  	=> 'Text Box',
        'date'  	=> 'Date',
        'combo' 	=> 'Select Box',
        'checkbox' 	=> 'Check Box',
		'radio' 	=> 'Radio Buttons',
		'header' 	=> 'Header',
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
	})
    $("#sections_table").dataTable({
		"pageLength": 50
	});
	
			
	$( "#add_condition" ).click(function() {
		var condition_count = parseInt( $( "#condition_count" ).val());
		condition_count = condition_count + 1;
		$( "#condition_count" ).val(condition_count);
		
		var field = "<div class='col-md-12'><div class='col-md-3'><label for='change_status_of_field'>Change Status of field</label><select class='form-control' name='change_status_of_field[]'>";
		<?php foreach($fields as $field){
			echo "field += '<option value=\"".$field['field_id']."\">".$field['field_name']."</option>';";
		}?>
		field += "</select></div>";
		field += "<div class='col-md-2'><label for='change_status_to'>to</label>";
		field += "<select class='form-control' name='change_status_to[]'>";
		<?php foreach($field_status_options as $field_status_key => $field_status){
			echo "field += '<option value=\"".$field_status_key."\">".$field_status."</option>';";
		}?>
		field += "</select></div>";
		field += "<div class='col-md-2'><label for='field_name'>If Field</label>";
		field += "<select name='field_name[]' id='field_name"+condition_count+"' class='form-control'>";
		<?php foreach($fields as $field){
			echo "field += '<option value=\"".$field['field_id']."\">".$field['field_name']."</option>';";
		}?>
		field += "</select></div>";
		field += "<div class='col-md-3'>";
		field += "<input type='radio' class='condition_type' checked name='condition_type["+condition_count+"]' value='has_value'/> has value ";
		field += "<input type='radio' class='condition_type' checked name='condition_type["+condition_count+"]' value='does_not_has_value'/> does not has value ";
		field += "<input type='text' class='form-control field_has_value'  name='field_has_value[]' value=''/>";
		field += "<input type='radio' name='condition_type["+condition_count+"]' class='condition_type' value='is_checked'/> is checked <br/>";
		field += "<input type='radio' name='condition_type["+condition_count+"]' class='condition_type' value='is_unchecked'/> is unchecked </div>";
		field += "<div class='col-md-1'><label></label><a href='#' id='delete_field"+condition_count+"' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a></div></div>";
		
		$( "#field_list" ).append(field);
		
		
		$("#delete_condition"+condition_count).click(function() {			
			$(this).parent().parent().remove();
		});			
										
	});
	$(document).on('change', '.condition_type', function() {
		if($(this).val() == 'is_unchecked' || $(this).val() == 'is_checked'){
			$( this ).siblings('.field_has_value').val();
			$( this ).siblings('.field_has_value').prop('readonly', true);
		}else{
			$( this ).siblings('.field_has_value').prop('readonly', false);
			$( this ).siblings('.field_has_value').prop('disabled', false);
		}
	});
	
});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">
					<?=$section['section_name'];?>
				</h1>
					<div>
						<?php echo form_open('history/edit_section_conditions/'.$section['section_id']) ?>
						
						<div class="col-md-12">	
						<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

						<div class="col-md-3">
						
							<a href="#" id="add_condition" class="btn btn-primary square-btn-adjust">Add Condition</a>
						</div>	
							<div id="field_list">
							<?php 
								if(isset($conditions)){
									$i=0;
									?>
									<input type="hidden" id="condition_count" value="<?=count($conditions)-1;?>"/>	
									<input type="hidden" id="section_id" name="section_id" value="<?=$section['section_id'];?>"/>	
									<?php foreach($conditions as $condition){ 
										
									?>
										<div class="col-md-12 panel panel-primary">
											<input type="hidden" class="form-control" name="condition_id[]" value="<?=$condition['condition_id'];?>"/>
											<div class='col-md-3'>
												<label for='change_status_of_field'>Change Status of field</label>
												<select class='form-control' name='change_status_of_field[]'>
												<?php foreach($fields as $field){ ?>
													<?php
														$selected = "";
														if($field['field_id'] == $condition['change_status_of_field']){
															$selected = "selected";
														}
													?>
													<option value="<?=$field['field_id'];?>" <?=$selected;?>><?=$field['field_name'];?></option>
												<?php } ?>
												</select>
											</div>
											<div class='col-md-2'>
												<label for='change_status_to'>to</label>
												<select class='form-control' name='change_status_to[]'>
												<?php foreach($field_status_options as $field_status_key => $field_status){ ?>
													<?php
														$selected = "";
														if($field_status_key == $condition['change_status_to']){
															$selected = "selected";
														}
													?>
													<option value="<?=$field_status_key;?>" <?=$selected;?>><?=$field_status;?></option>
												<?php } ?>
												</select>
											</div>
											<div class='col-md-2'>
												<label for='field_name'>If Field</label>
												<select name='field_name[]' id='field_name<?=$i;?>' class='form-control'>
												<?php foreach($fields as $field){ ?>
													<?php
														$selected = "";
														if($field['field_id'] == $condition['field_name']){
															$selected = "selected";
														}
													?>
													<option value="<?=$field['field_id'];?>" <?=$selected;?>><?=$field['field_name'];?></option>
												<?php }?>
												</select>
											</div>
											<div class='col-md-3'>
												<?php 
													$does_not_has_value = "";
													$has_value = "";
													$is_disabled = "";
													$is_checked = "";
													$is_unchecked = "";
													if($condition['condition_type'] == "does_not_has_value"){
														$does_not_has_value = "checked";
														$is_disabled = "";
													}
													if($condition['condition_type'] == "has_value"){
														$has_value = "checked";
														$is_disabled = "";
													}
													if($condition['condition_type'] == "is_checked"){
														$is_checked = "checked";
														$is_disabled = "disabled";
													}
													if($condition['condition_type'] == "is_unchecked"){
														$is_unchecked = "checked";
														$is_disabled = "disabled";
													}
													
													
												?>
												<input type='radio' class='condition_type' checked name='condition_type[<?=$i;?>]' <?=$has_value;?> value='has_value'/> has value 
												<input type='radio' class='condition_type' name='condition_type[<?=$i;?>]' <?=$does_not_has_value;?> value='does_not_has_value'/> does not has value 
												<input type='text' class='form-control field_has_value' <?=$is_disabled;?>  name='field_has_value[]' value='<?=$condition['field_has_value'];?>'/>
												<input type='radio' name='condition_type[<?=$i;?>]' <?=$is_checked;?> class='condition_type' value='is_checked'/> is checked <br/>
												<input type='radio' name='condition_type[<?=$i;?>]' <?=$is_unchecked;?> class='condition_type' value='is_unchecked'/> is unchecked 
											</div>
											<div class='col-md-1'><label></label><a href='#' id='delete_condition<?=$i;?>' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a></div>
											
										</div>
										<script>
											$("#delete_condition<?=$i;?>").click(function() {			
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

