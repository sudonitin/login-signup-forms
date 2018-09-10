<?php

	session_start();

$servername = "localhost";
$username = "root";
$password = "";
$db = "registration";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

$firstname_err = $lastname_err =  "";


if (isset($_POST['button'])) {
	# code...
	//session_start();

	 

	$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$pass = mysqli_real_escape_string($conn, $_POST['password']);
	$conpass = mysqli_real_escape_string($conn, $_POST['confirmpassword']);


	   if(!empty(trim($_POST["firstname"]))){
        	$first = test_input($firstname);
        	if (!preg_match("/^[a-zA-Z ]*$/",$first)) {
        		# code...
        		$firstname_err = "Only letters and spaces are allowed";
        	}
    }

    	if(!empty(trim($_POST["lastname"]))){
        	$last = test_input($lastname);
        	if (!preg_match("/^[a-zA-Z ]*$/",$last)) {
        		# code...
        		$lastname_err = "Only letters and spaces are allowed";
        	}
    }



		if (empty($firstname_err) && empty($lastname_err)) {
			# code...
			$email_query = "SELECT * FROM user_details WHERE emailid = ?";

			if ($emailstmt = mysqli_prepare($conn, $email_query)) {
				# code...
				mysqli_stmt_bind_param($emailstmt, 's', $email);
				
				mysqli_stmt_execute($emailstmt);

				$email_result = mysqli_stmt_get_result($emailstmt);
				$emailrow = mysqli_fetch_array($email_result, MYSQLI_BOTH);
				if ($emailrow['emailid'] == $email) {
					# code...
					echo "Email is already registered.";
				    mysqli_close($conn);
				    exit();
						
				}
				else{
					
				if ($pass == $conpass) {
			# code...
					$pass = md5($pass); //hashing 
					$sql = "INSERT INTO user_details (first_name, last_name, emailid, password) VALUES (?,?,?,?)"; 
					if ($stmt = mysqli_prepare($conn, $sql)) {
						# code...
						mysqli_stmt_bind_param($stmt, 'ssss', $firstname, $lastname, $email, $pass);
						mysqli_stmt_execute($stmt);
					}
					mysqli_stmt_close($stmt);
				}
					else{
						$_SESSION['message'] = "passwords didnt match";	
						}

				}
			}
			
		}

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>register</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="css/register.css">
</head>
<body>

	<div class="centered_container">
		<div class="row" id="tabs">
			<div class="col"><a href="./new_login.php">Login</a></div>
			<div class="col">Register</div>
		</div>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  	<div class="form-group row">
  	  	<div class="col">
    <label for="FirstName">First Name</label> *
    <input type="text" name="firstname" class="form-control" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;"
        onpaste="return false;" id="FirstName" placeholder="First Name" required><span id="error" style="font-size: 80%; color: Red; display: none">* Only Alphabets are allowed</span>


		</div>
		<div class="col">
	<label for="LastName">Last Name</label> *
    <input type="text" name="lastname" class="form-control" id="LastName" placeholder="Last Name" required onkeypress="return IsAlphaNumeric(event);" ondrop="return false;"
        onpaste="return false;" />
    <span id="error" style="font-size: 80%; color: Red; display: none">* Only Alphabets are allowed</span>
		</div>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label> *
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Password</label> *
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
    </div>

      <div class="form-group">
    <label for="exampleInputPassword2">Confirm Password</label> *
    <input type="password" name="confirmpassword" class="form-control" id="exampleInputPassword2" placeholder="Re-Type Password" required>
  </div>
  
  <button type="submit" id="submit" name="button">Submit</button>
</form>
	</div>

	<script type="text/javascript">
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        function IsAlphaNumeric(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            document.getElementById("error").style.display = ret ? "none" : "inline";
            return ret;
        }
    </script>
</body>
</html>