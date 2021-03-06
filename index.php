<html>

<header>
    <link rel="stylesheet" href="/src/material.min.css">
    <link rel="stylesheet" href="/src/material.indigo-pink.min.css">
    <script src="/src/material.min.js"></script>
    <link rel="stylesheet" href="/src/icon.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter API Token Generator</title>
</header>

<body style="width: calc(100% - 4px); margin-right: auto; margin-left : auto;">

    <br>
    <form action="./tokenGet.php" method="post">
        Consumer API key
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
            <input class="mdl-textfield__input" type="text" id="a1" name="a1">
            <label class="mdl-textfield__label">Key...</label>
        </div>
        <br>
        Consumer API secret key
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
            <input class="mdl-textfield__input" type="text" id="a2" name="a2">
            <label class="mdl-textfield__label">Secret...</label>
        </div>
        <br>
        Callback URL
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
            <input class="mdl-textfield__input" type="text" id="a3" name="a3"
                value="https://twtokengenerator.herokuapp.com/tokenCallback.php">
            <label class="mdl-textfield__label">Callback URL...</label>
        </div>
        <br>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" style="width: 100%;">
            Proceed
        </button>
    </form>
    <br>
    <br>
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" style="width: 100%;"
        onclick="document.getElementById('a1').value='';document.getElementById('a2').value='';">
        Clear CK/CS
    </button>
    <br>
    <br>
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" style="width: 100%;"
        onclick="document.getElementById('a3').value='https://twtokengenerator.herokuapp.com/tokenCallback.php';">
        Callback reset to default
    </button>
    <br>
    <br>
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" style="width: 100%;"
        onclick="document.getElementById('a3').value='oob';">
        Callback set to out-of-band('oob')
    </button>

</body>

</html>
