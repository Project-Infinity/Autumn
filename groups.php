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
		
		$query = "SELECT g.groupID, g.tagline, g.clanTag, g.groupName, gd.hasAlerts, gd.isAdmin, m.memberCount, ad.adminID, ad.adminName FROM tblGroups AS g, tblGroupDetails AS gd RIGHT JOIN(SELECT * FROM qryMembers) AS m ON (gd.groupID = m.groupID) RIGHT JOIN (SELECT * FROM qryGroupAdmins) AS ad ON (gd.groupID = ad.groupID) WHERE g.groupID = gd.groupID AND 
gd.userID =".$userID.";";
		
		$conn = db_connect();
        $data = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($data);
		
		for($i=0; $i<$num_rows; $i++)
		{
			$item = mysqli_fetch_array($data, MYSQLI_ASSOC);
			if($item['adminID'] == $userID)
			{
				$adminName = "You";
			}
			else
			{
				$adminName = $item['adminName'];
			}
			
			echo "<div class='media'> \r\n";
			echo "<div class='media-left'> \r\n";
			echo "<div style='width: 64px; height: 64px; '><span class='label label-primary'>[".$item['clanTag']."]</span></div> \r\n";
			echo "</div> \r\n";
			echo "<div class='media-body'> \r\n";
			echo "<a href='groupInfo.php?groupID=".$item['groupID']."'><h4 class='media-heading'>".$item['groupName']."</a></h4> \r\n";
			echo "<span class='text-muted'><i>".$item['tagline']."</i></span> \r\n";
			echo "<p>Admin: ".$adminName."<br>Members: ".$item['memberCount']."</p>";
			echo "</div> \r\n";
			echo "<hr> \r\n";
			echo "</div> \r\n";
			
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
	<?php build_navBar(); ?>
    <div class="container">
    <h1>Groups</h1>
    <div style="text-align: center;">
    <form class="form-inline">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
          <input type="text" class="form-control" name="tbSearch" id="tbSearch" placeholder="Search For Groups" />
          <span class="input-group-btn"><button type="submit" class="btn btn-default">Search</button></span>
        </div>
      </div>
      <a class="btn btn-success" href="createGroup.php" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Create Group</a>
    </form>
    </div>
    <div class="starter-template">
      <!--begin groups -->
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