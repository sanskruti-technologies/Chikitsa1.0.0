<script type="text/javascript">
    $(window).load(function(){
		$('.email_alert').change(function() {	
			if($(this).prop("checked")){
				$(this).parent().parent().siblings().prop('checked', true);
				$(this).parent().parent().parent().parent().siblings().prop('checked', true);
			}
		});
		$('.sms_alert').change(function() {	
			if($(this).prop("checked")){
				$(this).parent().parent().siblings().prop('checked', true);
				$(this).parent().parent().parent().parent().siblings().prop('checked', true);
			}
		});
	});
</script>
<?php
	function is_enabled($is_enabled){
		if($is_enabled == 1){
			return "checked='checked'";	
		}else{
			return "";
		}
	}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6">
			<div class="panel panel-primary" >
				<div class="panel-heading" >
					<?php echo $this->lang->line('settings');?>
				</div>
				<div class="panel-body" >
					<?php echo form_open('alert/save_settings'); ?>    	
						<div class="form-group">
							<?php 
								foreach($alerts as $alert_level1){
								if(!isset($alert_level1['parent_alert']) || $alert_level1['parent_alert'] == ''){
								?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="<?=$alert_level1['alert_type'];?>_alert[]" class="<?=$alert_level1['alert_type'];?>_alert" value="<?=$alert_level1['alert_name'];?>" <?=is_enabled($alert_level1['is_enabled']);?>> <?=$alert_level1['alert_label'];?> 
											<?php //Level 2
												foreach($alerts as $alert_level2){
													if($alert_level2['parent_alert'] == $alert_level1['alert_name']){
														?><div class="checkbox">
															<label>
																<input type="checkbox" name="<?=$alert_level2['alert_type'];?>_alert[]" class="<?=$alert_level1['alert_type'];?>_alert" value="<?=$alert_level2['alert_name'];?>" <?=is_enabled($alert_level2['is_enabled']);?>> <?=$alert_level2['alert_label'];?> 
																<?php //Level 3
																	foreach($alerts as $alert_level3){
																		if($alert_level3['parent_alert'] == $alert_level2['alert_name']){
																			$required_module = $alert_level3['required_module'];
																			if(in_array($required_module, $active_modules) || $required_module == '') { 
																			?><div class="checkbox">
																				<label>
																					<input type="checkbox" name="<?=$alert_level3['alert_type'];?>_alert[]" class="<?=$alert_level1['alert_type'];?>_alert" value="<?=$alert_level3['alert_name'];?>" <?=is_enabled($alert_level3['is_enabled']);?>> <?=$alert_level3['alert_label'];?>
																					<a href="<?=site_url('alert/'.$alert_level3['alert_type'].'_format/'.$alert_level3['alert_format_name']);?>" class="btn btn-info btn-xs square-btn-adjust"><?=$this->lang->line("edit_format");?></a>
																					<?php 
																					if($alert_level3['alert_occur'] != "EVENT"){?>
																						<a href="<?=site_url('alert/'.$alert_level3['alert_type'].'_alert_time/'.$alert_level3['alert_format_name'].'/'.$alert_level3['alert_occur']);?>" class="btn btn-warning btn-xs square-btn-adjust"><?=$this->lang->line("set_alert_time");?></a>
																					<?php }?>
																					
																				</label>
																				
																			  </div><?php
																			}
																		}
																	}?>
															</label>
														</div><?php	
													}
												}
											?>
										</label>
									</div>
									<?php
								}
							} ?>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
						</div>
					<?php echo form_close(); ?>    	
				</div>
			</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("sms_settings");?>
					</div>
					<div class="panel-body" >
						<?php echo form_open('alert/sms_settings'); ?>    	
							<div class="form-group">
								<label for="username"><?=$this->lang->line("username");?></label> <small><?=$this->lang->line("sms_username_instruction");?></small>
								<input type="text" name="username" value="<?=$sms_api_username;?>"  class="form-control"/>
								<?php echo form_error('username','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="password"><?=$this->lang->line("password");?></label> <small><?=$this->lang->line("sms_password_instruction");?></small>
								<input type="password" name="password" value="<?=$sms_api_password;?>"  class="form-control"/>
								<?php echo form_error('password','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="senderid"><?=$this->lang->line("senderid");?></label> <small><?=$this->lang->line("sms_senderid_instruction");?></small>
								<input type="text" name="senderid" value="<?=$senderid;?>"  class="form-control"/>
								<?php echo form_error('senderid','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="country_code"><?=$this->lang->line("country_code");?></label> <small><?=$this->lang->line("sms_countrycode_instruction");?></small>
								<input type="country_code" name="country_code" value="<?=$country_code;?>"  class="form-control"/>
								<?php echo form_error('country_code','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="send_sms_url"><?=$this->lang->line("sms_url");?></label> <small><?=$this->lang->line("sms_url_instruction");?></small>
								<textarea name="send_sms_url"  class="form-control"><?=$send_sms_url;?></textarea>
								<?php echo form_error('send_sms_url','<div class="alert alert-danger">','</div>'); ?>
								<label><?=$this->lang->line("shortcode_label");?></label>
								<ul>
									<li><?=$this->lang->line("shortcode_username");?></li>
									<li><?=$this->lang->line("shortcode_password");?></li>
									<li><?=$this->lang->line("shortcode_senderid");?></li>
									<li><?=$this->lang->line("shortcode_mobileno");?></li>
									<li><?=$this->lang->line("shortcode_message");?></li>
									<li><?=$this->lang->line("shortcode_template_id");?></li>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
							</div>
						<?php echo form_close(); ?>    	
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("email_settings");?>
					</div>
					<div class="panel-body" >
						<?php echo form_open('alert/email_settings'); ?>    	
							<div class="form-group">
								<label for="from_email"><?=$this->lang->line("from_email");?></label> 
								<input type="text" name="from_email" value="<?=$from_email;?>"  class="form-control"/>
								<?php echo form_error('from_email','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<small><?=$this->lang->line("smtp_instructions");?></small>
							<div class="form-group">
								<label for="email_password"><?=$this->lang->line("password");?></label> 
								<input type="password" name="email_password" value="<?=$email_password;?>"  class="form-control"/>
								<?php echo form_error('email_password','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="from_name"><?=$this->lang->line("from_name");?></label> 
								<input type="text" name="from_name" value="<?=$from_name;?>"  class="form-control"/>
								<?php echo form_error('from_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="smtp_host"><?=$this->lang->line("smtp_host");?></label> 
								<input type="text" name="smtp_host" value="<?=$smtp_host;?>"  class="form-control"/>
								<?php echo form_error('smtp_host','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="smtp_port"><?=$this->lang->line("smtp_port");?></label> 
								<input type="text" name="smtp_port" value="<?=$smtp_port;?>"  class="form-control"/>
								<?php echo form_error('smtp_port','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?=$this->lang->line("save");?></button>
							</div>
						<?php echo form_close(); ?>    	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>