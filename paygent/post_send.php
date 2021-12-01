<?php

$url = "https://nakazawakan.com/sapocup/post_recv.php";

$mail_to = "nakazawa6097@gmail.com";
// 送信データ
$data = array(
        "postrec" => "selfmedia1",
        "mail_to" => $mail_to
);

// URLエンコードされたクエリ文字列を生成
$data = http_build_query($data, "", "&");

// ストリームコンテキストのオプションを作成
$options = array(
        'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: '.strlen($data),
                'content' => $data
        )
);

// ストリームコンテキストの作成
$context = stream_context_create($options);

// POST送信
$contents = file_get_contents($url, false, $context);
echo $contents;
?>