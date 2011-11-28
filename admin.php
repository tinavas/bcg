		<?php require_once dirname(__FILE__).'\_template\header.php'; ?>
		<?php 
			require 'includes/class.leads.php';
			
			$i = 0;
			
			$leads = new Leads();
			
			$records = $leads->get_list();
		?>
		<div id="contentBody" class="w600">
			<div id="contentTitle">Admin View</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<?php if($_SESSION['bcg_admin'] == 1) : ?>
				<div id="list_view">
					<div class="frm_heading">
						<span>Record Info</span>&nbsp;&nbsp;<a href="export_excel.php?report=1"><img src="_template/css/excel-2007.gif" /></a>
					</div>
					<br />
					Filter:
					<input id="filter" type="text" size="20" maxlength="30" value="" name="filter" />
					<table class="tablesorter withFilter">
						<thead>
							<tr>
								<td>#</td>
								<th>Phone Number</th>
								<th>Current Disposition</th>
								<th>Last Modified</th>
								<th>Agent</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = mysql_fetch_object($records)) : ?>
							<tr>
								<td><?php echo ++$i;?></td>
								<td><a href="view.php?lead_id=<?php echo $row->lead_id; ?>" target="_blank"><?php echo $row->phone_number; ?></a></td>
								<td><?php echo $row->last_dispo; ?></td>
								<td><?php echo $row->last_modified; ?></td>
								<td><?php echo $row->user; ?></td>
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
		<?php require_once dirname(__FILE__).'\_template\footer.php'; ?>