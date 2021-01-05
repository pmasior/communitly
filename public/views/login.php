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
        <div class="logo logo-container">Communitly</div>
        <div class="login-container">
            <form action="login" method="post">
                <?php 
                    if(isset($messages)) {
                        foreach ($messages as $message) {
                            echo $message;
                        }
                    }
                ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) -->
                <input type="text" name='login' placeholder="Login" value="example@example.com" autofocus>
                <input type="password" name="pass" placeholder="Password">  <!-- TODO: change pass -> password -->
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </div>
</body>
</html>
