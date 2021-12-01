<?php
// データベース接続
// $host = localhostで動かなければipアドレスを記載
$host = '150.60.231.106';
// データベース名
$dbname = 'haw1008ufet9_spcp01';
// ユーザー名
$dbuser = 'haw1008ufet9';
// パスワード
$dbpass = 'LcifXbRp';

// データベース接続クラスPDOのインスタンス$dbhを作成する
try {
    $dbh = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $dbuser, $dbpass);
// PDOExceptionクラスのインスタンス$eからエラーメッセージを取得
} catch (PDOException $e) {
    // 接続できなかったらvar_dumpの後に処理を終了する
    var_dump($e->getMessage());
    exit;
}

// データ取得用SQL
// 値はバインドさせる
$sql = "SELECT c_id, c_type FROM customer WHERE id = ?";
// SQLをセット
$stmt = $dbh->prepare($sql);
// SQLを実行
$stmt->execute();

// あらかじめ配列$productListを作成する
// 受け取ったデータを配列に代入する
// 最終的にhtmlへ渡される
$productList = array();

// fetchメソッドでSQLの結果を取得
// 定数をPDO::FETCH_ASSOC:に指定すると連想配列で結果を取得できる
// 取得したデータを$productListへ代入する
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $productList[] = array(
        'c_id'    => $row['c_id'],
        'c_type'  => $row['c_type']
    );
}

// ヘッダーを指定することによりjsonの動作を安定させる
header('Content-type: application/json');
// htmlへ渡す配列$productListをjsonに変換する
echo json_encode($productList);