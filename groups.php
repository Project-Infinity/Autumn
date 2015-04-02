<?php
    /*
	Groups page
        
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
		
		$query = "SELECT g.groupID, g.tagline, g.groupName, gd.hasAlerts, gd.isAdmin, m.memberCount, ad.adminID, ad.adminName FROM tblGroups AS g, tblGroupDetails AS gd RIGHT JOIN(SELECT * FROM qryMembers) AS m ON (gd.groupID = m.groupID) RIGHT JOIN (SELECT * FROM qryGroupAdmins) AS ad ON (gd.groupID = ad.groupID) WHERE g.groupID = gd.groupID AND 
gd.userID =".$userID.";";
		
		$conn = db_connect();
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
		
		for($i=0; $i<$num_rows; $i++)
		{
			$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			if($item['adminID'] == $userID)
			{
				$spanClass = "label label-success";
				$adminName = "You";
			}
			else
			{
				$spanClass = "label label-info";
				$adminName = $item['adminName'];
			}
			
			echo "<div class='media'> \r\n";
			echo "<div class='media-left'> \r\n";
			echo "<a href='#'><img class='media-object' src='http://cgi.soic.indiana.edu/~team36/private/groups-2.png' alt='Generic placeholder image' style='height: 64px; width: 64px;'></a> \r\n";
			echo "</div> \r\n";
			echo "<div class='media-body'> \r\n";
			echo "<h4 class='media-heading'>".$item['groupName']." &nbsp; <span class='".$spanClass."'>".$adminName."</span></h4> \r\n";
			echo "<span class='text-muted'><i>".$item['tagline']."</i></span> \r\n";
			echo "<p>Members: ".$item['memberCount']."</p> \r\n";
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

    <title>Project Infinity | Groups</title>

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

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" style="font-family: titleFont;">INFINITY</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="home.php">Dashboard</a></li>
            <li class="dropdown">
              <a href="workouts.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Workouts <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="workouts.php">My Workouts</a></li>
                <li><a href="#">Create Workout</a></li>
              </ul>
            </li>
            <li class="active">
              <a href="groups.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Groups <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="groups.php">My Groups</a></li>
                <li><a href="#">Create Group</a></li>
              </ul>
            </li>
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Messages &nbsp;<span class="badge"><?php echo check_for_messages($activeUser); ?></span></a>
                <ul class="dropdown-menu" role="menu">
                    <?php build_mini_inbox(); ?>
                    <li><a href='messages.php'>Go to Inbox</a></li>
                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Settings <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Update Profile</a></li>
                <li><a href="#">Privacy</a></li>
                <li><a href="#">Help</a></li>
                <li class="divider"></li>
                <li><a href="#">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
    

      <!--<div class="starter-template">
        <h3>Team 36 &nbsp; <span class="label label-success">You</span></h3>
        <span class="text-muted"><i>Runnin' All Day</i></span>
        <p class="lead">Members: 5</p>
      </div>
      <hr>
      <div class="starter-template">
        <h3>Group Two &nbsp; <span class="label label-info">John Smith</span></h3>
        <span class="text-muted"><i>tagline</i></span>
        <p class="lead">Members: 6</p>
      </div>"
      <hr>
      <div class="starter-template">
        <h3>Group Two &nbsp; <span class="label label-danger">No Admin</span></h3>
        <p class="lead">Members: 6</p>
      </div> -->
    <div class="starter-template">
      <div class="row">
        <div class="col-lg">
          <div class="input-group">
			<span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-search" aria-hidden="true" id="sizing-addon1"></span></span>
            <input type="text" class="form-control" placeholder="Group Name" aria-describedby="sizing-addon1" />
            <span class="input-group-btn"><button class="btn btn-default" type="button" aria-describedby="sizing-addon1">Search</button></span>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
      </div><!-- /.row -->
      <!--begin groups -->
      <!--<div class="media">
        <div class="media-left">
          <a href="#">
            <img class="media-object" src="http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Marathon_logo.jpg/64px-Marathon_logo.jpg" alt="Generic placeholder image">
          </a>
        </div>
        <div class="media-body">
          <h4 class="media-heading">Team 36 &nbsp; <span class="label label-success">You</span></h4>
          <span class="text-muted"><i>Runnin' All Day</i></span>
          <p>Members: 5</p>
        </div>
      </div>
      <hr>
      <div class="media">
        <div class="media-left">
          <a href="#">
            <img class="media-object" src="http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Marathon_logo.jpg/64px-Marathon_logo.jpg" alt="Generic placeholder image">
          </a>
        </div>
        <div class="media-body">
          <h4 class="media-heading">Team 2 &nbsp; <span class="label label-info">John Smith</span></h4>
          <span class="text-muted"><i>Tagline here</i></span>
          <p>Members: 5</p>
        </div>
      </div> 
    </div>-->
    <?php build_groups(); ?>
    

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