
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

      .cards-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
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
        width: 400px;
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
        width: 300px; /* Set a fixed width */
        height: 300px; /* Set a fixed height */
        object-fit: contain; /* This will ensure the aspect ratio is maintained and the images fit within the dimensions without being cut off */
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
            border-radius: 12px;
        }

        .ranking-list-btn {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 0px;
        }

      .logout {
        border-radius: 0px;
      }

    </style>
  </head>
  <body>
    <div id="container">
        <h1>Hello <?php echo $username; ?></h1>
    </br>
        <h1>Welcome to the Playground!</h1>
    </br></br>
        <div class="cards-container">
        <div class="card">
            <h3>Tic-Tac-Toe</h3>
            <img src="./tic-tac-toe.gif" alt="Tic-Tac-Toe">
            
            <form method="post" action="tictactoe.php">
                <button type="submit" >Play Now</button>
            </form>
            <form method="post" action="tictactoe-ranking.php">
                <button type="submit" class="ranking-list-btn">Ranking List</button>
            </form>
        </div>
        <div class="card">
            <h3>Rock, Paper, Scissors</h3>
            <img src="./rock-paper-scissors.gif" alt="Rock, Paper, Scissors">
            <form method="post" action="rock-paper-scissors.php">
                <button type="submit" >Play Now</button>
            </form>
            <form method="post" action="rock-paper-scissors-ranking.php">
                <button type="submit" class="ranking-list-btn" >Ranking List</button>
            </form>
        </div>
        </div>
        <form method="post" action="login.php">
            <button type="submit" class="logout" onclick="logout()">Log out</button>
        </form>
    </div>
  </body>
</html>

