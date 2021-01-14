<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/all.css">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/dashboard-style.css">
    <script type="text/javascript" src="/public/js/form_validation.js" defer></script>
    <script type="text/javascript" src="/public/js/dialog.js" defer></script>
    <script type="text/javascript" src="/public/js/message-dialog.js" defer></script>
    <title><?= $subgroup->getFullName(); ?> - Communitly</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <?php include('message-dialog.php'); ?>
        <div class="main-title">
            <h1><?= $subgroup->getFullName(); ?></h1>
            <?php foreach ($activeThreads as $thread): ?>
                <p class="thread-name"><?= $thread->getName(); ?></p>
            <?php endforeach; ?>
        </div>
        <div class="statements">
            <div class="widget-group-header">
                <h3 class="widget-group">Komunikaty</h3>
                <a href="#" class="js-dialog-activator">
                    <i class="fas fa-plus-square fa-hover-hidden"></i>
                    <i class="far fa-plus-square fa-hover-show"></i>
                </a>
            </div>

            <div class="dialog-background js-dialog-background"></div>
            <div class="dialog js-dialog">
                <h1>Dodawanie komunikatu</h1>
                <h4>
                    <span class="group-name"><?= $group->getFullName(); ?></span>
                    <span class="subgroup"><a href=""><?= $subgroup->getFullName(); ?></a></span>
                </h4>
                <form action="/addStatement" method="post" enctype="multipart/form-data">
                    <input type="text" class='input-with-text' name='statement-header' placeholder="Nagłówek">
                    <input type="text" class='input-with-text' name='statement-url' placeholder="Link do źródła">
                    <textarea name="statement-content" class='input-with-text' placeholder="Treść nowego komunikatu"></textarea>
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
                    <?php if ($permission == 1): ?>
                    <a href="#" class="small-action-button">
                        <i class="fas fa-minus-square fa-hover-hidden"></i>
                        <i class="far fa-minus-square fa-hover-show"></i>
                    </a>  <!-- TODO: tworzenie grup, subgrup i wątków -->
                    <a href="#" class="small-action-button js-dialog-activator">
                        <i class="fas fa-pen-square fa-hover-hidden"></i>
                        <i class="fas fa-pen-square fa-hover-show"></i>
                    </a>  <!-- TODO: tworzenie grup, subgrup i wątków -->
                    <div class="dialog-background js-dialog-background"></div>
                    <div class="dialog js-dialog">
                        <h1>Edycja komunikatu</h1>
                        <form action="/editStatement" method="post">
                            <h4><?= $statement->getHeader(); ?></h4>
                            <input type="hidden" name='statementId' value="<?= $statement->getStatementId() ?>">
                            <input type="text" class='input-with-text' name='statement-header' placeholder="Nagłówek" value="<?= $statement->getHeader(); ?>">
                            <input type="text" class='input-with-text' name='statement-url' placeholder="Link do źródła" value="<?= $statement->getSourceURL(); ?>">
                            <textarea name="statement-content" class='input-with-text' placeholder="Treść nowego komunikatu"><?= $statement->getContent(); ?></textarea>
                            <?php foreach ($allThreadsInSubgroup as $thread): ?>
                            <span>
                                <label>
                                    <input type="checkbox" class='checkbox-in-form' name="thread[]" placeholder="Wątki" value='<?= $thread->getThreadId(); ?>'>
                                    <?= $thread->getName(); ?>
                                </label>
                            </span>
                            <?php endforeach; ?>
                            <input type="submit" class='button button-in-form' value="Potwierdź">
                        </form>
                    </div>
                    <? endif; ?>

                    <p class="date-and-source">
                        <?php if ($statement->getApproveDate()): ?>
                        <span title='Dodane <?= $statement->getCreationDate()->format('d.m.Y H:i:s') ?> przez <?= 1234; ?> \nZweryfikowane <?= $approveDate->format('d.m.Y H:i:s') ?> przez <?= 1234; ?>'>
                            <i class='fas fa-check-circle verified-statement'></i>
                        </span>
                        <?php else: ?>
                        <span title='Dodane <?= $statement->getCreationDate()->format('d.m.Y H:i:s') ?> przez <?= 1234; ?>'>
                            <i class='fas fa-exclamation-circle unverified-statement'></i>
                        </span>
                        <?php endif; ?>
                        <?= $statement->getCreationDate()->format('d.m.Y H:i:s'); ?>
                        <?php if ($statement->getSourceURL()): ?>
                        z <a href="<?= $statement->getSourceURL(); ?>">
                            <?php preg_match("/:\/\/(\S+\.\w+)/", $statement->getSourceURL(), $url); ?>
                            <?= $url[1]; ?>
                        </a>
                        <?php endif; ?>
                    </p>

                    <p><?= $statement->getContent(); ?></p>
                    <?php foreach ($statement->getAttachments() as $attachment): ?>
                        <a href="<?= '/public/uploads/' . $attachment->getFilename(); ?>" class="attachment">
                            <i class="fas fa-paperclip"></i>
                            <?= $attachment->getFilename(); ?>
                        </a>
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
                        <a href="https://example.com">
                            <i class="fa fa-link"></i>
                            Zdalne wykłady (app)
                        </a>
                        <!-- TODO: dodać odpowiednie kolory dla ikon [JavaScript] -->
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">
                            <i class="fa fa-link"></i>
                            Zdalne wykłady (www)
                        </a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">
                            <i class="fa fa-link"></i>
                            Materiały z zajęć (www)
                        </a>
                    </li>
                    <li class="record-in-links">
                        <!-- TODO: zmienić styl, zmienić action="" -->
                        <form action="login" method="post" enctype="multipart/form-data">
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
                        <a href="https://example.com">
                            <i class="fa fa-link"></i>
                            Zdalne laboratoria (Zoom)
                        </a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">
                            <i class="fa fa-link"></i>
                            Materiały z zajęć (www)
                        </a>
                    </li>
                    <li class="record-in-links">
                        <a href="mailto:example@example.com">
                            <i class="fa fa-link"></i>
                            Przesyłanie zadań (email)
                        </a>
                        <p>Na adres email: example@example.com<br>
                        Temat i nazwa pliku: [_] Imie Nazwisko</p>
                    </li>
                </ul>
            </div>

        </div>
    </main>
</body>
</html>