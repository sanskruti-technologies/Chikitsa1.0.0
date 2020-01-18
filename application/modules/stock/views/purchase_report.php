<script type="text/javascript" charset="utf-8">
	$( window ).load(function() {
		$("#from_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
			onShow:function( ct ){
				//var ToDate = $.datepicker.formatDate('yy/mm/dd', new Date($('#to_date').val()));
				var ToDate = $('#to_date').val();
				this.setOptions({
					maxDate:ToDate?ToDate:false,
					formatDate:'<?=$def_dateformate; ?>'
				})
			}
		});
		$("#to_date").datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollMonth:false,
			scrollTime:false,
			scrollInput:false,
			onShow:function( ct ){
				//var FromDate = $.datepicker.formatDate('yy/mm/dd', new Date($('#from_date').val()));
				var FromDate = $('#from_date').val();
				this.setOptions({
					minDate:FromDate?FromDate:false,
					formatDate:'<?=$def_dateformate; ?>'
				})
			}
		});
	});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
            <div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('purchase') . ' ' . $this->lang->line('report');?></h2></div>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open('stock/purchase_report'); ?>
				<div class="col-md-12">
				<div class="col-md-3">
						<label><?php echo $this->lang->line('from_date');?></label>
						<input type="text" name="from_date" id="from_date" class="form-control" value="<?=date($def_dateformate,strtotime($from_date));?>" />
					</div>
					<div class="col-md-3">
						<label><?php echo $this->lang->line('to_date');?></label>
						<input type="text" name="to_date" id="to_date" class="form-control" value="<?=date($def_dateformate,strtotime($to_date));?>" />
					</div>


					<div class="col-md-3">
						<label><?php echo $this->lang->line('item');?></label>
						<select id="item" class="form-control" multiple="multiple" tabindex="4" name="item[]">
							<?php foreach ($items as $item) {
								echo "<option value='".$item['item_id']."'";
								foreach ($selected_items as $selected_item){
									if($item['item_id'] == $selected_item){
										echo " selected ";
									}
								}
								echo ">".$item['item_name']."</option>";
							} ?>
						</select>
						<script>jQuery('#item').chosen();</script>
					</div>
					<div class="col-md-3">
						<label><?php echo $this->lang->line('supplier');?></label>
						<select id="supplier" class="form-control" multiple="multiple" tabindex="4" name="supplier[]">
							<?php foreach ($suppliers as $supplier) {
								echo "<option value='".$supplier['supplier_id']."'";
								foreach ($selected_suppliers as $selected_supplier){
									if($supplier['supplier_id'] == $selected_supplier){
										echo " selected ";
									}
								}
								echo ">".$supplier['first_name']." ".$supplier['middle_name']." ".$supplier['last_name']."</option>";
							} ?>
						</select>
						<script>jQuery('#supplier').chosen();</script>
					</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-12"><label><?= $this->lang->line('group_by');?><label></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="none" <?php if($group_by == 'none'){echo "checked='checked'";}?>><?= $this->lang->line('none');?></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="bill_no" <?php if($group_by == 'bill_no'){echo "checked='checked'";}?>><?= $this->lang->line('bill')." ".$this->lang->line('no');?> </div>
						<div class="col-md-2"><input type="radio" name="group_by" value="purchase_date" <?php if($group_by == 'purchase_date'){echo "checked='checked'";}?>><?= $this->lang->line('date');?></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="item_id" <?php if($group_by == 'item_id'){echo "checked='checked'";}?>><?= $this->lang->line('item');?></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="supplier_id" <?php if($group_by == 'supplier_id'){echo "checked='checked'";}?>><?= $this->lang->line('supplier');?></div>
					</div>

					<div class="col-md-12">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" ><?php echo $this->lang->line('go');?></button>
					</div>
					<?php echo form_close(); ?>
			</div>
			<div class="panel-body table-responsive-25">
				<div class="table-responsive">
					<table class="table table-striped table-hover display responsive nowrap" id="purchase_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('bill_no');?></th>
								<th><?php echo $this->lang->line('purchase_date');?></th>
								<th><?php echo $this->lang->line('item');?></th>
								<th><?php echo $this->lang->line('supplier');?></th>
								<th style="text-align:right;"><?php echo $this->lang->line('quantity');?></th>
								<th style="text-align:right;"><?php echo $this->lang->line('cost_price');?></th>
								<th style="text-align:right;"><?php echo $this->lang->line('total');?></th>
							</tr>
						</thead>
						<tbody>
						<?php
									$purchase_quantity=0;
									$purchase_cost=0;
									$purchase_total=0;
									$current_bill_no=0;
									$current_purchase_date = '1970-01-01';
									$current_item_id=0;
									$group_total=0;
									$total_quantity = 0;
									$current_supplier_id = 0;
									$current_supplier_name = "";
								?>
					<?php foreach ($purchase_report as $purchase){

									if($group_by == "bill_no"){
										if($current_bill_no != $purchase['bill_no']){
											if($current_bill_no != 0){?>
												<tr>
													<?php if($group_by == "bill_no"){ ?>
														<th colspan="3"><?=$current_bill_no;?></th>
													<?php }elseif($group_by == "purchase_date"){ ?>
														<th colspan="3"><?=date($def_dateformate,strtotime($current_purchase_date));?></th>
													<?php }elseif($group_by == "item_id"){ ?>
														<th colspan="3"><?=$current_item_name;?></th>
													<?php }else{ ?>
														<th colspan="3"></th>
													<?php } ?>
													<th></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;"><?=currency_format($group_total / $total_quantity);?><?php if($currency_postfix) echo $currency_postfix;?></th>
													<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
												</tr>
											<?php $group_total = 0;
												$total_quantity = 0;
											}
										}
										$current_bill_no = $purchase['bill_no'];
									}elseif($group_by == "purchase_date"){
										if($current_purchase_date != $purchase['purchase_date']){
											if($current_purchase_date != '1970-01-01'){?>
												<tr>
													<th colspan="3"><?=date($def_dateformate,strtotime($current_purchase_date));?></th>
													<th></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;"><?=currency_format($group_total / $total_quantity);?><?php if($currency_postfix) echo $currency_postfix;?></th>
													<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
												</tr>
											<?php $group_total = 0;
											$total_quantity = 0;

											}
										}
										$current_purchase_date = $purchase['purchase_date'];
									}elseif($group_by == "item_id"){
										if($current_item_id != $purchase['item_id']){
											if($current_item_id != 0){?>
												<tr>
													<th colspan="3"><?=$current_item_name;?></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;"><?=currency_format($group_total / $total_quantity);?><?php if($currency_postfix) echo $currency_postfix;?></th>
													<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
												</tr>
											<?php $group_total = 0;
											$total_quantity = 0;
											}
										}
										$current_item_id = $purchase['item_id'];
										$current_item_name = $purchase['item_name'];
									}elseif($group_by == "supplier_id"){

										if($current_supplier_id != $purchase['supplier_id']){

											if($current_supplier_id != 0){?>
												<tr>
													<th colspan="3"><?=$current_supplier_name;?></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;"><?=currency_format($group_total / $total_quantity);?><?php if($currency_postfix) echo $currency_postfix;?></th>
													<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
												</tr>
											<?php $group_total = 0;
											$total_quantity = 0;
											}
										}
										$current_supplier_id = $purchase['supplier_id'];
										$current_supplier_name = $purchase['supplier_name'];
									}
										$group_total = $group_total + ($purchase['cost_price']* $purchase['quantity']);
										$total_quantity = $total_quantity + $purchase['quantity'];
									?>
									<tr>
										<td><?=$purchase['bill_no']; ?></td>
										<td><?=date($def_dateformate,strtotime($purchase['purchase_date'])); ?></td>
										<td><?=$purchase['item_name']; ?></td>
										<td><?=$purchase['supplier_name'];?></td>
										<td style="text-align:right;"><?=$purchase['quantity']; $purchase_quantity=$purchase_quantity+$purchase['quantity'];?></td>

										<td style="text-align:right;"><?php
											echo currency_format($purchase['cost_price']);
											if($currency_postfix) echo $currency_postfix;

											$purchase_cost=$purchase_cost+$purchase['cost_price'];
										?></td>
										<td style="text-align:right;"><?php
											echo currency_format($purchase['quantity']*$purchase['cost_price']);
											if($currency_postfix) echo $currency_postfix;

											$purchase_total=$purchase_total+($purchase['quantity']*$purchase['cost_price']);
										?></td>
									</tr>
								<?php }
									if($group_by != "none"){?>
									<tr>
										<?php if($group_by == "bill_no"){ ?>
											<th colspan="3"><?=$current_bill_no;?></th>
										<?php }elseif($group_by == "purchase_date"){ ?>
											<th colspan="3"><?=date($def_dateformate,strtotime($current_purchase_date));?></th>
										<?php }elseif($group_by == "item_id"){ ?>
											<th colspan="3"><?=$current_item_name;?></th>
										<?php }elseif($group_by == "supplier_id"){ ?>
											<th colspan="3"><?=$current_supplier_name;?></th>
										<?php }else{ ?>
											<th colspan="3"></th>
										<?php } ?>
										<th></th>
										<th style="text-align:right;"><?=$total_quantity;?></th>
										<th></th>
										<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
									</tr>
									<?php } ?>

                </tbody>
				<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th style="text-align:right;"><?php echo $purchase_quantity; ?></th>
									<th></th>
									<th style="text-align:right;"><?php echo currency_format($purchase_total); if($currency_postfix) echo $currency_postfix; ?></th>
								</tr>
							</tfoot>
            </table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
