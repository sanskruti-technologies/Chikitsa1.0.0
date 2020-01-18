<?php
	function is_group_repeat($field_name,$fields){
		foreach($fields as $field){
			if($field['field_name']==$field_name){
				if($field['is_repeat']==1){
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	function display_group_multiple_fields($field,$field_options,$fields,$patient_history_details){
		$display_field = "";
		$value_array = array();
		
		foreach($fields as $f){
			if(($f['in_group'] == 1) && ($f['group_name'] == $field['field_name']) && ($f['section_id'] == $field['section_id'])){
				if(isset($patient_history_details[$f['field_id']])){
					$value = $patient_history_details[$f['field_id']];
				}else{
					$value = "";
				}
				
				$value_array[$f['field_id']] = $value;
				$value=explode(",",$value);
				$repeat_count = sizeof($value);
			}
		}
		
		for($i=0;$i<$repeat_count;$i++){
			foreach($value_array as $field_id => $value){
				$value=explode(",",$value);
				$v = $value[$i];
				foreach($fields as $f){
					if($f['field_id'] == $field_id){
						$display_field .= display_single_field($f,$v,$field_options,$fields,$patient_history_details,TRUE);
					}
				}
			}
		}
		
		
		return $display_field;
	}
	function display_group_single_fields($field,$field_options,$fields,$patient_history_details){
		$display_field = "";
		foreach($fields as $f){
			if(isset($patient_history_details[$f['field_id']])){
				$v = $patient_history_details[$f['field_id']];
			}else{
				$v = "";
			}
				
			if(($f['in_group'] == 1) && ($f['group_name'] == $field['field_name']) && ($f['section_id'] == $field['section_id'])){
				$display_field .= display_single_field($f,$v,$field_options,$fields,NULL,TRUE);
			}
		}
		return $display_field;
	}
	function display_single_field($field,$value,$field_options,$fields,$patient_history_details=NULL,$for_group = FALSE){
		
		$style="";
		$disabled="";
		$array = "";
		$add_button = "";
		if($field['in_group']=='1' && !$for_group){
			return "";
		}
		if($field['in_group']=='1' && is_group_repeat($field['group_name'],$fields)){
			$array = "[]";
		}
		if($field['is_repeat']=='1'){
			$array = "[]";
			$add_button = " <a class='btn btn-primary square-btn-adjust btn-xs repeat_field' data-field_name='history_".$field['field_id']."'>+</a>";
					
		}
		if($field['field_status'] == 'hidden' ){
			$style="style='display:none;'";
		}
		if($field['field_status'] == 'disabled' ){
			$disabled=" disabled='disabled'";
		}
		
		$display_field = "<div class='form-group col-md-".$field['field_width']." history_".$field['field_id']."' ".$style.">";
		if($field['field_type'] == "header"){
			$display_field .= "<h4>".$field['field_label']."</h4>"; 
		}else{
			$display_field .= "<label for='history_".$field['field_id']."'>".$field['field_label']."</label>"; 
		}
		$display_field .= $add_button;
		if($field['field_type'] == "text"){
			$display_field .= "<input type='input' ".$disabled." class='form-control' id='".$field['field_name']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
		}elseif($field['field_type'] == "textarea"){
			$display_field .= "<textarea ".$disabled." class='form-control' id='".$field['field_name']."' name='history_".$field['field_id']."$array' >".$value."</textarea>";
		}elseif($field['field_type'] == "file"){
			if($value != ""){
				$display_field .= "&nbsp;<a target='_blank' href='".base_url("/uploads/history_files/")."$value'>Click to view file</a>";
			}
			$display_field .= "<input type='file' ".$disabled." class='form-control' id='".$field['field_name']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
		}elseif($field['field_type'] == "date"){ 
			$display_field .= "<input type='input' ".$disabled." class='form-control datetimepicker' id='".$field['field_name']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
		}elseif($field['field_type'] == "combo"){ 
			$display_field .= "<select id='".$field['field_name']."' class='form-control' ".$disabled." name='history_".$field['field_id']."$array'>";
			foreach($field_options as $field_option){ 
				if($field_option['field_id'] == $field['field_id']){
					if($field_option['option_value'] == $value ){$selected = "selected";}
					$display_field .= "<option value='".$field_option['option_value']."' $selected >".$field_option['option_label']."</option>";
				} 
			} 
			$display_field .= "</select>";
		}elseif($field['field_type'] == "checkbox"){ 
			foreach($field_options as $field_option){ 
				if($field_option['field_id'] == $field['field_id']){
					$checked = "";
					if(strpos($value,$field_option['option_value']) !== FALSE ){$checked = "checked";}
					$display_field .= "<label class='checkbox'>";
					$display_field .= "<input id='".$field['field_name']."' $disabled type='checkbox' name='history_".$field['field_id']."[]' value='".$field_option['option_value']."' $checked />".$field_option['option_label'];
					$display_field .= "</label>";
				}
			}
		}elseif($field['field_type'] == "radio"){
			foreach($field_options as $field_option){
				if($field_option['field_id'] == $field['field_id']){
					$checked = "";
					if(strpos($value,$field_option['option_value']) !== FALSE ){$checked = "checked";}
					
					$display_field .= "<div class='radio'><label>";
					$display_field .= "<input id='".$field['field_name']."' $disabled type='radio' name='history_".$field['field_id']."' id='history_".$field['field_id']."' value='".$field_option['option_value']."' $checked>".$field_option['option_label'];
					$display_field .= "</label></div>";
				}
			}
		}elseif($field['field_type'] == "group"){
			$display_field .=  "<div id='".$field['field_name']."' class='div_history_".$field['field_id']."' >";
			if($field['is_repeat'] == 1){
				$display_field .= display_group_multiple_fields($field,$field_options,$fields,$patient_history_details);
			}else{
				$display_field .= display_group_single_fields($field,$field_options,$fields,$patient_history_details);
			}
			
			$display_field .=  "</div>";
		}
		$display_field .=  form_error($field['field_id'],'<div class="alert alert-danger">','</div>');
		$display_field .=  "</div>";
		return $display_field;
	}
	function display_repeat_field($field,$values,$field_options,$fields,$patient_history_details=NULL,$for_group = FALSE){
		$style="";
		$disabled="";
		$array = "";
		$add_button = "";
		if($field['in_group']=='1' && !$for_group){
			return "";
		}
		if($field['in_group']=='1' && is_group_repeat($field['group_name'],$fields)){
			$array = "[]";
		}
		if($field['is_repeat']=='1'){
			$array = "[]";
			$add_button = " <a class='btn btn-primary square-btn-adjust btn-xs repeat_field' data-field_name='history_".$field['field_id']."'>+</a>";
					
		}
		if($field['field_status'] == 'hidden' ){
			$style="style='display:none;'";
		}
		if($field['field_status'] == 'disabled' ){
			$disabled=" disabled='disabled'";
		}
		
		$display_field = "<div class='form-group col-md-".$field['field_width']." history_".$field['field_id']."' ".$style.">";
		if($field['field_type'] == "header"){
			$display_field .= "<h4>".$field['field_label']."</h4>"; 
		}else{
			$display_field .= "<label for='history_".$field['field_id']."'>".$field['field_label']."</label>"; 
		}
		$display_field .= $add_button;
		$values = explode(",",$values);
		$index = 0;
		foreach($values as $value){
			$display_field .= "<div>";
			$display_field .= "<div class='col-md-11'>";
			if($field['field_type'] == "text"){
				$display_field .= "<input type='input' ".$disabled." class='form-control input-history_".$field['field_id']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
			}elseif($field['field_type'] == "textarea"){
				$display_field .= "<textarea ".$disabled." class='form-control input-history_".$field['field_id']."' name='history_".$field['field_id']."$array' >".$value."</textarea>";
			}elseif($field['field_type'] == "file"){
				$display_field .= "<input type='file' ".$disabled." class='form-control input-history_".$field['field_id']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
			}elseif($field['field_type'] == "date"){ 
				$display_field .= "<input type='input' ".$disabled." class='form-control datetimepicker input-history_".$field['field_id']."' name='history_".$field['field_id']."$array' value='".$value."'/>";
			}elseif($field['field_type'] == "combo"){ 
				$display_field .= "<select class='form-control input-history_".$field['field_id']."' ".$disabled." name='history_".$field['field_id']."$array'>";
				foreach($field_options as $field_option){ 
					$selected = "";
					if($field_option['field_id'] == $field['field_id']){
						if($field_option['option_value'] == $value ){$selected = "selected";}
						$display_field .= "<option value='".$field_option['option_value']."' $selected >".$field_option['option_label']."</option>";
					} 
				} 
				$display_field .= "</select>";
			}elseif($field['field_type'] == "checkbox"){ 
				foreach($field_options as $field_option){ 
					if($field_option['field_id'] == $field['field_id']){
						$checked = "";
						if(strpos($value,$field_option['option_value']) !== FALSE ){$checked = "checked";}
						$display_field .= "<label class='checkbox input-history_".$field['field_id']."'>";
						$display_field .= "<input $disabled type='checkbox' name='history_".$field['field_id']."[]' value='".$field_option['option_value']."' $checked />".$field_option['option_label'];
						$display_field .= "</label>";
					}
				}
			}elseif($field['field_type'] == "radio"){
				foreach($field_options as $field_option){
					if($field_option['field_id'] == $field['field_id']){
						$checked = "";
						if(strpos($value,$field_option['option_value']) !== FALSE ){$checked = "checked";}
						
						$display_field .= "<div class='radio'><label>";
						$display_field .= "<input class='input-history_".$field['field_id']."' $disabled type='radio' name='history_".$field['field_id']."' id='history_".$field['field_id']."' value='".$field_option['option_value']."' $checked>".$field_option['option_label'];
						$display_field .= "</label></div>";
					}
				}
			}elseif($field['field_type'] == "group"){
				$display_field .=  "<div id='".$field['field_name']."' class='div_history_".$field['field_id']."' >";
				if($field['is_repeat'] == 1){
					$display_field .= display_group_multiple_fields($field,$field_options,$fields,$patient_history_details);
				}else{
					$display_field .= display_group_single_fields($field,$field_options,$fields,$patient_history_details);
				}
				
				$display_field .=  "</div>";
			}
		
			$display_field .=  "</div>";
			$display_field .=  "<div class='col-md-1'>";
			$display_field .=  "<a class='btn btn-danger btn-xs delete_field delete-history_".$field['field_id']."' data-history_id='".$patient_history_details[$field['field_id']]['history_id']."' data-index='".$index."'>-</a>";
			$display_field .=  "</div>";
			$display_field .=  "</div>";
			$index++;
			
		}
		$display_field .=  form_error($field['field_id'],'<div class="alert alert-danger">','</div>');
		$display_field .=  "</div>";
		return $display_field;
	}
	
	echo "<div class='col-md-12'>";
	if(isset($section_master)){ 
		echo "<script>";
		echo "$( document ).ready(function() {\n";
		echo "	$('.repeat_field').on('click', function() {\n";
		echo "    var field_name = $(this).data('field_name');\n";
		echo "    $('.input-'+ field_name +':first' ).first().parent().parent().clone().appendTo( 'div.'+ field_name);\n";
		echo "    $('.input-'+ field_name +':last' ).val('');\n";
		echo "    var count = $('.input-'+ field_name ).length;\n";
		echo "    $('.delete-'+ field_name +':last' ).attr('data-index',count);\n";
		echo "	  $('.input-'+ field_name +':last.datetimepicker').datetimepicker({timepicker: false,format: '".$def_dateformate."',scrollInput:false,scrollMonth:false,scrollTime:false});\n";
		echo "	});\n";
		echo "	$('body').on('click', '.delete_field',function() {\n";
		echo "    	var history_id = $(this).data('history_id');\n";
		echo "      console.log(history_id);";
		echo "    	var index = $(this).data('index');\n";
		echo "      console.log(index);";
		echo "		$.post('".site_url("history/remove_patient_value/")."'+history_id+'/'+index,  function(data) {console.log(data);})\n";
		echo "    	$(this).parent().parent().remove();\n";
		echo "	});\n";
		/*echo " function reset_additional_details(){ \n";
		echo "    $( '.section' ).each(function( index ) {\n";
		echo "	      var departments = $('#doctor').find(':selected').data('departments');\n";
		echo "	      var section_departments = $(this).data('department');\n";
		echo "	      departments = departments.toString();\n";
		echo "	      if(departments!=''){\n";
		echo "	          departments = departments.split(',');\n";
		echo "	  		  if(section_departments!=''){\n";
		echo "	       		section_departments = section_departments.toString();\n";
		echo "	       		section_departments = section_departments.split(',');\n";
		echo "	            var flag = false;\n";
		echo "		        $.each(section_departments, function( index, section_department_id ) {\n";
		echo "		            if(departments.includes(section_department_id)){\n";
		echo "				        flag = true;\n";
		echo "			        }\n";
		echo "		        });\n";
		echo "	            if(flag){\n";
		echo "		            $( this ).show();\n";
		echo "		            $( '#no_additional_details' ).hide();\n";
		echo "	            }else{\n";
		echo "		           $( this ).hide();\n";
		echo "	            }\n";
		echo "            }else{\n";
		echo "		          $( this ).show();\n";
		echo "		          $( '#no_additional_details' ).hide();\n";
	    echo "	          }\n";
        echo "        }else{\n";	
        echo "	           $( this ).hide();\n";
		echo "	      }\n";
        echo "	      });\n"; 
		echo " }\n";
		echo "   reset_additional_details();";
		echo "    $('#doctor').on('change', function (e) {";
		echo "		  $( '#no_additional_details' ).show();\n";
		echo "        reset_additional_details();";
		echo "});";*/
		
		foreach($section_conditions as $section_condition){
			echo "$(document).on('change', '#".$field_name[$section_condition['field_name']]."', function() {\n";
			
			//Check Value of field
			if($section_condition['field_is_checked'] != NULL &  $section_condition['field_is_checked'] == 1){ //checked
				echo "if ($('#".$field_name[$section_condition['field_name']]."').is(':checked')) {";
			}elseif($section_condition['field_is_checked'] != NULL & $section_condition['field_is_checked'] == 0){ //unchecked
				echo "if (!$('#".$field_name[$section_condition['field_name']]."').is(':checked')) {";
			}elseif($section_condition['condition_type'] == 'has_value' ){ //has value
				echo "var flag = false;\n";
				echo "$('#".$field_name[$section_condition['field_name']].":checked').each(function() {\n";
				echo "  if(this.value == '".$section_condition['field_has_value']."'){\n";
				echo "	flag = true;\n";
				echo "	}\n";
				echo "});\n";
				echo "if(flag){\n";
			}elseif($section_condition['condition_type'] == 'does_not_has_value' ){ //does not has value
				echo "var flag = true;\n";
				echo "$('#".$field_name[$section_condition['field_name']].":checked').each(function() {\n";
				echo "  if(this.value == '".$section_condition['field_has_value']."'){\n";
				echo "	flag = false;\n";
				echo "	}\n";
				echo "});\n";
				echo "if(flag){\n";
			}

			//Change Status of field
			if($section_condition['change_status_to'] == 'enabled'){
				echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().show();";
				echo "$('#".$field_name[$section_condition['change_status_of_field']]."').prop('disabled', false);";
			}elseif($section_condition['change_status_to'] == 'disabled'){
				echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().show();";
				echo "$('#".$field_name[$section_condition['change_status_of_field']]."').prop('disabled', true);";
			}elseif($section_condition['change_status_to'] == 'hidden'){
				echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().hide();";
			}
			echo "}";
			echo "});";
		} 
		// call functions
		echo "	});\n";
		echo "</script>";
		
		foreach($section_master as $section){ 
			echo "<div class='section' data-department='".$section['department_id']."'>";
			echo "<h3>".$section['section_name']."</h3>";
			$section_id = $section['section_id'];
				$g_array=array();
				$k=0;
			foreach($section_fields as $field){
				if($field['section_id'] == $section['section_id']) {
					//Get Value of Field
					if(isset($patient_history_details[$field['field_id']])){
						$value = $patient_history_details[$field['field_id']]['value'];
					}else{
						$value = "";
					}
					if($field['is_repeat']=='1'){
						echo display_repeat_field($field,$value,$field_options,$section_fields,$patient_history_details);	
					}else{
						echo display_single_field($field,$value,$field_options,$section_fields,$patient_history_details);
					}
				}
			}
			echo "</div>";
		}
		//echo "<div id='no_additional_details'>No Additional Details configured for this department.</div>";	
	} 
	echo "</div>";
	?>