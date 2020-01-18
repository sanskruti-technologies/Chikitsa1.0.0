<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
                    	<div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('prescription');?></h2></div>
                    	<div class="col-md-8 text-right nopadding">
							<a class="btn btn-primary square-btn-adjust" title="<?php echo $this->lang->line("print");?>" target="_blank" id="print_prescription"><?php echo $this->lang->line("print");?></a>
						</div>
					</div>
				</div>
				<div class="panel-body table-responsive-15">					
					<input type="hidden" name="patient_id" id="patient_id" value="<?php if(isset($curr_patient)){echo $curr_patient['patient_id']; } ?>"/>
					<div class="panel panel-default">
						<div class="panel-heading">
							<?= $this->lang->line('search')." ".$this->lang->line('patient');?>
						</div>
						<div class="panel-body">
							<input type="hidden" name="title" id="title" value="<?= $title; ?>" class="form-control"/>
							<div class="col-md-3">
								<label for="display_id"><?php echo $this->lang->line('patient_id');?></label>
								<input type="text" <?php if(isset($session_date_id)){echo "readonly";}?> name="display_id" id="display_id" value="<?php if(isset($curr_patient)){echo $curr_patient['display_id']; } ?>" class="form-control"/>
							</div>
							<div class="col-md-3">
								<label for="ssn_id"><?php echo $this->lang->line('ssn_id');?></label>
								<input type="text" <?php if(isset($session_date_id)){echo "readonly";}?> name="ssn_id" id="ssn_id" value="<?php if(isset($curr_patient)){echo $curr_patient['ssn_id']; } ?>" class="form-control"/>
							</div>
							<div class="col-md-3">
								<label for="patient"><?php echo $this->lang->line('patient_name');?></label>
								<input type="text" <?php if(isset($session_date_id)){echo "readonly";}?> name="patient_name" id="patient_name" value="<?php if(isset($curr_patient)){echo $curr_patient['first_name']." " .$curr_patient['middle_name']." " .$curr_patient['last_name']; } ?>" class="form-control"/>
								<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<label for="phone"><?php echo $this->lang->line('mobile');?></label>
								<input type="text" <?php if(isset($session_date_id)){echo "readonly";}?> name="phone_number" id="phone_number" value="<?php if(isset($curr_patient)){echo $curr_patient['phone_number']; } ?>" class="form-control"/>
							</div>
							
						</div>
					</div>
					<div id="prescribed_medicine_table"></div>
				</div>
			</div>
		</div>
	</div>	
<script type="text/javascript">
 
    $(window).load(function(){
			

		var searcharrpatient=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",num:"' . $patient['phone_number'] . '",ssn_id:"' . $patient['ssn_id'] . '"}';
			$i++;
		}?>];
		$("#patient_name").autocomplete({
			autoFocus: true,
			source: searcharrpatient,
			minLength: 1,//search after one characters
			
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#phone_number").val(ui.item ? ui.item.num : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
				reload_prescription_table(ui.item.id);
			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
					}
			},
			response: function(event, ui) {
				if (ui.content.length === 0) 
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			}
		});   
		var searcharrdispname=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['display_id'] . '",id:"' . $patient['patient_id'] . '",num:"' . $patient['phone_number'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",ssn_id:"' . $patient['ssn_id'] . '"}';
			$i++;
		}?>];
		$("#display_id").autocomplete({
			autoFocus: true,
			source: searcharrdispname,
			minLength: 1,//search after one characters
			select: function(event,ui)
			{
				//do something
			   $("#patient_id").val(ui.item ? ui.item.id : '');
			   $("#patient_name").val(ui.item ? ui.item.patient : '');
			   $("#phone_number").val(ui.item ? ui.item.num : '');
			   	$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
				reload_prescription_table(ui.item.id);
			},
			change: function(event, ui) 
			{
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#email_id").val('');
				}
			},
			response: function(event, ui) 
			{
				if (ui.content.length === 0) 
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#email_id").val('');
				}
			}
		});   
		var searcharrmob=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['phone_number'] . '",ssn_id:"' . $patient['ssn_id'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '"}';
			$i++;
		}?>];
		$("#phone_number").autocomplete({
			autoFocus: true,
			source: searcharrmob,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#patient_name").val(ui.item ? ui.item.patient : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				$("#ssn_id").val(ui.item ? ui.item.ssn_id : '');
				reload_prescription_table(ui.item.id);
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0) 
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			}
		});  
		var search_ssn_id=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['ssn_id'] . '",id:"' . $patient['patient_id'] . '",num:"' . $patient['phone_number'] . '",display:"' . $patient['display_id'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",email:"' . $patient['email'] . '"}';
			$i++;
		}?>];
		$("#ssn_id").autocomplete({
			autoFocus: true,
			source: search_ssn_id,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#phone_number").val(ui.item ? ui.item.num : '');
				$("#patient_name").val(ui.item ? ui.item.patient : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				reload_prescription_table(ui.item.id);
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0) 
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					$("#ssn_id").val('');
				}
			}
		});  
		
		$('#print_prescription').click(function(){
			var patient_id = $("#patient_id").val();
			var url = '<?=site_url('prescription/print_view_prescription/');?>'+patient_id;
			$(location).attr('href',url);

			
		});
		function reload_prescription_table(patient_id){
			$.get("<?=site_url('prescription/ajax_prescribed_medicine/');?>"+patient_id, function(data, status){
				var prescription = $.parseJSON(data);
				var medicine_table = "<table class='table table-striped table-bordered table-hover dataTable'>";
				medicine_table = medicine_table + "<thead><tr><th>Sr No</th><th>Medicine</th><th>Dose</th><th>For Days</th><th>Instructions</th></thead>";
				medicine_table = medicine_table + "<tbody>";
				var sr_no = 1; 
				jQuery.each( prescription, function( key, value ) {
						medicine_table = medicine_table + "<tr>";
						jQuery.each( value, function( k, v ) {
							if(k=='medicine_id'){
								medicine_id = v;
							}else if(k=='medicine_name'){
								medicine_name = v;
							}else if(k=='dose_method'){
								dose_method = v;
							}else if(k=='for_days'){
								for_days = v;
							}else if(k=='instructions'){
								instructions = v;
							}
							
						});
						
						medicine_table = medicine_table + "<td>"+sr_no+"</td>";
						medicine_table = medicine_table + "<td>"+medicine_name+"</td>";
						medicine_table = medicine_table + "<td>"+dose_method+"</td>";
						medicine_table = medicine_table + "<td>"+for_days+"</td>";
						medicine_table = medicine_table + "<td>"+instructions+"</td>";
						medicine_table = medicine_table + "</tr>";
						sr_no++;	
				});
				medicine_table = medicine_table + "</tbody></table>";
				console.log(medicine_table);
				$('#prescribed_medicine_table').html(medicine_table);
			});
		}
		


});


</script>