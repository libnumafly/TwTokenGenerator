<?php

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
		'oauth_consumer_key'     => CONSUMER_KEY,
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
	$key  = rawurlencode(CONSUMER_SECRET).'&';
	$key .= isset($secret) ? rawurlencode($secret) : ''; // リクエストトークンがあればセット
	$hash = hash_hmac('sha1', $data, $key, true);
	$sigparams['oauth_signature'] =  base64_encode($hash);

	// ヘッダ文字列にして返却
	return 'Authorization: OAuth '.http_build_query($sigparams, '', ',');
}