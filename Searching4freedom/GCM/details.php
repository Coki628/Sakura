<?php 
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

//GETでID受け取る
$id = $_GET['id'];

//messagesテーブルからGETでID取得した行だけ出す
$result = mysql_query("SELECT * FROM messages WHERE id = '$id'");
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
$table = mysql_fetch_assoc($result);

//接続の切断
$close_flag = mysql_close($link);
if (!$close_flag){
    die('切断失敗です。'.mysql_error());
}
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>GCM</title>
	</head>
	<body>
		<h1 style="text-align:center">送信済メッセージ詳細</h1>
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