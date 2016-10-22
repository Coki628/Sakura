<?php
//POSTで登録IDを受信
$id = $_POST['regId'];
$devid = $_POST['devId'];
 
//サーバー上のテキストファイルに書き込み
$fp = fopen("id.txt", "a");
fwrite($fp, $id);      //IDを書き込む
fwrite($fp, "\r\n");   //改行しとく
fwrite($fp, $devid);   //IDを書き込む
fwrite($fp, "\r\n");   //改行しとく
fclose($fp);

//DBへ接続(URL,user,pass)
$link = mysql_connect('mysql531.db.sakura.ne.jp', 'coki628', 'coki0628');
if (!$link) {									//falseなら
    die('接続失敗です。'.mysql_error());			//dieでスクリプト終了
}

//DBを選択
$db_selected = mysql_select_db('coki628_gcm', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

mysql_set_charset('utf8');		//文字化け防止

$time = date("Y-m-d H:i:s");	//MySQLのDATETIME書式で現在時刻を取得

//POSTで受けたIDをDBに格納
$sql = "INSERT INTO regid (reg_id, reg_time, dev_id) VALUES ('$id', '$time', '$devid')";//変数も''で囲む
$result = mysql_query($sql);
if (!$result) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}

//dev_idに重複が出たら最新以外のold_flagをTRUE(1)に
$sql = "UPDATE regid SET old_flag = 1 WHERE id NOT IN (SELECT max_id FROM (SELECT max(t1.id) AS max_id FROM regid AS t1 GROUP BY t1.dev_id) AS t2)";
$result = mysql_query($sql);
if (!$result) {
    die('UPDATEクエリーが失敗しました。'.mysql_error());
}

//接続の切断
$close_flag = mysql_close($link);
if (!$close_flag){
    die('切断失敗です。'.mysql_error());	
}

print('save ok!');
?>