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
					<?php echo $this->lang->line('treatment')." ".$this->lang->line('report');?>
				</div>
				<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="treatment_report" >
						<thead>
							<tr>
								<?php foreach($fields as $field){?>
								<th><?php echo $this->lang->line($field);?></th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($treatement_report as $treatement){ ?>
							<tr>
								<?php foreach($fields as $field){?>
								<?php if($field == 'date'){?>
									<td><?php echo date($def_dateformate,strtotime($treatement[$field]));?></td>
								<?php }elseif($field == 'time'){?>
									<td><?php echo date($def_timeformate,strtotime($treatement[$field]));?></td>
								<?php }elseif($field == 'doctor_share'){?>
									<td style="text-align:right;"><?php echo currency_format($treatement[$field]);?></td>
								<?php }else{?>
									<td><?php echo $treatement[$field];?></td>
								<?php }?>
								<?php }?>
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