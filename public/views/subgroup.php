<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <script type="text/javascript" src="/public/js/script.js" defer></script>
    <script type="text/javascript" src="/public/js/dialog.js" defer></script>
    <title>Communitly - subgroup</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <div class="main-title">
            <h1><?= $subgroup->getFullName(); ?></h1>
        </div>
        <div class="statements">
            <div class="widget-group-header">
                <h3 class="widget-group">Komunikaty</h3>
                <a href="#" class="add-content js-add-content"></a>
            </div>

            <div class="dialog-background js-dialog-background"></div>
            <div class="dialog js-dialog">
                <h1>Dodawanie komunikatu</h2>
                <h4>
                    <span class="group"><?= $group->getFullName(); ?></span>
                    <span class="subgroup"><a href="wdpai"><?= $subgroup->getFullName(); ?></a></span>
                </h4>
                <form action="/addStatement" method="post" enctype="multipart/form-data">
                    <?php 
                    echo '1234567';
                        if(isset($messages)) {
                            foreach ($messages as $message) {
                                echo $message;
                            }
                        }
                    ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) -->
                    <input type="text" class='input-with-text' name='statement-header' placeholder="Nagłówek">
                    <input type="text" class='input-with-text' name='statement-url' placeholder="Link do źródła">
                    <textarea name="statement-content" class='input-with-text' placeholder="Treść nowego komunikatu" autofocus></textarea>
                    <?php foreach ($allThreadsInSubgroup as $thread): ?>
                        <span>
                            <label>
                                <input type="checkbox" class='checkbox-in-form' name="thread[]" placeholder="Wątki" value='<?= $thread->getThreadId(); ?>'>
                                <?= $thread->getName(); ?>
                            </label>
                        </span>
                    <?php endforeach; ?>
                    <input type="file" name="attachment[]" multiple>
                    <input type="submit" class='button button-in-form' value="Wyślij">
                </form>
            </div>

            <?php foreach ($statements as $statement): ?>
                <div class="widget">
                    <h2><?= $statement->getHeader(); ?></h2>
                    <p class="date-and-source">
                        <?php
                        $approveDate = $statement->getApproveDate();
                        if ($approveDate) {
                            echo "<span class='verified-statement' title='Dodane " . $statement->getCreationDate()->format('d.m.Y H:i:s') . " przez ?\nZweryfikowane " . $approveDate->format('d.m.Y H:i:s') . " przez ?'></span>";
                        } else {
                            echo "<span class='unverified-statement' title='Dodane " . $statement->getCreationDate()->format('d.m.Y H:i:s') . " przez ?\n'></span>";
                        }
                        ?>

                        <?= $statement->getCreationDate()->format('d.m.Y H:i:s'); ?>

                        <?php if ($statement->getSourceURL()) {echo " z ";} ?>
                        <a href="<?= $statement->getSourceURL(); ?>">
                            <?php 
                                preg_match("/:\/\/(\S+\.\w+)/", $statement->getSourceURL(), $url);
                                echo $url[1];
                            ?>
                        </a>

                    </p>
                    <p class="statement-content"><?= $statement->getContent(); ?></p>
                    <?php foreach ($statement->getAttachments() as $attachment): ?>
                        <a href="<?= '/public/uploads/' . $attachment->getFilename(); ?>" class="attachment"><?= $attachment->getFilename(); ?></a>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div class="widget">
                <h3><a class="archived" href="#">Zobacz zarchiwizowane komunikaty</a></h3>
            </div>
        </div>

        
        <div class="links">
            <h3 class="widget-group">Linki</h3>
            <div class="widget">
                <h2>Wykłady</h2>
                <ul>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne wykłady (app)</a>
                        <!-- TODO: dodać odpowiednie kolory dla ikon [JavaScript] -->
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne wykłady (www)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Materiały z zajęć (www)</a>
                    </li>
                    <li class="record-in-links">
                        <!-- TODO: zmienić styl, zmienić action="" -->
                        <form action="login" method="post" enctype="multipart/form-data">
                            <?php 
                                if(isset($messages)) {
                                    foreach ($messages as $message) {
                                        echo $message;
                                    }
                                }
                            ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) -->
                            <input type="url" name='link-url' placeholder="Link">
                            <input type="text" name='link-header' placeholder="Nazwa">
                            <textarea name="link-note" placeholder="Notatka"></textarea>
                            <input type="submit" value="Wyślij wiadomość">
                        </form>
                    </li>
                </ul>
            </div>
            <div class="widget">
                <h2>Laboratoria</h2>
                <ul>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne laboratoria (Zoom)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Materiały z zajęć (www)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="mailto:example@example.com">Przesyłanie zadań (email)</a>
                        <p>Na adres email: example@example.com<br>
                        Temat i nazwa pliku: [_] Imie Nazwisko</p>
                    </li>
                </ul>
            </div>

        </div>
    </main>
</body>
</html>