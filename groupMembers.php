<?php
    /*
	Group Members Detail Page
	This page shows a query of the members in a group
	the group ID comes from the use of the GET function 
        
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
	
	function build_groups()
	{
		//build the group divs
		$userID = $_SESSION['active_user'];
		$groupID = $_GET['groupID'];
		
		$query = "SELECT u.userID, CONCAT(u.fName, ' ' , u.lName) AS userName, u.userPic, gd.isAdmin FROM tblUsers AS u, tblGroupDetails AS gd WHERE u.userID = gd.userID AND gd.groupID = ".$groupID." ORDER BY gd.isAdmin DESC, u.lName ASC;";
		
		$conn = db_connect();
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
		
		for($i=0; $i<$num_rows; $i++)
		{
			$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			if($item['isAdmin'] == 1)
			{
				$span = "<span class='label label-success'>[Admin]</span>";
			}
			else
			{
				$span = "";
			}
			
			echo "<div class='media'> \r\n";
			echo "<div class='media-left'> \r\n";
			echo "<div style='width: 64px; height: 64px; '>".$span."</div> \r\n";
			echo "</div> \r\n";
			echo "<div class='media-body'> \r\n";
			echo "<a href='#=".$item['userID']."'><h4 class='media-heading'>".$item['userName']."</a></h4> \r\n";
			echo "<hr> \r\n";
			echo "</div> \r\n";
			echo "</div> \r\n";
			
		}
		
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
          <h2>Members in Group</h2>
          <?php build_groups(); ?>
        </div><!--/.col-xs-12.col-sm-9-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
          <div class="list-group">
            <a href=<?php echo "'groupInfo.php?groupID=".$group_data['groupID']."'"; ?> class="list-group-item">Home</a>
            <a href=<?php echo "'groupMembers.php?groupID=".$group_data['groupID']."'"; ?> class="list-group-item active">Members</a>
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
