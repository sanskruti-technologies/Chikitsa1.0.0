<div id="page-inner"
	<div class="row">
		<div class="col-md-12">
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<?=$this->lang->line("sms_log");?>
					</div>
					<div class="panel-body" >
						<div class="col-md-12">
						<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<td><?=$this->lang->line("timestamp");?></td>
									<td><?=$this->lang->line("url");?></td>
									<td><?=$this->lang->line("response");?></td>
								</tr>
							</thead>
							<tbody>
						<?php 
							foreach($sms_log as $log){
								echo "<tr>";
								echo "<td>".date($def_dateformate . " ".$def_timeformate,strtotime($log['sms_timestamp']))."</td>";
								echo "<td>".$log['sms_url']."</td>";
								echo "<td>".$log['sms_response']."</td>";
								echo "</tr>";
							}
						?>
						</tbody>
						</table>
						</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>