<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$tpl = new Template();

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

  <title>Score</title>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header">
            試合を開始
          </div>
          <div class="card-body">
            <div class="card-text">
              <p>
                新しく記録を開始します
              </p>
              <div class="d-grid gap-2">
                <a href="/record/" class="btn btn-primary">試合記録を開始</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header">
            試合結果を見る
          </div>
          <div class="card-body">
            <div class="card-text">
              <?php
              foreach ($matches as $match) {
                print('<p>' . $match['match_name'] . '</p>');
                print('<p>' . $match['my_team_name'] . ' vs ' . $match['opp_team_name'] . '</p>');
              }
              ?>
            </div>
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