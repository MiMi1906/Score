
/* 初期設定 */
// 表示設定
display_block(block_1);
display_none(block_2_1);
display_none(block_2_2);
display_none(block_3);
display_none(block_3_l);
display_none(block_3_r);
display_none(block_4);
display_none(block_next);
display_none(change);
display_none(end_btn);
display_none(stolen);
display_none(bant);

// 試合情報
let matchData;
let score = [0, 0];
let batterIndex_list = [1, 1];
let cnt_inning = 1;
let flag_inningChange = 1;
let attack_flag = 0;

let runner_list = [0, 0, 0];

// カウント設定
let cnt_ball = 0;
let cnt_strike = 0;
let cnt_out = 0;

// フラグの設定
let flag_change = false;
let flag_fourBall = false;
let flag_missedStrikeOut = false;
let flag_strikeOut = false;
let flag_3BuntFailure = false;
let flag_showResult = false;
let flag_batterChange = false;
let flag_forcePlay = false;
let flag_homeRun = false;
let flag_hit = false;
let flag_deadBall = false;
let flag_stolen = false;
let flag_submit = false;
let flag_out = false;

// データのリセット
let data_result = "";
let data_hit = "";
let data_position = "";
let data_place = "";
let data_run = "";

let data_stack = [];

// 打球の分類
let value_hit = 0;

// ポジションの分類
let value_position = 0;
let value_place = 0;
let value_run = 0;

// ランナーの情報
let br = 0;
let fr = 0;
let sr = 0;
let tr = 0;
let hb = 0;

// 試合開始準備
// 試合情報の取得
getMatchData();

attack_flag = matchData.attack_flag;

// 試合開始
game_start();

// それっぽく見せるため2秒待たせる
$(function () {
    setTimeout('stopload()', 2000);
});

// 読み込みを終了
function stopload() {
    $('#wrap').css('display', 'block');
    $('#loading').css('display', 'none');
}

// スリープ関数
const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

// リロード時のアラート
var onBeforeunloadHandler = function (e) {
    e.returnValue = '更新すると、試合が強制終了されます。更新しますか?';
};
// イベントを登録
window.addEventListener('beforeunload', onBeforeunloadHandler, false);

document.getElementById("exit").addEventListener('click', function (e) {
    // イベントを削除
    window.removeEventListener('beforeunload', onBeforeunloadHandler, false);
}, false);

/* 関数_表示 */

// 非表示
// style display: none
function display_none(none) {
    none.style.display = "none";
}

// 表示
// style display: block;
function display_block(block) {
    block.style.display = "block";
}

// 非表示・表示を切り替え
// 第一引数 : style display: none;
// 第二引数 : style display: block;
function display_switch(none, block) {
    display_none(none);
    display_block(block);
}

// 数字を丸に変換
function num2cnt(cnt) {
    let show_cnt;
    switch (cnt) {
        case 0:
            show_cnt = '';
            break;
        case 1:
            show_cnt = '●';
            break;
        case 2:
            show_cnt = '●●';
            break;
        case 3:
            show_cnt = '●●●';
            break;
        default:
            show_cnt = '●●●';
            break;
    }
    return show_cnt;
}

/* 関数_データを整理 */

// カウントをリセット
function cnt_reset() {
    cnt_ball = 0;
    cnt_strike = 0;
    cnt_out = 0;
}

// フラグをリセット
function flag_reset() {
    flag_change = false;
    flag_fourBall = false;
    flag_missedStrikeOut = false;
    flag_strikeOut = false;
    flag_3BuntFailure = false;
    flag_showResult = false;
    flag_batterChange = false;
    flag_forcePlay = false;
    flag_homeRun = false;
    flag_hit = false;
    flag_deadBall = false;
    flag_stolen = false;
    flag_submit = false;
}

//データをリセット
function data_reset() {
    data_result = "";
    data_hit = "";
    data_position = "";
    data_place = "";
    data_run = "";
}

/* 関数_結果を記録 */

