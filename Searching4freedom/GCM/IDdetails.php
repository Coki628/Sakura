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

//regidテーブルからGETでID取得した行だけ出す
$result = mysql_query("SELECT * FROM regid WHERE id = '$id'");
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
		<h1 style="text-align:center">ID詳細</h1>
		<table width="95%" border="1"> 
			<tr> 
				<th colspan="2" scope="col">登録ID</th> 
			</tr> 
			<tr> 			<!--↓強制折り返し-->
				<td style="word-break:break-all" colspan="2"><?php print(htmlspecialchars($table['reg_id'])); ?></td>
			</tr>
			<tr>
				<th width="10%" style="text-align:right">登録日時</th>
				<td><?php print(htmlspecialchars($table['reg_time'])); ?></td>
			</tr>
			<tr>
				<th style="text-align:right">端末識別ID</th>
				<td><?php print(htmlspecialchars($table['dev_id'])); ?></td>
			</tr>
			<tr>
				<th style="text-align:right">削除フラグ</th>
				<td><?php
					if($table['old_flag'] == 1) {
						print('あり');
					} else {
						print('なし');
					}
				?></td>
			</tr> 

		</table>
		<br>
		<div style="text-align:center">
			<input type="button" onclick="location.href='javascript:history.back();'" value="戻る">
			<input type="button" onclick="location.href='index.html'" value="HOME">
		</div>
	</body> 
</html>