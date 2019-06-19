<?php

try {
	if (empty($_GET)) {}
	else {
		//--------------------------------------
		// 2. Twitterで認証して戻ってきた時
		//--------------------------------------
		// もらったパラメータ『oauth_verifier』をつけてAPIを叩き、アクセストークン取得
		$res = post(
			'https://api.twitter.com/oauth/access_token',
			[
				'oauth_verifier' => $_GET['oauth_verifier'],
			],
			$_SESSION['oauth_token'],       // セッションに保存していたリクエストトークンを署名に使う
			$_SESSION['oauth_token_secret'] // 同上
		);
		if (!isset($res['oauth_token'])) {
			throw new Exception('レスポンス→ '.var_export($res, true));
		}
		
		// アクセストークンを画面に表示
		header('Content-Type: text/html');
		echo implode([
			'成功！',
			'Access Token: '.$res['oauth_token'],
			'Access Token Secret: '.$res['oauth_token_secret'],
			'User ID: '.$res['user_id'],
			'Screen Name: '.$res['screen_name'],
		], '<br />');
	}
}
catch (Exception $e) {
	header('Content-Type: text/plain');
	echo '失敗！: '.$e->getMessage();
}

session_destroy();

require_once('common.php');
