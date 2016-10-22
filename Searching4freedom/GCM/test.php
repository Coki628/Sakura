<?php

$IDs = array(40, 23, 35); 		//数値型の配列

//foreachはfor文のように繰り返す命令
foreach($IDs as $id){ 		//fruitsの先頭から１つずつ$fruitに代入する
	$id = strval($id);			//数値を文字列に変換
    $strings .= $id . ", ";		//phpは.で文字列連結する
}
echo substr($strings, 0, -2);		//最後から2文字(2バイト)削ってコンマとスペース消す
?>