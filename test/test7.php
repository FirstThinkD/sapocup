<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <style>

.send {
    background-color: transparent;
    border: none;
    cursor: pointer;
    outline: none;
    padding: 0;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding: 0.8em 5.6em;
    background: #e95b43;
    font-size: 1.4rem;
    color: #fff;
    text-align: center;
    display: block;
    margin: auto;
}
.send[disabled] {
    background-color: #aaa;
/*    cursor: not-allowed; */
}
.required {
    color:red;
}
.required:before {
    content: "【必須】";
}

    </style>

  </head>
  <body>

<form>
    <label for="input01">入力欄1つめ</label>
    <input required id="input01" type="text" />
    <label for="input02">入力欄2つめ</label>
    <input type="text"  id="input02"/>
    <label for="input03">入力欄3つめ</label>
    <input required type="text"  id="input03"/>
    <button type="submit" class="send">送信</button>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>

$(function() {
    //始めにjQueryで送信ボタンを無効化する
    $('.send').prop("disabled", true);
    
    //始めにjQueryで必須欄を加工する
    $('form input:required').each(function () {
        $(this).prev("label").addClass("required");
    });
    
    // 設定 入力不可
//    $("#input02").prop('disabled', true);
//    $("#input02").prev("label").addClass("required");
    // 解除 入力可能
//    $("#input03").prop('disabled', false);
      $("#input03").prev("label").removeClass("required");

        console.log('inp01');
        var inp01 = $("#input01").val();
        console.log('inp01: ', inp01);
//        if ($("#input01").eq(e).val() != "") {
//            $("#input02").prev("label").addClass("required");
//        }

    //入力欄の操作時
    $('form input:required').change(function () {
        //必須項目が空かどうかフラグ
        let flag = true;
        //必須項目をひとつずつチェック
        $('form input:required').each(function(e) {
            //もし必須項目が空なら
            if ($('form input:required').eq(e).val() === "") {
                flag = false;
            }
        });

        //全て埋まっていたら
        if (flag) {
            //送信ボタンを復活
            $('.send').prop("disabled", false);
        } else {
            //送信ボタンを閉じる
            $('.send').prop("disabled", true);
        }
    });
});

  </script>
  </body>
</html>