// 打球判定
function hit(value) {
    flag_showResult = false;
    value_hit = value;
    switch (value_hit) {
        case 1:
            data_hit = 'ボール';
            cnt_ball++;
            break;
        case 2:
            data_hit = '空振りストライク';
            cnt_strike++;
            break;
        case 3:
            data_hit = '見逃しストライク';
            cnt_strike++;
            break;
        case 4:
            data_hit = 'ファウル';
            if (cnt_strike < 2) {
                cnt_strike++;
            }
            break;
        case 5:
            data_hit = 'ファウルフライ';
            cnt_out++;
            cnt_ball = 0;
            cnt_strike = 0;
            flag_out = true;
            break;
        case 6:
            data_hit = 'バントファウル';
            cnt_strike++;
            break;
        case 7:
            data_hit = 'バント空振り';
            cnt_strike++;
            break;
        case 8:
            data_hit = 'ゴロ';
            if (runner_list[0] == 0 && runner_list[1] == 0 && runner_list[2] == 0) {
                cnt_out++;
            }
            cnt_ball = 0;
            cnt_strike = 0;
            break;
        case 9:
            data_hit = 'フライ';
            cnt_out++;
            cnt_ball = 0;
            cnt_strike = 0;
            flag_out = true;
            break;
        case 10:
            data_hit = 'ヒット';
            cnt_ball = 0;
            cnt_strike = 0;
            flag_hit = true;
            break;
        case 11:
            data_hit = 'ホームラン';
            cnt_ball = 0;
            cnt_strike = 0;
            value_run = 4;
            score[attack_flag] += runner_list[0] + runner_list[1] + runner_list[2] + 1;
            flag_homeRun = true;
            break;
        case 12:
            data_hit = 'ランニングホームラン';
            cnt_ball = 0;
            cnt_strike = 0;
            value_run = 4;
            score[attack_flag] += runner_list[0] + runner_list[1] + runner_list[2] + 1;
            flag_homeRun = true;
            break;
        case 13:
            data_hit = 'デッドボール';
            flag_deadBall = true;
            cnt_ball = 0;
            cnt_strike = 0;
            flag_batterChange = true;
            flag_showResult = true;
            break;
        case 14:
            data_hit = 'バント';
            if (runner_list[0] == 0 && runner_list[1] == 0 && runner_list[2] == 0) {
                cnt_out++;
            }
            cnt_ball = 0;
            cnt_strike = 0;
            value_hit = 8;
            break;
        default:
            break;
    }

    flag_stolen = false;
    display_none(stolen);

    // フォアボール
    if (cnt_ball == 4) {
        cnt_ball = 0;
        cnt_strike = 0;
        flag_fourBall = true;
        flag_batterChange = true;
    }

    // 次へ進む
    if (value_hit <= 4 || value_hit == 6 || value_hit == 7 || value_hit == 13) {
        display_switch(block_1, block_next);
        flag_showResult = true;
    }

    // ポジションを選択
    else {
        flag_batterChange = true;
        // ホームラン
        if (value_hit == 11) {
            display_switch(block_1, block_2_2);
        }
        // それ以外
        else {
            display_switch(block_1, block_2_1);
        }
    }

    // 三振
    if (cnt_strike == 3) {
        cnt_ball = 0;
        cnt_strike = 0;
        cnt_out++;
        if (value == 2) flag_strikeOut = true;
        else if (value == 3) flag_missedStrikeOut = true;
        else if (value == 5 || value == 6) flag_3BuntFailure = true;
        flag_batterChange = true;
    }

    // 結果の表示のフラグをチェック
    if (flag_showResult == true) {
        show_result();
    }

}

// ポジション判定_ポジション
function position(value) {
    value_position = value;
    switch (value_position) {
        case 1:
            data_position = 'ピッチャー';
            break;
        case 2:
            data_position = 'キャッチャー';
            break;
        case 3:
            data_position = 'ファースト';
            break;
        case 4:
            data_position = 'セカンド';
            break;
        case 5:
            data_position = 'サード';
            break;
        case 6:
            data_position = 'ショート';
            break;
        case 7:
            data_position = 'レフト';
            break;
        case 8:
            data_position = 'センター';
            break;
        case 9:
            data_position = 'ライト';
            break;
        default:
            break;
    }

    // 打球 : ヒット or ランニングホームラン
    if (value_hit == 10) {
        //ポジション : 内野
        if (value_position <= 6) {
            display_none(block_2_1);
            showRunnerList(); // display_switch(block_2_1, block_4);
            if (value_hit == 10) {
                data_hit = "内野安打";
            }
        }
        // ポジション : 外野
        else {
            // レフト
            if (value_position == 7) {
                display_switch(block_2_1, block_3_l);
            }
            // ライト
            else if (value_position == 9) {
                display_switch(block_2_1, block_3_r);
            } else {
                display_switch(block_2_1, block_3)
            }

        }
    }
    else if (value_hit == 12) {
        if (value_position <= 6) {
            display_switch(block_2_1, block_next);
            show_result();
        }
        // ポジション : 外野
        else {
            // レフト
            if (value_position == 7) {
                display_switch(block_2_1, block_3_l);
            }
            // ライト
            else if (value_position == 9) {
                display_switch(block_2_1, block_3_r);
            } else {
                display_switch(block_2_1, block_3)
            }

        }
    }
    // 打球 : それ以外
    else {
        // ホームラン
        if (value_hit == 11) {
            data_position += "スタンドへの";
            display_switch(block_2_2, block_next);
            show_result();
        }
        // それ以外
        else {
            display_none(block_2_1);
            if (runner_list[0] == 0 && runner_list[1] == 0 && runner_list[2] == 0) {
                display_block(block_next);
                show_result();
            } else {
                flag_forcePlay = true;
                showRunnerList();
            }
        }
    }
}

