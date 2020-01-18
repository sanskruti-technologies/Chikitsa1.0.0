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
				<div class="panel-heading">
					<?php echo $this->lang->line('stock') . " " .$this->lang->line('report');?>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="stock_report_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('item_name');?></th>
								<th><?php echo $this->lang->line('desired_stock');?></th>
								<th><?php echo $this->lang->line('available_stock');?></th>
								<th><?= $this->lang->line('opening_stock')." ".$this->lang->line('quantity');?></th>
								<th><?= $this->lang->line('purchase')." ".$this->lang->line('quantity');?></th>
								<th><?= $this->lang->line('purchase_return')." ".$this->lang->line('quantity');?></th>
								<th><?= $this->lang->line('sell')." ".$this->lang->line('quantity');?></th>
								<th><?= $this->lang->line('sell_return')." ".$this->lang->line('quantity');?></th>
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
										<td style='text-align:right'><?php echo $stock['opening_stock_quantity'];?></td>
										<td style='text-align:right'><?php echo $stock['purchase_quantity'];?></td>
										<td style='text-align:right'><?=$stock['purchase_return_quantity'];?></td>
										<td style='text-align:right'><?php echo $stock['sell_quantity']; ?></td>
										<td style='text-align:right'><?=$stock['sell_return_quantity'];?></td>
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
</body>