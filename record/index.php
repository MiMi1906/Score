<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$tpl = new Template();

if (!empty($_POST)) {
    if ($_POST['date'] == '') $error = 'blank';
    else if ($_POST['matchName'] == '')  $error = 'blank';
    else if ($_POST['stadiumName'] == '') $error = 'blank';
    else if ($_POST['condition'] == '') $error = 'blank';
    else if ($_POST['weather'] == '') $error = 'blank';
    else if ($_POST['myTeam'] == '') $error = 'blank';
    else if ($_POST['oppTeam'] == '') $error = 'blank';
    else if ($_POST['judge'][0] == '') $error = 'blank';
    else if ($_POST['judge'][1] == '') $error = 'blank';
    else if ($_POST['judge'][2] == '') $error = 'blank';
    else if ($_POST['judge'][3] == '') $error = 'blank';
    else if ($_POST['recorder'] == '') $error = 'blank';
    else if (empty($_POST['myBatter'])) {
        foreach ($_POST['myBatter'] as $myBatter) {
            if ($myBatter == '') {
                $error = 'blank';
                break;
            }
        }
    } else if (empty($_POST['oppBatter'])) {
        foreach ($_POST['oppBatter'] as $oppBatter) {
            if ($oppBatter == '') {
                $error = 'blank';
                break;
            }
        }
    }

    if (empty($error)) {
        $sql = 'SELECT MAX(match_id) AS max FROM matches WHERE member_id = :member_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':member_id', $_SESSION['id']);
        $stmt->execute();
        $match_id = $stmt->fetchColumn();
        if (empty($match_id)){
            $match_id = 1;
        }
        else{
            $match_id++;
        }

        $sql = 'INSERT INTO matches(member_id, match_id) VALUES(:member_id, :match_id)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':member_id', $_SESSION['id']);
        $stmt->bindValue(':match_id', $match_id);
        $stmt->execute();

        $_SESSION['match_id'] = $match_id;

        $batter_index_cnt = 1;
        foreach ($_POST['myBatter'] as $myBatter) {
            $sql = 'INSERT INTO batters(match_id, team_flag, batter_index, batter_name) VALUES(:match_id, :team_flag, :batter_index, :batter_name)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':match_id', $_SESSION['match_id']);
            $stmt->bindValue(':team_flag', MY_TEAM);
            $stmt->bindValue('batter_index', $batter_index_cnt);
            $stmt->bindValue('batter_name', $myBatter);
            $stmt->execute();
            $batter_index_cnt++;
        }

        $batter_index_cnt = 1;
        foreach ($_POST['oppBatter'] as $oppBatter) {
            $sql = 'INSERT INTO batters(match_id, team_flag, batter_index, batter_name) VALUES(:match_id, :team_flag, :batter_index, :batter_name)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':match_id', $_SESSION['match_id']);
            $stmt->bindValue(':team_flag', OPP_TEAM);
            $stmt->bindValue('batter_index', $batter_index_cnt);
            $stmt->bindValue('batter_name', $oppBatter);
            $stmt->execute();
            $batter_index_cnt++;
        }

        header('Location: /record/matching/');
        exit();
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
    <link rel="stylesheet" href="/css/general.css">
    <title>Score</title>
</head>

<body>

    <div class="login_form_background">
        <div class="content login_form join_form">
            <form action="" method="post">
                <h3 class="label">試合情報</h3>
                <?php
                if (!empty($error)) {
                    echo '<div class="error">必要情報をすべて入力してください</div>';
                }
                ?>
                <div class="label">日付</div><input type="date" name="date" id="" class="login_form_input" placeholder=" " placeholder=" ">
                <div class="label">試合名</div><input type="text" name="matchName" id="" class="login_form_input" placeholder=" ">
                <div class="label">球場名</div><input type="text" name="stadiumName" id="" class="login_form_input" placeholder=" ">
                <div class="label">球場状態</div><input type="text" name="condition" id="" class="login_form_input" placeholder=" " class="login_form_input" placeholder=" ">
                <div class="label">天候</div><input type="text" name="weather" id="" class="login_form_input" placeholder=" ">
                <div class="label">自チーム名</div><input type="text" name="myTeam" id="" class="login_form_input" placeholder=" ">
                <div class="label">対戦チーム名</div><input type="text" name="oppTeam" id="" class="login_form_input" placeholder=" ">
                <div class="label">審判(球審)</div><input type="text" name="judge[]" id="" class="login_form_input" placeholder=" ">
                <div class="label">審判(1塁)</div><input type="text" name="judge[]" id="" class="login_form_input" placeholder=" ">
                <div class="label">審判(2塁)</div><input type="text" name="judge[]" id="" class="login_form_input" placeholder=" ">
                <div class="label">審判(3塁)</div><input type="text" name="judge[]" id="" class="login_form_input" placeholder=" ">
                <div class="label">記録者</div><input type="text" name="recorder" id="" class="login_form_input" placeholder=" ">
                <h3 class="label">選手名簿</h3>
                <div class="label">自チーム</div>
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="1番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="2番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="3番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="4番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="5番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="6番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="7番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="8番">
                <input type="text" name="myBatter[]" id="" class="login_form_input" placeholder="9番">
                <div class="label">相手チーム</div>
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="1番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="2番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="3番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="4番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="5番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="6番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="7番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="8番">
                <input type="text" name="oppBatter[]" id="" class="login_form_input" placeholder="9番">
                <div class="label">補足など</div>
                <textarea name="bio" id="" class="bio" placeholder=" "></textarea>
                <input type="submit" class="submit_btn" value="記録を開始">
            </form>
        </div>
    </div>

    <!-- script -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</body>

</html>