// ポジション判定_打球が飛んだ場所
function place(value) {
    value_place = value;
    switch (value_place) {
        case 1:
            data_place = "前";
            break;
        case 2:
            data_place = "オーバー";
            break;
        case 3:
            data_place = "左中間";
            break;
        case 4:
            data_place = "右中間";
            break;
        default:
            break;
    }
    // ランニングホームラン
    if (value_hit == 12) {
        display_none(block_3);
        display_none(block_3_l);
        display_none(block_3_r);
        display_block(block_next);
        show_result();
    }
    // それ以外
    else {
        display_none(block_3);
        display_none(block_3_l);
        display_none(block_3_r);
        showRunnerList();
    }
}

// ランナー判定
function showRunnerList() {
    display_block(brsel);
    display_block(br0);
    display_block(br1);
    display_block(br2);
    display_block(br3);
    display_block(br4);
    display_block(fr0);
    display_block(fr1);
    display_block(fr2);
    display_block(fr3);
    display_block(fr4);
    display_block(sr0);
    display_block(sr2);
    display_block(sr3);
    display_block(sr4);
    display_block(tr0);
    display_block(tr3);
    display_block(tr4);
    display_block(batter_runner);

    if (flag_stolen) {
        display_none(batter_runner);
    }
    if (flag_out) {
        display_none(batter_runner);
    }
    flag_out = false;

    if (flag_hit) {
        display_none(br0);
    } else {
        display_block(br0);
        display_block(batter_runner);
    }
    if (runner_list[0] == 1) {
        display_block(first_runner);
    } else {
        display_none(first_runner);
    }
    if (runner_list[1] == 1) {
        display_block(second_runner);
    } else {
        display_none(second_runner);
    }
    if (runner_list[2] == 1) {
        display_block(third_runner);
    } else {
        display_none(third_runner);
    }
    display_block(block_4);
}

// ランナーを移動
function setRunner() {
    br = 0;
    fr = 0;
    sr = 0;
    tr = 0;
    hb = 0;
    runner_list = [0, 0, 0];
    // バッターランナー
    if (document.getElementById("br").value != "" && !flag_stolen) {
        br = document.getElementById("br").value;
        if (br >= 4) {
            hb += 1;
        }
        else if (br >= 1) {
            runner_list[br - 1]++;
        }
        else if (br == 0) {
            cnt_out++;
        }
    }
    // ファーストランナー
    if (document.getElementById("fr") != "") {
        fr = document.getElementById("fr").value;
        if (fr >= 4) {
            hb += 1;
        }
        else if (fr >= 1) {
            runner_list[fr - 1]++;
        }
        else if (fr == 0) {
            cnt_out++;
            runner_list[0] = 0;
        }
    }
    // セカンドランナー
    if (document.getElementById("sr") != "") {
        sr = document.getElementById("sr").value;
        if (sr >= 4) {
            hb += 1;
        }
        else if (sr >= 1) {
            runner_list[sr - 1]++;
        }
        else if (sr == 0) {
            cnt_out++;
            runner_list[1] = 0;
        }
    }
    // サードランナー
    if (document.getElementById("tr") != "") {
        tr = document.getElementById("tr").value;
        if (tr >= 4) {
            hb += 1;
        }
        else if (tr >= 1) {
            runner_list[tr - 1]++;
        }
        else if (tr == 0) {
            cnt_out++;
            runner_list[2] = 0;
        }
    }

    // ランナーを移動
    value_run = br;

    // 点数を計算
    score[attack_flag] += hb;

    // ツーベース
    if (value_run == 2) {
        data_run = "ツーベース";
        flag_showResult = true;
    }
    // ツーベース
    else if (value_run == 3) {
        data_run = "スリーベース";
        flag_showResult = true;
    }
    display_switch(block_4, block_next);
    show_result();
}

