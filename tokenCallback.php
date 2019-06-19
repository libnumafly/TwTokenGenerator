<?php
foreach ($_POST as $name => $value) {
    $$name = $value;
}

$CONSUMER_KEY = print ''.$a1;
$CONSUMER_SECRET = print ''.$a2;

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
				'oauth_callback' => 'https://twtokengenerator.herokuapp.com/tokenCallback.php', // localhostだとこれは使えないらしいのでカラ。
			]
		);
		if (!isset($res['oauth_token'])) {
			throw new Exception('レスポンス→ '.var_export($res, true));
		}
		$_SESSION['oauth_token'] = $res['oauth_token'];
		$_SESSION['oauth_token_secret'] = $res['oauth_token_secret'];

		// リクエストトークンを持ってTiwtterの認証画面に行くリンクを表示
		header('Content-Type: text/html');
		$url = 'https://api.twitter.com/oauth/authenticate?oauth_token='.$res['oauth_token'];
		echo '<a href="'.$url.'">'.$url.'</a>';
	}
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

/**
 * 対象URLにOAuthの署名つきでPOSTし、結果を連想配列で返却する
 */
function post($url, $params, $token = null, $secret = null) {
	// curlでPOST
	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_URL => $url,
		CURLOPT_HTTPHEADER => [
			createOAuthHeader($url, $params, $token, $secret)
		],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query($params),
	]);
	if (($res = curl_exec($ch)) === false) {
		throw new Exception('通信時にエラーが発生しました。');
	}
	curl_close($ch);
	parse_str($res, $resArr);
	return $resArr;
}

/**
 * OAuth用のヘッダを作成して返却する
 */
function createOAuthHeader($url, $params, $token, $secret) {
	$sigparams = [
		'oauth_consumer_key'     => $CONSUMER_KEY,
		'oauth_signature_method' => 'HMAC-SHA1',
		'oauth_timestamp'        => time(),
		'oauth_nonce'            => md5(uniqid(rand(), true)),
		'oauth_version'          => '1.0',
	];
	if (isset($token)) {
		// リクエストトークンがあればセット
		$sigparams['oauth_token'] = $token;
	}
	$sigparams += $params;
	
	// ルール通りに署名を作成してセット
	// https://developer.twitter.com/en/docs/basics/authentication/guides/creating-a-signature.html
	ksort($sigparams);
	$data = 'POST&'.rawurlencode($url).'&'.rawurlencode(http_build_query($sigparams, '', '&', PHP_QUERY_RFC3986)); // ここでは関係無いが、パラメータにスペースが含まれてる時用にRFC3986を明示的に指定
	$key  = rawurlencode($CONSUMER_SECRET).'&';
	$key .= isset($secret) ? rawurlencode($secret) : ''; // リクエストトークンがあればセット
	$hash = hash_hmac('sha1', $data, $key, true);
	$sigparams['oauth_signature'] =  base64_encode($hash);

	// ヘッダ文字列にして返却
	return 'Authorization: OAuth '.http_build_query($sigparams, '', ',');
}
