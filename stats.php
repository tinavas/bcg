		<?php require_once dirname(__FILE__).'\_template\header.php';?>
		<?php 
			require 'includes/class.stats.php';
			
			$stats = new Stats();
			
			$dispo_summary = $stats->dispo_summary();
			$agent_summary = $stats->agent_summary();
		?>
		<div id="contentBody">
			<div id="contentTitle">Stats View</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<?php if($_SESSION['bcg_admin'] == 1) : ?>
				<div id="list_view">
					<div class="frm_heading">
						<span>Disposition Summary</span>&nbsp;&nbsp;<a href="export_excel.php"><img src="_template/css/excel-2007.gif" /></a>
					</div>
					<table class="" style="width: 300px;" cellpadding="2" cellspacing="0">
						<thead>
							<tr>
								<th>Disposition</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 0; ?>
							<?php while ($dispo = mysql_fetch_object($dispo_summary)) : ?>
							<tr class="<?php echo $counter % 2 == 0 ? 'light_bg' : 'dark_bg'; $counter++; ?>">
								<td><?php echo $dispo->last_dispo == '' ? 'Not Yet Disposed' : $dispo->last_dispo; ?></td><td><?php echo $dispo->total; ?></td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
					<br /><br />
					<div class="frm_heading">
						<span>Agent Disposition Summary</span>&nbsp;&nbsp;<a href="export_excel.php"><img src="_template/css/excel-2007.gif" /></a>
					</div>
					<table class="" style="width: 400px;" cellpadding="2" cellspacing="0">
						<thead>
							<tr>
								<th>Agent</th>
								<th>Disposition</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$user_temp = NULL; 
								$counter = 0;
							?>
							<?php while ($dispo = mysql_fetch_object($agent_summary)) : ?>
							<?php 
								$agent = $dispo->user;
								
								if ($dispo->user == $user_temp) {
									$agent = '';
								} else {
									$agent = $dispo->user;
									$user_temp = $dispo->user;
									$counter++;
								}
								
							?>
							<tr class="<?php echo $counter % 2 == 0 ? 'light_bg' : 'dark_bg'; ?>">
								<td>
									<?php 
										echo $agent;
									?>
								</td>
								<td><?php echo $dispo->last_dispo == '' ? 'Not Yet Disposed' : $dispo->last_dispo; ?></td><td><?php echo $dispo->total; ?></td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
				<?php else : ?>
				<div>You dont have access to view this page.</div>
				<?php endif; ?>
			</div>
		</div>
		<?php require_once dirname(__FILE__).'\_template\footer.php';?>