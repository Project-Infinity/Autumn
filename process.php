<?php

    /*
        process.php
		to be used with Project Pillar of Autumn
        To save time with writing functions that can be used in more
        than one page, they will be included in this file
    */
    
	//Static varaibles
	$METER_2_MILE = 0.000621371;
	$MILE_2_METER = 1609.34;
	$MILE_2_KMETER = 1.60934;
	$KMETER_2_MILE = 0.621371;
	$METER_2_KMETER = 0.001;
	$KMETER_2_METER = 1000;
	
	function validateText($text)
    {
        /*
            Cleans up any text if needed
            @param $text a string of text
            @return a "cleaned up" text string. 
        */
        return addslashes(htmlentities(strip_tags($text)));    
    }
	
    function get_active_user($field = "CONCAT(fName, ' ', lName)")
    {
        /*
            returns information on the current user
            **NOTE: MUST START SESSION BEFORE USING THIS FUNCTION**
            @param string $field. The field mySQL should return. Its default value returns the full name
            @return a value from the members table in the database. 
        */    
        
        $userID = $_SESSION['active_user'];
        
        $conn = db_connect();
        $query = "SELECT ".$field." AS target FROM tblUsers WHERE userID=".$userID.";";
        $data = mysqli_query($conn, $query);    
        $item = mysqli_fetch_array($data, MYSQLI_ASSOC);
        
        echo $item['target'];
        db_disconnect($conn);
        
    }
	
	function build_mini_inbox()
	{
		/*
			builds the miniature inbox
		*/	
		
		$userID = $_SESSION['active_user'];
		$conn = db_connect();
		
		$query = "SELECT m.messageID, m.sender, CONCAT(u.fName,' ',u.lName) AS senderName, IF(DATEDIFF(CURDATE(),m.sendingDate) <= 7, DATE_FORMAT(m.sendingDate,'%W'), DATE_FORMAT(m.sendingDate, '%c/%e/%Y')) AS dateSent, m.subject, m.isRead FROM tblUsers AS u, tblMessages AS m WHERE u.userID = m.sender AND m.isDeleted = -1 AND m.receiver = ".$userID." ORDER BY m.isRead ASC, m.messageID DESC LIMIT 5;";
		
		$data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
		
		if($num_rows == 0)
		{
			echo "<li><a href='#'>No Messages</a></li>";
			echo "<li class='divider'></li> \r\n";	
		}
		else
		{
			for($i=0; $i<$num_rows; $i++)
			{
				$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
				if($item['isRead'] == -1)
				{
					$sender = "<strong>".$item['senderName']."</strong>";	
				}
				else
				{
					$sender = $	$item['senderName'];
				}
				
				echo "<li><a href='#'>".$sender."<br><span class='text-muted'>".$item['subject']."</span></a></li> \r\n";
				
				echo "<li class='divider'></li> \r\n";
			}
		}
		
		db_disconnect($conn);
		
	}
	
	function get_UoM()
	{
		$userID = $_SESSION['active_user'];
		$conn = db_connect();
		
		$query = "SELECT UoM FROM tblUsers WHERE userID = ".$userID.";";
		
		$data = mysqli_query($conn, $query);
		
		$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
		
		return $item['UoM'];
	}
	
	function build_distance_ran()
	{
		/*
			-1 MILES / METERS
			 1 KILOMETERS / METERS 
		*/
		
		$userID = $_SESSION['active_user'];
		
		$conn = db_connect();
		$query = "SELECT SUM(logD.distance) AS totalDistance FROM tblHistory AS log, tblHistoryDetails AS logD WHERE log.logID = logD.logID AND log.userID = ".$userID.";";
		
		$data = mysqli_query($conn, $query);
		
		$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
		
		$distance = $item['totalDistance'];
		db_disconnect($conn);
		
		if(get_UoM() == -1)
		{	
			if($distance > 1610)
			{
				
				$distance = round($distance/1609.34, 2);
				echo "<string class='string-responsive' style='height: 200px; width: 200px; font-size:48pt;'>".$distance."</string> \r\n";
				echo "<h4>Miles</h4> \r\n";	
			}
			else
			{
				$distance = round($distance, 2);
				echo "<string class='string-responsive' style='height: 200px; width: 200px; font-size:48pt;'>".$distance."</string> \r\n";
				echo "<h4>Meters</h4> \r\n";	
			}
		}
		else
		{
			if($distance > 1610)
			{
				
				$distance = round($distance/1000, 2);
				echo "<string class='string-responsive' style='height: 200px; width: 200px; font-size:48pt;'>".$distance."</string> \r\n";
				echo "<h4>Kilometers</h4> \r\n";	
			}
			else
			{
				$distance = round($distance, 2);
				echo "<string class='string-responsive' style='height: 200px; width: 200px; font-size:48pt;'>".$distance."</string> \r\n";
				echo "<h4>Meters</h4> \r\n";	
			}	
		} 
		
		function meters_to_miles($distance)
		{
			/*
				converts meters to miles
			*/
			
			return round($distance/1609.34, 2);
		}
		
	}
?>