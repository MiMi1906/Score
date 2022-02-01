<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

// データベース接続
$db = dbConnect();

if (!empty($_POST)) {
  // エラー項目の確認
  if ($_POST['name'] == '') {
    $error['name'] = 'blank';
  }
  if ($_POST['email'] == '') {
    $error['email'] = 'blank';
  } else {
  }
  if (strlen($_POST['password']) < 4) {
    $error['password'] = 'length';
  }
  if ($_POST['password'] == '') {
    $error['password'] = 'blank';
  }

  if (empty($error['email'])) {
    $sql = 'SELECT COUNT(*) FROM members WHERE email = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->execute();
    $record = $stmt->fetchColumn();
    if ($record > 0) {
      $error['email'] = 'duplicate';
    }
  }

  if (empty($error)) {
    $_SESSION['join'] = $_POST;
    header('Location: /join/check/');
    exit();
  }
}

if (!empty($_GET) && $_GET['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
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
  <title>メンバー登録 / Score</title>
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
            <input type="text" class="form-control <?php if (!empty($error['name']) && $error['name'] == 'blank') {
                                                      echo 'is-invalid';
                                                    } else if (!empty($_POST)) {
                                                      echo 'is-valid';
                                                    } ?>" name="name" id="floatingName" placeholder="name" value="<?php if (!empty($_POST['name'])) {
                                                                                                                    echo $_POST['name'];
                                                                                                                  } ?>">
            <label for="floatingName" class="label-placeholder">ニックネーム</label>
            <?php if (!empty($error['name']) && $error['name'] == 'blank') : ?>
              <div class="invalid-feedback">
                ニックネームを入力してください
              </div>
            <?php endif; ?>
          </div>

          <div class="form-floating my-3 mx-auto w-75 text-start">
            <input type="email" class="form-control <?php if (!empty($error['email']) && ($error['email'] == 'blank' || $error['email'] == 'duplicate')) {
                                                      echo 'is-invalid';
                                                    } else if (!empty($_POST)) {
                                                      echo 'is-valid';
                                                    } ?>" name="email" id="floatingEmail" placeholder="email" value="<?php if (!empty($_POST['email'])) {
                                                                                                                        echo $_POST['email'];
                                                                                                                      } ?>">
            <label for="floatingEmail" class="label-placeholder">メールアドレス</label>
            <?php if (!empty($error['email']) && $error['email'] == 'blank') : ?>
              <div class="invalid-feedback">
                メールアドレスを入力してください
              </div>
            <?php endif; ?>
            <?php if (!empty($error['email']) && $error['email'] == 'duplicate') : ?>
              <div class="invalid-feedback">
                このメールアドレスはすでに登録されています
              </div>
            <?php endif; ?>
          </div>

          <div class="form-floating my-3 mx-auto w-75 text-start">
            <input type="password" class="form-control <?php if (!empty($error['password']) && ($error['password'] == 'blank' || $error['email'] == 'length')) {
                                                          echo 'is-invalid';
                                                        } else if (!empty($_POST)) {
                                                          echo 'is-valid';
                                                        } ?>" name="password" id="floatingPassword" placeholder="password">
            <label for="floatingPassword" class="label-placeholder">パスワード</label>
            <?php if (!empty($error['password']) && $error['password'] == 'blank') : ?>
              <div class="invalid-feedback">
                パスワードを入力してください
              </div>
            <?php endif; ?>
            <?php if (!empty($error['password']) && $error['password'] == 'length') : ?>
              <div class="invalid-feedback">
                パスワードは4文字以上で入力してください
              </div>
            <?php endif; ?>
          </div>
          <input type="submit" class="btn btn-success my-3 py-2 px-4 rounded-pill" value="入力内容を確認">
        </form>
        <p class="text-secondary">アカウントをお持ちの方は <a href="/login/" class="text-success">ログイン</a></p>
      </div>
    </div>
  </div>
</body>

</html>
