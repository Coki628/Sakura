<?php
// 送受信クラス
class SendReceive {

	// コンストラクタ
	function SendReceive($file) {	// 引数に送受信データの書き込みファイルを指定

		define('DATA_FILE', $file);

		/**
		 * データを取得
		 */
		function getData() {
			return file_get_contents(DATA_FILE);
		}

		/**
		 * 更新チェック
		 *
		 * 対象データに変化が無ければループし続ける。
		 * 変化が有れば新しいデータ追加した全てのデータを返す。
		 */
		function getUpdatedData() {
			$data = getData();
			$temp = $data;
			$timeCount = 0;
			while ($temp === $data) {

				$temp = getData();
				sleep(1);

				// 一定時間たったらブランクを返す
				$timeCount++;
				if ($timeCount >= 60) {
					return '';
				}
			}
			return $temp;
		}

		/**
		 * データ追加
		 *
		 * 新しいデータを追加して全てのデータを返す。
		 */
		function pushData($data) {
			if (!empty($data)) {
				$data = str_replace(array("\n", "\r"), '', $data);	//データ内の改行コードを何もない状態に置き換え
				file_put_contents(DATA_FILE, $data, LOCK_EX);	//排他ロック
			}
			return getData();
		}

		if (isset($_GET['mode'])) {
			// モードの振り分け
			switch ($_GET['mode']) {
				// データを取得
				case 'view':
					$data = getData();
					break;

				// 更新チェック
				case 'check':
					$data = getUpdatedData();
					break;

				// データを保存
				case 'add':
					$data = pushData($_POST['data']);
					break;
			}
			// 結果を表示
			echo nl2br(htmlspecialchars($data, ENT_QUOTES));	//シングルクォートとダブルクォートの両方をエスケープ
		}
	}
}
?>
