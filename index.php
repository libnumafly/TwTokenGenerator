<html>

    <header>
        <link rel="stylesheet" href="./material.min.css">
        <link rel="stylesheet" href="./material.indigo-pink.min.css">
        <script src="./material.min.js"></script>
        <link rel="stylesheet" href="./icon.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </header>

    <body>

        <form action="./getToken.php" method="post">
            Consumer API key
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="a1" name="a1">
                <label class="mdl-textfield__label" for="sample3">Key...</label>
            </div>
            Consumer API secret key
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="a2" name="a2">
                <label class="mdl-textfield__label" for="sample3">Secret...</label>
            </div>
            <!-- Raised button -->
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                Proceed
            </button>
        </form>

    </body>

</html>