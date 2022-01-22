<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

ini_set('display_errors', "On");


loginCheck();

// データベース接続
$db = dbConnect();

$sql = 'SELECT * FROM members WHERE id = :id';
$members = $db->prepare($sql);
$members->bindValue(':id', $_SESSION['id']);
$members->execute();
$member = $members->fetch();

$sql = 'SELECT * FROM matches WHERE member_id = :member_id ORDER BY id DESC LIMIT 0, 10';
$stmt = $db->prepare($sql);
$stmt->bindValue(':member_id', $member['id']);
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

  <title>ホーム / Score</title>
</head>

<body>
  <?php
  navbar();
  ?>
  <div class="container" style="margin-top: 86px;">
    <div class="row justify-content-around">
      <div class="col-md-5 mb-3">
        <div class="card mb-3">
          <div class="card-body text-center">
            <div class="text-center d-flex justify-content-center my-3 align-items-baseline">
              <img src="/image/logo.png" alt="" class="w-50" style="max-width: 150px">
              <small class="text-muted mx-3">ver.1.0.0</small>
            </div>
            <div class="card-text mt-5">
              <h5>ようこそ<?php echo ' ' . $member['name']; ?> さん</h5>
              <div class="row justify-content-center mt-5">
                <div class="col-xl-3 col-4 d-flex justify-content-center">
                  <a href="/record/" class="border border-success rounded-circle border-2 text-success d-flex justify-content-center align-items-center" style=" text-decoration: none; width: 50px; height: 50px;">
                    <i class="fas fa-pen"></i>
                  </a>
                </div>
                <div class="col-xl-3 col-4 d-flex justify-content-center">
                  <a href="/search/?page=1" class="border border-success rounded-circle border-2 text-success d-flex justify-content-center align-items-center" style=" text-decoration: none; width: 50px; height: 50px;">
                    <i class="fas fa-search"></i>
                  </a>
                </div>
                <div class="col-xl-3 col-4 d-flex justify-content-center">
                  <a href="/logout/" class="border border-success rounded-circle border-2 text-success d-flex justify-content-center align-items-center" style=" text-decoration: none; width: 50px; height: 50px;">
                    <i class="fas fa-sign-out-alt"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class=" card mb-3">
          <div class="card-body">
            <h4 class="card-title mb-3">
              NEWS
            </h4>
            <hr>
            <div class="card-text">
              <!-- <h6 class="mb-3">NEWS</h6> -->
              <ul class="list-group list-group-flush">
                <a href="#" class="list-group-item text-dark">
                  <b>ver.1.0.0をリリースしました</b> ・ 2022/1/22<br>
                </a>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="p-3 mb-3 bg-success rounded text-white">
          <h5>新しく記録を開始します</h5>
          <hr>
          <a href="/record/" class="btn border-light text-light">試合記録を開始</a>
        </div>
        <div class="card">
          <div class="card-body">
            <?php if (!empty($matches)) : ?>
              <h4>試合結果を見る</h4>
              <hr>
              <?php
              foreach ($matches as $match) {
                print('<div class="shadow p-3 mb-3 bg-light rounded">');
                print('<a href="/view/?match_id=' . $match['match_id'] . '&team_flag=0" class="text-dark" style="text-decoration: none;">');
                print('<h5>' . $match['match_name']);
                print('<small class="text-muted mx-3" style="font-size: 14px;">' . $match['my_team_name'] . ' vs ' . $match['opp_team_name'] . '</small>');
                print('</h5>');
                print('<hr>');
                print('<p class="mb-1">');
                print($match['my_team_score'] . ' - ' . $match['opp_team_score']);
                print('<br>');
                print('<small class="text-muted">');
                print(date('Y年m月d日',  strtotime($match['date'])));
                print('</small>');
                print('</p>');
                print('</a>');
                print('</div>');
              }
              ?>
              <div class="d-grid">
                <a href="/search/?page=1" class="btn btn-success">もっと見る</a>
              </div>
            <?php else : ?>
              <p class="mb-1">まだ試合の記録はありません</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  footer();
  ?>
  <!-- script -->
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="/script/bootstrap.bundle.min.js"></script>
</body>

</html>