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
<?php 
if($from_date == NULL){
	$from_date = "";
}else{
	$from_date = date($def_dateformate,strtotime($from_date));
}
if($to_date == NULL){
	$to_date = "";
}else{
	$to_date = date($def_dateformate,strtotime($to_date));
}
$selected_items = "";
$sel_items = explode(",",$items);
foreach($sel_items as $item){
	if($selected_items != "")
		$selected_items .= ",";	
	$selected_items .= $item_name[$item];
}
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<h2>
				<?php echo $this->lang->line('sell') . ' ' .$this->lang->line('return') . ' ' . $this->lang->line('report');?>
			</h2>
			<h4>
				<?=$from_date; ?> - <?=$to_date;?><br/>
				<?=$selected_items;?>
			</h4>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sell_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('return_date');?></th>
								<th><?php echo $this->lang->line('bill_no');?></th>
								<th><?php echo $this->lang->line('patient');?></th>
								<th><?php echo $this->lang->line('item');?></th>
								<th><?php echo $this->lang->line('quantity');?></th>
								<th><?php echo $this->lang->line('price');?></th>
								<th><?php echo $this->lang->line('total');?></th>
							</tr>
						</thead>    
						<tbody>
						<?php
							foreach ($sell_return_totals as $sell_return_total) {
								$found = FALSE;
								$show_total = FALSE;
								foreach ($sell_returns as $sell_return) {
									if ($sell_return_total['bill_no'] == $sell_return['bill_no'] && $sell_return_total['return_date'] == $sell_return['return_date'] ) {
										$date = $sell_return['return_date'];
										$bill_no = $sell_return['bill_no'];
										$patient_name = $sell_return['patient_name'];
										$item_name = $sell_return['item_name'];
										$qnt = $sell_return['quantity'];
										$cost = $sell_return['price'];
										$amount = ($sell_return['price']);
										$found = TRUE;
										$show_total = TRUE;
									}
									if ($found) {
										?>
										<tr>
											<td><?php echo date($def_dateformate,strtotime($date)); ?></td>
											<td><?php echo $bill_no ?></td>
											<td><?php echo $patient_name; ?></td>
											<td><?php echo $item_name; ?></td>
											<td style="text-align: right;"><?php echo $qnt; ?></td>                    
											<td style="text-align: right;"><?php echo currency_format($cost); if($currency_postfix) echo $currency_postfix; ?></td>
											<td style="text-align: right;"><?php echo currency_format($amount); if($currency_postfix) echo $currency_postfix; ?></td>
										</tr>
										<?php
										$found = FALSE;
									}
								}
								if ($show_total) {
							?>
								<tr>
									<td colspan="5"></td>
									<td style="text-align: right;"><strong><?php echo $this->lang->line('total');?></strong></td>
									<td style="text-align: right;"><strong><?php echo currency_format($sell_return_total['total']); if($currency_postfix) echo $currency_postfix; ?></strong></td>
								</tr>
								<?php 
								$show_total = FALSE;
								} ?>
                        <?php } ?>
                </tbody>

            </table>

        </div>
        </div>

    </body>
</html>