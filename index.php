<?php

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$db = "authentication";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

$name_err = $username_err =  "";

function insert($name, $email, $pass){
    $sql = "INSERT INTO users (username, emailid, password) VALUES (?, ? ,?)"; 
    if ($stmt = mysqli_prepare($GLOBALS['conn'], $sql)) {
      mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $pass);
      mysqli_stmt_execute($stmt);
      echo "<script type = \"text/javascript\">alert('You are now registered');</script>";
    }
    $_SESSION['message'] = "u r logged in";
    mysqli_stmt_close($stmt);               
}

function retrieve($email){

  $email_query = "SELECT * FROM users WHERE emailid = ?";

  if ($emailstmt = mysqli_prepare($GLOBALS['conn'], $email_query)) {
    mysqli_stmt_bind_param($emailstmt, 's', $email);
    mysqli_stmt_execute($emailstmt);
    $email_result = mysqli_stmt_get_result($emailstmt);
    $emailrow = mysqli_fetch_array($email_result, MYSQLI_BOTH);
    if ($emailrow['emailid'] == $email) {
      return true;     
    }
    else{
      return false;
    }
  }
}

if (isset($_POST['signin'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $pass = md5($pass); //hashing 

    $res = retrieve($email);
    if ($res) {
      echo "<script type = \"text/javascript\">alert('Email Id already registered');</script>";
    }
    else{
      insert($name, $email, $pass);
    }
}

function retrieveLogIn($email, $pass){
  $sql = "SELECT * FROM users WHERE emailid = ? and password = ?";

  if ($stmt = mysqli_prepare($GLOBALS['conn'], $sql)) {
    # code...
    mysqli_stmt_bind_param($stmt, 'ss', $email, $pass );
    
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    if ($row['emailid'] == $email && $row['password'] == $pass) {
      # code...
      return true;
    }
    else{
      return false;
    }
      
      }
}

if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($conn, $_POST['emaillog']);
  $pass = mysqli_real_escape_string($conn, $_POST['passwordlog']);
  $pass = md5($pass); //hashing 

    $res = retrieveLogIn($email, $pass);
    if ($res) {
      echo "<script type = \"text/javascript\">alert('Log In successful');</script>";
    }
    else{
      echo "<script type = \"text/javascript\">alert('Log In unsuccessful');</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Samex</title>
</head>
<body>
  <div id="signup">
    <h3>Register</h3><br>
    <form name="login" onsubmit="return validateForm()" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
      Name <input type="text" name="name" maxlength="10" required> <span id="names"></span><br>
      Email <input type="email" name="email" id="email" required> <span id="emails"></span><br>
      Confirm Email <input type="email" name="conemail" id="conemail" required> <span id="emailsc"></span><br>
      Password <input type="password" name="password" minlength="2" maxlength="16" id="pass" required> <span id="passc"></span><br>
      Confirm Password <input type="password" name="confirm" minlength="2" maxlength="16" id="conpass" required> <span id="passcn"></span><br>
      <input type="submit" name="signin" value="SignUp">
    </form>
  </div><br><br>
   <div id="login">
    <h3>Log In</h3><br>
    <form name="logIn" onsubmit="return validateFormLog()" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
      Email <input type="email" name="emaillog" required> <span id="emaillg"></span><br>
      Password <input type="password" name="passwordlog" minlength="2" maxlength="16" required> <span id="passlg"></span><br>
      <input type="submit" name="login" value="log In">
    </form>
  </div>
  <script type="text/javascript">
   
    function validateForm(){

      // name validation
      var name = document.forms['login']['name'].value;
      var c = 0;
      var nameRegex = /([a-zA-z ]+)/g;
      var nameFlag = 0;
      if(name.match(nameRegex)){
        for (var i = name.length - 1; i >= 0; i--) {
        if(name[i] == ' ')
            c += 1;
        }  
        if (c>2) {
        document.getElementById('names').innerHTML = 'More than 2 spaces are not allowed';
        }
        else {
          nameFlag = 1;
        }
      }
      else{
        document.getElementById('names').innerHTML = 'Only Alphabets And 2 spaces are allowed';
      }
      

      // email validation
      var email = document.forms['login']['email'].value;
      var conemail = document.forms['login']['conemail'].value;
      var emailFlag = 0;
      if (email == conemail) {
        if (email.includes("@samex")) {
          emailFlag = 1;  
        }
        else{
          document.getElementById('emails').innerHTML = "Email must have @samex";
        }
      }
      else {
        document.getElementById('emailsc').innerHTML = "Email id did not match";
      }

      // password validation
      var password = document.forms['login']['password'].value;
      var confirm = document.forms['login']['confirm'].value;
      var regex = /(?=.*?[A-Z])(?=.*?[0-9]){2,}.*\$$/g;
      var passwordFlag = 0;
      if (password.match(regex)) {
        if (password == confirm) {
          passwordFlag = 1;
        }
        else{
          document.getElementById('passcn').innerHTML = "Password not matching";
        }
      }
      else {
        document.getElementById('passc').innerHTML = "Does not staisfy the password condition(One upper case, 2 numbers and $ at the end of password must be used)";
      }

      // return 
      if (!(nameFlag && emailFlag && passwordFlag)) {
        return false;
      }
    }
  </script>
</body>
</html>