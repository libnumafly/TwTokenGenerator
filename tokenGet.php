<?php

require_once('common.php');

foreach ($_POST as $name => $value) {
    $$name = $value;
}

define(CONSUMER_KEY, ''.$a1);
define(CONSUMER_SECRET, ''.$a2);
define(OAUTH_CALLBACK_URL, ''.$a3);

session_set_cookie_params(600);
session_start();

try {
	if (empty($_GET)) {
		//--------------------------------------
		// 1. 最初にアクセスした時
		//--------------------------------------
		// セッション削除
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		
		// APIを叩いてリクエストトークン取得して、セッションに保存
		$res = post(
			'https://api.twitter.com/oauth/request_token',
			[
				'oauth_callback' => OAUTH_CALLBACK_URL, // localhostだとこれは使えないらしいのでカラ。
			]
		);
		if (!isset($res['oauth_token'])) {
			throw new Exception('レスポンス→ '.var_export($res, true));
		}
		$_SESSION['oauth_token'] = $res['oauth_token'];
		$_SESSION['oauth_token_secret'] = $res['oauth_token_secret'];
		//session_write_close();

		// リクエストトークンを持ってTiwtterの認証画面に行くリンクを表示
		header('Content-Type: text/html');
        $url = 'https://api.twitter.com/oauth/authorize?oauth_token='.$res['oauth_token'];
		//header('Location: '.$url.'');
		echo '<meta http-equiv="refresh" content="0;URL='.$url.'">';
        echo '<a href="'.$url.'">'.$url.'</a>';
	}
	else {}
}
catch (Exception $e) {
	header('Content-Type: text/plain');
	echo '失敗！: '.$e->getMessage();
}
