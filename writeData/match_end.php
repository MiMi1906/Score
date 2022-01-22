<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

header("Content-Type: application/text; charset=UTF-8"); //ヘッダー情報の明記。必須。

session_start();

$my_team_score = filter_input(INPUT_POST, 'my_team_score');
$opp_team_score = filter_input(INPUT_POST, 'opp_team_score');

$db = dbConnect();

$sql = 'UPDATE matches SET my_team_score = :my_team_score, opp_team_score = :opp_team_score WHERE match_id = :match_id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':match_id', $_SESSION['match_id']);
$stmt->bindValue(':my_team_score', $my_team_score);
$stmt->bindValue(':opp_team_score', $opp_team_score);
$stmt->execute();

exit();
