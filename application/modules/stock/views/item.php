<script type="text/javascript" charset="utf-8">
$(window).load(function() {
    $('#item_table').dataTable();
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	})

});
</script>

<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('items');?></h1>
        <div class="form-group">
          <a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("stock/add_item/"); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add');?> <?php echo $this->lang->line('item');?></a>
        </div>
        <div class="col-md-12 table-responsive">
					<table class="table table-striped table-hover" id="item_table">
					<thead>
						<tr>
							<th><?= $this->lang->line('sr_no');?></th>
							<th><?php echo $this->lang->line('item');?></th>
							<th><?php echo $this->lang->line('barcode');?></th>
							<th><?= $this->lang->line('minimum_stock');?></th>
							<th><?= $this->lang->line('sell')." ".$this->lang->line('price');?></th>
							<th><?php echo $this->lang->line('edit');?></th>
							<th><?php echo $this->lang->line('delete');?></th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1; ?>
					<?php foreach ($items as $item): ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $item['item_name'] ?></td>
						<td><?php echo $item['barcode'] ?></td>
						<td style="text-align:right"><?php echo $item['desired_stock'] ?></td>
						<td style="text-align:right"><?php echo currency_format($item['mrp']);if($currency_postfix) echo $currency_postfix; ?></td>
						<td>
						<a class="btn btn-primary btn-sm square-btn-adjust editbt"  title="Edit" href="<?php echo site_url("stock/edit_item/" . $item['item_id']); ?>"><?= $this->lang->line('edit');?></a>
						</td>
						<td>
						<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="<?php echo  $this->lang->line('delete_item')." :" . $item['item_name']?>" href="<?php echo site_url("stock/delete_item/" . $item['item_id']); ?>"><?= $this->lang->line('delete');?></a>
						</td>
					</tr>
					 <?php $i++; ?>
					<?php endforeach ?>
					</tbody>

					</table>
				</div>
			</div>
			
