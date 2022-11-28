<?php

// このスクリプトと同じディレクトリに「count.dat」という名前のファイルを作成し、置いておくこと。
if(!$file) { $file='count.dat'; }

// 設定
$HOME = '/var/yuki/html/counter'; // ディレクトリ
$data = "$HOME/$file";               // カウント記録ファイル
$ip_check = 1;                       // 連続カウント防止（yes=1 no=0)
$hostfile = "$HOME/ipdata.dat";	// LOGファイル先


$count = file($data);                            // ファイルを配列に

// データ内の文字列を分解してそれぞれの変数に代入
list($total, $ip)=explode('<>', $count[0]);

// クローラーをカウントアップしない
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , "msnbot") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".crawl.yahoo.net") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".crawl.baidu.com") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".cuil.com") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.193.55") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.193.107") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "123.125.64.38") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "123.125.66.") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.199.") !== false )exit;

// 自分のとこもカウントしない
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".ppp.wakwak.ne.jp") !== false )exit;


// カウントアップ処理
if(($ip_check == 1 && $ip != $_SERVER['REMOTE_ADDR']) || $ip_check == 0){
    $total++;
    $new_data = implode('<>', array($total, $_SERVER['REMOTE_ADDR']));
    $fp = fopen($data, 'w');         // 書きモードでオープン
    flock($fp, LOCK_EX);             // ファイルロック
    fputs($fp, $new_data);           // 書き込み
    flock($fp, LOCK_UN);             // ロック解除
    fclose($fp);                     // ファイルを閉じる

	// IPとHOSTを記録する
	$hostdata = $total . "\t" . date('ymdHis', $_SERVER['REQUEST_TIME']) . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\t" . urldecode(urldecode($_SERVER['HTTP_REFERER'])) . "\t" . $_SERVER['REQUEST_URI'] . "\n";
	$fp = fopen($hostfile, 'a');     // 追加書き込みモードでオープン
	flock($fp, LOCK_EX);             // ファイルロック
	fputs($fp, $hostdata);           // 書き込み
	flock($fp, LOCK_UN);             // ロック解除
	fclose($fp);                     // ファイルを閉じる
}


//$total = number_format($total);      // カンマ区切りにする
print $total;                        // カウンタを表示

?>
