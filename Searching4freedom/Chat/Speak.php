<?php
// 送受信クラスの読み込み
include ('SendReceive.php');

// なんか最近このヘッダーないとChromeのクロスサイト通信うまくいかないみたい
header("Access-Control-Allow-Origin: *");

$speak = new SendReceive("speak.log");
?>