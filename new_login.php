<?php 

	$servername = "localhost";
	$username = "root";
	$password = "";
	$db = "registration";

	$conn = mysqli_connect($servername, $username, $password, $db);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		# code...
		$email = mysqli_real_escape_string($conn, $_POST["email"]);
		$user_password = mysqli_real_escape_string($conn, $_POST["password"]);
		$user_password = md5($user_password);

		$sql = "SELECT * FROM user_details WHERE emailid = ? and password = ?";

		if ($stmt = mysqli_prepare($conn, $sql)) {
			# code...
			mysqli_stmt_bind_param($stmt, 'ss', $email, $user_password );
			
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_fetch_array($result, MYSQLI_BOTH);
			if ($row['emailid'] == $email && $row['password'] == $user_password) {
				# code...
				echo "success";
			}
			else{
				echo "fail";
			}
				
				}
			
	}

?>



<!DOCTYPE html>
<html>
<head>
	<title>login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>

	<div class="centered_container">
		<div class="row" id="tabs">
			<div class="col">Login</div>
			<div class="col"><a href="./new_register.php">Register</a></div>
		</div>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label> *
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Password</label> *
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
    </div>

  <button type="submit" id="submit" name="button">Submit</button>
</form>
	</div>
</body>
</html>