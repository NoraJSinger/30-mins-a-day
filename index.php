<?php

    session_start();

    $error = ""; 

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION['id']);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        include("connection.php");
        
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } 
                else 
                {

                    $query = "INSERT INTO users (`email`) VALUES('".mysqli_real_escape_string($link, $_POST["email"])."')";


                    if (mysqli_query($link, $query)) {
                        
                        $id = mysqli_insert_id($link);

                        $password = md5(md5($id).mysqli_real_escape_string($link, $_POST["password"]));

                        
                       $query = "UPDATE `users` SET password = '$password' WHERE id = '$id' LIMIT 1";
                        
                        if ( mysqli_query($link, $query) ) 
                        {
                            if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                        }  
                        }
                         
                        $_SESSION['id'] = $id;
                        header("Location: loggedinpage.php");

                    } else {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if ($_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 

                            header("Location: loggedinpage.php");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else {
                        
                        $error = "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }


?>


<?php include("header.php"); ?>
          
<div class="container" id="homePageContainer">
    
    <p><strong>Never seem to have time to write? Everyone can do it for 30 minutes a day. Lose yourself in WORDS, I'll time you. Good luck! </strong></p>
    
    <div id="error"><?php echo $error; ?></div>
    
   

    <form method="post" id="signUpForm">
        <div class="form-group">
            <input id="inp" class="form-control" type="email" name="email" placeholder="Your Email">
        </div>

        <div class="form-group">
            <input id="inp" class="form-control" type="password" name="password" placeholder="Password">
        </div>

        <div class="form-check">
            <label class="form-check-label">
                <input  type="checkbox" name="stayLoggedIn" value=1>
            Stay logged in
        </label>   
        </div>

        <div class="form-group">
            <input id="inp" class="form-control" type="hidden" name="signUp" value="1">
        
            <input id="button" class="btn btn-success" type="submit" name="submit" value="Sign Up!">
        </div>
        <p><a  class="toggleForms">Log in</a></p>

    </form>
    
    
    

    <form method="post" id="logInForm">

        <div class="form-group">
        <input id="inp" class="form-control" type="email" name="email" placeholder="Your Email">
        </div>

        <div class="form-group">

        <input id="inp" class="form-control" type="password" name="password" placeholder="Password">
            </div>

        <div class="form-check">
            <label class="form-check-label">
                <input  type="checkbox" name="stayLoggedIn" value=1>
            Stay logged in
        </label>   
        </div>

        <div class="form-group">

        <input class="form-control" type="hidden" name="signUp" value="0">
            
        <input class="btn btn-success" type="submit" name="submit" value="Log In!">
            </div>

        <p><a  class="toggleForms">Sign Up</a></p>
        
    </form>
</div>
    
<footer class="footer">

    
        <p>&copy; Nora Singer 2016</p>
    
   

</footer>
      

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
      
    <script type="text/javascript">
      
        $(".toggleForms").click(function() {
            
            $("#signUpForm").toggle();
            $("#logInForm").toggle();
            
            
        });
        
        
        
      </script>

        

  </body>
</html>








