		<?php require_once dirname(__FILE__).'\_template\header.php';?>
		<?php 
			require 'includes/class.leads.php';
			$i = 0;
			
			$leads = new Leads();
			
			if (isset($_POST['sync'])) {
				$leads->sync();
			}
			
			$records = $leads->get_leads();
		?>
		<div id="contentBody">
			<div id="contentTitle">List View</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<div id="agent_menu">
					<form action="" method="post">
						<ul><li><input type="submit" value="Sync" name="sync" /></li></ul>
					</form>
				</div>
				----------------------------------------------------------------------
				<div id="list_view">
					<?php if (! $records) : ?>
						No leads yet in the system. You may try to <strong>Sync</strong> to update your list.
					<?php else :?>
					<table class="tablesorter">
						<thead>
							<tr>
								<td>#</td>
								<th>Phone</th>
								<th>City</th>
								<th>Category</th>
								<th>Business Name</th>
								<th>Last Dispo</th>
								<th>Last Called</th>
								<th>Scheduled</th>
							</tr>
						</thead>
						<tbody>
						<?php while ($row = mysql_fetch_object($records)) : ?>
							<tr>
								<td><a href="form.php?id=<?php echo $row->record_id ;?>"><?php echo ++$i ;?></a></td>
								<td><?php echo $row->f_phonenumber;?></td>
								<td><?php echo $row->f_city;?></td>
								<td><?php echo $row->f_category;?></td>
								<td><?php echo $row->f_business_name;?></td>
								<td><?php echo $row->last_dispo; ?></td>
								<td><?php echo $row->last_modified != '' ? date('M d H:i', strtotime($row->last_modified)):''; ?></td>
								<td><?php echo $row->s_date != '' ? date('M d H:i', strtotime($row->s_date)).' '.$row->s_zone:''; ?></td>
							</tr>
						<?php endwhile; ?>
						</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php require_once dirname(__FILE__).'\_template\footer.php';?>