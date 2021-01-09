<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">  <!-- TODO: w labach bez /-->
    <script type="text/javascript" src="/public/js/script.js" defer></script>
    <title>Communitly - login</title>
</head>
<body>
    <div class="container">
    <!-- TODO: przycisk do rejestracji -->
        <div class="logo logo-container">Communitly</div>
        <div class="login-container">
            <form action="signIn" method="post">
                <input type="text" name='email' placeholder="Login" value="example@example.com" autofocus>  <!-- TODO: delete value -->
                <input type="password" name="pass" placeholder="Password">  <!-- TODO: change pass -> password -->
                <?php if(isset($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <span class="error"><?= $message; ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </div>
</body>
</html>
