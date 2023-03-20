
<?php
require "functions.php";
session_start();
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rock, Paper, Scissors Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .game-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
        }
        h1 {
            margin-bottom: 2rem;
        }
        .choices {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .choice {
            cursor: pointer;
            margin: 0 1rem;
        }
        p {
            font-size: 1.1rem;
            margin: 0.5rem 0;
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
    <script src="rock-paper-scissors.js"></script>
</head>
<body>
    <div class="game-container">
        <h1>Rock, Paper, Scissors Game</h1>
        <h3>click the image of your choice</h3>
        <form method="post" id="game-form">
            <input type="hidden" name="choice" id="choice">
        </form>
        <div class="choices">
            <img src="rock.png" alt="Rock" class="choice" onclick="submitForm('rock')">
            <img src="paper.png" alt="Paper" class="choice" onclick="submitForm('paper')">
            <img src="scissors.png" alt="Scissors" class="choice" onclick="submitForm('scissors')">
        </div>
        <?php
        if (isset($_POST['choice'])) {
            $userChoice = $_POST['choice'];
            $choices = ['rock', 'paper', 'scissors'];
            $computerChoice = $choices[array_rand($choices)];

            echo "<h3>You chose: $userChoice</h3>";
            echo "<h3>Computer chose: $computerChoice</h3>";

            if ($userChoice === $computerChoice) {
                echo "<h1>It's a tie!</h1>";
                saveRockPaperScissorsResult('Rock Paper Scissors',$username,0,0,1);
            } else {
                $winningConditions = [
                    'rock' => 'scissors',
                    'paper' => 'rock',
                    'scissors' => 'paper',
                ];

                if ($winningConditions[$userChoice] === $computerChoice) {
                    echo "<h1>You win!</h1>";
                    saveRockPaperScissorsResult('Rock Paper Scissors',$username,1,0,0);
                    rockPaperScissorsUpdateGameStatistics('Rock Paper Scissors',$username);
                } else {
                    echo "<h1>You lose!</h1>";
                    saveRockPaperScissorsResult('Rock Paper Scissors',$username,0,1,0);
                    rockPaperScissorsUpdateGameStatistics('Rock Paper Scissors',$username);
                }
            }
        }
        ?>
         <form action="dashboard.php">
                <button type="submit">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>
