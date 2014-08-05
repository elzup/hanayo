<?php
require_once('../dbp/api_keybox.php');
$consumer_key = $api_keybox->twitter_consumer_key;
$consumer_secret = $api_keybox->twitter_consumer_key_secret;
$access_token = $api_keybox->twitter_access_token;
$access_token_secret = $api_keybox->twitter_access_token_secret;

require_once('./twitteroauth.php');
$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

require_once('./hanayo_config.php');

echo "Welcome to hanayo.php!!" . PHP_EOL
	. "this  program looks for rice for HANAYO" . PHP_EOL
	. PHP_EOL
	. "Press ENTER KEY" . PHP_EOL;
while (1){
	//���͑҂�
	$standby = trim(fgets(STDIN));
       	switch($standby){
	case "":
		//�����P�� �擾���� �����z���
		$search_options = array(
			'q'=>"$search_word",
			'count'=>100,
			'lang'=>'ja'
		);
		//search api
		$search_obj = $connection->OAuthRequest(
			'https://api.twitter.com/1.1/search/tweets.json',
			'GET',
			$search_options
		);
		//json��decode
		$decode_obj = json_decode($search_obj, true);
		//UTF-8 -> SJIS Mac,Linux���Ȃ�R�����g�A�E�g����
		mb_convert_variables('sjis', 'utf-8', $decode_obj);
                //�������ʂ̌�����count
        	$result_number = count($decode_obj['statuses']);
		//timezone�̐ݒ�
		date_default_timezone_set('Asia/Tokyo');
		//�������ʂ̒��ň�ԓ��e���Ԃ̒x�����̂��擾
		$last_time = $decode_obj['statuses'][$result_number - 1]['created_at'];
		//�^�C���X�^���v�ϊ�(seconds)
		$timestamp_last = strtotime($last_time);
		//������ per minutes
		$per_minutes = $result_number * 60 / (time() - $timestamp_last);
		//������ per minutes���e�p���[�h
		$per_minutes_word = mb_convert_encoding("���� $per_minutes [okome/min]",'utf-8', 'sjis'); 
		
		//���悿��ɂ��͂�������悤
		if($per_minutes >= $config['best']['score']){
		$tweet_word = $per_minutes_word . $config['best']['message'];
		$hanayo_review = $connection->OAuthRequest(
        		'https://api.twitter.com/1.1/statuses/update.json',
			'POST',
			array("status"=>$tweet_word)
		);
		echo "tweet" . PHP_EOL;
		exit;	
		}elseif($per_minutes >= $config['better']['score']){ 
		$tweet_word = $per_minutes_word . $config['better']['message']; 	
		$hanayo_review = $connection->OAuthRequest(
        		'https://api.twitter.com/1.1/statuses/update.json',
			'POST',
			array("status"=>$tweet_word)
		);
		echo "tweet" . PHP_EOL;
		exit;	
		}elseif($per_minutes >= $config['worse']['score']){
		$tweet_word = $per_minutes_word . $config['worse']['message'];        	
		$hanayo_review = $connection->OAuthRequest(
        		'https://api.twitter.com/1.1/statuses/update.json',
			'POST',
			array("status"=>$tweet_word)
		);
		echo "tweet" . PHP_EOL;
		exit;	
		}else{
                $tweet_word = $per_minutes_word . $config['worst']['message']; 
		$hanayo_review = $connection->OAuthRequest(
        		'https://api.twitter.com/1.1/statuses/update.json',
			'POST',
			array("status"=>$tweet_word)
		);
		echo "tweet" . PHP_EOL;
		exit;
		}	

	}
}
                                                 
