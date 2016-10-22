<?php

//送りたいメッセージ
$message = $_POST['message'];

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

//regidテーブル中でフラグがfalse(0)の所だけ選択
$result = mysql_query("SELECT * FROM regid WHERE old_flag = 0");
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
//DBにあるIDを全部配列に格納
$regIdsArray = array();
$dbIdsArray = array();
while ($row = mysql_fetch_assoc($result)) {
	array_push($regIdsArray, $row['reg_id']);
  array_push($dbIdsArray, $row['id']);
}

//宛先のIDをDB格納用にまとめる
foreach($dbIdsArray as $dbId){      
    $dbId = strval($dbId);          //数値を文字列に変換
    $destination .= $dbId . ", ";    //phpは.で文字列連結する
}
$destination = substr($destination, 0, -2);   //最後から2文字(2バイト)削ってコンマとスペース消す

//POSTで受けたメッセージをDBに格納
$sql = "INSERT INTO messages (message, send_time, destination) VALUES ('$message', '$time', '$destination')";  //変数も''で囲む
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

$url = 'https://android.googleapis.com/gcm/send';

$apiKey="AIzaSyDeUemII1slfrKtPO8ZvT1QnT4ze-E-C0c"; //API Keyはここ
 
$tickerText   = "ticker text message";
$contentTitle = "content title";
$contentText  = "content body";

$response = sendNotification( 
               $apiKey, 
               $regIdsArray,  //IDを入れた配列
               array('message' => $message, 'tickerText' => $tickerText, 'contentTitle' => $contentTitle, 
               "contentText" => $contentText) );

echo $response;

function sendNotification( $apiKey, $registrationIdsArray, $messageData )
{   
   $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . $apiKey);
   $data = array(
       'data' => $messageData,
       'registration_ids' => $registrationIdsArray
   );

   $ch = curl_init();

   curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers ); 
   curl_setopt( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
   curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
   curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
   curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
   curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

   $response = curl_exec($ch);
   curl_close($ch);

//   return $response;  //送信結果を出力
}　
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>GCM</title>
  </head>
  <body>
    <h1 style="text-align:center">メッセージを送信しました</h1>
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