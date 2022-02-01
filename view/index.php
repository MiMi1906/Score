<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

// データベース接続
$db = dbConnect();

if (!empty($_GET)) {
  $sql = 'SELECT * FROM matches WHERE match_id = :match_id';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':match_id', $_GET['match_id']);
  $stmt->execute();
  $match_data = $stmt->fetch();

  $sql = 'SELECT * FROM records WHERE match_id = :match_id AND team_flag = :team_flag';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':match_id', $_GET['match_id']);
  $stmt->bindValue(':team_flag', $_GET['team_flag']);
  $stmt->execute();
  $records = $stmt->fetchAll();

  $sql = 'SELECT * FROM batters WHERE match_id = :match_id AND team_flag = :team_flag';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':match_id', $_GET['match_id']);
  $stmt->bindValue(':team_flag', $_GET['team_flag']);
  $stmt->execute();
  $batters = $stmt->fetchAll();
}

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

  <link rel="icon" type="image/x-icon" href="/favicon.png">
  <link rel="apple-touch-icon" sizes="125x125" href="/favicon.png">

  <title><?php echo $match_data['my_team_name'] ?> vs <?php echo $match_data['opp_team_name']; ?> / Score</title>
</head>

<body>
  <?php
  navbar();
  ?>
  <div class="container justify-content-center" style="max-width: 800px; margin-top:86px;">
    <div class="text-center my-3">

    </div>
    <div class="card mb-3">
      <div class="card-header text-white bg-success">
        試合情報
      </div>
      <card class="card-body">
        <div class="text-center">
          <h1><?php echo $match_data['match_name']; ?></h1>
          <hr>
          <p><?php echo $match_data['my_team_name'] ?> vs <?php echo $match_data['opp_team_name']; ?></p>
          <h2>
            <?php echo $match_data['my_team_score']; ?> - <?php echo $match_data['opp_team_score']; ?>
          </h2>
          <hr>
        </div>
        <div class="table-responsive">
          <table class="table table-borderless table-sm">
            <tr>
              <th>日付</th>
              <td><?php echo date('Y年m月d日',  strtotime($match_data['date'])); ?></td>
            </tr>
            <tr>
              <th>球場名</th>
              <td><?php echo $match_data['stadium_name']; ?></td>
            </tr>
            <tr>
              <th>球場状態</th>
              <td><?php echo $match_data['condition']; ?></td>
            </tr>
            <tr>
              <th>天候</th>
              <td><?php echo $match_data['weather']; ?></td>
            </tr>
            <tr>
              <th>審判</th>
              <td>
                <span class="badge bg-success" style="margin-right: 5px; width: 40px;">球審</span><?php echo $match_data['judge0']; ?><br>
                <span class="badge bg-secondary" style="margin-right: 5px; width: 40px;">1塁</span><?php echo $match_data['judge1']; ?><br>
                <span class="badge bg-secondary" style="margin-right: 5px; width: 40px;">2塁</span><?php echo $match_data['judge2']; ?><br>
                <span class="badge bg-secondary" style="margin-right: 5px; width: 40px;">3塁</span><?php echo $match_data['judge3']; ?>
              </td>
            </tr>
            <tr>
              <th>記録者</th>
              <td><?php echo $match_data['recorder']; ?></td>
            </tr>
          </table>
        </div>
        <p class="text-secondary">SNSで共有する</p>
        <hr>
        <div class="row">
          <div class="col-3">
            <a href="https://twitter.com/intent/tweet?text=<?php echo $match_data['my_team_name'] . '対' . $match_data['opp_team_name'] . 'の試合結果' ?>&url=<?php echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="btn mb-3 w-100 text-white mx-auto" style="background-color: #059ff5; font-size: 20px;"><i class="fab fa-twitter"></i></a>
          </div>
          <div class="col-3">
            <a href="https://www.facebook.com/share.php?u=<?php echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="btn mb-3 text-white w-100 mx-auto" style="background-color: #3b5998; font-size: 20px;"><i class="fab fa-facebook"></i></a>
          </div>
          <div class="col-3">
            <a href="https://line.me/R/msg/text/?<?php echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="btn mb-3 text-white w-100 mx-auto" style="background-color: #00c300; font-size: 20px;"><i class="fab fa-line"></i></a>
          </div>
          <div class="col-3">
            <button onclick="copyUrl();" class="btn mb-3 text-white w-100 mx-auto bg-secondary" style="background-color: #3b5998; font-size: 20px;"><i class="far fa-copy"></i></a>
          </div>
        </div>
    </div>

    <div class="card" id="play_data">
      <div class="card-header bg-success text-white">
        プレー情報
      </div>
      <div class="card-body p-4">
        <nav class="nav nav-pills justify-content-center mb-4">
          <a href="/view/?match_id=<?php echo $_GET['match_id'] ?>&team_flag=0#play_data" class=" nav-item nav-link w-50 text-center <?php if ($_GET['team_flag'] == 0) {
                                                                                                                              echo 'active text-white bg-success';
                                                                                                                            } else {
                                                                                                                              echo 'text-success bg-white';
                                                                                                                            } ?>" style="max-width: 400px;"><?php echo $match_data['my_team_name']; ?></a>
          <a href="/view/?match_id=<?php echo $_GET['match_id'] ?>&team_flag=1#play_data" class="nav-item nav-link w-50 text-center  <?php if ($_GET['team_flag'] == 1) {
                                                                                                                              echo 'active text-white bg-success';
                                                                                                                            } else {
                                                                                                                              echo 'text-success bg-white';
                                                                                                                            } ?>" style="max-width: 400px;"><?php echo $match_data['opp_team_name']; ?></a>
        </nav>
        <hr>
        <?php if (empty($records)) : ?>
          <div class="alert alert-danger">
            この試合の記録はありません
          </div>
          <div class="d-grid my-3">
            <a href="/search/" class="btn btn-success">ほかの試合を探す</a>
          </div>
        <?php endif; ?>
        <?php foreach ($records as $record) : ?>
          <div class="shadow p-3 mb-2 bg-light rounded">
            <?php
            $inning_str = '';
            $inning_str .= $record['inning'] . '回';
            if ($record['attack_flag'] == 0) {
              $inning_str .= '表';
            } else {
              $inning_str .= '裏';
            }

            $batter_str = '';
            if ($record['batter_index'] != '') {
              $batter_str .= $record['batter_index'] . '番 ';
              $batter_str .= $batters[$record['batter_index']]['batter_name'];
            }
            ?>
            <p><?php echo $inning_str; ?>
              <small class="text-muted mx-3"><?php echo $batter_str; ?><span class="badge <?php if ($batters[$record['batter_index']]['flag_LR'] == 'left') {
                                                                                            echo 'bg-primary';
                                                                                            $LR = '左';
                                                                                          } else if ($batters[$record['batter_index']]['flag_LR'] == 'right') {
                                                                                            echo 'bg-danger';
                                                                                            $LR = '右';
                                                                                          } else {
                                                                                            echo 'bg-warning';
                                                                                            $LR = '両';
                                                                                          } ?>" style="margin-left: 5px;"><?php echo $LR; ?></span>
              </small>
            </p>
            <hr>
            <p class="text-success"><b><?php echo $record['result']; ?></b></p>
            <small class="text-muted"><?php echo $record['ball_array']; ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <?php
  footer();
  ?>

  <!-- script -->
  <!-- jQuery -->
  <script src=" https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="/script/bootstrap.bundle.min.js"></script>

  <script>
    function copyUrl() {
      var url = location.href;
      navigator.clipboard.writeText(url);
    }
  </script>

</body>

</html>
