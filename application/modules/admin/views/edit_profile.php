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

<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {//Check if input has files.
		var reader = new FileReader(); //Initialize FileReader.

		reader.onload = function (e) {
		$('#PreviewImage').attr('src', e.target.result);
		$("#PreviewImage").resizable({ aspectRatio: true, maxHeight: 300 });
		};
		reader.readAsDataURL(input.files[0]);
	}else {
		$('#PreviewImage').attr('src', "#");
	}
}
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
	<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('change') . " " .$this->lang->line('profile');?></h1>

					<?php echo form_open_multipart('admin/change_profile/'); ?>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="name"><?php echo $this->lang->line('name');?></label>
							<input type="text" class="form-control" name="name" id="name" value="<?php echo $user['name']; ?>" />
							<?php echo form_error('name','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group image_wrapper col-md-6">
							<?php if($user['profile_image']!=""){ ?>
							<img id="PreviewImage" src="<?php echo base_url()."uploads/profile_picture/". $user['profile_image']; ?>" alt="Profile Image"  height="100" width="100" />
							<?php }else{ ?>
							<img id="PreviewImage" src="<?php echo base_url()."uploads/images/Profile.png"; ?>" alt="Profile Image"  height="100" width="100" />
							<?php } ?>
							<?php if(isset($patient_id)) {?>
							<a class="btn btn-danger btn-sm square-btn-adjust" href="<?=site_url('patient/remove_profile_image/'.$patient_id.'/'.$called_from);?>"><?php echo $this->lang->line('remove_patient_image');?></a>
							<?php }?>
							<input type="file" id="profile_image" name="profile_image" class="form-control" size="20" onchange="readURL(this);" />
							<input type="hidden" id="src" name="src" value="<?php echo $user['profile_image']; ?>" />
							<?php echo form_error('profile_image','<div class="alert alert-danger">','</div>'); ?>
						</div>
						</div>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="username"><?php echo $this->lang->line('username');?></label>
							<input type="text" class="form-control" name="username" id="username" value="<?php echo $user['username']; ?>" readonly="readonly"/>
							<?php echo form_error('username','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group col-md-6">
							<label for="oldpassword"><?php echo $this->lang->line('old_password');?></label>
							<input type="password" class="form-control"  name="oldpassword" id="oldpassword" value="" />
							<?php echo form_error('oldpassword','<div class="alert alert-danger">','</div>'); ?>
						</div>
						</div>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="newpassword"><?php echo $this->lang->line('new_password');?></label>
							<input type="password" class="form-control"  name="newpassword" id="newpassword" value=""/>
							<?php echo form_error('newpassword','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group col-md-6">
							<label for="passconf"><?php echo $this->lang->line('confirm_password');?></label>
							<input type="password" class="form-control"  name="passconf" id="passconf" value=""/>
							<?php echo form_error('passconf','<div class="alert alert-danger">','</div>'); ?>
						</div>
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('edit');?></button>
						</div>
					<?php echo form_close(); ?>
				</div>
