<?php

// ���̃X�N���v�g�Ɠ����f�B���N�g���Ɂucount.dat�v�Ƃ������O�̃t�@�C�����쐬���A�u���Ă������ƁB
if(!$file) { $file='count.dat'; }

// �ݒ�
$HOME = '/var/yuki/html/counter'; // �f�B���N�g��
$data = "$HOME/$file";               // �J�E���g�L�^�t�@�C��
$ip_check = 1;                       // �A���J�E���g�h�~�iyes=1 no=0)
$hostfile = "$HOME/ipdata.dat";	// LOG�t�@�C����


$count = file($data);                            // �t�@�C����z���

// �f�[�^���̕�����𕪉����Ă��ꂼ��̕ϐ��ɑ��
list($total, $ip)=explode('<>', $count[0]);

// �N���[���[���J�E���g�A�b�v���Ȃ�
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , "msnbot") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".crawl.yahoo.net") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".crawl.baidu.com") !== false )exit;
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".cuil.com") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.193.55") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.193.107") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "123.125.64.38") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "123.125.66.") !== false )exit;
if( strpos( $_SERVER['REMOTE_ADDR'] , "119.63.199.") !== false )exit;

// �����̂Ƃ����J�E���g���Ȃ�
if( strpos( gethostbyaddr($_SERVER['REMOTE_ADDR']) , ".ppp.wakwak.ne.jp") !== false )exit;


// �J�E���g�A�b�v����
if(($ip_check == 1 && $ip != $_SERVER['REMOTE_ADDR']) || $ip_check == 0){
    $total++;
    $new_data = implode('<>', array($total, $_SERVER['REMOTE_ADDR']));
    $fp = fopen($data, 'w');         // �������[�h�ŃI�[�v��
    flock($fp, LOCK_EX);             // �t�@�C�����b�N
    fputs($fp, $new_data);           // ��������
    flock($fp, LOCK_UN);             // ���b�N����
    fclose($fp);                     // �t�@�C�������

	// IP��HOST���L�^����
	$hostdata = $total . "\t" . date('ymdHis', $_SERVER['REQUEST_TIME']) . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\t" . urldecode(urldecode($_SERVER['HTTP_REFERER'])) . "\t" . $_SERVER['REQUEST_URI'] . "\n";
	$fp = fopen($hostfile, 'a');     // �ǉ��������݃��[�h�ŃI�[�v��
	flock($fp, LOCK_EX);             // �t�@�C�����b�N
	fputs($fp, $hostdata);           // ��������
	flock($fp, LOCK_UN);             // ���b�N����
	fclose($fp);                     // �t�@�C�������
}


//$total = number_format($total);      // �J���}��؂�ɂ���
print $total;                        // �J�E���^��\��

?>
