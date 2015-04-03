<?php
    /*
	 Workouts page
        
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
	
	function build_groups()
	{
		//build the group divs
		$userID = $_SESSION['active_user'];
		$UoM = get_UoM();
		
		$query = "SELECT w.workoutName, COUNT(wd.phase) AS totalPhases, SUM(wd.distance) AS totalDistance FROM tblWorkouts AS w, tblWorkoutDetails as wd WHERE w.workoutID = wd.workoutID AND w.userID = ".$userID." GROUP BY w.workoutName;";
		
		$conn = db_connect();
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
		
		for($i=0; $i<$num_rows; $i++)
		{
			$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			
			echo "<div class='media'> \r\n";
			echo "<div class='media-body'> \r\n";
			echo "<h4 class='media-heading'>".$item['workoutName']."</h4> \r\n";
			echo "<span class='text-muted'><i>Number of Phases: ".$item['totalPhases']."</i></span> \r\n";
			echo "<p>Total Distance: ".get_distance_string($item['totalDistance'], $UoM)."</p> \r\n";
			echo "</div> \r\n";
			echo "</div> \r\n";
			echo "<hr> \r\n";
			
		}
		
		db_disconnect($conn);
		
	}
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
    <style>
		@font-face {
    font-family: titleFont;
    src: url(../autumn/assets/fonts/Handel_Gothic.ttf);
					}
	</style>    

    <title>Project Infinity | Workouts</title>

    <!-- Bootstrap core CSS -->
    <link href="../autumn/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../autumn/assets/css/starter-template.css" rel="stylesheet">

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

    <div class="container">
    <div class="starter-template">
    <h2>General Information</h2>
    	<form class="form-horizontal" name ='frmChangeText' action ='index.php' method ='post' role="form">
      		<div class="col">
            	<div class="form-group">
                	<div class="col-sm-5"><input type="text" name="tbFName" class="form-control" placeholder="First Name" id="inputText3" value="<?php echo $_POST['tbFName'] ;?>" required /></div>
                </div>
                <div class="form-group">
          			<div class="col-sm-5"><input type="text" name="tbLName"  class="form-control" placeholder="Last Name" id="inputText3" value="<?php echo $_POST['tbLName'] ;?>" required /></div>
                </div>
          </div><!-- /.row -->
      </form>
      <hr>
      <h2>Units of Measurment</h2>
      <div class="btn-group" role="group" aria-label="...">
        <button type="submit" class="btn btn-success" value="-1">United States (Miles/Meters)</button>
        <button type="submit" class="btn btn-default" value="1">Metric (Kilometers/Meters)</button>
      </div>
      <!--begin groups -->
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../autumn/assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../autumn/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>