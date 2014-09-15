<?php
//自分はapikey格納用クラスを生成していますが、適当に取得したapikeyを突っ込むのが楽だと思います
//$consumer_key = "";
//$consumer_secret = ""; 
//$access_token = "";
//$access_token_secret = "";
// 外部ファイル読み込み
require_once('./keys.php');
require_once('./twitteroauth/twitteroauth.php');
require_once('./hanayo_config.php');
//timezoneの設定
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
	//入力待ち
	$standby = trim(fgets(STDIN));
	switch($standby){
	case "":
		//検索単語 取得件数 言語を配列に
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
		//jsonをdecode はすでにされている stdclassから連想配列に変換
		$decode_obj = json_decode(json_encode($search_obj), true);
		//UTF-8 -> SJIS Mac,Linux環境ならコメントアウト推奨
		mb_convert_variables('sjis', 'utf-8', $decode_obj);
		//検索結果の件数をcount
		$result_number = count($decode_obj['statuses']);
		//検索結果の中で一番投稿時間の遅いものを取得
		$last_time = $decode_obj['statuses'][$result_number - 1]['created_at'];
		//タイムスタンプ変換(seconds)
		$timestamp_last = strtotime($last_time);
		//おこめ per minutes
		$per_minutes = $result_number * 60 / (time() - $timestamp_last);
		//おこめ per minutes投稿用ワード
		$per_minutes_word = mb_convert_encoding("現在 $per_minutes [okome/min]",'utf-8', 'sjis'); 

		//かよちんにごはんをあげよう
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
