<?php
//�X�R�A�ƙꂭ���e��A�z�z��Ɋi�[
$config = array(
	'best' => array(                     
		'score' => 5,
		'message' => '�u���ɂ���͂���ς肱���łȂ�������I�I�v http://t.co/A0S6hwO7et'
	),
	'better' => array(
		'score' => 1,
		'message' => '�u�������Ă̂��͂�Ȃ�ł����I�v http://t.co/N9HWk5YMbA'
	),
	'worse' => array(
		'score' => 0.5,
		'message' => '�u����Ȃ񂶂�A���Ȃ������ς��ɂȂ�Ȃ��患...�vhttp://t.co/VHQz51uLk7'
	),
	'worst' => array(
		'score' => 0.1,
		'message' => '�u����...����...�v http://t.co/hDp62nWB1W'
	)
);
//SJIS����UTF-8�ɃL���X�g Mac,Linux�n�񐄏�
mb_convert_variables('utf-8', 'sjis', $config);
//Mac,Linux�n�̓R�����g�A�E�g
$search_word = mb_convert_encoding('������', 'utf-8','sjis');
//$serch_word = '������'
