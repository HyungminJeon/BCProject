

<?php
require "functions.php";
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
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
        tictactoeUpdateGameStatistics('Tic Tac Toe', $username);
    } else {
        placeO($box);

        if (checkWinner($box, 'o')) {
            $winner = 'o';
            $response = saveTicTacToeResult('Tic Tac Toe', $username, 0,1,0);
            tictactoeUpdateGameStatistics('Tic Tac Toe', $username);
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
            margin-bottom: 10px;
        }

        .button-container {
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


         /* styles for the modal */

        .instructions {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.4;
            max-width: 400px;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            margin-bottom: 20px;
        }

         .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="center">
        <div>
            <h1>Tic Tac Toe</h1>

            <div class="button-container">
                <button id="instructionBtn">How to play?</button>
            </div>


            <!-- Add this modal with Tic Tac Toe instructions -->
            <div id="instructionModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Tic Tac Toe Instructions</h2>
                    <p>1. Place an 'x' in any empty cell on the game board.</p>
                    <p>2. Click the 'Play' button to submit your move.</p>
                    <p>3. The computer will automatically place an 'o' in response.</p>
                    <p>4. Take turns with the computer placing 'x' and 'o' until there is a winner or a tie.</p>
                    <p>5. To start a new game, click the 'Play Again' button.</p>
                </div>
            </div>
            
        
            <form method="post" action="" onsubmit="return validateForm();">
                <div class="grid-container">
                    <?php for ($i = 0; $i < 9; $i++): ?>
                        <input type="text" name="box<?= $i ?>" value="<?= $box[$i] ?>" maxlength="1">
                    <?php endfor; ?>
                </div>
                <div>
                <input type="submit" id="play" name="submit" value="Play"></div>
                <?php 
			if($response == "success"){
			}else{
				?>
					<p class="error"><?php echo $response; ?></p>
				<?php
			}
		?>
            </form>
            
            <?php if ($winner != 'n'): ?>
            <h2 id="resultMessage">
                <?= $winner == 't' ? 'It\'s a tie!' : ($winner == 'x' ? 'You win!' : 'Computer wins!') ?>
            </h2>
            <button id= "playAgainButton" onclick="resetGame();">Play Again</button>
            <?php endif; ?>

            <form action="dashboard.php">
                <button type="submit">Go to Dashboard</button>
            </form>
            

            <script>
                // handle the modal behavior
                const instructionBtn = document.getElementById("instructionBtn");
                const instructionModal = document.getElementById("instructionModal");
                const closeBtn = document.querySelector(".close");

                instructionBtn.onclick = function () {
                    instructionModal.style.display = "block";
                }

                closeBtn.onclick = function () {
                    instructionModal.style.display = "none";
                }

                window.onclick = function (event) {
                    if (event.target == instructionModal) {
                        instructionModal.style.display = "none";
                    }
                }
            </script>

        </div>
    </div>
</body>
</html>