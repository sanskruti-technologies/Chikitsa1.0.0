<?php
/*
	This file is part of Chikitsa.

    Chikitsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Chikitsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Chikitsa.  If not, see <https://www.gnu.org/licenses/>.
*/
?>
<!-- Begin Page Content -->
	<div class="container-fluid">
<!-- Page Heading -->
	  <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line('payment_methods');?></h1>
		<a href="<?= site_url("payment/insert_payment_method/");?>" class="btn btn-primary square-btn-adjust"><?php echo $this->lang->line("add")." ".$this->lang->line("payment_method");?></a>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="tax_rate_table">
				<thead>
					<tr>
						<th><?php echo $this->lang->line("sr_no");?></th>
						<th><?php echo $this->lang->line("payment_method");?></th>
						<th><?php echo $this->lang->line("action");?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach($payment_methods as $payment_method){ ?>
						<tr>
							<td><?=$i;?></td>
							<td><?=$payment_method['payment_method_name'];?></td>
							<td>
								<a class="btn btn-primary square-btn-adjust" href="<?=site_url('payment/edit_payment_method/'.$payment_method['payment_method_id']);?>"><?php echo $this->lang->line('edit');?></a>
								<a class="btn btn-danger square-btn-adjust confirmDelete" href="<?=site_url('payment/delete_payment_method/'.$payment_method['payment_method_id']);?>"><?php echo $this->lang->line('delete');?></a>
							</td>
						</tr>
						<?php $i++; ?>
					<?php }?>
				</tbody>
			</table>
		</div>
			<!--End Advanced Tables -->
	</div>
	
<script type="text/javascript" charset="utf-8">
$(window).on('load', function(){

	$('.confirmDelete').click(function(){
		return confirm(<?=$this->lang->line('areyousure_delete');?>);
	})

    $("#tax_rate_table").dataTable({
		"pageLength": 50
	});
});
</script>
