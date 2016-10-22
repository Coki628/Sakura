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

//messagesテーブル全部
$result = mysql_query("SELECT * FROM messages ORDER BY id DESC");
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

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
		<h1 style="text-align:center">送信済メッセージ一覧</h1>
		<table width="95%" border="1"> 
			<tr> 
				<th scope="col">ID</th> 
				<th scope="col">メッセージ内容</th> 
				<th scope="col">送信日時</th> 
				<th scope="col">宛先(ID一覧テーブルのID)</th>  
			</tr> 
			<?php
				while($table = mysql_fetch_assoc($result)) { 
					?> 
					<tr> 
						<td><?php print(htmlspecialchars($table['id'])); ?></td> 
						<td><?php
							print("<a href='details.php?id=" . $table['id'] . "'>");	//GETでID送る
							if(mb_strlen($table['message']) <= 30 ) {	//30文字以下だったら省略処理しない
								print(htmlspecialchars($table['message']));
							} else {
								print(htmlspecialchars(mb_substr($table['message'], 0, 30)) . " …"); 
							}
						?></a></td>
						<td><?php print(htmlspecialchars($table['send_time'])); ?></td> 
						<td><?php
							if(strlen($table['destination']) <= 30 ) {	//30バイト以下だったら省略処理しない
								print(htmlspecialchars($table['destination']));
							} else {
								print(htmlspecialchars(substr($table['destination'], 0, 30)) . " …"); 
							}
						?></td> 
					</tr> 
					<?php 
				} 
			?>
		</table>
		<br>
		<div style="text-align:center">
			<input type="button" onclick="location.href='javascript:history.back();'" value="戻る">
			<input type="button" onclick="location.href='index.html'" value="HOME">
		</div>
	</body> 
</html>
