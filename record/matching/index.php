<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$sql = 'SELECT * FROM members WHERE id = :id';
$members = $db->prepare($sql);
$members->bindValue(':id', $_SESSION['id']);
$members->execute();
$member = $members->fetch();
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

    <title>試合中 / Score</title>
</head>

<body>
    <div id="loading">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container" id="wrap">
        <div class="row">
            <div class="col-md" id="match_data">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span id="inning"></span>
                            <span id="attack_flag"></span>
                        </h4>
                        <div class="card-text">
                            <span id="score"></span>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="batter card-title">
                            <span id="batter_index">　</span>
                            <span id="batter_name" class="align-item-center">　</span>
                        </h4>
                        <hr>
                        <div id="result" class="card-text">
                            <div class=" cnt">
                                <span class="title">B</span><span id="cnt_ball" class="cnt_ball cnt_num"></span>
                            </div>
                            <div class="cnt">
                                <span class="title">S</span><span id="cnt_strike" class="cnt_strike cnt_num"></span>
                            </div>
                            <div class="cnt">
                                <span class="title">O</span><span id="cnt_out" class="cnt_out cnt_num">
                                </span>
                            </div>
                        </div>
                        <div class="runner">
                            <span id="runner"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md">
                <div id="data_result">

                </div>
                <div id="change">

                </div>
                <div id="match_end">

                </div>
                <div id="end_btn" class="text-end">
                    <a href="/record/end_match/" class="btn btn-success">終了</a>
                </div>
                <div id="block_next">
                    <div class="text-end">
                        <input type="button" class="btn btn-success" id="btn_next" value="次へ" onclick="display_none(block_next); submit()">
                    </div>
                </div>
                <div id="select_block">
                    <div class="list-group mb-3" id="block_1">
                        <input type="button" class="list-group-item list-group-item-action" value="ボール" onclick="hit(1)">
                        <input type="button" class="list-group-item list-group-item-action" value="空振りストライク" onclick="hit(2)">
                        <input type="button" class="list-group-item list-group-item-action" value="見逃しストライク" onclick="hit(3)">
                        <input type="button" class="list-group-item list-group-item-action" value="ファウル" onclick="hit(4)">
                        <input type="button" class="list-group-item list-group-item-action" value="ファウルフライ" onclick="hit(5)">
                        <input type="button" class="list-group-item list-group-item-action" value="バントファウル" onclick="hit(6)">
                        <input type="button" class="list-group-item list-group-item-action" value="バント空振り" onclick="hit(7)">
                        <input type="button" class="list-group-item list-group-item-action" value="バント" onclick="hit(14)" id="bant">
                        <input type="button" class="list-group-item list-group-item-action" value="デッドボール" onclick="hit(13)">
                        <input type="button" class="list-group-item list-group-item-action" value="ゴロ" onclick="hit(8)">
                        <input type="button" class="list-group-item list-group-item-action" value="フライ" onclick="hit(9)">
                        <input type="button" class="list-group-item list-group-item-action" value="ヒット" onclick="hit(10)">
                        <input type="button" class="list-group-item list-group-item-action" value="ホームラン" onclick="hit(11)">
                        <input type="button" class="list-group-item list-group-item-action" value="ランニングホームラン" onclick="hit(12)">
                    </div>
                    <div class="list-group mb-3" id="stolen">
                        <input type="button" class="list-group-item list-group-item-action" value="盗塁" onclick="stolen_base()">
                    </div>
                    <div class="list-group mb-3" id="block_2_1">
                        <input type="button" class="list-group-item list-group-item-action" value="ピッチャー" onclick="position(1)">
                        <input type="button" class="list-group-item list-group-item-action" value="キャッチャー" onclick="position(2)">
                        <input type="button" class="list-group-item list-group-item-action" value="ファースト" onclick="position(3)">
                        <input type="button" class="list-group-item list-group-item-action" value="セカンド" onclick="position(4)">
                        <input type="button" class="list-group-item list-group-item-action" value="サード" onclick="position(5)">
                        <input type="button" class="list-group-item list-group-item-action" value="ショート" onclick="position(6)">
                        <input type="button" class="list-group-item list-group-item-action" value="レフト" onclick="position(7)">
                        <input type="button" class="list-group-item list-group-item-action" value="センター" onclick="position(8)">
                        <input type="button" class="list-group-item list-group-item-action" value="ライト" onclick="position(9)">
                    </div>
                    <div class="list-group mb-3" id="block_2_2">
                        <input type="button" class="list-group-item list-group-item-action" value="レフト" onclick="position(7)">
                        <input type="button" class="list-group-item list-group-item-action" value="センター" onclick="position(8)">
                        <input type="button" class="list-group-item list-group-item-action" value="ライト" onclick="position(9)">
                    </div>
                    <div class="list-group mb-3" id="block_3">
                        <input type="button" class="list-group-item list-group-item-action" value="前" onclick="place(1)">
                        <input type="button" class="list-group-item list-group-item-action" value="オーバー" onclick="place(2)">
                        <input type="button" class="list-group-item list-group-item-action" value="左中間" onclick="place(3)">
                        <input type="button" class="list-group-item list-group-item-action" value="右中間" onclick="place(4)">
                    </div>
                    <div class="list-group mb-3" id="block_3_l">
                        <input type="button" class="list-group-item list-group-item-action" value="前" onclick="place(1)">
                        <input type="button" class="list-group-item list-group-item-action" value="オーバー" onclick="place(2)">
                        <input type="button" class="list-group-item list-group-item-action" value="左中間" onclick="place(3)">
                    </div>
                    <div class="list-group mb-3" id="block_3_r">
                        <input type="button" class="list-group-item list-group-item-action" value="前" onclick="place(1)">
                        <input type="button" class="list-group-item list-group-item-action" value="オーバー" onclick="place(2)">
                        <input type="button" class="list-group-item list-group-item-action" value="右中間" onclick="place(4)">
                    </div>
                    <div id="block_4" class="mb-3">
                        <div class="mb-3" id="batter_runner">
                            <div class="label">
                                バッターランナー
                            </div>
                            <select name="batter_runner" id="br" class="form-select" autocomplete="off">
                                <option value="-1" id="brsel" selected>選択してください</option>
                                <option value="0" id="br0">アウト</option>
                                <option value="1" id="br1">1塁へ</option>
                                <option value="2" id="br2">2塁へ</option>
                                <option value="3" id="br3">3塁へ</option>
                                <option value="4" id="br4">ホームへ</option>
                            </select>
                        </div>
                        <div class="mb-3" id="first_runner">
                            <div class="label">1塁ランナー</div>
                            <select name="first_runner" id="fr" class="form-select" autocomplete="off">
                                <option value="-1" id="frsel" selected>選択してください</option>
                                <option value="0" id="fr0">アウト</option>
                                <option value="1" id="fr1">そのまま</option>
                                <option value="2" id="fr2">2塁へ</option>
                                <option value="3" id="fr3">3塁へ</option>
                                <option value="4" id="fr4">ホームへ</option>
                            </select>
                        </div>
                        <div class="mb-3" id="second_runner">
                            <div class="label">
                                2塁ランナー
                            </div>
                            <select name="second_runner" id="sr" class="form-select" autocomplete="off">
                                <option value="-1" id="srsel" selected>選択してください</option>
                                <option value="0" id="sr0">アウト</option>
                                <option value="2" id="sr2">そのまま</option>
                                <option value="3" id="sr3">3塁へ</option>
                                <option value="4" id="sr4">ホームへ</option>
                            </select>
                        </div>
                        <div class="mb-3" id="third_runner">
                            <div class="label">
                                3塁ランナー
                            </div>
                            <select name="third_runner" id="tr" class="form-select" autocomplete="off">
                                <option value="-1" id="trsel" selected>選択してください</option>
                                <option value="0" id="tr0">アウト</option>
                                <option value="3" id="tr3">そのまま</option>
                                <option value="4" id="tr4">ホームへ</option>
                            </select>
                        </div>
                        <div class="text-end mb-3">
                            <input type="button" class="btn btn-secondary" id="OK" value="OK" onclick="setRunner()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="/script/script.js"></script>
    </div>

    <!-- script -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/script/bootstrap.min.js"></script>
</body>

</html>