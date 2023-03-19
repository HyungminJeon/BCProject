<?php 
	require "functions.php";
	if(isset($_POST['submit'])){
		$response = loginUser($_POST['username'], $_POST['password']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<title>Secure login system with php and mysql</title>
</head>
<body>
	<form action="" method="post" autocomplete="off">
		<h2>Sign in</h2>
		<h4>Please fill this form to sign in to your account.</h4>
		
		<div class="grid">
			<div>
				<label>Username</label>
				<input type="text" name="username" value="<?php echo @$_POST['username']; ?>">
			</div>

			<div>
				<label>Password</label>
				<input type="text" name="password" value="<?php echo @$_POST['password']; ?>">
			</div>
		</div>
		
		<button type="submit" name="submit">Submit</button>	

		<p>
			Don't have an account? 
			<a href="index.php">Create here</a>
		</p>

		<p class="error"><?php echo @$response; ?></p>
	</form>
</body>
</html>
