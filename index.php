<?php
/*!
  * TITLE : Home / Score
  * PATH : /root/index.php
  * FINAL UPDATE : 01.20.2022
  */
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$sql = 'SELECT * FROM members WHERE id = :id';
$members = $db->prepare($sql);
$members->bindValue(':id', $_SESSION['id']);
$members->execute();
$member = $members->fetch();

$sql = 'SELECT g.* FROM members m, matches g WHERE m.id = g.member_id ORDER BY g.id DESC';
$stmt = $db->prepare($sql);
$stmt->execute();
$matches = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" rel="stylesheet">

  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/customize.css">

  <title>Home / Score</title>
</head>

<body>

  <div class="container">
    <div class="row justify-content-around">
      <div class="col-md-5 mb-3">
        <div class="card mb-3">
          <div class="card-body">
            <h4 class="card-title mb-3">
              NEWS
            </h4>
            <hr>
            <div class="card-text">
              <!-- <h6 class="mb-3">NEWS</h6> -->
              <ul class="list-group list-group-flush">
                <a href="#" class="list-group-item text-dark">v.0.0.4をリリースしました</a>
                <a href="#" class="list-group-item text-dark">v.0.0.3をリリースしました</a>
                <a href="#" class="list-group-item text-dark">v.0.0.2をリリースしました</a>
                <a href="#" class="list-group-item text-dark">v.0.0.1をリリースしました</a>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card mb-3">
          <div class="card-body">
            <h4>試合を開始</h4>
            <hr>
            <div class="shadow p-3 mb-3 bg-light rounded">
              <h5>新しく記録を開始します</h5>
              <hr>
              <a href="/record/" class="btn btn-success">試合記録を開始</a>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h4>試合結果を見る</h4>
            <hr>
            <?php
            foreach ($matches as $match) {
              print('<div class="shadow p-3 mb-3 bg-light rounded">');
              print('<a href="/view/?match_id=' . $match['match_id'] . '&team_flag=0" class="text-dark" style="text-decoration: none;">');
              print('<h5>' . $match['match_name'] . '</h5>');
              print('<hr>');
              print('<div>' . $match['my_team_name'] . ' vs ' . $match['opp_team_name'] . '</div>');
              print('</a>');
              print('</div>');
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- script -->
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</body>

</html>