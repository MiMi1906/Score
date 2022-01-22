<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

// データベース接続
$db = dbConnect();

if (!empty($_COOKIE['email'])) {
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}

// データを受け取った時
if (!empty($_POST)) {
  if ($_POST['email'] !== '' && $_POST['password'] !== '') {
    $sql = 'SELECT * FROM members WHERE email = :email AND password = :password';
    $login = $db->prepare($sql);
    $login->bindValue(':email', $_POST['email']);
    $login->bindValue(':password', sha1($_POST['password']));
    $login->execute();

    $member = $login->fetch();

    if ($member) {
      // ログイン成功
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      if (!empty($_POST['save']) && $_POST['save'] == 'on') {
        // ログイン情報を記録する
        setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14, "/");
        setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 14, "/");
        setcookie('save', 'on', time() + 60 * 60 * 24 * 14, "/");
      }

      header('Location: /');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
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
  <title>ログイン / Score</title>
</head>

<body id="login-form" class="bg-success">
  <div class="container" style="max-width: 800px">
    <div class="card py-5 px-3 text-center">
      <div class="card-title">
        <img src="/image/logo.png" alt="" class="w-50" style="max-width: 150px">
        <h6 class="mt-3 text-secondary">ログイン</h6>
      </div>
      <div class="card-text">
        <form action="" method="post">
          <?php if (!empty($error['login']) && $error['login'] == 'blank') : ?>
            <div class="alert alert-danger w-75 my-3 mx-auto">メールアドレスとパスワードを入力してください</div>
          <?php endif; ?>
          <?php if (!empty($error['login']) && $error['login'] == 'failed') : ?>
            <div class="alert alert-danger w-75 my-3 mx-auto">メールアドレスかパスワードが間違っています</div>
          <?php endif; ?>
          <div class="form-floating my-3 mx-auto w-75">
            <input type="email" class="form-control" name="email" id="floatingEmail" placeholder="email" value="<?php if (!empty($_POST['email'])) {
                                                                                                                  echo $_POST['email'];
                                                                                                                } ?>">
            <label for="floatingEmail" class="label-placeholder">メールアドレス</label>
          </div>
          <div class="form-floating my-3 mx-auto w-75">
            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="password">
            <label for="floatingPassword" class="label-placeholder">パスワード</label>
          </div>
          <input type="hidden" name="save" id="" value="on">
          <input type="submit" class="btn btn-success my-3 py-2 px-4 rounded-pill" value="ログインする">
        </form>
        <p class="text-secondary">アカウントをお持ちでない方は <a href="/join/" class="text-success">メンバー登録</a></p>
      </div>
    </div>
  </div>
</body>

</html>