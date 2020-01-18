<head>
<style>
	.table-bordered{
		border-collapse:collapse;
	}
	.table-bordered > thead > tr > th,
	.table-bordered > tbody > tr > th,
	.table-bordered > tfoot > tr > th,
	.table-bordered > thead > tr > td,
	.table-bordered > tbody > tr > td,
	.table-bordered > tfoot > tr > td{
		border:1px solid #ddd;
	}
	.table > thead > tr > th,
	.table > tbody > tr > th,
	.table > tfoot > tr > th,
	.table > thead > tr > td,
	.table > tbody > tr > td,
	.table > tfoot > tr > td{
		padding:8px;
		line-height:1.42857143;
		vertical-align:top;
	}
</style>
</head>
<body onload="window.print();">
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<h3>
					<?php echo $this->lang->line('prescription');?>
				</h3>
				<div class="panel-body">
					<input type="hidden" name="patient_id" id="patient_id" value="<?php if(isset($curr_patient)){echo $curr_patient['patient_id']; } ?>"/>
					<div class="panel panel-default">
						<h4>
							<?= $this->lang->line('patient');?>
						</h4>
						<table class='table table-bordered'>
                            <tbody>
							<tr>
								<td><?php echo $this->lang->line('patient_id');?></td>
								<td><?php echo $this->lang->line('patient_name');?></td>
								<td><?php echo $this->lang->line('ssn_id');?></td>
								<td><?php echo $this->lang->line('mobile');?></td>
							</tr>
							<tr>
								<td><?php echo $patient['display_id'];?></td>
								<td><?php echo $patient['first_name']." " .$patient['middle_name']." " .$patient['last_name'];?></td>
								<td><?php echo $patient['ssn_id'];?></td>
								<td><?php echo $patient['phone_number'];?></td>
							</tr>
                            </tbody>
						</table>
					</div>
					<h4><?= $this->lang->line('prescription');?></h4>
					<div id="prescribed_medicine_table">
					<table class='table table-bordered'>
					<thead>
						<tr>
							<th><?php echo $this->lang->line('sr_no');?></th>
							<th><?php echo $this->lang->line('medicine');?></th>
							<th><?php echo $this->lang->line('dose');?></th>
							<th><?php echo $this->lang->line('days');?></th>
						</tr>
					</thead>
					<tbody>
					<?php $sr_no = 1; ?>
					<?php 
						$dose_method = array(
											'OD'       => 'Once (OD)',
											'BD'       => 'Twice (BD)',
											'TDS'      => 'Three Time (TDS)',
											'HS'       => 'Night (HS)',
											'QID'      => 'Four Time (QID)',
									);
					?>
					<?php foreach($prescriptions as $prescription){ ?>
						<tr>
							<td><?=$sr_no;?></td>
							<td><?=$medicine_name[$prescription['medicine_id']];?></td>
							<td><?=$dose_method[$prescription['dose_method']];?></td>
							<td><?=$prescription['for_days'];?></td>
						</tr>
					<?php $sr_no++;?>
					<?php } ?>
					</tbody>
					</table>
					</div>
					
				</div>
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
				medicine_table = medicine_table + "<thead><tr><th>Sr No</th><th>Medicine</th><th>Morning</th><th>Afternoon</th><th>Night</th><th>Days</th><th>Total</th></thead>";
				medicine_table = medicine_table + "<tbody>";
				var sr_no = 1; 
				jQuery.each( prescription, function( key, value ) {
						medicine_table = medicine_table + "<tr>";
						jQuery.each( value, function( k, v ) {
							if(k=='medicine_id'){
								medicine_id = v;
							}else if(k=='medicine_name'){
								medicine_name = v;
							}else if(k=='freq_morning'){
								freq_morning = v;
							}else if(k=='freq_afternoon'){
								freq_afternoon = v;
							}else if(k=='freq_night'){
								freq_night = v;
							}else if(k=='quantity'){
								quantity = v;
							}else if(k=='for_days'){
								for_days = v;
							}
							
						});
						
						medicine_table = medicine_table + "<td>"+sr_no+"</td>";
						medicine_table = medicine_table + "<td>"+medicine_name+"</td>";
						medicine_table = medicine_table + "<td>"+freq_morning+"</td>";
						medicine_table = medicine_table + "<td>"+freq_afternoon+"</td>";
						medicine_table = medicine_table + "<td>"+freq_night+"</td>";
						medicine_table = medicine_table + "<td>"+for_days+"</td>";
						medicine_table = medicine_table + "<td>"+quantity+"</td>";
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
</body>