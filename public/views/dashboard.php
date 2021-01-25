<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/all.css">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/dashboard-style.css">
    <script type="text/javascript" src="/public/js/message-dialog.js" defer></script>
    <title>Communitly - dashboard</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <?php include('message-dialog.php'); ?>
        <div class="main-title">
            <h1>Witaj <?= $userFirstname; ?>!</h1>
        </div>
        <div class="statements">
            <h3 class="widget-group">Komunikaty z ostatnich 7 dni</h3>

            <?php foreach ($statements as $statement): ?>
                <div class="widget">
                    <h2><?= $statement->getHeader(); ?></h2>
                    <?php include('statement-content.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="links">
        </div>
    </main>
</body>
</html>