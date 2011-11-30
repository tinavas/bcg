<?php

require_once 'class.connection_mysql.php';

Class Leads extends Connection {
	
	private $agent = NULL;
	
	public function __construct()
	{
		if (!isset($_SESSION['bcg_agent'])) {
			header('Location: logout.php');
		}
		
		$this->agent = $_SESSION['bcg_agent'];
		$this->dialer = $_SESSION['bcg_agent'];
	}

	public function get_leads($filter = NULL)
	{
		$query = "
			SELECT *
			FROM bcg_records a 
			INNER JOIN bcg_record_info b
			  ON a.id = b.record_id
			WHERE a.user = '".$this->agent."'
		";
		
		if (!is_null($filter)) {
			$query = $query.' AND '.$filter;
		}
		
		//connect
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		if (mysql_num_rows($result) <= 0)
		{
			return FALSE;
		} else {
			return $result;
		}
	}
	
	public function save_history($lead_id, $params)
	{
		//connect
		$this->local_db();
		
		//update info
		foreach ($params as $key => $value ) {
			$update = "
				UPDATE bcg_record_info
				SET ".$key." = '".mysql_escape_string($value)."'
				WHERE record_id = ".$lead_id."
			";
			
			@mysql_query($update);
		}
		
		$dispo = $this->lookup_disposition($params['disposition']);
		
		//update last modified, last disposition and last status
		$update = mysql_query("UPDATE bcg_records SET last_modified = NOW(), last_status = '".$dispo->status."', last_dispo = '".$dispo->label."' WHERE id = ".$lead_id);
		
		//update scheduled date/time and zone
		if ($params['disposition'] == 24) {
			$update2 = mysql_query("UPDATE bcg_records SET s_date = '".$params['s_date']."', s_zone = '".$params['s_zone']."' WHERE id = ".$lead_id);
		} else {
			//clear scheduled date/time and zone if disposition selected is not a Scheduled type
			$update2 = mysql_query("UPDATE bcg_records SET s_date = '', s_zone = '' WHERE id = ".$lead_id);
		}
		
		//insert history
		$history = mysql_query("INSERT INTO bcg_record_history(record_id, disposition, agent) VALUES(".$lead_id.", ".$params['disposition'].", '".$this->agent."')");
		
		if ($update && $update2 && $history) {
			$_SESSION['prompt'] = 'Record Save!';
			
			return TRUE;
		} else {
			$_SESSION['prompt'] = 'Error encountered during saving. Contact your Administration';
			
			return FALSE;
		}
	}
	
	private function lookup_disposition($id)
	{
		$query = "
			SELECT *
			FROM bcg_record_dispositions
			WHERE id = ".$id."
		";
		
		$result = mysql_query($query);
		
		return mysql_fetch_object($result);
	}
	
	public function sync()
	{
		//get info of all the disposed leads as 'IPA', 'IPSI' by the active agent
		/*
		$query = "
			SELECT *
			FROM vicidial_list a, (SELECT list_id, campaign_id FROM vicidial_lists WHERE campaign_id in ('COMODO', 'COMODOGK', 'COMODO-2', 'COMODO-3', 'COMODOUK')) b
			WHERE b.list_id = a.list_id
			    AND a.status in ('IPA', 'IPSI', 'IN130', 'IN3160', 'IN6190', 'IN91', 'PCBG')
			    AND a.user = '".$this->agent."'
		";
		*/
		
		$query = "
			SELECT *
			FROM I3_NSI_BCG_CH0 a
				INNER JOIN Calllist b ON a.i3_rowid = b.i3_rowid
			WHERE a.finishcode in ('Personal Callback', 'Personal Callback - Profile Created')
				AND a.agentid = '".$this->agent."'
		";
		
		//connect
		$this->i3_db();
		
		$result = mssql_query($query);
		
		if (mssql_num_rows($result) <= 0)
		{
			//no record found
			return FALSE;
		} else {
			//record found
			//init
			$temp = array();
			$i = 0;
			
			//assign to array those records
			while ($row = mssql_fetch_object($result)) 
			{
				$temp[$i]['lead_id'] = $row->i3_identity;
				$temp[$i]['campaign_id'] = $row->campaignname;
				$temp[$i]['phone_number'] = $row->phonenumber;
				$temp[$i]['list_id'] = $row->Diallist;
				$temp[$i]['call_time'] = $row->callplacedtime;
				$temp[$i]['disposition'] = $row->finishcode;
				$temp[$i]['zone'] = $row->Zone;
				$i++;
			}

			$this->i3_close();
			
			if (count($temp) > 0)
			{
				//connect
				$this->local_db();
				
				foreach ($temp as $info => $value)
				{
					$lead_id = $value['lead_id'];
					$campaign_id = $value['campaign_id'];
					$phone_number = $value['phone_number'];
					$list_id = $value['list_id'];
					$call_time = $value['call_time'];
					$disposition = $value['disposition'];
					$zone = $value['zone'];
					
					//check each records if already present in application database
					$query2 = "
						SELECT *
						FROM bcg_records
						WHERE lead_id = ".$lead_id."
							AND user = '".$this->agent."'
					";
					
					$result2 = mysql_query($query2);
					
					//format mssql datetime to valid mysql datetime
					$call_time = date('Y-m-d H:m:s', strtotime($call_time));
					//echo $call_time;
					if (mysql_num_rows($result2) <= 0) {
						//if not present, create a new one
						$insert = "
							INSERT INTO bcg_records(list_id, lead_id, campaign_id, phone_number, dialer, user, d_dispo, d_date, d_zone)
							VALUES(".$list_id.", ".$lead_id.", '".$campaign_id."', '".$phone_number."', 'i3', '".$this->agent."', '".$disposition."', '".$call_time."', '".$zone."')
						";
						
						$success = mysql_query($insert);
						
						$record_id = mysql_insert_id();
						
						$this->Close();
						
						if ($success) {
						//if successfully created insert all of its info
							$this->insert_info($record_id, $lead_id);
						}
					}
				}
				
				@$this->Close();
			}
			
		}
	}
	
	public function get_dispositions($params = NULL)
	{
		$query = "
			SELECT *
			FROM bcg_record_dispositions
		";
		
		if (!is_null($params)) {
			$query = $query.' WHERE '.$params;
		}
		
		//connect
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
	private function insert_info($record_id, $lead_id)
	{
		//query info in custom tables
		$query = "
			SELECT *
			FROM calllist
			WHERE i3_identity = ".$lead_id."
		";
		
		//connect
		@$this->i3_db();
		
		$result = mssql_query($query);
		
		if (mssql_num_rows($result) > 0) {
			
			$row = mssql_fetch_object($result);
			
			$this->i3_close();
			
			$info = array();
			
			/*
			$info['lead_id'] = $row->lead_id;
			$info['user'] = $this->agent;
			$info['dialer'] = $this->dialer;
			$info['f_lead_id'] = $row->Comodo_Lead_ID;
			$info['f_website'] = $row->Ecommernce_website;
			$info['f_fname'] = $row->Lead_First_Name;
			$info['f_lname'] = $row->Lead_Last_Name;
			$info['f_phone'] = $row->Lead_Phone;
			$info['f_country'] = $row->Lead_Country;
			$info['f_ssl_end'] = $row->SSL_End_date;
			$info['f_ssl_duration'] = $row->SSL_Duration;
			$info['f_primary_prod'] = $row->Primary_Product;
			$info['f_validation_lvl'] = $row->Validation_Level;
			$info['f_prod_type'] = $row->Product_Type;
			$info['f_domain'] = $row->Domain_Name;
			$info['f_research_stat'] = $row->Research_Status;
			$info['f_lead_priority'] = $row->Dialer_Lead_Priority;
			$info['f_3cl_in'] = $row->cl_in;
			$info['f_create_date'] = $row->Create_Date;
			*/
			
			//connect
			$this->local_db();
			
			$insert = "
				INSERT INTO bcg_record_info(record_id, f_phonenumber, f_city, f_category, f_business_name, f_address, f_website, f_email, f_email2)
				VALUES(".$record_id.", '".$row->PhoneNumber."', '".$row->City."', '".$row->Category."', '".$row->Business_Name."', '".$row->Address."', '".$row->Website."', '".$row->Email."', '".$row->Email2."');
			";
			
			echo $insert;
			
			$success = mysql_query($insert);
			
			$this->Close();
		}
	}
	
	public function get_list() 
	{
		$query = "
			SELECT *
			FROM bcg_records
			WHERE last_dispo != ''
			ORDER BY last_dispo
		";
		
		//connect
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
	public function view_info($lead_id)
	{
		$query = "
			SELECT *
			FROM bcg_records a 
			INNER JOIN bcg_record_info b
			  ON a.id = b.record_id
			WHERE a.lead_id = ".$lead_id."
		";
		
		//connect
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
	public function get_zones()
	{
		$query = "
			SELECT * 
			FROM bcg_zones
		";
		
		//connect
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
}