
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

// 試合情報
let score = [0, 0];
let batterIndex_list = [1, 1];
let batterName = "";
showBatterName(1, 0);
let runner_list = [0, 0, 0];

console.log(batterName);



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

// データのリセット
let data_result = "";
let data_hit = "";
let data_position = "";
let data_place = "";
let data_run = "";

let data_post = "";

let data_stack = [];

// 打球の分類
let value_hit = 0;

// ポジションの分類
let value_position = 0;
let value_place = 0;
let value_run = 0;

// 
let move_list = [0, 0, 0, 0];
let br = 0;
let fr = 0;
let sr = 0;
let tr = 0;
let point = 0;

/* 関数_表示 */

// style display: none
function display_none(none) {
    none.style.display = "none";
}

// style display: block;
function display_block(block) {
    block.style.display = "block";
}

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
            show_cnt = '　　　';
            break;
        case 1:
            show_cnt = '●　　';
            break;
        case 2:
            show_cnt = '●●　';
            break;
        case 3:
            show_cnt = '●●●';
            break;
        default:
            break;
    }

    return show_cnt;
}

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
            cnt_out++;
            cnt_ball = 0;
            cnt_strike = 0;
            break;
        case 9:
            data_hit = 'フライ';
            cnt_out++;
            cnt_ball = 0;
            cnt_strike = 0;
            break;
        case 10:
            data_hit = 'ヒット';
            cnt_ball = 0;
            cnt_strike = 0;
            break;
        case 11:
            data_hit = 'ホームラン';
            cnt_ball = 0;
            cnt_strike = 0;
            value_run = 4;
            break;
        case 12:
            data_hit = 'ランニングホームラン';
            cnt_ball = 0;
            cnt_strike = 0;
            value_run = 4;
            break;
        default:
            break;
    }

    // フォアボール
    if (cnt_ball == 4) {
        cnt_ball = 0;
        cnt_strike = 0;
        flag_fourBall = true;
        flag_batterChange = true;
    }

    // 次へ進む
    if (value_hit <= 4 || value_hit == 6 || value_hit == 7) {
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
            display_switch(block_2_1, block_next);
            show_result();
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

//　ランナー判定
function showRunnerList() {
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

function setRunner() {
    br = 0;
    fr = 0;
    sr = 0;
    tr = 0;
    move_list = [0, 0, 0, 0];
    if (document.getElementById("br").value != "") {
        br = document.getElementById("br").value;
        if (br >= 4) {
            br = 4;
        }
        if (br >= 1)
            move_list[br - 1]++;
    }
    else {
        alert("選択してください");
    }
    if (document.getElementById("fr") != "") {
        fr = document.getElementById("fr").value;
        if (fr >= 4) {
            fr = 4;
        }
        if (fr >= 1)
            move_list[fr - 1]++;
    }
    if (document.getElementById("sr") != "") {
        sr = document.getElementById("sr").value;
        if (sr >= 4) {
            sr = 4;
        }
        if (sr >= 1)
            move_list[sr - 1]++;
    }
    if (document.getElementById("tr") != "") {
        tr = document.getElementById("tr").value;
        if (tr >= 4) {
            tr = 4;
        }
        if (tr >= 1)
            move_list[tr - 1]++;
    }
    runner_list[0] = move_list[0];
    runner_list[1] = move_list[1];
    runner_list[2] = move_list[2];
    value_run = br;

    point = move_list[3];

    console.log(point);

    console.log(move_list);

    if (value_run == 2) {
        data_run = "ツーベス";
        flag_showResult = true;
    }
    else if (value_run == 3) {
        data_run = "スリーベース";
        flag_showResult = true;
    }
    display_switch(block_4, block_next);
    show_result();
}

$(function ($) {
    $('#br').change(function () {
        frval = $("#br").val(); //選択したメニューの値
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

/* 関数_結果を表示・送信 */

// 結果を表示
function show_result() {
    display_block(result);  //block_result(結果)を表示
    document.getElementById("cnt_ball").innerHTML = num2cnt(cnt_ball);  // ボールカウントの表示
    document.getElementById("cnt_strike").innerHTML = num2cnt(cnt_strike);  // ストライクカウントの表示
    document.getElementById("cnt_out").innerHTML = num2cnt(cnt_out);  // アウトカウントの表示

    // 打球等のイベントの記録
    data_stack.push(data_hit);

    // 結果_フォアボール
    if (flag_fourBall == true) {
        data_result = 'フォアボール';
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

    // バッターの整理
    // バッターが交代するフラグの判定
    if (flag_batterChange == true) {
        document.getElementById("btn_next").value = "送信";
    } else {
        document.getElementById("btn_next").value = "次へ";
    }

    // 結果を画面に表示
    document.getElementById("data_result").innerHTML = data_hit + "<br>" + data_result;

}

// 結果を送信
function submit() {
    // 結果を送信
    if (flag_batterChange == true) {
        postData(0, data_result, data_stack.join(','), value_position, data_place, value_run);

        // data_stackのリセット
        data_stack = [];
    }
    display_switch(block_next, block_1);
    flag_reset();
    data_reset();
}

function postData(team_flag, result, position, place, b_runner, ball_array) {
    $.post({
        url: '/writeData/play_data.php',
        data: {
            'team_flag': team_flag,
            'result': result,
            'position': position,
            'place': place,
            'b_runner': b_runner,
            'ball_array': ball_array,
        },
        dataType: 'json', //json形式で返すように設定
    }).done(function (_data) {
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        console.error(errorThrown);
    })
}

function showBatterName(batter_index, team_flag) {
    $.post({
        url: '/getData/batter_name.php',
        data: {
            'batter_index': batter_index,
            'team_flag': team_flag,
        },
        dataType: 'json', // json形式で返すように設定
    }).done(function (data) {
        $('#batter_index').text(data.batter_index + '番');
        $('#batter_name').text(data.batter_name);
    }).fail(function (_XMLHttpRequest, _textStatus, errorThrown) {
        console.error(errorThrown);
    })
}
