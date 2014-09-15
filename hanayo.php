<?php
//������apikey�i�[�p�N���X�𐶐����Ă��܂����A�K���Ɏ擾����apikey��˂����ނ̂��y���Ǝv���܂�
//$consumer_key = "";
//$consumer_secret = ""; 
//$access_token = "";
//$access_token_secret = "";
// �O���t�@�C���ǂݍ���
require_once('./keys.php');
require_once('./twitteroauth/twitteroauth.php');
require_once('./hanayo_config.php');
//timezone�̐ݒ�
date_default_timezone_set('Asia/Tokyo');
$consumer_key = $api_keybox->twitter_consumer_key;
$consumer_secret = $api_keybox->twitter_consumer_key_secret;
$access_token = $api_keybox->twitter_access_token;
$access_token_secret = $api_keybox->twitter_access_token_secret;

$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

echo <<<EOF
Welcome to hanayo.php!!
this  program looks for rice for HANAYO

Press ENTER KEY
EOF;

while (1){
	//���͑҂�
	$standby = trim(fgets(STDIN));
	switch($standby){
	case "":
		//�����P�� �擾���� �����z���
		$search_options = array(
			'q' => $search_word,
			'count' => 100,
			'lang' => 'ja',
		);
		//search api
		$search_obj = $connection->get(
			'search/tweets',
			$search_options
		);
		//json��decode �͂��łɂ���Ă��� stdclass����A�z�z��ɕϊ�
		$decode_obj = json_decode(json_encode($search_obj), true);
		//UTF-8 -> SJIS Mac,Linux���Ȃ�R�����g�A�E�g����
		mb_convert_variables('sjis', 'utf-8', $decode_obj);
		//�������ʂ̌�����count
		$result_number = count($decode_obj['statuses']);
		//�������ʂ̒��ň�ԓ��e���Ԃ̒x�����̂��擾
		$last_time = $decode_obj['statuses'][$result_number - 1]['created_at'];
		//�^�C���X�^���v�ϊ�(seconds)
		$timestamp_last = strtotime($last_time);
		//������ per minutes
		$per_minutes = $result_number * 60 / (time() - $timestamp_last);
		//������ per minutes���e�p���[�h
		$per_minutes_word = mb_convert_encoding("���� $per_minutes [okome/min]",'utf-8', 'sjis'); 

		//���悿��ɂ��͂�������悤
        foreach ($config as $rank => $value) {
            if ($per_minutes >= $value['score'] || $rank == 'worst') {
                $tweet_word = $per_minutes_word . $value['message'];
                break;
            }
        }
        $hanayo_review = $connection->post(
            'statuses/update',
            array("status" => $tweet_word)
        );
		echo "tweet end...";	
		exit;	
	}
}