// 盗塁
function stolen_base() {
    data_hit = '盗塁';
    data_result = '盗塁';
    flag_stolen = true;
    flag_submit = true;
    display_none(block_1);
    display_none(stolen);
    showRunnerList();
}

// 適切な値を入力させるための関数
// バッターランナー用
$(function ($) {
    $('#br').change(function () {
        frval = $("#br").val(); //選択したメニューの値
        if (frval == -1) {
            alert('選択してください');
        }
        if (frval >= 1) {
            display_none(fr1);
        } else {
            display_block(fr1);
        }
        if (frval >= 2) {
            display_none(fr2);
            display_none(sr2);
        } else {
            display_block(fr2);
            display_block(sr2);
        }
        if (frval >= 3) {
            display_none(fr3);
            display_none(sr3);
            display_none(tr3);
        } else {
            display_block(fr3);
            display_block(sr3);
            display_block(tr3);
        }
    })
});

// ファーストランナー用
$(function ($) {
    $('#fr').change(function () {
        frval = $("#fr").val(); //選択したメニューの値
        if (frval >= 2) {
            display_none(sr2);
        } else {
            display_block(sr2);
        }
        if (frval >= 3) {
            display_none(sr3);
            display_none(tr3);
        } else {
            display_block(sr3);
            display_block(tr3);
        }
    })
});

// セカンドランナー用
$(function ($) {
    $('#sr').change(function () {
        frval = $("#sr").val(); //選択したメニューの値
        if (frval >= 3) {
            display_none(tr3);
        } else {
            display_block(tr3);
        }
    })
});

/* 関数_試合を制御 */
function game_start() {
    // バッターの名前を表示
    showBatterName(batterIndex_list[attack_flag], attack_flag);
    showInning();
    showRunner();
    showScore();

    document.getElementById('br').options[0].selected = true;
    document.getElementById('fr').options[0].selected = true;
    document.getElementById('sr').options[0].selected = true;
    document.getElementById('tr').options[0].selected = true;

    // 打順を次へ
    // 9番なら1番に戻す
    if (batterIndex_list[attack_flag] == 9) {
        batterIndex_list[attack_flag] = 1;
    } else {
        batterIndex_list[attack_flag]++;
    }
}

function next() {
    // ストライクカウント・ボールカウントをリセット
    cnt_ball = 0;
    cnt_strike = 0;
    flag_reset();

    document.getElementById('br').options[0].selected = true;
    document.getElementById('fr').options[0].selected = true;
    document.getElementById('sr').options[0].selected = true;
    document.getElementById('tr').options[0].selected = true;

    data_stack = [];

    // スリーアウトでチェンジ
    if (cnt_out >= 3) {
        if (flag_inningChange == 0) {
            cnt_inning++;
        }
        if (cnt_inning >= 10) {
            display_none(match_data);
            display_none(select_block);
            display_block(end_btn);
            document.getElementById("match_end").innerHTML = '<div class="alert alert-primary" role="alert">' + '<p>試合終了</p><hr><p class="mb-0">' + score[0] + ' - ' + score[1] + '</p>' + '</div>'
            matchEnd();
        }
        attack_flag = (attack_flag + 1) % 2;
        flag_inningChange = (flag_inningChange + 1) % 2;
        runner_list = [0, 0, 0];
        data_reset();
        cnt_reset();
        document.getElementById("cnt_ball").innerHTML = num2cnt(cnt_ball);  // ボールカウントの表示
        document.getElementById("cnt_strike").innerHTML = num2cnt(cnt_strike);  // ストライクカウントの表示
        document.getElementById("cnt_out").innerHTML = num2cnt(cnt_out);  // アウトカウントの表示
    }



    // バッターの名前を表示
    showBatterName(batterIndex_list[attack_flag], attack_flag);
    showInning();
    showRunner();
    showScore();

    showStolenBase();
    showBant();

    // 打順を次へ
    // 9番なら1番に戻す
    if (batterIndex_list[attack_flag] == 9) {
        batterIndex_list[attack_flag] = 1;
    } else {
        batterIndex_list[attack_flag]++;
    }
}

