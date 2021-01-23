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
    <title><?= $openSubgroup->getFullName(); ?> - Communitly</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <?php include('message-dialog.php'); ?>
        <div class="main-title">
            <h1><?= $openSubgroup->getFullName(); ?></h1>
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
                    <input type="text" class='input-with-text' name='statementHeader' placeholder="Nagłówek">
                    <input type="text" class='input-with-text' name='statementURL' placeholder="Link do źródła">
                    <textarea name="statementContent" class='input-with-text' placeholder="Treść nowego komunikatu"></textarea>
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
                    <a href="#" class="small-action-button js-dialog-activator">
                        <i class="fas fa-minus-square fa-hover-hidden"></i>
                        <i class="far fa-minus-square fa-hover-show"></i>
                    </a>
                    <div class="dialog-background js-dialog-background"></div>
                    <div class="dialog js-dialog">
                        <h1>Usuwanie komunikatu</h1>
                        <h4>
                            <span class="group-name"><?= $group->getFullName(); ?></span>
                            <span class="subgroup"><a href=""><?= $subgroup->getFullName(); ?></a></span>
                        </h4>
                        <p>Czy napewno chcesz usunać komunikat <?= $statement->getHeader(); ?>?</p>
                        <form action="/removeStatement" method="post" enctype="multipart/form-data">
                            <input type="hidden" name='statementId' value="<?= $statement->getStatementId() ?>">
                            <input type="submit" class='button button-in-form' value="Potwierdź">
                        </form>
                    </div>

                    <a href="#" class="small-action-button js-dialog-activator">
                        <i class="fas fa-pen-square fa-hover-hidden"></i>
                        <i class="fas fa-pen-square fa-hover-show"></i>
                    </a>
                    <div class="dialog-background js-dialog-background"></div>
                    <div class="dialog js-dialog">
                        <h1>Edycja komunikatu</h1>
                        <form action="/editStatement" method="post">
                            <h4><?= $statement->getHeader(); ?></h4>
                            <input type="hidden" name='statementId' value="<?= $statement->getStatementId() ?>">
                            <input type="text" class='input-with-text' name='statementHeader' placeholder="Nagłówek" value="<?= $statement->getHeader(); ?>">
                            <input type="text" class='input-with-text' name='statementURL' placeholder="Link do źródła" value="<?= $statement->getSourceURL(); ?>">
                            <textarea name="statementContent" class='input-with-text' placeholder="Treść nowego komunikatu"><?= $statement->getContent(); ?></textarea>
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
                    <a href="#" class="small-action-button js-dialog-activator">
                        <i class="fas fa-check-square fa-hover-hidden"></i>
                        <i class="far fa-check-square fa-hover-show"></i>
                    </a>
                    <div class="dialog-background js-dialog-background"></div>
                    <div class="dialog js-dialog">
                        <?php if ($statement->getApproveDate()): ?>
                            <h1>Cofanie potwierdzenia komunikatu</h1>
                            Czy chcesz cofnąć potwierdzenie komunikatu?
                            <form action="/undoConfirmStatement" method="post">
                                <h4><?= $statement->getHeader(); ?></h4>
                                <input type="hidden" name='statementId' value="<?= $statement->getStatementId() ?>">
                                <input type="submit" class='button button-in-form' value="Potwierdź">
                            </form>
                        <?php else: ?>
                            <h1>Potwierdzanie komunikatu</h1>
                            Czy chcesz potwierdzić komunikat?
                            <form action="/confirmStatement" method="post">
                                <h4><?= $statement->getHeader(); ?></h4>
                                <input type="hidden" name='userId' value="<?= $_SESSION['userId'] ?>">
                                <input type="hidden" name='statementId' value="<?= $statement->getStatementId() ?>">
                                <input type="submit" class='button button-in-form' value="Potwierdź">
                            </form>
                        <?php endif; ?>
                    </div>

                    <? endif; ?>

                    <?php include('statement-content.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>

        
        <div class="links">
            <div class="widget-group-header">
                <h3 class="widget-group">Linki</h3>
                <?php if ($permission == 1): ?>
                <a href="#" class="js-dialog-activator">
                    <i class="fas fa-plus-square fa-hover-hidden"></i>
                    <i class="far fa-plus-square fa-hover-show"></i>
                </a>
                <?php endif; ?>
            </div>

            <?php if ($permission == 1): ?>
            <div class="dialog-background js-dialog-background"></div>
            <div class="dialog js-dialog">
                <h1>Dodawanie linku</h1>
                <h4>
                    <span class="group-name"><?= $group->getFullName(); ?></span>
                    <span class="subgroup"><a href=""><?= $subgroup->getFullName(); ?></a></span>
                </h4>
                <form action="/addLink" method="post" enctype="multipart/form-data">
                    <input type="text" class='input-with-text' name='linkName' placeholder="Nazwa">
                    <input type="text" class='input-with-text' name='linkURL' placeholder="Link">
                    <textarea name="linkNote" class='input-with-text' placeholder="Notatka"></textarea>
                    <?php foreach ($allThreadsInSubgroup as $thread): ?>
                        <span>
                            <label>
                                <input type="checkbox" class='checkbox-in-form' name="thread[]" placeholder="Wątki" value='<?= $thread->getThreadId(); ?>'>
                                <?= $thread->getName(); ?>
                            </label>
                        </span>
                    <?php endforeach; ?>
                    <input type="submit" class='button button-in-form' value="Wyślij">
                </form>
            </div>
            <?php endif; ?>

            <div class="widget">
                <ul>
                    <?php foreach ($links as $link): ?>
                    <li class="record-in-links">
                        <?php if ($permission == 1): ?>
                        <a href="#" class="small-action-button js-dialog-activator">
                            <i class="fas fa-minus-square fa-hover-hidden"></i>
                            <i class="far fa-minus-square fa-hover-show"></i>
                        </a>
                        <div class="dialog-background js-dialog-background"></div>
                        <div class="dialog js-dialog">
                            <h1>Usuwanie linku</h1>
                            <h4>
                                <span class="group-name"><?= $group->getFullName(); ?></span>
                                <span class="subgroup"><a href=""><?= $subgroup->getFullName(); ?></a></span>
                            </h4>
                            Czy napewno chcesz usunać link <?= $link->getTitle(); ?>?
                            <form action="/removeLink" method="post" enctype="multipart/form-data">
                                <input type="hidden" name='linkId' value="<?= $link->getLinkId(); ?>">
                                <input type="submit" class='button button-in-form' value="Potwierdź">
                            </form>
                        </div>
                        <?php endif; ?>

                        <a href="<?= $link->getUrl(); ?>">
                            <i class="fa fa-link"></i>
                            <?= $link->getTitle(); ?>
                        </a>
                        <p><?= $link->getNote(); ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            </div>

        </div>
    </main>
</body>
</html>