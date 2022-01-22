<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

// ini_set('display_errors', "On");

session_start();

// データベース接続
$db = dbConnect();

if (empty($_SESSION['join'])) {
  header('Location: /join/');
  exit();
}

if (!empty($_POST)) {
  // 登録処理をする
  $sql = 'INSERT INTO members(name, email, password, created) VALUES(:name, :email, :password, :created)';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':name', $_SESSION['join']['name']);
  $stmt->bindValue(':email', $_SESSION['join']['email']);
  $stmt->bindValue(':password', sha1($_SESSION['join']['password']));
  $stmt->bindValue(':created', date('Y/m/d H:i:s'));
  $stmt->execute();

  unset($_SESSION['join']);

  header('Location: /join/complete/');
  exit();
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
  <title>登録内容の確認 / Score</title>
</head>

<body id="login-form" class="bg-success">
  <div class="container" style="max-width: 800px">
    <div class="card py-5 px-3 text-center">
      <div class="card-title">
        <img src="/image/logo.png" alt="" class="w-50" style="max-width: 150px">
        <h6 class="mt-3 text-secondary">メンバー登録</h6>
      </div>
      <div class="card-text">
        <form action="" method="post">
          <div class="form-floating my-3 mx-auto w-75 text-start">
            <input type="text" class="form-control bg-white is-valid" name="name" id="floatingName" placeholder="name" value="<?php echo $_SESSION['join']['name']; ?>" readonly>

            <label for="floatingName" class="label-placeholder">ニックネーム</label>
          </div>

          <div class="form-floating my-3 mx-auto w-75 text-start">
            <input type="email" class="form-control is-valid" name="email" id="floatingEmail" placeholder="email" value="<?php echo $_SESSION['join']['email']; ?>">
            <label for="floatingEmail" class="label-placeholder">メールアドレス</label>
          </div>

          <div class="form-floating my-3 mx-auto w-75 text-start">
            <input type="text" class="form-control is-valid" name="password" id="floatingPassword" placeholder="password" value="表示されません">
            <label for="floatingPassword" class="label-placeholder">パスワード</label>
          </div>
          <p class="text-secondary">登録内容を修正したい場合は <a href="/join/?action=rewrite" class="text-success">こちら</a></p>
          <input type="submit" class="btn btn-success my-3 py-2 px-4 rounded-pill" value="この内容で登録">
        </form>
      </div>
    </div>
  </div>
</body>

</html>