// イニング&裏表を表示
function showInning() {
    let inning_str;
    if (flag_inningChange == 1) {
        inning_str = '表';
    } else {
        inning_str = '裏';
    }
    $('#inning').text(cnt_inning + '回' + inning_str);
}

// ランナーを表示
function showRunner() {
    let runner_str = '';
    let runner_flag = false;
    if (runner_list[0] == 1) {
        runner_str += '1塁';
        runner_flag = true;
    }
    if (runner_list[1] == 1) {
        runner_str += ' 2塁';
        runner_flag = true;
    }
    if (runner_list[2] == 1) {
        runner_str += ' 3塁';
        runner_flag = true;
    }
    if (runner_flag) {
        $('#runner').text('ランナー ' + runner_str);
    }
}

// 点数を表示
function showScore() {
    $('#score').text(score[0] + ' - ' + score[1]);
}

// 盗塁の選択肢を表示
function showStolenBase() {
    if (runner_list[0] != 0 || runner_list[1] != 0) {
        flag_stolen = true;
        display_block(stolen);
    } else {
        flag_stolen = false;
        display_none(stolen);
    }
}

// バントの選択肢を表示
function showBant() {
    if (runner_list[0] != 0 || runner_list[1] != 0 || runner_list[2] != 0) {
        display_block(bant);
    } else {
        display_none(bant);
    }
}

/* 関数_結果を表示・送信 */

// 結果を表示
function show_result() {
    display_none(stolen);
    display_block(result);  // block_result(結果)を表示
    showRunner();
    document.getElementById("cnt_ball").innerHTML = num2cnt(cnt_ball);  // ボールカウントの表示
    document.getElementById("cnt_strike").innerHTML = num2cnt(cnt_strike);  // ストライクカウントの表示
    document.getElementById("cnt_out").innerHTML = num2cnt(cnt_out);  // アウトカウントの表示

    // 打球等のイベントの記録
    data_stack.push(data_hit);

    // 結果_フォアボール
    if (flag_fourBall == true) {
        data_result = 'フォアボール';
        let move = [0, 0, 0, 0];
        move[0] = 1;
        if (runner_list[0] == 1) {
            move[1] = 1;
        } else {
            move[1] = 0;
        }
        if (move[1] == 1) {
            if (runner_list[1] == 1) {
                move[2] = 1;
            } else {
                move[2] = 0;
            }
        } else {
            move[1] = runner_list[1];
        }
        if (move[2] == 1) {
            if (runner_list[2] == 1) {
                move[3] = 1;
            } else {
                move[3] = 0;
            }
        } else {
            move[2] = runner_list[2];
        }
        runner_list[0] = move[0];
        runner_list[1] = move[1];
        runner_list[2] = move[2];
        score[attack_flag] += move[3];
    }
    if (flag_deadBall == true) {
        data_result = 'デッドボール';
        let move = [0, 0, 0, 0];
        move[0] = 1;
        if (runner_list[0] == 1) {
            move[1] = 1;
        } else {
            move[1] = 0;
        }
        if (move[1] == 1) {
            if (runner_list[1] == 1) {
                move[2] = 1;
            } else {
                move[2] = 0;
            }
        } else {
            move[1] = runner_list[1];
        }
        if (move[2] == 1) {
            if (runner_list[2] == 1) {
                move[3] = 1;
            } else {
                move[3] = 0;
            }
        } else {
            move[2] = runner_list[2];
        }
        runner_list[0] = move[0];
        runner_list[1] = move[1];
        runner_list[2] = move[2];
        score[attack_flag] += move[3];
    }
    // 結果_見逃し三振
    if (flag_missedStrikeOut == true) {
        data_result = '見逃し三振';
    }
    // 結果_空振り三振
    if (flag_strikeOut == true) {
        data_result = '空振り三振';
    }
    // 結果_スリーバント失敗
    if (flag_3BuntFailure == true) {
        data_result = 'スリーバント失敗';
    }

    // 結果_打球がどこかに飛んだ場合(ファール以外)
    if (data_position != "") {
        data_result = data_position + data_place + data_run + data_hit;
    }

    if (flag_homeRun == true) {
        runner_list = [0, 0, 0];
        $('#runner').text('');
        flag_homeRun = false;
    }

    // バッターの整理
    // バッターが交代するフラグの判定
    if (flag_batterChange == true || flag_submit == true) {
        document.getElementById("btn_next").value = "送信";
    } else {
        document.getElementById("btn_next").value = "次へ";
    }

    // 結果を画面に表示
    if (data_result == '') {
        document.getElementById("data_result").innerHTML = '<div class="alert alert-success" role="alert">' + data_hit + '</div>';
    } else {
        document.getElementById("data_result").innerHTML = '<div class="alert alert-success" role="alert">' + data_result + '</div>'
    }

    if (cnt_out >= 3) {
        if (cnt_inning >= 9 && flag_inningChange == 0) {
            display_block(change);
            document.getElementById("change").innerHTML = '<div class="alert alert-primary" role="alert">' + '試合終了' + '</div>'
        } else {
            display_block(change);
            document.getElementById("change").innerHTML = '<div class="alert alert-warning" role="alert">' + 'チェンジ' + '</div>'
        }
    }
    else if (cnt_inning == 9 && flag_inningChange == 0 && score[0] < score[1]) {
        display_block(change);
        document.getElementById("change").innerHTML = '<div class="alert alert-primary" role="alert">' + 'サヨナラ' + '</div>'
        cnt_out = 3;
    }
}

