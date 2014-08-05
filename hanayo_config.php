<?php
//スコアと呟く内容を連想配列に格納
$config = array(
	'best' => array(                     
		'score' => 5,
		'message' => '「おにぎりはやっぱりこうでなくっちゃ！！」 http://t.co/A0S6hwO7et'
	),
	'better' => array(
		'score' => 1,
		'message' => '「たきたてのごはんなんですっ！」 http://t.co/N9HWk5YMbA'
	),
	'worse' => array(
		'score' => 0.5,
		'message' => '「こんなんじゃ、おなかいっぱいにならないよぅ...」http://t.co/VHQz51uLk7'
	),
	'worst' => array(
		'score' => 0.1,
		'message' => '「もう...だめ...」 http://t.co/hDp62nWB1W'
	)
);
//SJISからUTF-8にキャスト Mac,Linux系非推奨
mb_convert_variables('utf-8', 'sjis', $config);
//Mac,Linux系はコメントアウト
$search_word = mb_convert_encoding('おこめ', 'utf-8','sjis');
//$serch_word = 'おこめ'
