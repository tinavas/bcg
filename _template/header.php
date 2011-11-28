<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Northstar Solutions Inc - BigCityGuide</title>
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="fav.ico" />
		
	    <link type="text/css" href="_template/css/style.css" rel="stylesheet" />
	    <script type="text/javascript" src="_template/jquery/jquery.js"></script>  
	    
	    <link type="text/css" href="_template/tablesorter/style.css" rel="stylesheet" />
	    <script type="text/javascript" src="_template/tablesorter/jquery.tablesorter.min.js"></script>
	    
	    <link type="text/css" href="_template/jquery-ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	    <script type="text/javascript" src="_template/jquery-ui/jquery-ui-1.8.16.custom.min.js"></script>
	    
	    <script type="text/javascript" src="_template/jquery/jquery.validate.min.js"></script>
	    <script type="text/javascript" src="_template/jquery/datetimepicker.js"></script>
	    
	    <script type="text/javascript" src="_template/jquery/tablefilter.js"></script> 
	    
	    <script>
	    	$(function(){
				$(".tablesorter").tablesorter();
				
				//filter
				var theTable = $('table.withFilter')
				
				$("#filter").keyup(function() {
					$.uiTableFilter( theTable, this.value );
				})

				$("#commentForm").validate();

				$("#example1").datetimepicker();
				
				test_dispo();
				$("#disposition").change(function(){
					test_dispo();
				});
				
				function test_dispo() {
					var dispo = $("#disposition").val();
					
					if (dispo == 24)
					{
						$("#scheduler").show();
					} else {
						$("#scheduler").hide();
					}
				}
		    });
	    </script>
	</head>
	<body>
	<div id="content">
		<div id="contentHeaderWrapper">
			<div id="contentHeader">
				<div id="nslogo"></div>
				<div id="appTitle">BigCityGuide Callback Manager</div>
			</div>
		</div>
		<div id="contentWrapper">
		<div id="navMenu">
			<div id="location"><div id="greeting">
			<?php  
				if(isset($_SESSION['bcg_agent'])){
					echo 'Hi '.$_SESSION['bcg_agent']. ' | <a href="index.php">List View</a>';
					echo $_SESSION['bcg_admin'] == 1 ? ' | <a href="admin.php">Admin</a> | <a href="stats.php">Stats</a>' : '';
					echo ' | <a href="logout.php">Logout</a>';
					
				}  
			
			?>
			</div></div>
		</div>
		<div class="clearFix"></div>