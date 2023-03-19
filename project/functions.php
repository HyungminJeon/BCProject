<?php

	function connect(){
		$pdo = new PDO('sqlite:php.db');
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		if(!$pdo){
			$error = $pdo->errorInfo();
			$error_date = date("F j, Y, g:i a");
			$message = "{$error[2]} | {$error_date} \r\n";
			file_put_contents("db-log.txt", $message, FILE_APPEND);
			return false;
		}else{
			return $pdo;	
		}
	}
	
	function getUsersData(){
		$pdo = connect();
	
		$stmt = $pdo->query('SELECT * FROM Users');
		$stmt->execute();
		$data = $stmt->fetchAll();
	
		if($data == NULL){
			return "No users found";
		}else{
			return $data;
		}
	}

	function registerUser($email, $username, $password, $confirm_password){
		$pdo = connect();
		
		$email = trim($email);
		$username = trim($username);
		$password = trim($password);
		$confirm_password = trim($confirm_password);

		$args = func_get_args();
		foreach ($args as $value) {
			if(empty($value)){
				return "All fields are required";
			}
		}

		foreach ($args as $value) {
			if(preg_match("/([<|>])/", $value)){
				return "<> characters are not allowed";
			}
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return "Email is not valid";
		}

		$stmt = $pdo->prepare('SELECT email FROM Users WHERE email = ?');
		$stmt->execute([$email]);
		$data = $stmt->fetch();
		if($data != NULL){
			return "Email already exists, please use a different username";
		}

		if(strlen($username) > 100){
			return "Username is to long";
		}

		$stmt = $pdo->prepare('SELECT username FROM Users WHERE username = ?');
		$stmt->execute([$username]);
		$data = $stmt->fetch();
		if($data != NULL){
			return "Username already exists, please use a different username";
		}

		if(strlen($password) > 255){
			return "Password is to long";
		}

		if($password != $confirm_password){
			return "Passwords don't match";
		}

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $pdo->prepare('INSERT INTO Users(username, password, email) VALUES(?,?,?)');
		$stmt->execute([$username, $hashed_password, $email]);
		if($stmt->rowCount() != 1){
			return "An error occurred. Please try again";
		}else{
			header("location: login.php");
			return "success";			
		}
	}

	function loginUser($username, $password){
		session_start();
		$pdo = connect();
		$username = trim($username);
		$password = trim($password);
		
		if($username == "" || $password == ""){
			return "Both fields are required";
		}
	
		$username = filter_var($username, FILTER_SANITIZE_STRING);
		$password = filter_var($password, FILTER_SANITIZE_STRING);
	
		$sql = "SELECT username, password FROM Users WHERE username = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$username]);
		$data = $stmt->fetch();
	
		if($data == NULL){
			// Save failed login attempt
			$attemptresult = "Wrong username or password";
			$stmt = $pdo->prepare('INSERT INTO LoginAttempt(username, time, attemptresult) VALUES(?,?,?)');
			$stmt->execute([$username, date("Y-m-d H:i:s"), $attemptresult]);
			return "Wrong username or password";
		}
	
		if(password_verify($password, $data["password"]) == FALSE){
			// Save failed login attempt
			$attemptresult = "Wrong username or password";
			$stmt = $pdo->prepare('INSERT INTO LoginAttempt(username, time, attemptresult) VALUES(?,?,?)');
			$stmt->execute([$username, date("Y-m-d H:i:s"), $attemptresult]);
			return "Wrong username or password";
		}else{
			$_SESSION["username"] = $username;
			$_SESSION['logged_in'] = true;
			header("Location: dashboard.php");
			// Save successful login attempt
			$attemptresult = "Success";
			$stmt = $pdo->prepare('INSERT INTO LoginAttempt(username, time, attemptresult) VALUES(?,?,?)');
			$stmt->execute([$username, date("Y-m-d H:i:s"), $attemptresult]);
			exit();
		}
	}

	function saveTicTacToeResult($gameName, $username, $win, $lose, $tie){
		$pdo = connect();
	
		$stmt = $pdo->prepare('INSERT INTO Tictactoe(gamename, username, win, lose, tie, time) VALUES(?,?,?,?,?,?)');
		$stmt->execute([$gameName, $username, $win, $lose, $tie, date("Y-m-d H:i:s")]);
	
		if($stmt->rowCount() != 1){
			$error = $stmt->errorInfo();
			$error_date = date("F j, Y, g:i a");
			$message = "{$error[2]} | {$error_date} \r\n";
			file_put_contents("db-log.txt", $message, FILE_APPEND);
			return "An error occurred. Please try again";
		}else{
			return "success";			
		}
	}

	function updateGameStatistics($gamename, $username) {
		$pdo = connect();
		
		$stmt = $pdo->prepare('SELECT COUNT(*) AS total, SUM(win) AS wins, SUM(lose) AS loses FROM Tictactoe WHERE gamename = ? AND username = ?');
		$stmt->execute([$gamename, $username]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$winrate = $result['total'] > 0 ? $result['wins'] / $result['total'] : 0;
		$loserate = $result['total'] > 0 ? $result['loses'] / $result['total'] : 0;
		
		$stmt = $pdo->prepare('INSERT INTO GameStatistics (gamename, username, winrate, loserate) 
								VALUES (?, ?, ?, ?) 
								ON CONFLICT(username) DO UPDATE SET 
									winrate=EXCLUDED.winrate, 
									loserate=EXCLUDED.loserate 
								WHERE winrate != EXCLUDED.winrate OR loserate != EXCLUDED.loserate');
		$stmt->execute([$gamename, $username, $winrate, $loserate]);
	}

	function logoutUser(){
		session_destroy();
		header("location: login.php");
		exit();
	}
	