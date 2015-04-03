<?php
    /*
	Group Info Page
	This page acts as the "homepage" for a paticular group
	The group information is retreived by using a $_GET Function and getting the group's ID number
        
    */
    
    //Start the Session include the stuff you know the drill
    session_start();
    include "../database-functions.php";
    include "process.php";
    
    if(!$_SESSION['active_user'])
    {
        header("Location: index.php");
    }
	if(is_group($_GET['groupID']) == 0)
	{
		header("Location: home.php");
	}
	$activeUser = $_SESSION['active_user'];
	$groupID = $_GET['groupID'];
	
    function build_standings()
    {
        /*
            Build the Profile Table for the User
        */
        $member = $_SESSION['active_user'];
		$UoM = get_UoM();
        $conn = db_connect();
		$groupID = $_GET['groupID'];
        $query = "SELECT info.userID, info.userName, info.totalDistance, gd.isAdmin FROM qryDistanceByUser AS info, tblGroupDetails AS gd WHERE info.userID = gd.userID AND gd.groupID = ".$groupID." ORDER BY info.totalDistance DESC LIMIT 5;";
        
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
       	
		echo "<table class='table table-striped'> \r\n";
		echo "<h1>Top 5 Runners - Distance</h1> \r\n";
		echo "<thead> \r\n"; 
		echo "<tr> \r\n";
		echo "<th>Rank</th> \r\n";
		echo "<th>Name</th> \r\n"; 
		echo "<th>Distance</th> \r\n";
		echo "</tr> \r\n";
		echo "</thead> \r\n";
		echo "<tbody> \r\n";
		
		for($i=0; $i<$num_rows; $i++)
        {
       		$extra = "";
			
			$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			if($item['isAdmin'] == 1)
			{
				$extra = " &nbsp; <span class='label label-primary'>Admin</span>";	
			}
			
			if($item['userID'] == $member)
			{
				$extra = $extra." &nbsp; <span class='label label-success'>You</span>";
			}
			echo "<tr> \r\n";
			echo "<td>".($i+1)."</td> \r\n";
			echo "<td>".$item['userName'].$extra."</td> \r\n";
			echo "<td>".get_distance_string($item['totalDistance'], $UoM)."</td> \r\n";
			echo "</tr> \r\n";
		}
			echo "</tbody> \r\n";
			echo "</table> \r\n";
			
        db_disconnect($conn);

    }
    
	$group_data = get_group_info($groupID);

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

    <title><?php echo $group_data['groupName']; ?> | Project Infinity</title>

    <!-- Bootstrap core CSS -->
    <link href="../autumn/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../autumn/assets/css/offcanvas.css" rel="stylesheet">

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

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs"><button type="button" class="btn btn-primary" data-toggle="offcanvas">Menu</button></p>
          <div class="jumbotron">
            <h1><?php echo $group_data['groupName']; ?>&nbsp;<small><span class="label label-primary">[<?php echo $group_data['clanTag'];?>]</small></span></h1>
            <p class='text-muted'><?php echo $group_data['tagline']; ?></p>
          </div>
          <div class="row">
            <div class="col-xs-10 col-lg-10">
              <h2>Group Memo</h2>
              <p><?php echo $group_data['bulletin']; ?></p>
              <?php if($group_data['adminID'] == $activeUser){ echo "<p><a class='btn btn-default' href='#' role='button'>Update Memo &raquo;</a></p>";}?>
            </div><!--/.col-xs-6.col-lg-4-->
          </div><!--/row-->
          <?php build_standings(); ?>
        </div><!--/.col-xs-12.col-sm-9-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
          <div class="list-group">
            <a href="#" class="list-group-item active">Home</a>
            <a href= <?php echo "'groupMembers.php?groupID=".$group_data['groupID']."'"; ?> class="list-group-item">Members</a>
            <a href="#" class="list-group-item">Leaderboard</a>
            <?php
				if($group_data['adminID'] == $activeUser)
				{
					echo "<a href='#' class='list-group-item'>Group Settings</a>";
				}
			?>
          </div>
        </div><!--/.sidebar-offcanvas-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>

    </div><!--/.container-->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../autumn/assets/js/bootstrap.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../autumn/assets/js/ie10-viewport-bug-workaround.js"></script>

    <script src="../autumn/assets/js/offcanvas.js"></script>
  </body>
</html>
