<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/login-style.css">
    <script type="text/javascript" src="/public/js/form_validation.js" defer></script>
    <title>Communitly - login</title>
</head>
<body>
    <header>
        <a href="/register" class="button header-login-button">Zarejestruj się</a>
    </header>
    <div class="logo-container">
        <a href="/" class="logo">Communitly</a>
    </div>
    <div class="login-container">
        <form action="signIn" method="post">
            <input class="input-with-text" type="text" name='email' placeholder="Login" autofocus>
            <input class="input-with-text" type="password" name="password" placeholder="Password">
            <?php if (isset($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <span class="error"><?= $message; ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
            <input class="button button-in-form" type="submit" value="Zaloguj się">
        </form>
    </div>
</body>
</html>
