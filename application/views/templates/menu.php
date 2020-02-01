<!-- Sidebar -->

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
		
        <!-- Sidebar - Brand -->
	    <?php if($clinic['clinic_logo'] != NULL){  ?>
			<a class="sidebar-brand d-flex align-items-center justify-content-center" style="padding:0px;background:#FFF;" href="<?= site_url($login_page); ?>">
				<img src="<?php echo base_url().$clinic['clinic_logo']; ?>" alt="Logo"  height="60"  />
			</a>
		<?php  }elseif($clinic['clinic_name'] != NULL){  ?>
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url($login_page); ?>">
				<?= $clinic['clinic_name'];?>
			</a>
		<?php  } else { ?>
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url($login_page); ?>">
				<?= $software_name;?>
			</a>
		<?php }  ?>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

	  <?php
		$menus = $this->menu_model->get_menu_array();

		foreach($menus as $menu_name => $menu){
			$active = "";
			if(!isset($menu['parent_name']) || $menu['parent_name'] == ""){
				if (isset($menu['has_access']) && in_array($level, $menu['has_access'])){
					//if (isset($menu['required_module'])  && $menu['required_module']!= ""&& in_array($menu['required_module'],$active_modules)){
						$number_of_child = 0;
						if(isset($menu['child_menus'])){
							$number_of_child = count($menu['child_menus']);
						}
						if( isset($menu['menu_url']) && $menu['menu_url'] == $this->uri->segment(1)."/".$this->uri->segment(2)){
							$active = "active";
						}
				?>
				<li class="nav-item <?=$active;?>">
					<?php if($number_of_child > 0){ ?>
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_<?=$menu_name;?>" aria-expanded="true" aria-controls="collapse_<?=$menu_name;?>">

					<?php }else{ ?>
					<?php if(isset($menu['menu_url'])){ ?>
					<a class="nav-link" href="<?= site_url( $menu['menu_url'] ); ?>">
					<?php }else{ ?>
					<a class="nav-link" href="#">

					<?php } ?>
					<?php } ?>
						<?php if(isset($menu['menu_icon'])){ ?>
						<i class="fas fa-fw <?php echo $menu['menu_icon']; ?>"></i>
						<?php } ?>
						<?php if(isset($menu['menu_text'])){ ?>

						<span><?php echo $this->lang->line($menu['menu_text']);  ?></span>
						<?php } ?>
					</a>
					<?php if($number_of_child > 0){ ?>
					<div id="collapse_<?=$menu_name;?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					  <div class="bg-white py-2 collapse-inner rounded">
						<?php foreach($menu['child_menus'] as $child_menu) { ?>
							<?php foreach($menus as $child_menu_name => $child_menu_detail) { ?>
								<?php if($child_menu_name == $child_menu) { ?>
									<?php if(count($child_menu_detail['child_menus']) > 0){ ?>
										<h6 class="collapse-header"><?php echo $this->lang->line($child_menu_detail['menu_text']);?></h6>
										<?php foreach($child_menu_detail['child_menus'] as $grandchild_menu) { ?>
											<?php foreach($menus as $grand_child_menu_name => $grand_child_menu_detail) { ?>
												<?php if($grand_child_menu_name == $grandchild_menu) { ?>
													<?php $active = ""; ?>
													<?php if( isset($grand_child_menu_detail['menu_url']) && $grand_child_menu_detail['menu_url'] == $this->uri->segment(1)."/".$this->uri->segment(2)){
														$active = "active";
												  	}?>
													<a class="collapse-item <?=$active;?>" href="<?= site_url( $grand_child_menu_detail['menu_url'] ); ?>"><?php echo $this->lang->line($grand_child_menu_detail['menu_text']);?></a>

												<?php } ?>
											<?php } ?>
										<?php } ?>
										<hr class="sidebar-divider">
									<?php }else{ ?>
										<?php $active = ""; ?>
										<?php if( isset($child_menu_detail['menu_url']) && $child_menu_detail['menu_url'] == $this->uri->segment(1)."/".$this->uri->segment(2)){
											$active = "active";
										}?>
									<a class="collapse-item <?=$active;?>" href="<?= site_url( $child_menu_detail['menu_url'] ); ?>"><?php echo $this->lang->line($child_menu_detail['menu_text']);?></a>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					  </div>
					</div>
					<?php } ?>
				</li>
			<?php //}
				}
			}
		}
	  /*
			//List all top level menus
			$parent_name="";
			$result_top_menu = $this->menu_model->find_menu($parent_name,$level);
			foreach ($result_top_menu as $top_menu):
				$id = $top_menu['id'];
				$parent_name = $top_menu['menu_name'];
				//Does the user have access to this menu?
				if($this->menu_model->has_access($top_menu['menu_name'],$level)){
					if($this->menu_model->is_module_active($top_menu['required_module'])){ ?>

					<li class="nav-item">
						<a class="nav-link" href="<?= site_url( $top_menu['menu_url'] ); ?>" <?php if($this->menu_model->is_active_menu($current_page,$top_menu['menu_name'])) echo "class='active-menu'";?>><i class="fas fa-fw <?php echo $top_menu['menu_icon']; ?>"></i><?php echo $this->lang->line($top_menu['menu_text']);  ?>
							<?php if($top_menu['menu_name'] == "modules" && $updates_available != "") {echo "<span class='available_updates'>".$updates_available."</span>";} ?>
						</a>

					<?php
						//Select all Childs
						$result_sub_menu = $this->menu_model->find_menu($parent_name,$level);
						$rowcount= count($result_sub_menu);
						if($rowcount != 0){?>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
							  <div class="bg-white py-2 collapse-inner rounded">
								<h6 class="collapse-header">Custom Components:</h6>
								<a class="collapse-item" href="buttons.html">Buttons</a>
								<a class="collapse-item" href="cards.html">Cards</a>
							  </div>
							</div>
							<ul class="navbar-nav">
								<?php
								foreach ($result_sub_menu as $sub_menu){
									//Check access for sub menu
									if($this->menu_model->has_access($sub_menu['menu_name'],$level)){
										if($this->menu_model->is_module_active($sub_menu['required_module'])){ ?>
										<li>
											<a href="<?php echo site_url($sub_menu['menu_url']); ?>" <?php if($this->menu_model->is_active_menu($current_page,$sub_menu['menu_name'])) echo "class='active-menu'";?>><?php echo  $this->lang->line($sub_menu['menu_text']); ?></a>
											<?php //Select all Childs
												$result_sub_menu2 = $this->menu_model->find_menu($sub_menu['menu_name'],$level);
												$rowcount2= count($result_sub_menu2);
												if($rowcount2 != 0){?>
													<ul class="nav nav-third-level">
													<?php
													foreach ($result_sub_menu2 as $sub_menu2):
														if($this->menu_model->has_access($sub_menu2['menu_name'],$level)){
															if($this->menu_model->is_module_active($sub_menu2['required_module'])){ ?>
																<li><a href="<?php echo site_url($sub_menu2['menu_url']); ?>" <?php if($this->menu_model->is_active_menu($current_page,$sub_menu2['menu_name'])) echo "class='active-menu'";?>><?php echo $this->lang->line($sub_menu2['menu_text']); ?></a></li>
													<?php
															}
														}
													endforeach;
													?>
													</ul>
												<?php  } ?>
										</li>
										<?php  } ?>
									<?php  } ?>
								<?php  } ?>
							</ul>
						<?php  } ?>
				</li>
					<?php
					}
					}
			endforeach;
*/
			?>



    </ul>
    <!-- End of Sidebar -->

	<!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>

            </li>



            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$user['name'];?></span>

				<?php if($user['profile_image']!=""){ ?>
				<img class="img-profile rounded-circle" src="<?php echo base_url()."uploads/profile_picture/". $user['profile_image']; ?>" alt="Profile Image"  height="100" width="100" />
				<?php }else{ ?>
				<img class="img-profile rounded-circle" src="<?php echo base_url()."uploads/images/Profile.png"; ?>" alt="Profile Image"  height="100" width="100" />
				<?php } ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?=site_url("admin/change_profile"); ?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <?php
					$new_messages = $this->menu_model->new_messages_count();
					if($new_messages > 0){
				?>
				<a data-notifications="<?=$new_messages;?>" href="<?=site_url("chat/index"); ?>" class="btn btn-primary square-btn-adjust"><i class="fa fa-bell" aria-hidden="true"></i></a>
				<?php } elseif($new_messages == 0) { ?>
				<a href="<?=site_url("chat/index");?>" class="btn btn-primary square-btn-adjust"><i class="fa fa-bell" aria-hidden="true"></i></a>
				<?php } ?>
				<?php if (in_array("centers", $active_modules)) { ?>
				<a href="<?=site_url("centers/change_center"); ?>" class="btn btn-primary square-btn-adjust">Change Center</a>
				<?php } ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= site_url("login/logout"); ?>" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->
