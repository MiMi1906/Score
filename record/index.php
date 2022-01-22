<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$myBatterList = [];
$oppBatterList = [];

if (!empty($_POST)) {
    if ($_POST['date'] == '') $error = 'blank';
    else if ($_POST['matchName'] == '')  $error = 'blank';
    else if ($_POST['stadiumName'] == '') $error = 'blank';
    else if (empty($_POST['condition'])) $error = 'blank';
    else if ($_POST['weather'] == '') $error = 'blank';
    else if ($_POST['myTeam'] == '') $error = 'blank';
    else if ($_POST['oppTeam'] == '') $error = 'blank';
    else if ($_POST['judge'][0] == '') $error = 'blank';
    else if ($_POST['judge'][1] == '') $error = 'blank';
    else if ($_POST['judge'][2] == '') $error = 'blank';
    else if ($_POST['judge'][3] == '') $error = 'blank';
    else if ($_POST['recorder'] == '') $error = 'blank';
    else if ($_POST['attackFlag'] == '') $error = 'blank';

    if (!empty($_POST['myBatter'])) {
        foreach ($_POST['myBatter'] as $myBatter) {
            if ($myBatter == '') {
                $error = 'blank';
                break;
            } else {
                $myBatterList[]['batterName'] = $myBatter;
            }
        }
    }
    if (!empty($_POST['oppBatter'])) {
        foreach ($_POST['oppBatter'] as $oppBatter) {
            if ($oppBatter == '') {
                $error = 'blank';
                break;
            } else {
                $oppBatterList[]['batterName'] = $oppBatter;
            }
        }
    }
    $i = 0;
    if (!empty($_POST['myBatterIndex'])) {
        foreach ($_POST['myBatterIndex'] as $myBatterIndex) {
            if ($myBatterIndex == '') {
                $error = 'blank';
                break;
            } else {
                $myBatterList[$i]['batterIndex'] = $myBatterIndex;
            }
            $i++;
        }
    }

    $i = 0;
    if (!empty($_POST['oppBatterIndex'])) {
        foreach ($_POST['oppBatterIndex'] as $oppBatterIndex) {
            if ($oppBatterIndex == '') {
                $error = 'blank';
                break;
            } else {
                $oppBatterList[$i]['batterIndex'] = $oppBatterIndex;
            }
            $i++;
        }
    }

    $i = 0;
    if (!empty($_POST['myBatterLR'])) {
        foreach ($_POST['myBatterLR'] as $myBatterLR) {
            if ($myBatterLR == '') {
                $error = 'blank';
                break;
            } else {
                $myBatterList[$i]['batterLR'] = $myBatterLR;
            }
            $i++;
        }
    }

    $i = 0;
    if (!empty($_POST['oppBatterLR'])) {
        foreach ($_POST['oppBatterLR'] as $oppBatterLR) {
            if ($oppBatterLR == '') {
                $error = 'blank';
                break;
            } else {
                $oppBatterList[$i]['batterLR'] = $oppBatterLR;
            }
            $i++;
        }
    }

    if (empty($error)) {
        $sql = 'SELECT MAX(match_id) AS max FROM matches WHERE member_id = :member_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':member_id', $_SESSION['id']);
        $stmt->execute();
        $match_id = $stmt->fetchColumn();
        if (empty($match_id)) {
            $match_id = 1;
        } else {
            $match_id++;
        }

        $sql =
            'INSERT INTO 
        matches(
            member_id,
            match_id,
            date,
            match_name,
            stadium_name,
            condition,
            weather,
            judge0,
            judge1,
            judge2,
            judge3,
            recorder,
            my_team_name,
            opp_team_name,
            attack_flag
            ) 
        VALUES(
            :member_id,
            :match_id,
            :date,
            :match_name,
            :stadium_name,
            :condition,
            :weather,
            :judge0,
            :judge1,
            :judge2,
            :judge3,
            :recorder,
            :my_team_name,
            :opp_team_name,
            :attack_flag
            )';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':member_id', $_SESSION['id']);
        $stmt->bindValue(':match_id', $match_id);
        $stmt->bindValue(':date', $_POST['date']);
        $stmt->bindValue(':match_name', $_POST['matchName']);
        $stmt->bindValue(':stadium_name', $_POST['stadiumName']);
        $stmt->bindValue(':condition', $_POST['condition']);
        $stmt->bindValue(':weather', $_POST['weather']);
        $stmt->bindValue(':judge0', $_POST['judge'][0]);
        $stmt->bindValue(':judge1', $_POST['judge'][1]);
        $stmt->bindValue(':judge2', $_POST['judge'][2]);
        $stmt->bindValue(':judge3', $_POST['judge'][3]);
        $stmt->bindValue(':recorder', $_POST['recorder']);
        $stmt->bindValue(':my_team_name', $_POST['myTeam']);
        $stmt->bindValue(':opp_team_name', $_POST['oppTeam']);
        $stmt->bindValue(':attack_flag', $_POST['attackFlag']);
        $stmt->execute();

        $_SESSION['match_id'] = $match_id;

        $batter_index_cnt = 0;
        foreach ($myBatterList as $myBatter) {
            $sql = 'INSERT INTO batters(match_id, team_flag, batter_index, batter_name, batter_back_num, flag_LR) VALUES(:match_id, :team_flag, :batter_index, :batter_name, :batter_back_num, :flag_LR)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':match_id', $_SESSION['match_id']);
            $stmt->bindValue(':team_flag', MY_TEAM);
            $stmt->bindValue(':batter_index', $batter_index_cnt + 1);
            $stmt->bindValue(':batter_name', $myBatter['batterName']);
            $stmt->bindValue(':batter_back_num', $myBatter['batterIndex']);
            $stmt->bindValue(':flag_LR', $myBatter['batterLR']);
            $stmt->execute();
            $batter_index_cnt++;
        }

        $batter_index_cnt = 0;
        foreach ($oppBatterList as $oppBatter) {
            $sql = 'INSERT INTO batters(match_id, team_flag, batter_index, batter_name, batter_back_num, flag_LR) VALUES(:match_id, :team_flag, :batter_index, :batter_name, :batter_back_num, :flag_LR)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':match_id', $_SESSION['match_id']);
            $stmt->bindValue(':team_flag', OPP_TEAM);
            $stmt->bindValue(':batter_index', $batter_index_cnt + 1);
            $stmt->bindValue(':batter_name', $oppBatter['batterName']);
            $stmt->bindValue(':batter_back_num', $oppBatter['batterIndex']);
            $stmt->bindValue(':flag_LR', $oppBatter['batterLR']);
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
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/customize.css">
    <title>Score</title>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <?php
            if (!empty($error)) {
                echo '<div class="alert alert-danger">必要情報をすべて入力してください</div>';
            }
            ?>
            <div class="card mb-3">
                <div class="card-header text-white bg-success">
                    試合情報
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <div class="row">
                            <div class="col-lg">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" name="date" id="floatingDate" placeholder="date">
                                    <label for="floatingDate" class="label-placeholder">日付</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="matchName" id="floatingMatchName" placeholder="matchName">
                                    <label for="floatingMatchName" class="label-placeholder">試合名</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="stadiumName" id="floatingStadiumName" placeholder="stadiumName">
                                    <label for="floatingStadiumName" class="label-placeholder">球場名</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select name="condition" id="floatCondition" class="form-select" autocomplete="off" placeholder="condition">
                                        <option value="" disabled selected>選択してください</option>
                                        <option value="良">良</option>
                                        <option value="不良">不良</option>
                                    </select>
                                    <label for="floatCondition" class="label-placeholder">球場状態</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="weather" id="floatingWeather" placeholder="weather">
                                    <label for="floatingWeather" class="label-placeholder">天候</label>
                                </div>

                            </div>
                            <div class="col-lg">

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="judge[]" id="floatingJudge0" placeholder="judge">
                                    <label for="floatingJudge0" class="label-placeholder">審判 (球審)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="judge[]" id="floatingJudge1" placeholder="judge">
                                    <label for="floatingJudge1" class="label-placeholder">審判 (1塁)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="judge[]" id="floatingJudge2" placeholder="judge">
                                    <label for="floatingJudge2" class="label-placeholder">審判 (2塁)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="judge[]" id="floatingJudge3" placeholder="judge">
                                    <label for="floatingJudge3" class="label-placeholder">審判 (3塁)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="recorder" id="floatingRecorder" placeholder="recorder">
                                    <label for="floatingRecorder" class="label-placeholder">記録者</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="card mb-3">
                        <div class="card-header text-white bg-success">
                            選手名簿 (自チーム)
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myTeam" id="floatingMyTeam" placeholder="myTeam">
                                            <label for="floatingMyTeam" class="label-placeholder">自チーム名</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-floating mb-3">
                                            <select name="attackFlag" id="floatingAttackFlag" class="form-select" autocomplete="off" placeholder="attackFlag">
                                                <option value="" disabled selected>選択</option>
                                                <option value="0">先攻</option>
                                                <option value="1">後攻</option>
                                            </select>
                                            <label for="floatingAttackFlag" class="label-placeholder">先攻・後攻</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="" class="form-label">1番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex1" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex1" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter1" placeholder="myBatter">
                                            <label for="floatingMyBatter1" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR1" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR1" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">2番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex2" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex2" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter2" placeholder="myBatter">
                                            <label for="floatingMyBatter2" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR2" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR2" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">3番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex3" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex3" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter3" placeholder="myBatter">
                                            <label for="floatingMyBatter3" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR3" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR3" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">4番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex4" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex4" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter4" placeholder="myBatter">
                                            <label for="floatingMyBatter4" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR4" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR4" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">5番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex5" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex5" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter5" placeholder="myBatter">
                                            <label for="floatingMyBatter5" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR5" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR5" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">6番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex6" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex6" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter6" placeholder="myBatter">
                                            <label for="floatingMyBatter6" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR6" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR6" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">7番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex7" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex7" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter7" placeholder="myBatter">
                                            <label for="floatingMyBatter7" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR7" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR7" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">8番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex8" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex8" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter8" placeholder="myBatter">
                                            <label for="floatingMyBatter8" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR8" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR8" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">9番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="myBatterIndex[]" id="floatingMyBatterIndex9" placeholder="myBatterIndex" min="0">
                                            <label for="floatingMyBatterIndex9" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="myBatter[]" id="floatingMyBatter9" placeholder="myBatter">
                                            <label for="floatingMyBatter9" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="myBatterLR[]" id="floatingMyBatterLR9" class="form-select" autocomplete="off" placeholder="myBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingMyBatterLR9" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="card mb-3">
                        <div class="card-header text-white bg-success">
                            選手名簿 (対戦チーム)
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="oppTeam" id="floatingOppTeam" placeholder="oppTeam">
                                    <label for="floatingOppTeam" class="label-placeholder">対戦チーム名</label>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">1番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex1" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex1" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter1" placeholder="oppBatter">
                                            <label for="floatingOppBatter1" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR1" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR1" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">2番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex2" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex2" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter2" placeholder="oppBatter">
                                            <label for="floatingOppBatter2" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR2" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR2" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">3番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex3" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex3" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter3" placeholder="oppBatter">
                                            <label for="floatingOppBatter3" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR3" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR3" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">4番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex4" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex4" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter4" placeholder="oppBatter">
                                            <label for="floatingOppBatter4" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR4" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR4" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">5番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex5" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex5" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter5" placeholder="oppBatter">
                                            <label for="floatingOppBatter5" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR5" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR5" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">6番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex6" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex6" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter6" placeholder="oppBatter">
                                            <label for="floatingOppBatter6" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR6" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR6" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">7番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex7" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex7" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter7" placeholder="oppBatter">
                                            <label for="floatingOppBatter7" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR7" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR7" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">8番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex8" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex8" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter8" placeholder="oppBatter">
                                            <label for="floatingOppBatter8" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR8" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR8" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">9番</label>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="oppBatterIndex[]" id="floatingOppBatterIndex9" placeholder="oppBatterIndex" min="0">
                                            <label for="floatingOppBatterIndex9" class="label-placeholder">背番号</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="oppBatter[]" id="floatingOppBatter9" placeholder="oppBatter">
                                            <label for="floatingOppBatter9" class="label-placeholder">名前</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select name="oppBatterLR[]" id="floatingOppBatterLR9" class="form-select" autocomplete="off" placeholder="oppBatterLR">
                                                <option value="" disabled selected>選択</option>
                                                <option value="right">右打ち</option>
                                                <option value="left">左打ち</option>
                                                <option value="both">両打ち</option>
                                            </select>
                                            <label for="floatingOppBatterLR9" class="label-placeholder">打ち方</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <input type="submit" class="btn btn-primary" value="記録を開始">
            </div>
        </form>
    </div>
    </div>

    <!-- script -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</body>

</html>