// 結果を送信
function submit() {
    if (flag_stolen) {
        display_block(stolen);
    } else {
        display_none(stolen);
    }
    // 結果を送信
    if (flag_batterChange) {
        postData(attack_flag, data_result, value_position, data_place, value_run, data_stack.join(' / '), batterIndex_list[attack_flag] - 1, cnt_inning, (flag_inningChange + 1) % 2);
        next();
        data_stack = [];
    }
    else if (flag_submit) {
        postData(attack_flag, data_result, value_position, data_place, value_run, data_stack.join(' / '), '', cnt_inning, (flag_inningChange + 1) % 2);
        flag_stolen = false;
        data_stack = [];
        document.getElementById('br').options[0].selected = true;
        document.getElementById('fr').options[0].selected = true;
        document.getElementById('sr').options[0].selected = true;
        document.getElementById('tr').options[0].selected = true;
    }
    $('#data_result').text('');
    $('#change').text('');
    display_none(change);
    data_reset();
    showRunner();

    display_switch(block_next, block_1);
}

// 試合情報を取得(Ajax)
function getMatchData() {
    $.post({
        url: '/getData/match_data.php',
        dataType: 'json', //json形式で返すように設定
        async: false,
    }).done(function (data) {
        matchData = data;
        console.log(data);
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        // 失敗時はエラーを吐かせる
        console.error(errorThrown);
    })
}

// データを送信(Ajax)
function postData(team_flag, result, position, place, b_runner, ball_array, batter_index, inning, attack_flag) {
    console.log('batter_index >>> ' + batter_index);
    $.post({
        url: '/writeData/play_data.php',
        data: {
            'team_flag': team_flag,
            'result': result,
            'position': position,
            'place': place,
            'b_runner': b_runner,
            'ball_array': ball_array,
            'batter_index': batter_index,
            'inning': inning,
            'attack_flag': attack_flag,
        },
        dataType: 'text', //json形式で返すように設定
    }).done(function (data) {
        console.log(data);
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        // 失敗時はエラーを吐かせる
        console.error(errorThrown);
    })
}

// バッター情報を取得(Ajax)
function showBatterName(batter_index, team_flag) {
    $.post({
        url: '/getData/batter_name.php',
        data: {
            'batter_index': batter_index,
            'team_flag': team_flag,
        },
        dataType: 'json', // json形式で返すように設定
    }).done(function (data) {
        let badge;
        $('#batter_index').html(data.batter_index + '番');
        if (data.flag_lr == 'left') {
            badge = 'bg-primary';
            data.flag_lr = '左';
        } else if (data.flag_lr == 'right') {
            badge = 'bg-danger';
            data.flag_lr = '右';
        } else {
            badge = 'bg-warning';
            data.flag_lr = '両';
        }
        $('#batter_name').html(data.batter_name + ' ・ ' + data.batter_back_num + '<span class="badge ' + badge + '" style="margin-left: 7.5px; font-size: 14px;">' + data.flag_lr + '</small>');
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        // 失敗時はエラーを吐かせる
        console.error(errorThrown);
    })
}

function matchEnd() {
    $.post({
        url: '/writeData/match_end.php',
        data: {
            'my_team_score': score[0],
            'opp_team_score': score[1],
        },
        dataType: 'text',
    }).done(function (data) {
        console.log(data);
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        // 失敗時はエラーを吐かせる
        console.error(errorThrown);
    })
}
