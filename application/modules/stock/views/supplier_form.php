<?php
	if(isset($supplier_id)){
		$first_name = set_value('first_name',$supplier['first_name']);
		$middle_name = set_value('middle_name',$supplier['middle_name']);
		$last_name = set_value('last_name',$supplier['last_name']);
		$phone_number = set_value('phone_number',$supplier['phone_number']);
		$contact_id = set_value('contact_id',$supplier['contact_id']);
		$email = set_value('email',$supplier['email']);
		$type = set_value('type',$supplier['type']);
		$address_line_1 = set_value('address_line_1',$supplier['address_line_1']);
		$address_line_2 = set_value('address_line_2',$supplier['address_line_2']);
		$city = set_value('city',$supplier['city']);
		$state = set_value('state',$supplier['state']);
		$postal_code = set_value('postal_code',$supplier['postal_code']);
		$country = set_value('country',$supplier['country']);
		$edit = TRUE;
	}else{

		$supplier_id = "";
		$first_name = set_value('first_name',"");
		$middle_name = set_value('middle_name',"");
		$last_name = set_value('last_name',"");
		$phone_number = set_value('phone_number',"");
		$email = set_value('email',"");
		$type = set_value('type',"");
		$address_line_1 = set_value('address_line_1',"");
		$address_line_2 = set_value('address_line_2',"");
		$city = set_value('city',"");
		$state = set_value('state',"");
		$postal_code = set_value('postal_code',"");
		$country = set_value('country',"");
		$edit = FALSE;
	}
