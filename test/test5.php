<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <style>

    .flex_test-box {
        background-color: #eee;     /* 背景色指定 */
        padding:  10px;             /* 余白指定 */
        display: flex;              /* フレックスボックスにする */
    }
     
    .flex_test-item {
        padding: 10px;
        color:  #fff;               /* 文字色 */
        margin:  10px;              /* 外側の余白 */
        border-radius:  5px;        /* 角丸指定 */
        font-size: 30px;            /* 文字サイズ指定 */
        text-align:  center;        /* 文字中央揃え */
    }
     
    .flex_test-item:nth-child(1) {
        background-color:  #2196F3; /* 背景色指定 */
        /* flex:2 1 100px; */            /* 幅指定 */
        flex-basis:  600px;         /* 幅指定 */
    }
     
    .flex_test-item:nth-child(2) {
        background-color:  #4CAF50; /* 背景色指定 */
        /* flex:1 3 100px; */            /* 幅指定 */
        flex-basis:  300px;         /* 幅指定 */
    }
     
    .flex_test-item:nth-child(3) {
        background-color: #3F51B5;  /* 背景色指定 */
    }
     
    .flex_test-item:nth-child(4) {
        background-color: #00BCD4;  /* 背景色指定 */
    }

    </style>
  </head>
  <body>

    <div class="flex_test-box">
        <div class="flex_test-item">
            1.コンテンツが入ります。コンテンツが入ります。
        </div>
        <div class="flex_test-item">
            2.コンテンツが入ります。コンテンツが入ります。コンテンツが入ります。コンテンツが入ります。コンテンツが入ります。
        </div>
        <!-- <div class="flex_test-item">
            3.コンテンツが入ります。コンテンツが入ります。コンテンツが入ります。
        </div>
        <div class="flex_test-item">
            4.コンテンツが入ります。
        </div> -->
    </div>

  </body>
</html>
