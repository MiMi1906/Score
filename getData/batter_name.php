<?php
ini_set('display_errors', 1);
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

header("Content-Type: application/json; charset=UTF-8"); //ヘッダー情報の明記。必須。

session_start();

$batter_index = filter_input(INPUT_POST, 'batter_index');
$team_flag = filter_input(INPUT_POST, 'team_flag');

$db = dbConnect();

$sql = 'SELECT * FROM batters WHERE match_id = :match_id AND team_flag = :team_flag AND batter_index = :batter_index';
$stmt = $db->prepare($sql);
$stmt->bindValue(':match_id', $_SESSION['match_id']);
$stmt->bindValue(':team_flag', $team_flag);
$stmt->bindValue(':batter_index', $batter_index);
$stmt->execute();
$batter_name = $stmt->fetch();

echo json_encode($batter_name);
exit();