?>
<script>
	$(window).load(function() {
		$( "#add_contact_detail" ).click(function() {

			var contact_detail_count = parseInt( $( "#contact_detail_count" ).val());
			contact_detail_count = contact_detail_count + 1;
			$( "#contact_detail_count" ).val(contact_detail_count);

			var contact_detail = "<div class='col-md-12'><div class='col-md-4'><select name='contact_type[]' class='form-control'><option value='mobile'>Mobile</option><option value='office'>Office</option><option value='residence'>Residence</option></select></div><div class='col-md-4'><input type='input' name='contact_detail[]' value='' class='form-control'/></div><div class='col-md-4'><a href='#' id='delete_contact_detail"+contact_detail_count+"' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a></div></div>";
			$( "#contact_detail_list" ).append(contact_detail);

			$("#delete_contact_detail"+contact_detail_count).click(function() {
				$(this).parent().parent().remove();
			});
		});
		<?php
			/*$i=1;
			foreach($contact_details as $contact_detail){
				?>
				$("#delete_contact_detail<?=$i;?>").click(function() {
					$(this).parent().parent().remove();
				});
				<?php
				$i++;
			}*/
		?>
	});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('supplier');?></h1>
			<?php
				if($edit){
					echo form_open('stock/edit_supplier');
					?>
					<input type="hidden" name="supplier_id" value="<?=$supplier_id?>"/>
					<input type="hidden" name="contact_id" value="<?=$contact_id?>"/>
					<?php
				}else{
					echo form_open('stock/add_supplier');
				}
			?>
				<div class="row">
						<div class="col-md-12">
							<label for="supplier_name"><?php echo $this->lang->line('supplier_name');?></label>
						</div>
						</div>
						<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							<input type="input" name="first_name" value="<?=$first_name;?>" placeholder="First Name" class="form-control"/>
						</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<input type="input" name="middle_name" value="<?=$middle_name;?>" placeholder="Middle Name" class="form-control"/>
						</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<input type="input" name="last_name" value="<?=$last_name;?>" placeholder="Last Name" class="form-control"/>
						</div>
						</div>
						</div>
						<?php echo form_error('last_name','<div class="alert alert-danger">','</div>'); ?>

							<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							<label for="phone_number"><?php echo $this->lang->line('contact_number');?></label>
							<input type="input" name="phone_number" value="<?=$phone_number;?>" class="form-control"/>
						</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<label for="email"><?php echo $this->lang->line('email');?></label>
							<input type="input" name="email" value="<?=$email;?>" class="form-control"/>
						</div>
						</div>
						</div>
						<div id="contact_detail_list">
						<!--div class="col-md-12">
							<a href="#" id="add_contact_detail" class="btn btn-primary square-btn-adjust">Add More Contact Numbers</a>
							<?php
								$count = 0;
								if(isset($contact_details)){
									$count = count($contact_details);
								}
							?>
							<input type="hidden" id="contact_detail_count" value="<?=$count;?>"/>
						</div-->

							<?php /*if(!empty($contact_details)){?>
								<?php $i=1; ?>
								<?php foreach($contact_details as $contact_detail){ ?>
									<div class="col-md-12">
									<div class="col-md-4">
										<select name="contact_type[]" class="form-control">
											<option value="mobile" <?php if($contact_detail['type'] == "mobile") echo "selected"; ?>>Mobile</option>
											<option value="office" <?php if($contact_detail['type'] == "office") echo "selected"; ?>>Office</option>
											<option value="residence" <?php if($contact_detail['type'] == "residence") echo "selected"; ?>>Residence</option>
										</select>
									</div>
									<div class="col-md-4">
										<input type="input" name="contact_detail[]" value="<?=$contact_detail['detail'];?>" placeholder="Contact Number"  class="form-control"/>
									</div>
									<div class='col-md-4'>
										<a href='#' id='delete_contact_detail<?=$i;?>' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a>
									</div>
									</div>
									<?php $i++; ?>
								<?php } ?>
							<?php }else{ ?>
								<div class="col-md-12">
								<div class="col-md-4">
									<select name="contact_type[]" class="form-control">
										<option value="mobile">Mobile</option>
										<option value="office">Office</option>
										<option value="residence">Residence</option>
									</select>
								</div>
								<div class="col-md-4">
									<input type="input" name="contact_detail[]" value="" placeholder="Contact Number"  class="form-control"/>
								</div>
								<div class="col-md-4">
								</div>
								</div>
							<?php } ?>
							<?php echo form_error('contact_detail[]','<div class="alert alert-danger">','</div>'); */?>
						</div>

							<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							<label for="type"><?php echo $this->lang->line('addresstype');?></label>
							<select name="type" class="form-control">
								<option selected></option>
								<option value="Home" <?php if ($type == "Home") { echo "selected"; } ?>><?php echo $this->lang->line('home');?></option>
								<option value="Office" <?php if ($type == "Office") { echo "selected"; } ?>><?php echo $this->lang->line('office');?></option>
								</select>
							<?php echo form_error('type','<div class="alert alert-danger">','</div>'); ?>
						</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label for="address_line_1"><?php echo $this->lang->line('address_line_1');?></label>
							<input type="input" name="address_line_1" value="<?=$address_line_1;?>" class="form-control"/>
						</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label for="address_line_2"><?php echo $this->lang->line('address_line_2');?></label>
							<input type="input" name="address_line_2" value="<?=$address_line_2;?>" class="form-control"/>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							<label for="city"><?php echo $this->lang->line('city');?></label>
							<input type="input" name="city" value="<?=$city;?>" class="form-control"/>
						</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<label for="state"><?php echo $this->lang->line('state');?></label>
							<input type="input" name="state" value="<?=$state;?>" class="form-control"/>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							<label for="postal_code"><?php echo $this->lang->line('postal_code');?></label>
							<input type="input" name="postal_code" value="<?=$postal_code;?>" class="form-control"/>
						</div>
					</div>
						<div class="col-md-6">
							<div class="form-group">
							<label for="country"><?php echo $this->lang->line('country');?></label>
							<input type="input" name="country" value="<?=$country;?>" class="form-control"/>
						</div>
					</div>
					</div>
						<div class="row">
					<div class="col-md-6">
					<div class="form-group">

							<button type="submit" name="submit" class="btn-sm square-btn-adjust btn btn-primary" /><i class="fa fa-floppy-o"></i>&nbsp;<?php echo $this->lang->line('save');?></button>
							<a class="btn-sm square-btn-adjust btn btn-primary" href="<?=site_url('stock/supplier');?>" /><?php echo $this->lang->line('back');?></a>
						</div>
						</div>
						</div>
			<?php echo form_close(); ?>
			</div>
