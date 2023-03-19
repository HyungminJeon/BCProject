
<?php
require "functions.php";
// Start session
session_start();

$username = $_SESSION["username"];
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to sign in page if not logged in
    header("location: login.php");
    exit;
}
?>

<script>
const username = '<?php echo $username; ?>';
</script>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
      body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
      }
      
      #container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
      }
      
      h1, h2, h3, h4, h5, h6 {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bold;
        text-align: center;
        margin: 0;
        padding: 0;
      }
      
      .card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        margin: 10px;
        padding: 20px;
        text-align: center;
        width: 300px;
        max-width: 100%;
        overflow: hidden;
      }
      
      .card h3 {
        color: #444;
        font-size: 24px;
        margin-bottom: 10px;
      }
      
      .card img {
        border-radius: 10px;
        width: 100%;
        height: auto;
        margin-bottom: 10px;
      }

      button {
            font-size: 18px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
  </head>
  <body>
    <div id="container">
        <h1>Hello <?php echo $username; ?>, welcome to the game dashboard!</h1>
        <div class="card">
            <h3>Tic-Tac-Toe game</h3>
            <img src="./tictactoe.PNG" alt="Tic-Tac-Toe">
            <a href="tictactoe.php">Play Now</a>
        </div>
        <div class="card">
            <h3>Rock, Paper, Scissors game</h3>
            <img src="./rockpaperscissors.PNG" alt="Rock, Paper, Scissors">
            <a href="#">Coming Soon</a>
        </div>
        <form method="post" action="login.php">
            <button type="submit" onclick="logout()">Log out</button>
        </form>
    </div>
  </body>
</html>

