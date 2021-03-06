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
	
    function build_table()
    {
        /*
            Build the Profile Table for the User
        */
        $member = $_SESSION['active_user'];
		$UoM = get_UoM();
        $conn = db_connect();
        $query = "SELECT IF(DATEDIFF(CURDATE(),log.dateLogged) <= 7, DATE_FORMAT(log.dateLogged,'%W'), DATE_FORMAT(log.dateLogged, '%c/%e/%Y')) AS dateLogged1, COUNT(logD.phase) AS totalPhases, SUM(logD.distance) AS totalDistance, SUM(logD.timeRan) AS totalTime FROM tblHistory AS log, tblHistoryDetails as logD WHERE log.logID = logD.logID AND log.userID = ".$member." GROUP BY log.dateLogged DESC LIMIT 5;";
        
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
       	
		echo "<table class='table table-striped'> \r\n";
		echo "<thead> \r\n"; 
		echo "<tr> \r\n";
		echo "<th>Date</th> \r\n";
		echo "<th>Distance</th> \r\n"; 
		echo "<th>Time</th> \r\n";
		echo "</tr> \r\n";
		echo "</thead> \r\n";
		echo "<tbody> \r\n";
		
		for($i=0; $i<$num_rows; $i++)
        {
       		$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			
			if($item['totalTime']<3600)
			{
				$timeVar = gmdate('i:s',$item['totalTime']);
			}else
			{
				$timeVar = gmdate('H:i:s',$item['totalTime']);
			}
			echo "<tr> \r\n";
			echo "<td>".$item['dateLogged1']."</td> \r\n";
			echo "<td>".get_distance_string($item['totalDistance'], $UoM)."</td> \r\n";
			echo "<td>".$timeVar."</td> \r\n";
			echo "</tr> \r\n";
		}
			echo "</tbody> \r\n";
			echo "</table> \r\n";
			
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
        $query = "SELECT u.fName, u.lName, u.userEmail, SUM(logD.distance) AS totalDistance, COUNT(DISTINCT log.logID) AS totalWorkouts FROM tblUsers AS u, tblWorkoutLog AS log, tblWorkoutLogDetails AS logD WHERE u.userID = log.userID AND log.logID = logD.logID AND u.userID = ".$member." GROUP BY u.fName, u.lName, u.userEmail;";
        
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
        
        $item = mysqli_fetch_array($data, MYSQLI_ASSOC);
        db_disconnect($conn);
        return $item;        
    }
    
    $user_data = getMember();

?>
 <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<link href="main.css" rel="stylesheet">
    <title>Project Infinity | Home</title>

    <!-- Bootstrap core CSS -->
    <link href="../autumn/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../autumn/assets/css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../autumn/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
	<?php build_navBar(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#overview" data-toggle="collapse">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Recent Workouts</a></li>
            <li><a href="#goals" data-toggle="collapse">Goals</a></li>
            <li><a href="#">Export</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <div id='overview' class="collapse in">
            <h1 class="page-header"><?php echo get_active_user(); ?></h1>
  
            <div class="row placeholders">
              <div class="col-xs-6 col-sm-3 placeholder">
              	<?php build_distance_ran() ;?>
                <span class="text-muted">Total Distance Ran</span>
              </div>
              <div class="col-xs-6 col-sm-3 placeholder">
                <string class="string-responsive" style="height: 200px; width: 200px; font-size:48pt;"><?php echo get_workouts($activeUser); ?></string>
                <h4>Workouts</h4>
                <span class="text-muted">Number of Workouts Recorded</span>
              </div>
            </div>
            <h2 class="sub-header">Recent Workouts&nbsp;<a href="#"><small class='label label-primary' style="font-size:12px">Go to Workout Log &raquo;</small></a></h2>
            <div class="table-responsive">
              <?php build_table(); ?>
            </div>
          </div>
          <div id='goals'>
          <h2 class="sub-header">Goals&nbsp;<a href="#"><small class='label label-primary' style="font-size:12px">Go to Goals &raquo;</small></a></h2>
          <h2>Run 5 Miles</h2>
          <span class="text-muted">Finished! Date Finished: 1/17/2015</span>
          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width:100%"> Finished! </div>
          </div>
          <h2>Run 10 Miles</h2>
          <span class="text-muted">Date Started: 1/1/2015</span>
          <div class="progress">
            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 50%"> 5 miles </div>
          </div>
		  </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../autumn/assets/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../autumn/assets/js/vendor/holder.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../autumn/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>


