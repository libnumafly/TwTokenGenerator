<?php

require_once('common.php');

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
			//$_SESSION['oauth_token'],       // セッションに保存していたリクエストトークンを署名に使う
            //$_SESSION['oauth_token_secret'] // 同上
            $_GET['oauth_token'],       
			$_GET['oauth_token_secret'] 
		);
		if (!isset($res['oauth_token'])) {
			throw new Exception('レスポンス→ '.var_export($res, true));
		}
		
		// アクセストークンを画面に表示
		/* header('Content-Type: text/html');
		echo implode([
			'成功！',
			'Access Token: '.$res['oauth_token'],
			'Access Token Secret: '.$res['oauth_token_secret'],
			'User ID: '.$res['user_id'],
			'Screen Name: '.$res['screen_name'],
		], '<br />'); */
	}
}
catch (Exception $e) {
	header('Content-Type: text/plain');
    echo '失敗！: '.$e->getMessage();
    exit();
}

?>

<html>

<header>
    <link rel="stylesheet" href="/src/material.min.css">
    <link rel="stylesheet" href="/src/material.indigo-pink.min.css">
    <script src="/src/material.min.js"></script>
    <link rel="stylesheet" href="/src/icon.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</header>

<body style="width: calc(100% - 4px); margin-right: auto; margin-left : auto;">

	Access Token
	<br>
	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
		<?php echo '<input class="mdl-textfield__input" type="text" id="a1" name="a1" value="'.$res['oauth_token'].'">'; ?>
		<label class="mdl-textfield__label">Token...</label>
	</div>
	<br>
	Access Token Secret
	<br>
	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
		<?php echo '<input class="mdl-textfield__input" type="text" id="a2" name="a2" value="'.$res['oauth_token_secret'].'">'; ?>
		<label class="mdl-textfield__label">Secret...</label>
	</div>

</body>

</html>