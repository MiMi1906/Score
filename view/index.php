<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$tpl = new Template();

if (!empty($_GET)) {
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

  <title>Score</title>
</head>

<body>

  <div class="container justify-content-center" style="max-width: 800px;">
    <nav class="nav nav-pills justify-content-center mb-4">
      <a href="/view/?match_id=<?php echo $_GET['match_id'] ?>&team_flag=0" class=" nav-item nav-link w-50 text-center <?php if ($_GET['team_flag'] == 0) {
                                                                                                                          echo 'active text-white bg-success';
                                                                                                                        } else {
                                                                                                                          echo 'text-success bg-white';
                                                                                                                        } ?>" style="max-width: 400px;">自チーム</a>
      <a href="/view/?match_id=<?php echo $_GET['match_id'] ?>&team_flag=1" class="nav-item nav-link w-50 text-center  <?php if ($_GET['team_flag'] == 1) {
                                                                                                                          echo 'active text-white bg-success';
                                                                                                                        } else {
                                                                                                                          echo 'text-success bg-white';
                                                                                                                        } ?>" style="max-width: 400px;">対戦チーム</a>
    </nav>
    <?php
    print('<pre>');
    print_r($records);
    // print_r($batters);
    print('</pre>');
    ?>
  </div>

  <!-- script -->
  <!-- jQuery -->
  <script src=" https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</body>

</html>