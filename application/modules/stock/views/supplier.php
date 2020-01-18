<script type="text/javascript" charset="utf-8">
$(window).load(function() {
    $('#supplier_table').dataTable();
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	})
} )
</script>
<!-- Begin Page Content -->
        <div class="container-fluid">
<!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('suppliers');?></h1>
        <div class="form-group">
					   <a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("stock/add_supplier/"); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add').' '.$this->lang->line('supplier');?></a>
           </div>
          <div class="table-responsive">
					<table class="table table-striped table-hover display responsive nowrap" id="supplier_table">

					<thead>
						<tr>
							<th><?php echo $this->lang->line('sr_no');?></th>
							<th><?php echo $this->lang->line('supplier_name');?></th>
							<th><?php echo $this->lang->line('contact_number');?></th>
							<th><?php echo $this->lang->line('email');?></th>
							<th><?php echo $this->lang->line('edit');?></th>
							<th><?php echo $this->lang->line('delete');?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; ?>
						<?php foreach ($suppliers as $supplier):  ?>
						<tr class="<?php if($i%2==0){echo 'even';}else{echo 'odd';}?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $supplier['first_name'] . ' '. $supplier['middle_name'] . ' ' .$supplier['last_name'] ?></td>
							<td>
								<?=$supplier['phone_number'];?>
							</td>
							<td>
								<?=$supplier['email'];?>
							</td>
							<td>
							<a class="btn btn-primary btn-sm square-btn-adjust editbt" href="<?php echo site_url("stock/edit_supplier/" . $supplier['supplier_id']); ?>"><?php echo  $this->lang->line('edit')?></a>

							</td>
							<td>
							<a class="btn btn-danger btn-sm square-btn-adjust confirmDelete"  href="<?php echo site_url("stock/delete_supplier/" . $supplier['supplier_id']); ?>"><?php echo  $this->lang->line('delete')?></a>
							</td>
						</tr>
						<?php $i++; ?>
						<?php endforeach ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
