<?php
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

session_start();

loginCheck();

// データベース接続
$db = dbConnect();

$tpl = new Template();

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

    <link rel="stylesheet" href="/css/general.css">

    <title>Score</title>
</head>

<body>
    <div class="matching">
        <div class="login_form_background">
            <div class="content login_form">
                <div id="result">
                    <div class="cnt">
                        <span class="title">B</span><span id="cnt_ball" class="cnt_show"></span>
                    </div>
                    <div class="cnt">
                        <span class="title">S</span><span id="cnt_strike" class="cnt_show"></span>
                    </div>
                    <div class="cnt">
                        <span class="title">O</span><span id="cnt_out" class="cnt_show">
                        </span>
                    </div>
                    <div id="data_result" class="cnt"></div>
                </div>
                <div id="matchData">
                    <span id="batter_index"></span>
                    <span id="batter_name"></span>
                </div>
                <div class="select-block"></div>
                <div id="block_1">
                    <input type="button" class="login_form_input button" value="ボール" onclick="hit(1)"><br>
                    <input type="button" class="login_form_input button" value="空振りストライク" onclick="hit(2)"><br>
                    <input type="button" class="login_form_input button" value="見逃しストライク" onclick="hit(3)"><br>
                    <input type="button" class="login_form_input button" value="ファウル" onclick="hit(4)"><br>
                    <input type="button" class="login_form_input button" value="ファウルフライ" onclick="hit(5)"><br>
                    <input type="button" class="login_form_input button" value="バントファウル" onclick="hit(6)"><br>
                    <input type="button" class="login_form_input button" value="バント空振り" onclick="hit(7)"><br>
                    <input type="button" class="login_form_input button" value="ゴロ" onclick="hit(8)"><br>
                    <input type="button" class="login_form_input button" value="フライ" onclick="hit(9)"><br>
                    <input type="button" class="login_form_input button" value="ヒット" onclick="hit(10)"><br>
                    <input type="button" class="login_form_input button" value="ホームラン" onclick="hit(11)"><br>
                    <input type="button" class="login_form_input button" value="ランニングホームラン" onclick="hit(12)"><br>
                </div>
                <div id="block_2_1">
                    <input type="button" class="login_form_input button" value="ピッチャー" onclick="position(1)"><br>
                    <input type="button" class="login_form_input button" value="キャッチャー" onclick="position(2)"><br>
                    <input type="button" class="login_form_input button" value="ファースト" onclick="position(3)"><br>
                    <input type="button" class="login_form_input button" value="セカンド" onclick="position(4)"><br>
                    <input type="button" class="login_form_input button" value="サード" onclick="position(5)"><br>
                    <input type="button" class="login_form_input button" value="ショート" onclick="position(6)"><br>
                    <input type="button" class="login_form_input button" value="レフト" onclick="position(7)"><br>
                    <input type="button" class="login_form_input button" value="センター" onclick="position(8)"><br>
                    <input type="button" class="login_form_input button" value="ライト" onclick="position(9)"><br>
                </div>
                <div id="block_2_2">
                    <input type="button" class="login_form_input button" value="レフト" onclick="position(7)"><br>
                    <input type="button" class="login_form_input button" value="センター" onclick="position(8)"><br>
                    <input type="button" class="login_form_input button" value="ライト" onclick="position(9)"><br>
                </div>
                <div id="block_3">
                    <input type="button" class="login_form_input button" value="前" onclick="place(1)"><br>
                    <input type="button" class="login_form_input button" value="オーバー" onclick="place(2)"><br>
                    <input type="button" class="login_form_input button" value="左中間" onclick="place(3)"><br>
                    <input type="button" class="login_form_input button" value="右中間" onclick="place(4)"><br>
                </div>
                <div id="block_3_l">
                    <input type="button" class="login_form_input button" value="前" onclick="place(1)"><br>
                    <input type="button" class="login_form_input button" value="オーバー" onclick="place(2)"><br>
                    <input type="button" class="login_form_input button" value="左中間" onclick="place(3)"><br>
                </div>
                <div id="block_3_r">
                    <input type="button" class="login_form_input button" value="前" onclick="place(1)"><br>
                    <input type="button" class="login_form_input button" value="オーバー" onclick="place(2)"><br>
                    <input type="button" class="login_form_input button" value="右中間" onclick="place(4)"><br>
                </div>
                <div id="block_4">
                    <div class="" id="batter_runner">
                        <div class="label">
                            バッターランナー
                        </div>
                        <select name="batter_runner" id="br" class="login_form_input button">
                            <option value="" disabled selected>選択してください</option>
                            <option value="1" id="br1">1塁へ</option>
                            <option value="2" id="br2">2塁へ</option>
                            <option value="3" id="br3">3塁へ</option>
                            <option value="4" id="br4">ホームへ</option>
                        </select><br>
                    </div>
                    <div class="" id="first_runner">
                        <div class="label">1塁ランナー</div>
                        <select name="first_runner" id="fr" class="login_form_input button">
                            <option value="" id="frsel" disabled selected>選択してください</option>
                            <option value="0" id="fr0">アウト</option>
                            <option value="1" id="fr1">そのまま</option>
                            <option value="2" id="fr2">2塁へ</option>
                            <option value="3" id="fr3">3塁へ</option>
                            <option value="4" id="fr4">ホームへ</option>
                        </select><br>
                    </div>
                    <div class="" id="second_runner">
                        <div class="label">
                            2塁ランナー
                        </div>
                        <select name="second_runner" id="sr" class="login_form_input button">
                            <option value="" id="srsel" disabled selected>選択してください</option>
                            <option value="0" id="sr0">アウト</option>
                            <option value="2" id="sr2">そのまま</option>
                            <option value="3" id="sr3">3塁へ</option>
                            <option value="4" id="sr4">ホームへ</option>
                        </select><br>
                    </div>
                    <div class="" id="third_runner">
                        <div class="label">
                            3塁ランナー
                        </div>
                        <select name="third_runner" id="tr" class="login_form_input button">
                            <option value="" id="trsel" disabled selected>選択してください</option>
                            <option value="0" id="tr0">アウト</option>
                            <option value="3" id="tr3">そのまま</option>
                            <option value="4" id="tr4">ホームへ</option>
                        </select><br>
                    </div>
                    <input type="button" class="login_form_input button" id="OK" value="OK" onclick="setRunner()">
                </div>
                <div id="block_next">
                    <input type="button" class="login_form_input button" id="btn_next" value="次へ" onclick="display_none(block_next); submit()">
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                <script src="/script/script.js"></script>
            </div>
        </div>
    </div>

    <!-- script -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</body>

</html>