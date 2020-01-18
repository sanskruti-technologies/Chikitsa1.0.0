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
	            <div class="col-md-4 nopadding"><h2><?= $this->lang->line('total_sell_report');?> </h2></div>
					</div>
				</div>
				<div class="panel-body">
					<?php echo form_open('stock/sell_report'); ?>
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
					<div class="col-md-12">
						<div class="col-md-12"><label><?= $this->lang->line('group_by');?><label></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="none" <?php if($group_by == 'none'){echo "checked='checked'";}?>><?= $this->lang->line('none');?></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="sell_no" <?php if($group_by == 'sell_no'){echo "checked='checked'";}?>><?= $this->lang->line('sell')." ".$this->lang->line('no');?> </div>
						<div class="col-md-2"><input type="radio" name="group_by" value="sell_date" <?php if($group_by == 'sell_date'){echo "checked='checked'";}?>><?= $this->lang->line('date');?></div>
						<div class="col-md-2"><input type="radio" name="group_by" value="item_id" <?php if($group_by == 'item_id'){echo "checked='checked'";}?>><?= $this->lang->line('item');?></div>
					</div>

					<div class="col-md-12">
						<button type="submit" name="submit" class="btn btn-primary btn-sm square-btn-adjust" ><?php echo $this->lang->line('go');?></button>
					</div>
					<?php echo form_close(); ?>
			</div>
			<div class="panel-body table-responsive-25">
				<div class="table-responsive">
					<table class="table table-striped table-hover display responsive nowrap" id="stock_report_table">
						<thead>
							<tr>
								<th><?= $this->lang->line('sell')." ".$this->lang->line('no');?> </th>
								<th><?= $this->lang->line('date');?> </th>
								<th><?= $this->lang->line('item');?> </th>
								<th style="text-align:right;"><?= $this->lang->line('quantity');?> </th>
								<th style="text-align:right;"><?= $this->lang->line('cost');?> </th>
								<th style="text-align:right;"><?= $this->lang->line('total');?> </th>
							</tr>
						</thead>

								<tbody>
								<?php
									$sell_quantity=0;
									$sell_cost=0;
									$sell_total=0;
									$current_sell_no=0;
									$current_sell_date = '1970-01-01';
									$current_item_id=0;
									$group_total=0;
									$total_quantity = 0;
								?>
								<?php foreach ($sell_report as $sell){

									if($group_by == "sell_no"){
										if($current_sell_no != $sell['sell_no']){
											if($current_sell_no != 0){?>
												<tr>
													<th colspan="3"><?=$current_sell_no;?></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;">Cost</th>
													<th style="text-align:right;"><? echo currency_format($group_total);?></th>
												</tr>
											<?php $group_total = 0;
												$total_quantity = 0;
											}
										}
										$current_sell_no = $sell['sell_no'];
									}elseif($group_by == "sell_date"){
										if($current_sell_date != $sell['sell_date']){
											if($current_sell_date != '1970-01-01'){?>
												<tr>
													<th colspan="3"><?=date($def_dateformate,strtotime($current_sell_date));?></th>
													<th style="text-align:right;"><?=$total_quantity;?></th>
													<th style="text-align:right;"><?=$group_total / $total_quantity;?></th>
													<th style="text-align:right;"><? echo currency_format($group_total);?></th>
												</tr>
											<?php $group_total = 0;
											$total_quantity = 0;

											}
										}
										$current_sell_date = $sell['sell_date'];
									}elseif($group_by == "item_id"){
										if($current_item_id != $sell['item_id']){
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
										$current_item_id = $sell['item_id'];
										$current_item_name = $sell['item_name'];
									}
										$group_total = $group_total + $sell['sell_amount'];
										$total_quantity = $total_quantity + $sell['quantity'];
									?>
									<tr>
										<td><?=$sell['sell_no']; ?></td>
										<td><?=date($def_dateformate,strtotime($sell['sell_date'])); ?></td>
										<td><?=$sell['item_name']; ?></td>
										<td style="text-align:right;"><?=$sell['quantity']; $sell_quantity=$sell_quantity+$sell['quantity'];?></td>

										<td style="text-align:right;"><?php
											echo currency_format($sell['sell_price']);
											if($currency_postfix) echo $currency_postfix;

											$sell_cost=$sell_cost+$sell['sell_price'];
										?></td>
										<td style="text-align:right;"><?php
											echo currency_format($sell['sell_amount']);
											if($currency_postfix) echo $currency_postfix;

											$sell_total=$sell_total+$sell['sell_amount'];
										?></td>
									</tr>
								<?php }
									if($group_by != "none"){?>
									<tr>
										<?php if($group_by == "sell_no"){ ?>
											<th colspan="3"><?=$current_sell_no;?></th>
										<?php }elseif($group_by == "sell_date"){ ?>
											<th colspan="3"><?=date($def_dateformate,strtotime($current_sell_date));?></th>
										<?php }elseif($group_by == "item_id"){ ?>
											<th colspan="3"><?=$current_item_name;?></th>
										<?php }else{ ?>
											<th colspan="3"></th>
										<?php } ?>
										<th style="text-align:right;"><?=$total_quantity;?></th>
										<th style="text-align:right;"><?=currency_format($group_total / $total_quantity);?><?php if($currency_postfix) echo $currency_postfix;?></th>
										<th style="text-align:right;"><?=currency_format($group_total);?><?php if($currency_postfix) echo $currency_postfix;?></th>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th style="text-align:right;"><?php echo $sell_quantity; ?></th>
									<th style="text-align:right;"><?php if($sell_quantity>0){echo currency_format($sell_total / $sell_quantity); if($currency_postfix) echo $currency_postfix;}else{ echo 0;} ?></th>
									<th style="text-align:right;"><?php echo currency_format($sell_total); if($currency_postfix) echo $currency_postfix; ?></th>
								</tr>
							</tfoot>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>