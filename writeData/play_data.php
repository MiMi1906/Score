<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

header("Content-Type: application/json; charset=UTF-8"); //ヘッダー情報の明記。必須。

session_start();

$team_flag = filter_input(INPUT_POST, 'team_flag');
$result = filter_input(INPUT_POST, 'result');
$position = filter_input(INPUT_POST, 'position');
$place = filter_input(INPUT_POST, 'place');
$b_runner = filter_input(INPUT_POST, 'b_runner');
$ball_array = filter_input(INPUT_POST, 'ball_array');

$db = dbConnect();

$sql = 'INSERT INTO records(match_id, team_flag, result, position, place, b_runner, ball_array) VALUES(:match_id, :team_flag, :result, :position, :place, :b_runner, :ball_array)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':match_id', $_SESSION['match_id']);
$stmt->bindValue(':team_flag', $team_flag);
$stmt->bindValue(':result', $result);
$stmt->bindValue(':position', $position);
$stmt->bindValue(':place', $place);
$stmt->bindValue(':b_runner', $b_runner);
$stmt->bindValue(':ball_array', $ball_array);
$stmt->execute();

exit();
