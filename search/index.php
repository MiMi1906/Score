<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

$match_name = "";

if (empty($_GET['page'])) {
  header('Location: /');
  exit();
}

if (!empty($_GET['match_name'])) {
  $match_name = $_GET['match_name'];
}

loginCheck();

// データベース接続
$db = dbConnect();

$sql = 'SELECT * FROM members WHERE id = :id';
$members = $db->prepare($sql);
$members->bindValue(':id', $_SESSION['id']);
$members->execute();
$member = $members->fetch();

$sql = "SELECT COUNT(*) AS cnt FROM matches WHERE member_id = :member_id AND match_name LIKE '%" . $match_name . "%'";
$stmt = $db->prepare($sql);
$stmt->bindValue(':member_id', $member['id']);
$stmt->execute();
$total = $stmt->fetchColumn();

$sql = "SELECT g.* FROM members m, matches g WHERE m.id = g.member_id AND match_name LIKE '%" . $match_name . "%' ORDER BY g.id DESC LIMIT :start, :end";
$stmt = $db->prepare($sql);
$stmt->bindValue(':start', ($_GET['page'] - 1) * 10);
$stmt->bindValue(':end', ($_GET['page'] - 1) * 10 + 10);
$stmt->execute();
$matches = $stmt->fetchAll();

$pages = ceil($total / 10);

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

  <title>記録 / Score</title>
</head>

<body>
  <?php
  navbar();
  ?>
  <div class="container" style="margin-top: 86px;">
    <div class="text-center mx-auto" style="max-width: 800px;">
      <img src=" /image/logo.png" alt="" class="w-50 mt-2 mb-5" style="max-width: 100px;">
      <form action="" method="get" class="my-3 mx-auto" style="max-width: 600px;">
        <div class="input-group">
          <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">
          <input type="text" class="form-control" name="match_name" placeholder="試合名で検索" value="<?php echo $match_name; ?>" style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;">
          <span class="input-group-btn">
            <button class="btn btn-success" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
              <i class='fas fa-search'></i>
            </button>
          </span>
        </div>
      </form>
    </div>
    <div class="mx-auto mt-5" style="max-width: 800px;">
      <?php
      if ($match_name != '') {
        print('<div class="text-secondary">');
        print('<p><b>' . $match_name . ' </b>の検索結果</p>');
        print('</div>');
      }
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

    <div class="d-flex justify-content-center mt-5">
      <ul class="pagination">
        <?php
        if ($_GET['page'] != 1) : ?>
          <li class="page-item"><a href="/search/?page=<?php echo $_GET['page'] - 1; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary">&laquo;</a></li>
        <?php else : ?>
          <li class="page-item disabled"><a href="" class="page-link text-secondary" tabindex="-1">&laquo;</a></li>
        <?php endif; ?>
        <?php if ($pages > 5) : ?>
          <li class="page-item <?php if ($_GET['page'] == 1) {
                                  echo 'disabled';
                                } ?>"><a href="/search/?page=<?php echo 1; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary <?php if ($_GET['page'] == 1) {
                                                                                                                                                        echo 'bg-success text-white';
                                                                                                                                                      } ?>">1</a></li>
          <li class="page-item <?php if ($_GET['page'] == 2) {
                                  echo 'disabled';
                                } ?>"><a href="/search/?page=<?php echo 2; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary <?php if ($_GET['page'] == 2) {
                                                                                                                                                        echo 'bg-success text-white';
                                                                                                                                                      } ?>">2</a></li>
          <li class="page-item disabled"><a href="" class="page-link text-secondary" tabindex="-1">...</a></li>
          <?php if ($_GET['page'] > 2 && $_GET['page'] < $pages - 1) : ?>
            <li class="page-item disabled"><a href="" class="page-link text-secondary border-success bg-success text-white" tabindex="-1"><?php echo $_GET['page'] + 1; ?></a></li>
            <li class="page-item disabled"><a href="" class="page-link text-secondary" tabindex="-1">...</a></li>
          <?php endif; ?>
          <li class=" page-item <?php if ($_GET['page'] == $pages - 1) {
                                  echo 'disabled';
                                } ?>"><a href="/search/?page=<?php echo $pages - 1; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary <?php if ($_GET['page'] == $pages - 1) {
                                                                                                                                                                echo 'bg-success text-white';
                                                                                                                                                              } ?>"><?php echo $pages - 1; ?></a></li>
          <li class="page-item <?php if ($_GET['page'] == $pages) {
                                  echo 'disabled';
                                } ?>"><a href="/search/?page=<?php echo $pages; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary <?php if ($_GET['page'] == $pages) {
                                                                                                                                                            echo 'bg-success text-white';
                                                                                                                                                          } ?>"><?php echo $pages; ?></a></li>
        <?php else : ?>
          <?php for ($i = 1; $i <= $pages; $i++) : ?>
            <li class="page-item <?php if ($_GET['page'] == $i) {
                                    echo 'disabled';
                                  } ?>"><a href="/search/?page=<?php echo $i; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary <?php if ($_GET['page'] == $i) {
                                                                                                                                                          echo 'bg-success text-white';
                                                                                                                                                        } ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
        <?php endif; ?>
        <?php if ($_GET['page'] + 1 < $pages) : ?>
          <li class="page-item"><a href="/search/?page=<?php echo $_GET['page'] + 1; ?>&match_name=<?php echo $match_name; ?>" class="page-link text-secondary">&raquo;</a></li>
        <?php else : ?>
          <li class="page-item disabled"><a href="" class="page-link text-secondary" tabindex="-1">&raquo;</a></li>
        <?php endif; ?>
      </ul>
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