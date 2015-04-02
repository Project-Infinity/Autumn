/*
	Javascript Document for
	I211 FINAL PROJECT
	This has a few JavaScript functions mostly transitional
	JQuery ones but also has the core AJAX functions too
*/

/*$(function() {
    $( document ).tooltip();
}); */
  
function echoBack(userID)
{
	  if (window.XMLHttpRequest)
  {
	  // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE6
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  
  
  xmlhttp.onreadystatechange=function()
  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
		  //Change the 'tweetbox' innerHTML to whatever PHP gave us.
		  document.getElementById("dialogBox").innerHTML=xmlhttp.responseText;
		  //document.getElementById("dialogBox").title="Order "+orderID+"'s Details";
	  
	  }//end of if
  }
  
  // We're going to open a connection to the server, give it a request, and let it do its thing.
  xmlhttp.open("GET","group.php?userID="+userID,true);
  
  xmlhttp.send();
  
  //BEGIN JQUERY
  $("#dialogBox").dialog
  ({
	  show: {
        effect: "fold",
        duration: 1000
      },
      hide: {
        effect: "fold",
        duration: 1000
      },
	  draggable: true,
	  width:600,
      modal: true,
	  closeText: "Close Window",
	  closeOnEscape: true,
	  resizable: false
   });
           
}
/*
	These functions are JQuery intensive and the JQuery is 
	Mostly used for transitions as well as 
	getting the dialog boxes for the Member Profile
	and Order Recipt
*/
function show_profile()
{
  $("#profileEdit").dialog
  ({
      draggable: true,
	  width:600,
      modal: true,
	  closeText: "Close Window",
	  closeOnEscape: true,
	  resizable: false,
	  show: {
        effect: "blind",
        duration: 1000
      },
	  hide: {
		effect: "blind",
		duration: 1000  
	  }
   });	
}

function show_hide(key)
{
	switch(key)
	{
		case "Show Filter":
			showFilter();
			action = "Hide Filter";
			break;
		case "Hide Filter":
			hideFilter();
			action = "Show Filter";
			break;
	}
	
	return action;
		
}

function hideFilter()
{
  $("#filterBox").hide("blind", 500)
}

function showFilter()
{
  $("#filterBox").show("blind", 500)
}