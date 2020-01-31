<?php
/*
	This file is part of Chikitsa.

    Chikitsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Chikitsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Chikitsa.  If not, see <https://www.gnu.org/licenses/>.
*/
?>
<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

    $("#new_inquires").dataTable({
		"pageLength": 50
	});
});
</script>
<!-- Begin Page Content -->
    <div class="container-fluid">
<!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line("new_inquires");?></h1>
				
			<a href="#" class="btn square-btn-adjust btn-primary" data-toggle="modal" data-target="#myModal"><?php echo $this->lang->line('add_inquiry');?></a><br/> &nbsp; <br/>

			<?php if ($patients_detail) { ?>
			<table class="table table-striped table-bordered table-hover dataTable no-footer" id="new_inquires" >
				<thead>
				<tr>
					<th><?php echo $this->lang->line("patient")." ".$this->lang->line("name");?></th>
					<th><?php echo $this->lang->line("phone_number");?></th>
					<th><?php echo $this->lang->line("email");?></th>
					<th><?php echo $this->lang->line("visit");?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($patients_detail as $patient_detail) { ?>
					<tr>
						<td><?php echo $patient_detail['patient_name']; ?></td>
						<td><?php echo $patient_detail['phone_number']; ?></td>
						<td><?php echo $patient_detail['email']; ?></td>
						<td><?php echo $patient_detail['count']; ?></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<?php }else{ ?>
				<?php echo $this->lang->line("no") . " " .$this->lang->line("new_inquires") . " " . $this->lang->line("found");?>
			<?php } ?>

			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
							<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_inquiry');?></h4>
						</div>
						<?php echo form_open(); ?>
						<div class="modal-body">
								<div class="col-md-12"><label><?php echo $this->lang->line('name');?>:</label></div>
								<div class="col-md-12">
								<div class="row">
								<div class="col-md-4"><input type="text" id="first_name" name="first_name" class="form-control" placeholder="first name"/></div>
								<div class="col-md-4"><input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="middle name"/></div>
								<div class="col-md-4"><input type="text" id="last_name" name="last_name" class="form-control" placeholder="last name"/></div>
								</div>
								</div>

								<div class="col-md-12"><label><?php echo $this->lang->line('email_id');?>:</label></div>
								<div class="col-md-12"><input type="text" id="email_id" name="email_id" class="form-control"/></div>


								<div class="col-md-12"><label><?php echo $this->lang->line('mobile_no');?>:</label></div>
								<div class="col-md-12"><input type="text" id="mobile_no" name="mobile_no" class="form-control"/></div>

						</div>
						<div class="modal-footer">
							<input id="add_inquiry_submit" type="submit" name="submit" value="Save" class="btn btn-primary" data-dismiss="modal"/>
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close');?></button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
	</div>
<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$("#add_inquiry_submit").click(function(event) {
		event.preventDefault();
		var first_name = $("#first_name").val();
		var middle_name = $("#middle_name").val();
		var last_name = $("#last_name").val();
		var email_id = $("#email_id").val();
		var mobile_no = $("#mobile_no").val();

		$.post( "<?php echo base_url(); ?>index.php/patient/add_inquiry",
			{first_name: first_name, middle_name: middle_name,last_name: last_name,email: email_id, phone_number:mobile_no},
			function(data,status){
				alert(data);
				location.reload();
			});
	});
});
</script>
    </body>
</html>