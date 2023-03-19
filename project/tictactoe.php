

<?php
require "functions.php";
session_start();
$username = $_SESSION["username"];
$winner = 'n';
$box = array('','','','','','','','','');


function placeO(&$box) {
    $emptyCells = array();
    for ($i = 0; $i < 9; $i++) {
        if ($box[$i] == '') {
            $emptyCells[] = $i;
        }
    }

    if (!empty($emptyCells)) {
        $randomIndex = $emptyCells[array_rand($emptyCells)];
        $box[$randomIndex] = 'o';
    }
}

function checkWinner($box, $mark) {
    $winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
    ];

    foreach ($winningCombinations as $combination) {
        if ($box[$combination[0]] == $mark &&
            $box[$combination[1]] == $mark &&
            $box[$combination[2]] == $mark) {
            return true;
        }
    }

    return false;
}


$response = "";
if (isset($_POST['submit'])) {

    for ($i = 0; $i < 9; $i++) {
        $box[$i] = $_POST["box$i"];
    }

    if (checkWinner($box, 'x')) {
        $winner = 'x';
        $response = saveTicTacToeResult('Tic Tac Toe', $username, 1,0,0);
        updateGameStatistics('Tic Tac Toe', $username);
    } else {
        placeO($box);

        if (checkWinner($box, 'o')) {
            $winner = 'o';
            $response = saveTicTacToeResult('Tic Tac Toe', $username, 0,1,0);
            updateGameStatistics('Tic Tac Toe', $username);
        } elseif (!in_array('', $box)) {
            $winner = 't';
            $response = saveTicTacToeResult('Tic Tac Toe', $username, 0,0,1);
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Tic Tac Toe</title>
    <script src="tictactoe.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .grid-container {
            display: inline-grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 60px;
            height: 60px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            font-size: 18px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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

        button:hover {
            background-color: #45a049;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="center">
        <div>
            <h1>Tic Tac Toe</h1>
            <form method="post" action="" onsubmit="return validateForm();">
                <div class="grid-container">
                    <?php for ($i = 0; $i < 9; $i++): ?>
                        <input type="text" name="box<?= $i ?>" value="<?= $box[$i] ?>" maxlength="1">
                    <?php endfor; ?>
                </div>
                <div><input type="submit" name="submit" value="Play"></div>
                
                <?php 
			if($response == "success"){
				?>
					<p class="success">(Your registration was successful)</p>
				<?php
			}else{
				?>
					<p class="error"><?php echo $response; ?></p>
				<?php
			}
		?>

            </form>
            
            <?php if ($winner != 'n'): ?>
            <h2 id="resultMessage">
                <?= $winner == 't' ? 'It\'s a tie!' : ($winner == 'x' ? $username . ' wins!' : 'Computer wins!') ?>
            </h2>
            <button onclick="resetGame();">Play Again</button>
            <?php endif; ?>

            <form action="dashboard.php">
                    <button type="submit">Go to Dashboard</button>
            </form>
        </div>
    </div>
</body>
</html>