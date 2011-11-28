		<?php require_once dirname(__FILE__).'\_template\header.php';?>
		<?php 
			require 'includes/class.leads.php';
			$i = 0;
			
			$leads = new Leads();
			
			$records = @$leads->view_info($_GET['lead_id']);
			
			$row = @mysql_fetch_object($records);
		?>
		<div id="contentBody" class="w800">
			<div id="contentTitle">Lead Info View</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<div class="frm_container">
					<?php if (isset($_GET['lead_id']) AND $records) : ?>
					<div class="frm_heading">
						<span>Lead Labels</span>
					</div>
					<div class="frm_inputs">
						<table class="info_view">
							<tbody>
								<tr>
									<td>Phone:</td>
									<td><?php echo $row->f_phonenumber; ?></td>
								</tr>
								<tr>
									<td>City:</td>
									<td><?php echo $row->f_city; ?></td>
								</tr>
								<tr>
									<td>Category:</td>
									<td><?php echo $row->f_category; ?></td>
								</tr>
								<tr>
									<td>Business Name:</td>
									<td><?php echo $row->f_business_name; ?></td>
								</tr>
								<tr>
									<td>Address:</td>
									<td><?php echo $row->f_address; ?></td>
								</tr>
								<tr>
									<td>Website</td>
									<td><?php echo $row->f_website; ?></td>
								</tr>
								<tr>
									<td>Email:</td>
									<td><?php echo $row->f_email; ?></td>
								</tr>
								<tr>
									<td>Email2:</td>
									<td><?php echo $row->f_email2; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<br /><br /><br />
					<div class="frm_container">
						<div class="frm_heading">
							<span>Other Info</span>
						</div>
						<div class="frm_inputs">
							<table class="info_view">
								<tr>
									<td>GK Name:</td>
									<td><?php echo $row->c_gk_name; ?></td>
								</tr>
								<tr>
									<td>DM Name:</td>
									<td><?php echo $row->c_dm_name; ?></td>
								</tr>
								<tr>
									<td>Agent:</td>
									<td><?php echo $row->user; ?></td>
								</tr>
								<tr>
									<td>Call notes:</td>
									<td><?php echo $row->c_call_notes; ?></td>
								</tr>
								<tr>
									<td>Disposition:</td>
									<td><?php echo $row->last_dispo; ?></td>
								</tr>
								<tr>
									<td>Scheduled Date:</td>
									<td><?php echo $row->s_date.' '.$row->s_zone ?></td>
								</tr>
							</table>
						</div>
					</div>
					<br /><br /><br />
					<div class="frm_container">
						<div class="frm_heading">
							<span>Business Info</span>
						</div>
						<div class="frm_inputs">
							<table class="info_view">
								<tr>
									<td>Location:</td>
									<td><?php echo $row->c_location; ?></td>
								</tr>
								<tr>
									<td>Business Name:</td>
									<td><?php echo $row->c_business_name; ?></td>
								</tr>
								<tr>
									<td>Building Name/No:</td>
									<td><?php echo $row->c_building_name; ?></td>
								</tr>
								<tr>
									<td>Street:</td>
									<td><?php echo $row->c_street; ?></td>
								</tr>
								<tr>
									<td>City:</td>
									<td><?php echo $row->c_city; ?></td>
								</tr>
								<tr>
									<td>Postcode:</td>
									<td><?php echo $row->c_postcode; ?></td>
								</tr>
								<tr>
									<td>Country:</td>
									<td><?php echo $row->c_country; ?></td>
								</tr>
								<tr>
									<td>Phone:</td>
									<td><?php echo $row->c_phone; ?></td>
								</tr>
								<tr>
									<td>Email:</td>
									<td><?php echo $row->c_email; ?></td>
								</tr>
								<tr>
									<td>Website:</td>
									<td><?php echo $row->c_website; ?></td>
								</tr>
								<tr>
									<td>Opening Times:</td>
									<td><?php echo $row->c_opening_times; ?></td>
								</tr>
								<tr>
									<td>Business Description:</td>
									<td><?php echo $row->c_business_description; ?></td>
								</tr>
								<tr>
									<td>Business Keyword 1:</td>
									<td><?php echo $row->c_business_keyword_1; ?></td>
								</tr>
								<tr>
									<td>Business Keyword 2:</td>
									<td><?php echo $row->c_business_keyword_2; ?></td>
								</tr>
							</table>
						</div>
					</div>
					<br /><br />
					<div class="frm_heading">
						<span>Contact Information</span>
					</div>
					<div class="frm_inputs">
						<table class="info_view">
							<tr>
								<td>Contact Person:</td>
								<td><?php echo $row->c_contact_person; ?></td>
							</tr>
							<tr>
								<td>Contact Email:</td>
								<td><?php echo $row->c_contact_email; ?></td>
							</tr>
							<tr>
								<td>Contact Phone:</td>
								<td><?php echo $row->c_contact_phone; ?></td>
							</tr>
						</table>
					</div>
					<?php else : ?>
						No record found.
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php require_once dirname(__FILE__).'\_template\footer.php';?>
