<?php
require "functions.php";

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION["username"];

$users = fetch_tictactoe_ranking();
$loginuser = user_fetch_tictactoe_ranking($username);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tic Tac Toe Ranking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
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
    <h1>Tic Tac Toe Ranking</h1>
	
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Winrate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($user["username"]) ?></td>
                <td><?= number_format(floatval($user["winrate"]), 2) ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


	<h1>My Tic Tac Toe Ranking</h1>
	
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Winrate</th>
            </tr>
        </thead>
        <tbody>
		<?php if (!empty($loginuser)): ?>
            <tr>
                <td><?= $loginuser[0]["rank"] ?></td>
                <td><?= htmlspecialchars($loginuser[0]["username"]) ?></td>
                <td><?= number_format(floatval($loginuser[0]["winrate"]), 2) ?>%</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

	<form action="dashboard.php">
		<button type="submit">Go to Dashboard</button>
	</form>
</body>
</html>
