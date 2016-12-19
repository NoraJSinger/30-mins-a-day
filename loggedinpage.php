  <?php
      
        session_start();

        $diaryContent = "";

        if (array_key_exists("id", $_COOKIE) && $_COOKIE['id']) {

             $_SESSION['id'] = $_COOKIE['id'];

        }

        if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {

            include("connection.php");
           

            $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
            $row =  mysqli_fetch_array(mysqli_query($link, $query));
            $diaryContent = $row['diary'];
            $query = "SELECT email FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
            $row =  mysqli_fetch_array(mysqli_query($link, $query));
            $email = $row['email'];
        } else{
                 header("Location: index.php");
           }

 
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
      
    <link rel = "stylesheet" href = "http://79.170.44.222/norazindahouse.com/diary/styles.css">
      
  </head>
  <body>
      
      <nav class="navbar navbar-light bg-faded">
          <a class="navbar-brand" href="#"><h1> 30 Minutes a Day</h1></a>
          
          <div id="timer">
            Time Left:<input id="minutes" type="text" style="width: 24px; border: none; background-color:none; font-size: 16px; font-weight: bold;">:<input id="seconds" type="text" style="width: 24px; border: none; background-color:none; font-size: 16px; font-weight: bold;">
          </div>
          
          <div id="buttondiv"><a id="showLine" class="btn btn-success-outline" href=''>Get a writing prompt</a></div>
     
          <div id="triviadiv">
              <p id="trivia" ></p>
          </div>
          
          
          
          <div id="logoutbtn" class="btn btn-success-outline" > 
              
              <a  href='index.php?logout=1' ><?php echo $email; ?>   <strong>Logout</strong></a> 
          </div>    
      </nav>

      


      
      

<div  class="container-fluid" id="ContainerLoggedIn" >
    <textarea id="diary" class="form-control"> <?php echo $diaryContent; ?> </textarea>
</div>



<footer class="footer">

    
        <p>&copy; Nora Singer 2016</p>
    
   

</footer>
      

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
      
<script type="text/javascript">
      
     $('#diary').bind('input propertychange', function() {

                $.ajax({
                  method: "POST",
                  url: "updatedatabase.php",
                  data: { content: $("#diary").val() }
                });
             

            });
          
        
        
      
</script>

      
<script type="text/javascript">
   // set minutes
var mins = 30;

// calculate the seconds (don't change this! unless time progresses at a different speed for you...)
var secs = mins * 60;
function countdown() {
    setTimeout('Decrement()',1000);
}
function Decrement() {
    if (document.getElementById) {
        secs--;

        minutes = document.getElementById("minutes");
        seconds = document.getElementById("seconds");

        if (secs <= 0) {
            seconds.value = 0;
            alert("WELL DONE! Come again tomorrow!");
        }
        else {
            // if less than a minute remaining
            if (secs < 59) {
                seconds.value = secs;
            } else {
                minutes.value = getminutes();
                seconds.value = getseconds();
            }
            setTimeout('Decrement()',1000);
        }
    }
}
function getminutes() {
    // minutes is seconds divided by 60, rounded down
    mins = Math.floor(secs / 60);
    return mins;
}
function getseconds() {
    // take mins remaining (as seconds) away from total seconds remaining
    return secs-Math.round(mins *60);
}

countdown();
      
</script> 
      
<script>
   var lines;
    var randomNumber;
    var lastRandomNumber;
    
    $(document.body).ready(function () {
      
      // load the trivia from the server
      $.ajax({
        url: 'trivia.txt'
      }).done(function(content) {
        
        // normalize the line breaks, then split into lines
        lines = content.replace(/\r\n|\r/g, '\n').trim().split('\n');
        
        // only set up the click handler if there were lines found
        if (lines && lines.length) {
          $('#showLine').on('click', function () {
            // loop to prevent repeating the last random number
            while (randomNumber === lastRandomNumber) {
              randomNumber = parseInt(Math.random() * lines.length);
              // check to prevent infinite loop
              if (lines.length === 1) { break; }
            }
            // keep track of the last random number
            lastRandomNumber = randomNumber;
            
            // show the corresponding line
            $('#trivia').text(lines[randomNumber]);
          });
        }
      });
    });
</script>
      



