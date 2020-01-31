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
          <h1 class="h3 mb-2 text-gray-800"><?php echo $this->lang->line("refund");?></h1>					
			<div class="form-group">
				<a href="<?=site_url('payment/add_issue_refund');?>" class="btn square-btn-adjust btn-primary"><?php echo $this->lang->line('add_refund');?></a>
			</div>

			<div class="table-responsive ">
				<table class="table table-striped table-hover display responsive nowrap" id="patient_table">
					<thead>
						<tr>
							<th><?php echo $this->lang->line("sr_no");?></th>
							<th><?php echo $this->lang->line("date");?></th>
							<th><?php echo $this->lang->line("patient");?></th>
							<th><?php echo $this->lang->line("amount");?></th>
							<th><?php echo $this->lang->line("action");?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; ?>
						<?php foreach ($refunds as $refund):  ?>
							<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
								<td><?php echo $i; ?></td>
								<td><?php echo date($def_dateformate,strtotime($refund['refund_date'])); ?></td>
								<td><?php echo $patient_name[$refund['patient_id']]; ?></td>
								<td><?php echo currency_format($refund['refund_amount']); ?></td>
								<td>
								<!--a href="<?= site_url('payment/edit_refund/'.$refund['refund_id']);?>" class="btn btn-sm btn-primary square-btn-adjust"><?php echo $this->lang->line("edit");?></a-->
								<a class="btn btn-primary square-btn-adjust" style="color:#fff;" title="Edit" "<?= site_url('payment/edit_refund/'.$refund['refund_id']);?>">Edit</a>
								
								<!--a href="<?= site_url('payment/delete_refund/'.$refund['refund_id']);?>" class="btn btn-sm btn-danger square-btn-adjust confirmDelete"><?php echo $this->lang->line("delete");?></a-->
								<a class="btn btn-danger square-btn-adjust confirmDelete" title="delete" href="<?= site_url('payment/delete_refund/'.$refund['refund_id']);?>">Delete</a>
								
								</td>
							</tr>
						<?php $i++; ?>
						<?php endforeach ?>
					</tbody>
				</div>							
		</div>	
	
	
