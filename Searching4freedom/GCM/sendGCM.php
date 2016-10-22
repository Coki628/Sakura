<?php

$url = 'https://android.googleapis.com/gcm/send'; 

//フォームから送られたデータの受け取り
$message = $_POST['message'];
$id = $_POST['id'];

//DBへ接続(URL,user,pass)
$link = mysql_connect('mysql531.db.sakura.ne.jp', 'coki628', 'coki0628');
if (!$link) {                 //falseなら
    die('接続失敗です。'.mysql_error());     //dieでスクリプト終了
}

//DBを選択
$db_selected = mysql_select_db('coki628_gcm', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

mysql_set_charset('utf8');  	//文字化け防止

$time = date("Y-m-d H:i:s");	//MySQLのDATETIME書式で現在時刻を取得

//regidテーブルから、指定されたid行のreg_idフィールドを選択
$result = mysql_query("SELECT * FROM regid WHERE id = '$id'");
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
$row = mysql_fetch_assoc($result);
$registration_id = $row['reg_id'];

//宛先IDをDB格納用に変換
$dbId = $row['id'];
$dbId = strval($dbId);          //数値を文字列に変換

//POSTで受けたメッセージをDBに格納
$sql = "INSERT INTO messages (message, send_time, destination) VALUES ('$message', '$time', '$dbId')";  //変数も''で囲む
$result = mysql_query($sql);
if (!$result) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}

//messagesテーブルからIDが最大値(つまり最新)の行を取り出す
$result = mysql_query("SELECT * FROM messages WHERE id =(SELECT MAX(id) FROM messages)");
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
$table = mysql_fetch_assoc($result);

//接続の切断
$close_flag = mysql_close($link);
if (!$close_flag){
    die('切断失敗です。'.mysql_error());	
}



$header = array(
  'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
  'Authorization: key=AIzaSyDeUemII1slfrKtPO8ZvT1QnT4ze-E-C0c', //API keyはここ
);
$post_list = array(
 'registration_id' => $registration_id,
 'collapse_key' => 'update',
 'data.message' => $message,
);
$post = http_build_query($post_list, '&');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$ret = curl_exec($ch);

//var_dump($ret);	//送信結果を出力

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>GCM</title>
	</head>
	<body>
		<h1 style="text-align:center"><?php 
			if($registration_id == null) {	//存在しないIDが入力された時の場合分け
				print('宛先が見つかりません');
			} else {
				print('メッセージを送信しました');
			}
		?></h1>
		<table width="95%" border="1"> 
			<tr> 
				<th colspan="2" scope="col">メッセージ内容</th> 
			</tr> 
			<tr> 
				<td colspan="2"><?php print(nl2br(htmlspecialchars($table['message']))); ?></td>
			</tr>
			<tr>
				<th width="10%" style="text-align:right">送信日時</th>
				<td><?php print(htmlspecialchars($table['send_time'])); ?></td>
			</tr>
			<tr>
				<th style="text-align:right">宛先</th>
				<td><?php print(htmlspecialchars($table['destination'])); ?></td>
			</tr> 
		</table>
		<br>
		<div style="text-align:center">
			<input type="button" onclick="location.href='javascript:history.back();'" value="戻る">
			<input type="button" onclick="location.href='index.html'" value="HOME">
		</div>
	</body> 
</html>