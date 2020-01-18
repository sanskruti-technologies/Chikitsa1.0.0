<script type="text/javascript" charset="utf-8">
$(window).load(function() {
    $('#sell_table').dataTable( {
        "order": [[ 1, "desc" ]],
		"columnDefs": [
            {
                "targets": [ 1 ],
                "visible": false
            }
        ]
    } );
});

</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('all_sell');?></h1>
    <div class="form-group">

			<a class="btn btn-primary btn-sm square-btn-adjust" title="" href="<?php echo site_url("stock/sell/"); ?>"><i class="fa fa-plus"></i>&nbsp;<?= $this->lang->line('new')." ".$this->lang->line('bill');?></a>
		</div>
		<div class="col-md-12 table-responsive">

			<table class="table table-striped table-hover" id="sell_table">
			<thead>
				<tr>
					<th><?= $this->lang->line('sell')." ".$this->lang->line('no');?></th>
					<th><?= $this->lang->line('hidden')." ".$this->lang->line('sell')." ".$this->lang->line('date');?></th>
					<th><?php echo $this->lang->line('sell_date');?></th>
					<th><?php echo $this->lang->line('patient');?></th>
					<th><?php echo $this->lang->line('sell_amount');?></th>
					<th><?php echo $this->lang->line('edit');?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; ?>
				<?php foreach ($sells as $sell):  ?>
				<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
					<td><?php echo $sell['sell_no'] ?></td>
					<td><?php echo date('Y-m-d',strtotime($sell['sell_date'])); ?></td>
					<td><?php echo date($def_dateformate,strtotime($sell['sell_date'])); ?></td>
					<td><?=$sell['first_name'] ?> <?=$sell['middle_name'] ?> <?=$sell['last_name'] ?></td>
					<td style="text-align: right"><?php echo currency_format($sell['sell_amount'] - $sell['discount']); if($currency_postfix) echo $currency_postfix;?></td>
					<td><!--a class="btn btn-primary" title="Edit" href="<?php echo site_url("stock/sell/" . $sell['sell_id']); ?>"><?php echo $this->lang->line('edit');?></a-->
					<a class="btn btn-primary btn-sm square-btn-adjust editbt" title="Edit" href="<?php echo site_url("stock/sell/" . $sell['sell_id']); ?>"><?php echo $this->lang->line('edit');?></a>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
	</div>
