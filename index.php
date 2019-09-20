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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Roboto:500');
    body{
      background-image: linear-gradient(90deg,#db004d 0, #94103e);
      font-family: Roboto;
      color: white;
      font-size: 100%;
    }
    .card{
      background-color: transparent;
      border-width: thin;
      border-color: white;
    }
    span{
      color: white;
      font-style: italic;
    }
    small a{
      color: white;
    }
    small a:hover{
      text-decoration-line: underline;
      text-decoration-style: solid;
      text-decoration-color: white;
      color: #03fcfa;
    }
  </style>
</head>
<body>
  <div class="container"><br>
    <div class="card" style="width: 23rem;" id="signup">
      <form class="card-body" name="login" onsubmit="return validateForm()" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <h3 class="card-title">Register</h3>
        <div class="card-text">
          <div class="form-group">
            <label for="Name">Name</label> 
            <input type="text" class="form-control" id="Name" name="name" maxlength="10" required><small class="form-text text-muted"><span id="names"></span></small>
          </div>
          <div class="form-group">
            <label for="email">Email</label> 
            <input type="email" class="form-control" name="email" id="email" required><small class="form-text text-muted"><span id="emails"></span></small>
          </div>
          <div class="form-group">
            <label for="conemail">Confirm Email</label> 
            <input type="email" class="form-control" name="conemail" id="conemail" required><small class="form-text text-muted"><span id="emailsc"></span></small>
          </div>
          <div class="form-group">
            <label for="pass">Password</label> 
            <input type="password" class="form-control" name="password" minlength="2" maxlength="16" id="pass" required><small class="form-text text-muted"><span id="passc"></span></small>
          </div>
          <div class="form-group">
            <label for="conpass">Confirm Password</label>
            <input type="password" class="form-control" name="confirm" minlength="2" maxlength="16" id="conpass" required><small class="form-text text-muted"><span id="passcn"></span></small>
          </div>
        </div>
        <input type="submit" class="btn btn-outline-primary" name="signin" value="SignUp">
        <small class="form-text text-muted swap"><a href="#" onclick="swap('login')">Already registered?</a></small>
      </form>
    </div><br><br>
     <div class="card" style="width: 23rem; display: none;" id="login">
      <form method="post" class="card-body" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <h3 class="card-title">Log In</h3>
        <div class="form-group">
          <label for="emaillog">Email</label> 
          <input type="email" class="form-control" name="emaillog" id="emaillog" required> <small class="form-text text-muted"><span id="emaillg"></span></small>
        </div>
        <div class="form-group">
          <label for="passwordlog">Password</label> 
          <input type="password" class="form-control" name="passwordlog" id="passwordlog" minlength="2" maxlength="16" required> <small class="form-text text-muted"><span id="passlg"></span></small><br>
        </div>
        <input type="submit" class="btn btn-outline-success" name="login" value="log In">
        <small class="form-text text-muted swap"><a href="#" onclick="swap('signup')">Not Registered?</a></small>
      </form>
    </div>
  </div>
  
  <script type="text/javascript">
    function swap(id){
      if (id == 'login') {
        document.getElementById('signup').style.display = "none";
        document.getElementById('login').style.display = "block";
      }
      else{
        document.getElementById('signup').style.display = "block";
        document.getElementById('login').style.display = "none";
      }
    }
   
    function validateForm(){

      // name validation
      var name = document.forms['login']['name'].value;
      var c = 0;
      var nameRegex = /.*(?=.*?[0-9]).*/g;
      var nameFlag = 0;
      if(!name.match(nameRegex)){
        for (var i = name.length - 1; i >= 0; i--) {
        if(name[i] == ' ')
            c += 1;
        }  
        if (c>2) {
        document.getElementById('names').innerHTML = 'More than 2 spaces are not allowed';
        }
        else {
          nameFlag = 1;
          document.getElementById('names').innerHTML = '';
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
          document.getElementById('emailsc').innerHTML = "";
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
          document.getElementById('passcn').innerHTML = "";
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