<?php
    /*
        Members profile page 
        (or, Personal Profile Page boo-yah!)
        
        this is the ONLY page that uses JQuery and AJAX
        More information JQuery in the source.js page.
        
    */
    
    //Start the Session include the stuff you know the drill
    session_start();
    include "../database-functions.php";
    include "process.php";
	
    
    if(!$_SESSION['active_user'])
    {
        header("Location: index.php");
    }
	$activeUser = $_SESSION['active_user'];
    /*
    //If the update profile button was cl
    if($_POST['btnSubmit'])
    {
        $member = $_SESSION['logged_in'];
        
        //Clean the user input text
        $first = addslashes(strip_tags($_POST['tbFirst']));
        $last = addslashes(strip_tags($_POST['tbLast']));
        $street = addslashes(strip_tags($_POST['tbStreet']));
        $street2 = addslashes(strip_tags($_POST['tbStreet2']));
        $city = addslashes(strip_tags($_POST['tbCity']));
        $state = addslashes(strip_tags($_POST['lbState']));
        $ZIP = addslashes(strip_tags($_POST['tbZIP']));
        $phone = addslashes(strip_tags($_POST['tbPhone']));
        
        //connect to the database
        $conn = db_connect();
        $query = "UPDATE members SET first = '".$first."', last = '".$last."', street = '".$street."', street2 = '".$street2."', city = '".$city."', state = '".$state."', ZIPcode = '".$ZIP."', phone = '".$phone."' WHERE memberID = '".$member."';";
        
        $data = mysqli_query($conn, $query);
        db_disconnect($conn);
        //resets the Submit Button.
        $_POST['btnSubmit'] = "";
        
    }
    */
    function build_profile()
    {
        /*
            Build the Profile Table for the User
        */
        $member = $_SESSION['active_user'];
        $conn = db_connect();
        $query = "SELECT u.fName, u.lName, u.userEmail, SUM(logD.distance) AS totalDistance, COUNT(DISTINCT log.logID) AS totalWorkouts FROM tblUsers AS u, tblWorkoutLog AS log, tblWorkoutLogDetails AS logD WHERE u.userID = log.userID AND log.logID = logD.logID AND u.userID = ".$member." GROUP BY u.fName, u.lName, u.userEmail;";
        
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
        
        $item = mysqli_fetch_array($data, MYSQLI_ASSOC);
    
        echo "<table style='text-align:left !important;'> \r\n";
        echo "<tr> \r\n";
        echo "<td>Name: </td> \r\n";
        echo "<td>".$item['fName']." ".$item['lName']."</td> \r\n";
        echo "</tr> \r\n <tr> \r\n";
        echo "<td>E-Mail Address: </td> \r\n";
        echo "<td>".strtolower($item['userEmail'])."</td>";
		echo "</tr> \r\n <tr> \r\n";
		echo "<td> Total Distance Ran: </td> \r\n";
		echo "<td>".$item['totalDistance']." Meters</td> \r\n";
		echo "</tr> \r\n <tr> \r\n";
		echo "<td> Total Workouts Logged: </td> \r\n";
		echo "<td>".$item['totalWorkouts']."</td> \r\n";		
        echo "</tr> \r\n </table> \r\n";
        echo "<button onclick='show_profile()'>Edit Profile</button>";
        db_disconnect($conn);

    }
    
    function getMember()
    {
        /*
            Returns an array of the user's information
            used to fill in the default values for the user in
            the editor section.
        */
        $member = $_SESSION['active_user'];
        $conn = db_connect();
        $query = "SELECT * FROM tblUsers WHERE userID ='".$member."';";
        
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
        
        $item = mysqli_fetch_array($data, MYSQLI_ASSOC);
        db_disconnect($conn);
        return $item;        
    }
    
    $user_data = getMember();
    
    
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="main.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="source.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css">
        <title>Project Infinity | Member Profile</title>
    </head>
    <body>  
      <div class="menuBar">
      <string class="fltlft"style="margin-left: 10%;">INFINITY</string>
      	<ul class="fltrt" style="margin-right: 10%;">
          <li><a href="home.php" title="Return to the Home Screen"><img src="../private/home-2.png" /> Home</a></li>
          <li><a href=<?php echo '"javascript:echoBack('.$activeUser.');"'; ?> ><img src="../private/groups-2.png"/> Groups</a></li>
          <li><a href="workouts.php" ><img src="../private/workouts-2.png" /> Workouts</a></li>
          <li><a href="inbox.php"><img src="../private/mail-2.png"  /> Inbox </a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <br class="clearfloat"/>
      <div class="container">
          <?php build_profile(); ?>
      </div>
      <br class="clearfloat" />
      <!-- BEGIN JQUERY DIV CLASS -->
      <div class="profileEdit" id="profileEdit" title="Edit Profile">
        <form action="member.php" method="post">
              <table border="0" class="bordered">
                  <tr>
                      <td>Name: </td>
                      <td><input type="text" name="tbFirst" value="<?php echo $user_data['fName']; ?>" required/></td>
                      <td><input type="text" name="tbLast" value="<?php echo $user_data['lName']; ?>" required/></td>
                  </tr>
                  <tr>
                      <td rowspan="1">E-Mail: </td>
                      <td colspan="2"><input type="email" name="tbEmail" value="<?php echo strtolower($user_data['userEmail']); ?>" placeholder ="E-Mail Address" style="width: 100%;" required/></td>
                  </tr>
                  <tr> 
                      <td colspan="3"><button type='submit' name='btnSubmit' value='submit'>Update Member Information</button></td>
                  </tr> 
              </table>                            
        </form>
      </div>
      <!-- END JQUERY DIV CLASS -->
      <br class="clearfloat" />
      <!-- BEGIN JQUERY DIV CLASS -->
      <div id="dialogBox" title="Groups">
      </div>
      <!-- END JQUERY DIV CLASS -->
    </body>
</html>