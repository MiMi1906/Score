<?php
ini_set('display_errors', 1);

require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

header("Content-Type: application/json; charset=UTF-8"); //ヘッダー情報の明記。必須。

session_start();

$db = dbConnect();

$sql = 'SELECT * FROM matches WHERE match_id = :match_id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':match_id', $_SESSION['match_id']);
$stmt->execute();
$matchData = $stmt->fetch();

echo json_encode($matchData);
exit();
