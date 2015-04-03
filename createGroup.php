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
	<!--Begin Content-->
    <div class="container">
    <div class="starter-template">
        <form class="form-horizontal">
        <fieldset>
        
        <!-- Form Name -->
        <legend>Create Group</legend>
        
        <!-- Prepended text-->
            <div class="input-group">
              <span class="input-group-addon" id="sizing-addon1">Group Name</span><input name="tbGroupName" type="text" class="form-control" aria-describedby="sizing-addon1" required />
             </div>
            <p class="help-block">Type a Group Name</p>
            <br>
            <div class="input-group">
              <span class="input-group-addon" id="sizing-addon1">Motto</span><input name="tbTagline" type="text" class="form-control" aria-describedby="sizing-addon1" />
             </div>
            <p class="help-block">Type a Group Motto (Optional)</p>
            <br>
            <div class="input-group">
              <span class="input-group-addon" id="sizing-addon1">Clan Tag</span><input name="tbClanTag" type="text" class="form-control" aria-describedby="sizing-addon1" />
             </div>
            <p class="help-block">Enter a Personalized Clan Tag (4 Characters Max)</p>
        <!-- Multiple Radios -->
        <div class="control-group">
          <label class="control-label" for="rbGroupStatus">Group Privacy</label>
          <div class="controls">
            <label class="radio" for="rbGroupStatus-0">
              <input type="radio" name="rbGroupStatus" value="1" checked="checked">
              Closed Group (Only Admin Can Add Members)
            </label>
            <label class="radio" for="rbGroupStatus-1"><input type="radio" id=name="rbGroupStatus-0" name="rbGroupStatus" value="-1">Open Group (Anyone Can Join)</label>
          </div>
        </div>
        
        <!-- Button -->
        <div class="control-group">
          <label class="control-label" for="btnSubmit"></label>
          <div class="controls">
            <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-success">Create Group</button>
          </div>
        </div>
        
        </fieldset>
        </form>
   	</div>
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