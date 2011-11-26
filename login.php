		<?php include_once dirname(__FILE__).'\_template\header.php';?>
		<?php 
			//init error
			$error = NULL;
			//check if form was submitted
			if(isset($_POST['submit_login'])){
				//load user class
				require_once 'includes/class.user.php';
				
				//create new instance
				$user = new User();
				//check if exist
				$pass = $user->chk_login($_POST['uname'], $_POST['pword'], $_POST['dialer']);
				
				//on success
				if($pass) {
					//redirect to main
					header('Location: index.php');
				} else {
					//fail
					$error = '<br>Invalid Username/Password <br />';
				}
			}
		?>
		
		<div id="contentBody" class="w400">
			<div id="contentTitle">Login</div>
			<div class="clearFix"></div>
			<div id="midcontentBody">
				<form action="" method="post">
				<table class="tblForm">
					<tr>
						<td>Username:</td>
						<td><input type="text" name="uname" value="<?php echo isset($_POST['uname']) ? $_POST['uname']:''; ?>"/></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="pword" value="<?php echo isset($_POST['pword']) ? $_POST['pword']:''; ?>"/></td>
					</tr>
					<tr>
						<td>Dialer:</td>
						<td>
							<select name="dialer">
								<!-- <option value="vici">ViCi</option> -->
								<option value="i3">Interactive Intelligence</option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="submit_login" value="GO" /></td>
					</tr>
					<?php if (!is_null($error)):?>
					<tr>
						<td></td>
						<td><?php echo $error;?></td>
					</tr>
					<?php endif;?>
				</table>
				</form>
			</div>
		</div>
		<?php include_once dirname(__FILE__).'\_template\footer.php';?>