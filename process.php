<?php

    /*
        process.php
	to be used with Project Pillar of Autumn
        To save time with writing functions that can be used in more
        than one page, they will be included in this file
    */
    
	//Static varaibles These are conversion units
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
		
		$query = "SELECT m.messageID, m.sender, CONCAT(u.fName,' ',u.lName) AS senderName, IF(DATEDIFF(CURDATE(),m.sendingDate) <= 7, DATE_FORMAT(m.sendingDate,'%W'), DATE_FORMAT(m.sendingDate, '%c/%e/%Y')) AS dateSent, m.subject, m.isRead FROM tblUsers AS u, tblMessages AS m WHERE u.userID = m.sender AND m.isDeleted = -1 AND m.receiver = ".$userID." ORDER BY m.isRead ASC, m.messageID DESC LIMIT 3;";
		
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
		/*
			returns the unit of measurement for a paticular user
			-1 US UNITS (MILES/METERES)
			1 METRIC UNITS (KILOMETERES/METERES)
		*/
		$userID = $_SESSION['active_user'];
		$conn = db_connect();
		
		$query = "SELECT UoM FROM tblUsers WHERE userID = ".$userID.";";
		
		$data = mysqli_query($conn, $query);
		
		$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
		db_disconnect($conn);
		
		return $item['UoM'];
	}
	
	function build_distance_ran()
	{
		/*
			Builds the distance ran as a number
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
	
	function get_distance_string($distance, $UoM)
	{
		/*
			reutrn distance as a string (distance + UoM)
			-1 MILES / METERS
			+1 KILOMETERS / METERS
		*/	
			
		if($UoM == -1)
		{
			if($distance < 1610)
			{
				$dist_var = $distance."m";
			}
			else
			{
				$dist_var = round($distance/1609.34,2)." mi";
			}
					
			}
			else
			{
				if($distance < 1000)
				{
					$dist_var = $distance."m";
				}
				else
				{
					$dist_var = round($distance/1000,2)." Km";
				}
			}
		return $dist_var;
	}
	
	function get_group_info($groupID)
	{
			$query = "SELECT g.groupID, g.groupName, g.tagline, g.clanTag, g.isClosed, g.bulletin, u.userID AS adminID, CONCAT(u.fName,' ',u.lName) AS adminName, counter.members AS totalMembers FROM tblUsers AS u, tblGroups AS g, tblGroupDetails AS gd LEFT JOIN (SELECT groupID, COUNT(*) AS members FROM tblGroupDetails GROUP BY groupID)AS counter ON (gd.groupID = counter.groupID) WHERE u.userID = gd.userID AND g.groupID = gd.groupID AND gd.isAdmin = 1 AND g.groupID = ".$groupID." GROUP BY g.groupName, g.tagline, g.isClosed, adminName;";
			
		$conn = db_connect();
		
		$data = mysqli_query($conn, $query);
		
		$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
		
		db_disconnect($conn);
		return $item;
	}
	
	function build_navBar()
	{
		/*
			Because the NavBar div is big, updating it would take too long
			this function builds the navBar div and makes the markup on the other pages eaiser to read
			any updates to the NavBar are made here. 
		*/
		$activeUser = $_SESSION['active_user'];
		echo '<nav class="navbar navbar-inverse navbar-fixed-top">';
		echo '<div class="container-fluid">';
		echo '<div class="navbar-header">';
		echo '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">';
		echo '<span class="sr-only">Toggle navigation</span>';
		echo '<span class="icon-bar"></span>';
		echo '<span class="icon-bar"></span>';
		echo '<span class="icon-bar"></span>';
		echo '</button>';
		echo '<a class="navbar-brand" href="#" style="font-family: titleFont;">INFINITY</a>';
		echo '</div>';
		echo '<div id="navbar" class="navbar-collapse collapse">';
		echo '<ul class="nav navbar-nav navbar-right">';
		echo '<li><a href="home.php">Dashboard</a></li>';
		echo '<li class="dropdown">';
		echo '<a href="workouts.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Workouts <span class="caret"></span></a>';
		echo '<ul class="dropdown-menu" role="menu">';
		echo '<li><a href="workouts.php">My Workouts</a></li>';
		echo '<li><a href="workoutLog.php">My Log</a></li>';
		echo '<li class="divider"></li>';
		echo '<li><a href="#">Create Workout</a></li>';
		echo '<li><a href="#">Record Workout</a></li>';
		echo '</ul>';
		echo '</li>';
		echo '<li class="dropdown">';
		echo '<a href="groups.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Groups <span class="caret"></span></a>';
		echo '<ul class="dropdown-menu" role="menu">';
		echo '<li><a href="groups.php">My Groups</a></li>';
		echo '<li><a href="createGroup.php">Create Group</a></li>';
		echo '</ul>';
		echo '</li>';
		echo '<li class="dropdown">';
		echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Messages &nbsp;<span class="badge">'.check_for_messages($activeUser).'</span></a>';
		echo '<ul class="dropdown-menu" role="menu">';
		build_mini_inbox();
		echo '<li><a href="messages.php">Go to Inbox</a></li>';
		echo '</ul>';
		echo '</li>';
		echo '<li class="dropdown">';
		echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Settings <span class="caret"></span></a>';
		echo '<ul class="dropdown-menu" role="menu">';
		echo '<li><a href="settings.php">Update Profile</a></li>';
		echo '<li><a href="#">Privacy</a></li>';
		echo '<li><a href="#">Help</a></li>';
		echo '<li class="divider"></li>';
		echo '<li><a href="#">Logout</a></li>';
		echo '</ul>';
		echo '</li>';
		echo '</ul>';
		echo '</div>';
		echo '</div>';
		echo '</nav>';	
	}
?>
