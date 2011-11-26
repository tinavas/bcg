		<?php require_once dirname(__FILE__).'\_template\header.php';?>
		<?php 
			require 'includes/class.leads.php';
			$i = 0;
			
			$leads = new Leads();
			
			if (isset($_POST['submit_form'])) {
				$save = $leads->save_history($_GET['id'], $_POST);
			}
			
			$records = @$leads->get_leads('a.id = '.$_GET['id']);
			
			$row = @mysql_fetch_object($records);
		?>
		<div id="contentBody" class="w800">
			<div id="contentTitle">Form View</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<div class="frm_container">
					<?php if (isset($_GET['id']) AND $records) : ?>
					<div id="<?php echo $save ? 'prompt_success' : 'prompt_failed';?>"><?php echo @$_SESSION['prompt']; unset($_SESSION['prompt']); ?></div>
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
								<tr>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<br /><br /><br />
					<div class="frm_container">
						<form action="" method="post" id="commentForm">
							<div class="frm_heading">
								<span></span>
							</div>
							<div class="frm_inputs">
								<table class="form_tbl">
									<tr>
										<td>GK Name:</td>
										<td><input type="text" name="c_gk_name" value="<?php echo $row->c_gk_name; ?>"/></td>
									</tr>
									<tr>
										<td>DM Name:</td>
										<td><input type="text" name="c_dm_name"  value="<?php echo $row->c_dm_name; ?>"/></td>
									</tr>
									<tr>
										<td>PCB Flag:</td>
										<td><input type="text" name="c_call_notes"  value="<?php echo $row->c_call_notes; ?>"/></td>
									</tr>
									<tr>
										<td>Call notes:</td>
										<td><textarea name="c_call_notes" cols="50" rows="7"><?php echo $row->c_call_notes; ?></textarea></td>
									</tr>
									<tr>
										<td>* <strong>Disposition</strong>:</td>
										<td>
											<select name="disposition" class="required" id="disposition">
												<option></option>
												<optgroup label="Callable">
													<?php $callables = $leads->get_dispositions('status = "C" ORDER BY label'); ?>
													<?php while ($dispo = mysql_fetch_object($callables)): ?>
													<option value="<?php echo $dispo->id; ?>" <?php echo $row->last_dispo == $dispo->label ? 'selected = "selected"':''; ?>><?php echo $dispo->label; ?></option>
													<?php endwhile; ?>
												</optgroup>
												<optgroup label="Scheduled">
													<?php $callables = $leads->get_dispositions('status = "S" ORDER BY label'); ?>
													<?php while ($dispo = mysql_fetch_object($callables)): ?>
													<option value="<?php echo $dispo->id; ?>" <?php echo $row->last_dispo == $dispo->label ? 'selected = "selected"':''; ?>><?php echo $dispo->label; ?></option>
													<?php endwhile; ?>
												</optgroup>
												<optgroup label="Uncallable">
													<?php $callables = $leads->get_dispositions('status = "U" AND sale = 0 ORDER BY label'); ?>
													<?php while ($dispo = mysql_fetch_object($callables)): ?>
													<option value="<?php echo $dispo->id; ?>" <?php echo $row->last_dispo == $dispo->label ? 'selected = "selected"':''; ?>><?php echo $dispo->label; ?></option>
													<?php endwhile; ?>
												</optgroup>
												<optgroup label="Sale/Uncallable">
													<?php $callables = $leads->get_dispositions('status = "U" AND sale = 1 ORDER BY label'); ?>
													<?php while ($dispo = mysql_fetch_object($callables)): ?>
													<option value="<?php echo $dispo->id; ?>" <?php echo $row->last_dispo == $dispo->label ? 'selected = "selected"':''; ?>><?php echo $dispo->label; ?></option>
													<?php endwhile; ?>
												</optgroup>
											</select>
										</td>
									</tr>
									<tr id="scheduler">
										<td>* <strong>Scheduled Date</strong>:</td>
										<td>
											<input type="text" name="s_date" value="<?php echo $row->s_date; ?>" id="example1" class="required" />
											<select name="s_zone" class="required">
												<option></option>
												<?php $zones = $leads->get_zones(); ?>
												<?php while ($zone = mysql_fetch_object($zones)): ?>
												<option value="<?php echo $zone->zone; ?>" <?php echo $row->s_zone == $zone->zone ? 'selected = "selected"':''; ?>><?php echo $zone->zone; ?></option>
												<?php endwhile; ?>
											</select>
										</td>
									</tr>
								</table>
							</div>
							<br /><br />
							<div class="frm_heading">
								<span>Business Information</span>
							</div>
							<div class="frm_inputs">
								<table class="form_tbl">
									<tr>
										<td>Location:</td>
										<td><input type="text" name="c_location"  value="<?php echo $row->c_location; ?>"/></td>
									</tr>
									<tr>
										<td>Business Name:</td>
										<td><input type="text" name="c_business_name"  value="<?php echo $row->c_business_name; ?>"/></td>
									</tr>
									<tr>
										<td>Building Name/No:</td>
										<td><input type="text" name="c_building_name"  value="<?php echo $row->c_building_name; ?>"/></td>
									</tr>
									<tr>
										<td>Street:</td>
										<td><input type="text" name="c_street"  value="<?php echo $row->c_street; ?>"/></td>
									</tr>
									<tr>
										<td>City:</td>
										<td><input type="text" name="c_city"  value="<?php echo $row->c_city; ?>"/></td>
									</tr>
									<tr>
										<td>Postcode:</td>
										<td><input type="text" name="c_postcode"  value="<?php echo $row->c_postcode; ?>"/></td>
									</tr>
									<tr>
										<td>Country:</td>
										<td><input type="text" name="c_country"  value="<?php echo $row->c_country; ?>"/></td>
									</tr>
									<tr>
										<td>Phone:</td>
										<td><input type="text" name="c_phone"  value="<?php echo $row->c_phone; ?>"/></td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><input type="text" name="c_email"  value="<?php echo $row->c_email; ?>"/></td>
									</tr>
									<tr>
										<td>Website:</td>
										<td><input type="text" name="c_website" value="<?php echo $row->c_website; ?>"/></td>
									</tr>
									<tr>
										<td>Opening Times:</td>
										<td><input type="text" name="c_opening_times" value="<?php echo $row->c_opening_times; ?>"/></td>
									</tr>
									<tr>
										<td>Business Description:</td>
										<td><input type="text" name="c_business_description" value="<?php echo $row->c_business_description; ?>"/></td>
									</tr>
									<tr>
										<td>Business Keyword 1:</td>
										<td><input type="text" name="c_business_keyword_1" value="<?php echo $row->c_business_keyword_1; ?>"/></td>
									</tr>
									<tr>
										<td>Business Keyword 2:</td>
										<td><input type="text" name="c_business_keyword_2" value="<?php echo $row->c_business_keyword_2; ?>"/></td>
									</tr>
								</table>
							</div>
							<br /><br />
							<div class="frm_heading">
								<span>Contact Information</span>
							</div>
							<div class="frm_inputs">
								<table class="form_tbl">
									<tr>
										<td>Contact Person:</td>
										<td><input type="text" name="c_contact_person" value="<?php echo $row->c_contact_person; ?>"/></td>
									</tr>
									<tr>
										<td>Contact Email:</td>
										<td><input type="text" name="c_contact_email" value="<?php echo $row->c_contact_email; ?>"/></td>
									</tr>
									<tr>
										<td>Contact Phone:</td>
										<td><input type="text" name="c_contact_phone" value="<?php echo $row->c_contact_phone; ?>"/></td>
									</tr>
									<tr>
										<td></td>
										<td><input type="submit" name="submit_form" value="Submit"/></td>
									</tr>
								</table>
							</div>
						</form>
					</div>
					<?php else : ?>
						No record found.
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php require_once dirname(__FILE__).'\_template\footer.php';?>
