<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
		$('#stock_report_table').dataTable();
		});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-4 nopadding"><h2><?php echo $this->lang->line('stock') . " " .$this->lang->line('report');?></h2></div>

					</div>
				</div>
				<div class="panel-body table-responsive-25">
					<div class="form-group">
						<a target="_blank" href="<?= site_url('stock/print_stock_report/');?>" class="btn btn-primary btn-sm square-btn-adjust" /><i class="fa fa-print"></i>&nbsp;Print</a>
					</div>
					<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-hover display responsive nowrap" id="stock_report_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('item_name');?></th>
								<th><?php echo $this->lang->line('desired_stock');?></th>
								<th><?php echo $this->lang->line('available_stock');?></th>
								<th><?= $this->lang->line('opening_stock')." ".$this->lang->line('quantity');?> </th>
								<th><?= $this->lang->line('purchase')." ".$this->lang->line('quantity');?> </th>
								<th><?= $this->lang->line('purchase_return')." ".$this->lang->line('quantity');?></th>
								<th><?= $this->lang->line('sell')." ".$this->lang->line('quantity');?> </th>
								<th><?= $this->lang->line('sell_return')." ".$this->lang->line('quantity');?> </th>
							</tr>
						</thead>

								<tbody>
								<?php foreach ($stock_report as $stock){ ?>
									<tr>
										<td><?php echo $stock['item_name']; ?></td>
										<td style='text-align:right'><?php echo $stock['desired_stock']; ?></td>
										<?php if (($stock['purchase_quantity']-$stock['sell_quantity'])< $stock['desired_stock'])
										{
											echo "<td class='red-bg' style='text-align:right'>";
										}
										else
										{
											echo "<td style='text-align:right'>";
										}
										echo $stock['opening_stock_quantity'] + $stock['purchase_quantity']-$stock['sell_quantity']-$stock['purchase_return_quantity']+$stock['sell_return_quantity']."</td>"; ?>
										<td style='text-align:right'><a href="<?=site_url('stock/opening_stock/'.$stock['item_id']);?>"><?=$stock['opening_stock_quantity'];?></a></td>
										<td style='text-align:right'><a href="<?=site_url('stock/purchase/'.$stock['item_id']);?>"><?=$stock['purchase_quantity'];?></a></td>
										<td style='text-align:right'><a href="<?=site_url('stock/purchase_return/'.$stock['item_id']);?>"><?=$stock['purchase_return_quantity'];?></a></td>
										<td style='text-align:right'><?php echo $stock['sell_quantity']; ?></td>
										<td style='text-align:right'><a href="<?=site_url('stock/sell_return/'.$stock['item_id']);?>"><?=$stock['sell_return_quantity'];?></a></td>
									</tr>
								<?php } ?>
								</tbody>

						</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
