<?php

$fn = NULL;
$query = NULL;

switch ($_GET['report']) {
	case 1:
		$fn = 'BigCityGuide_records.xls';
		$query = '
			SELECT 
			  a.id as `Record_ID`, 
			  a.list_id as `List_ID`,
			  a.campaign_id as `Campaign_ID`,
			  a.lead_id as `Lead_ID`, 
			  a.phone_number as `Phone_Number`, 
			  a.dialer as `Dialer`, 
			  a.user as `Agent`, 
			  a.d_dispo as `Dialer_Dispo`,
			  a.d_zone as `Dialer_Zone`,
			  CONCAT(a.s_date,a.s_zone) as `Scheduled_Date`, 
			  a.last_dispo as `Current_Dispo`, 
			  a.last_modified as `Last_Modified`,
			  b.*
			FROM bcg_records a 
			  INNER JOIN bcg_record_info b ON a.id = b.record_id
			ORDER BY a.last_modified;
		';
		break;
	
	default:
		
		break;
}

if ($fn != NULL && $query != NULL) {
	session_start(); 
	ob_start();
	
	include_once('includes/class.connection_mysql.php');
	
	//connect
	$conn = new Connection;
	$conn->local_db();
	
	$result = mysql_query($query);
	//Get Result Count
	$result_count = mysql_num_rows($result);

	//Print headers on excel
	$fields = array();
	for ($i = 0; $i < mysql_num_fields($result); ++$i) {
		// Fetch the field information
		$field = mysql_fetch_field($result, $i);
		//Convert to array;
		$fields[$i] = $field->name;
	}
	$_SESSION['report_header'] = $fields;
	
	//Print rows in excel
	$j = 0;
	while($row = mysql_fetch_object($result))
	{
		for ($i = 0; $i < mysql_num_fields($result); ++$i) {
			$field = mysql_fetch_field($result, $i);
			$field_name = $field->name;
			$_SESSION['report_values'][$j][$i]=$row->$field_name." ";
		}
		$j++;
	}
	
	include_once('includes/class.export_excel.php');
	//create the instance of the exportexcel format
	$excel_obj=new ExportExcel("$fn");
	//setting the values of the headers and data of the excel file 
	//and these values comes from the other file which file shows the data
	$excel_obj->setHeadersAndValues($_SESSION['report_header'],$_SESSION['report_values']); 
	//now generate the excel file with the data and headers set
	$excel_obj->GenerateExcelFile();
	//print_r($_SESSION['report_values']);
	
}
