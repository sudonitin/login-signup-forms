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
    $pass = md5($pass); //hashing 
    $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)"; 
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, 'sss', $name,  $email, $pass);
      mysqli_stmt_execute($stmt);
      $_SESSION['logged_in'] = "active";
      $_SESSION['email'] = $email;
      $_SESSION['username'] = $name;
      return true;
    }
    else{
      return false;
    }
                
}

function retrieve($email){

  $email_query = "SELECT * FROM users WHERE email = ?";

  if ($emailstmt = mysqli_prepare($conn, $email_query)) {
    mysqli_stmt_bind_param($emailstmt, 's', $email);
    mysqli_stmt_execute($emailstmt);
    $email_result = mysqli_stmt_get_result($emailstmt);
    $emailrow = mysqli_fetch_array($email_result, MYSQLI_BOTH);
    if ($emailrow['email'] == $email) {
      return true;     
    }
    else{
      return false;
    }
  }
}

//sigup code
// if (isset($_POST['signin'])) {
//   //session_start();

//   $name = mysqli_real_escape_string($conn, $_POST['name']);
//   $email = mysqli_real_escape_string($conn, $_POST['email']);
//   $pass = mysqli_real_escape_string($conn, $_POST['password']);
//   $conpass = mysqli_real_escape_string($conn, $_POST['confirm']);
//   echo $name;
//   echo $email;

//      if(!empty(trim($_POST["name"]))){
//           $first = test_input($name);
//           if (!preg_match("/^[a-zA-Z ]*$/",$first)) {
//             $name_err = "Only letters and spaces are allowed";
//           }
//     }


//     // if (empty($name_err)) {
//     //   $res = retrieve($email);
//     //   // $email_query = "SELECT * FROM users WHERE email = ?";

//     //   // if ($emailstmt = mysqli_prepare($conn, $email_query)) {
//     //   //   mysqli_stmt_bind_param($emailstmt, 's', $email);
//     //   //   mysqli_stmt_execute($emailstmt);
//     //   //   $email_result = mysqli_stmt_get_result($emailstmt);
//     //   //   $emailrow = mysqli_fetch_array($email_result, MYSQLI_BOTH);
//     //   //   if ($emailrow['email'] == $email) {
//     //   if ($res) {
//     //       echo "
//     //         <script type = \"text/javascript\">
//     //           alert('Email is already registered.');
//     //         </script>
//     //         ";        
//     //     }
//     //     else{

//     //       if ($pass == $conpass) {
//     //             $res2 = insert($name, $email, $pass);
//     //             if ($res2) {
//     //               echo "<script type = \"text/javascript\">alert('welcome, u have successfully registered..!!');</script>";            
//     //             }
//     //             // $pass = md5($pass); //hashing 
//     //             // $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)"; 
//     //             // if ($stmt = mysqli_prepare($conn, $sql)) {
//     //             //   mysqli_stmt_bind_param($stmt, 'sss', $name,  $email, $pass);
//     //             //   mysqli_stmt_execute($stmt);
//     //             //   $_SESSION['logged_in'] = "active";
//     //             //   $_SESSION['email'] = $email;
//     //             //   $_SESSION['username'] = $name;
//     //             //   // $_SESSION['id'] = $row['usrid'];
//     //             //   echo "<script type = \"text/javascript\">alert('welcome, u have successfully registered..!!');</script>";            
//     //             //   header("location: ./user-index.php");
//     //             // }
//     //             // $id_query = "SELECT * FROM users WHERE username = ?";

//     //             // if ($idstmt = mysqli_prepare($conn, $id_query)) {
//     //             //   mysqli_stmt_bind_param($idstmt, 's', $name);
//     //             //   mysqli_stmt_execute($idstmt);
//     //             //   $id_result = mysqli_stmt_get_result($idstmt);
//     //             //   $idrow = mysqli_fetch_array($id_result, MYSQLI_BOTH);
//     //             //   $_SESSION['id'] = $idrow['usrid'];
//     //             //       mysqli_stmt_close($stmt);
//     //             //     }
//     //         }
//     //             else{
//     //               echo "<script type = \"text/javascript\">alert('passwords did not match.');</script>";  
//     //               }
//     // }
//     //  // }
      
//     // }

// }

if (isset($_POST['signin'])) {
  # code...
  //session_start();
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = mysqli_real_escape_string($conn, $_POST['password']);
  $pass2 = mysqli_real_escape_string($conn, $_POST['confirm']);

  if ($pass == $pass2) {
    # code...
    $pass = md5($pass); //hashing 
    $sql = "INSERT INTO users (username, emailid, password) VALUES (?, ? ,?)"; //do not miss the inverted commas of VALUE
    //mysqli_query($conn, $sql);
    if ($stmt = mysqli_prepare($conn, $sql)) {
      # code...
      mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $pass);
      mysqli_stmt_execute($stmt);
    }
    $_SESSION['message'] = "u r logged in";
    mysqli_stmt_close($stmt);
    //$_SESSION['username'] = $name;
    //header("location: home.php")
  }
  else{
    $_SESSION['message'] = "passwords didnt match"; 
  }
  //mysql_real_escape_string(unescaped_string)
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
      Name <input type="text" name="name" maxlength="10" required="hello"> <span id="names"></span><br>
      Email <input type="email" name="email" id="email" required> <span id="emails"></span><br>
      Confirm Email <input type="email" name="conemail" id="conemail" required> <span id="emailsc"></span><br>
      Password <input type="password" name="password" minlength="8" maxlength="16" id="pass" required> <span></span><br>
      Confirm Password <input type="password" name="confirm" minlength="8" maxlength="16" id="conpass" required> <span></span><br>
      <input type="submit" name="signin">
    </form>
  </div>
  <script type="text/javascript">
    function validateForm(){
      var name = document.forms['login']['name'].value;
      var c = 0;
      for (var i = name.length - 1; i >= 0; i--) {
        if(name[i] == ' ')
          c += 1;
      }
      if (c>2) {
        document.getElementById('names').innerHTML = 'More than 2 spaces are not allowed';
      }

      var email = document.forms['login']['email'].value;
      var conemail = document.forms['login']['conemail'].value;
      if (email == conemail) {
        if (email.includes("@samex")) {
          return true;  
        }
        else{
          document.getElementById('emails').innerHTML = "Email must have @samex"
        }
      }
      else {
        document.getElementById('emaisc').innerHTML = "Email id did not match"
      }

      var password = document.forms['login']['password'].value;
      var confirm = document.forms['login']['confirm'].value;


    }
  </script>
</body>
</html>