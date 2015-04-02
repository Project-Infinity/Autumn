<?php
	/*
		Welcome Page
		Created for Project Pillar of Autumn
		a Project Infinity Development
		
		Registers/Login the user
	
	*/
	include "process.php";
	include "../database-functions.php";
	
	//start the session
	session_start();
	
	if($_POST['btnLogin'])
	{
		$email = addslashes(strip_tags($_POST['tbEmail']));
		$password = addslashes(strip_tags($_POST['tbPswd']));
		
		if(is_valid_user($email, $password) == 1)
		{
			$_SESSION['active_user'] = return_user_ID($email);
			header("Location: home.php");
		}
		else
		{
			$_POST['btnLogin'] = "";	
		}
	}
	else if($_POST['btnRegister'])
	{
		/*$fName = $_POST['tbFName'];
		$lName = $_POST['tbLName'];
		$emailA = $_POST['tbEmailR'];
		$pswd = $_POST['tbPswdR'];
		
		switch(is_user($emailA))
		{
			case 1:
				//echo "User in Databse";
				$_POST['btnRegister'] = "";
				$_POST['tbEmail'] = $_POST['tbEmailR'];
				break;
			case 0:
				if(alt_register_user($fName, $lName, $emailA, $pswd))
				{
					$_SESSION['activeUser'] = return_user_ID($emailA);
					echo "Success Reg!";
					echo return_user_ID($emailA);
					//header("Location: index.php");
					$_POST['btnRegister'] = "";
					break;
				}
				break;
		}
		if(is_user($emailA))
		{
			//echo "User in Database";
			$_POST['btnRegister'] = "";
			
		}
		else if(alt_register_user($fName, $lName, $emailA, $pswd))
		{
			$_SESSION['active_user'] = return_user_ID($emailA);
			//echo "Success Reg!";
			//header("Location: index.php");
			$_POST['btnRegister'] = "";
		}
		*/
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

    <title>Project Infinity | Welcome</title>

    <!-- Bootstrap core CSS -->
    <link href="../autumn/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../autumn/assets/jumbotron.css" rel="stylesheet">

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
      <div class="container">
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
          <form name='frmLogin' action='index.php' class='navbar-form navbar-right' method='post' />
            <div class="form-group">
              <input type="text" name="tbEmail" placeholder="E-Mail" class="form-control" />
            </div>
            <div class="form-group">
              <input type="password" name="tbPswd" placeholder="Password" class="form-control" />
            </div>
            <button type="submit" name="btnLogin" class="btn btn-success" value='login'>Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Welcome to <string style="font-family: titleFont;">INFINITY</string></h1>
        <p>Running is more than just a workout. It's a way of life.</p>
        <form class="form-horizontal" name ='frmRegister' action ='index.php' method ='post' role="form">
          <div class="form-group">
          	<div class="col-sm-5"><input type="text" name="tbFName" class="form-control" placeholder="First Name" id="inputText3" value="<?php echo $_POST['tbFName'] ;?>" required /></div>
          	<div class="col-sm-5"><input type="text" name="tbLName"  class="form-control" placeholder="Last Name" id="inputText3" value="<?php echo $_POST['tbLName'] ;?>" required /></div>
          </div>
          <div class="form-group">
            <div class="col-sm-10"><input type="email" name="tbEmailR" class="form-control" placeholder="E-Mail Address" required /></div>
          </div>
          <div class="form-group">
            <div class="col-sm-10"><input type="email" name="tbEmailConfirm" class="form-control" placeholder="Confirm E-Mail Address" required /></div>
          </div>
          <div class="form-group">
            <div class="col-sm-10"><input type="password" name="tbPswdR" class="form-control" placeholder="Your Password" required /></div>
          </div>
          <div class="form-group">
            <div class="col-sm-10"><button type="submit" name="btnRegister" class="btn btn-primary btn-lg" value='register'>Register &raquo;</button></div>
          </div>
        </form>
      </div>
    </div>
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Track Workouts</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Compete with Friends</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Set Goals</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../autumn/assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../autumn/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

