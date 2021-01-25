<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/all.css">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/settings-style.css">
    <script type="text/javascript" src="/public/js/form_validation.js" defer></script>
    <script type="text/javascript" src="/public/js/dialog.js" defer></script>
    <script type="text/javascript" src="/public/js/message-dialog.js" defer></script>
    <title>Communitly - settings</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <?php include('message-dialog.php'); ?>
        <div class="main-title">
            <h1>Zarządzaj swoim kontem</h1>
        </div>
        <div class="settings-for-account">
            <div class="widget-group-header">
                <h3 class="widget-group">Twoje konto</h3>
            </div>
            <div class="settings-widget widget">
                <a class="button action-button" href="/logout">Wyloguj się</a>
                <p>Witaj</p>
                <p>Jesteś zalogowany jako <?= $userFirstname; ?> <?= $userLastName;?></p>
            </div>
        </div>

        <div class="settings-for-user-groups">
            <div class="widget-group-header">
                <h3 class="widget-group">Twoje grupy</h3>
                <a href="#" class="js-dialog-activator">
                    <i class="fas fa-plus-square fa-hover-hidden"></i>
                    <i class="far fa-plus-square fa-hover-show"></i>
                </a>
                <div class="dialog-background js-dialog-background"></div>
                <div class="dialog js-dialog">
                    <h1>Dołączanie do grupy</h1>
                    <form action="/optInGroup" method="post">
                        <input type="text" class='input-with-text' name='accessPassword' placeholder="Kod dołączenia do grupy">
                        <input type="submit" class='button button-in-form' value="Dołącz do grupy">
                    </form>
                </div>
            </div>

            <div class="settings-widget widget">
                <ul class="group-list">
                <?php foreach ($availableGroups as $group): ?>
                    <li><?= $group->getFullName(); ?>
                        <a href="/optOutGroup/<?= $group->getGroupId(); ?>">
                            <i class="fas fa-sign-out-alt" title="Wypisz się"></i>
                        </a>
                    </li>
                    <ul class="group-list">
                    <?php foreach ($group->getSubgroups() as $subgroup): ?>
                        <li><?= $subgroup->getFullName(); ?>
                            <?php if (in_array($subgroup->getSubgroupId(), $subgroupsIdForUser)): ?>
                            <a href="/optOutSubgroup/<?= $subgroup->getSubgroupId(); ?>">
                                <i class="fas fa-sign-out-alt" title="Wypisz się"></i>
                            </a>
                            <?php else: ?>
                            <a href="/optInSubgroup/<?= $subgroup->getSubgroupId(); ?>">
                                <i class="fas fa-sign-in-alt" title="Zapisz się"></i>
                            </a>
                            <?php endif; ?>
                        </li>
                        <ul class="group-list">
                        <?php foreach ($subgroup->getThreads() as $thread): ?>
                            <li><?= $thread->getName(); ?>
                                <?php if (in_array($thread->getThreadId(), $threadsIdForUser)): ?>
                                <a href="/optOutThread/<?= $thread->getThreadId(); ?>">
                                    <i class="fas fa-sign-out-alt" title="Wypisz się"></i>
                                </a>
                                <?php else: ?>
                                <a href="/optInThread/<?= $thread->getThreadId(); ?>">
                                    <i class="fas fa-sign-in-alt" title="Zapisz się"></i>
                                </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>




        <div class="settings-for-administrated-groups">
            <div class="widget-group-header">
                <h3 class="widget-group">Administrowanie grupami</h3>
                <a href="#" class="js-dialog-activator">
                    <i class="fas fa-plus-square fa-hover-hidden"></i>
                    <i class="far fa-plus-square fa-hover-show"></i>
                </a>
                <div class="dialog-background js-dialog-background"></div>
                <div class="dialog js-dialog">
                    <h1>Tworzenie nowej grupy</h1>
                    <form action="/createGroup" method="post">
                        <input type="text" class='input-with-text' name='fullName' placeholder="Pełna nazwa">
                        <input type="text" class='input-with-text' name='shortName' placeholder="Skrócona nazwa">
                        <input type="submit" class='button button-in-form' value="Zatwierdź">
                    </form>
                </div>
            </div>

            <div class="settings-widget widget">
                <ul class="group-list">
                    <?php foreach ($availableGroups as $group): ?>
                    <?php if ($userPermissions[$group->getGroupId()] == 1): ?>
                        <li><?= $group->getFullName(); ?>
                            <a href="#" class="js-dialog-activator">
                                <i class="fas fa-minus-square fa-hover-hidden"></i>
                                <i class="far fa-minus-square fa-hover-show"></i>
                            </a>
                            <div class="dialog-background js-dialog-background"></div>
                            <div class="dialog js-dialog">
                                <h1>Usuwanie grupy</h1>
                                <form action="/deleteGroup" method="post">
                                    <input type="hidden" name='groupId' value="<?= $group->getGroupId() ?>">
                                    <input type="submit" class='button button-in-form' value="Zatwierdź">
                                </form>
                            </div>
                        </li>
                        <ul class="group-list">
                            <?php foreach ($group->getSubgroups() as $subgroup): ?>
                            <li><?= $subgroup->getFullName(); ?>
                                <a href="#" class="js-dialog-activator">
                                    <i class="fas fa-minus-square fa-hover-hidden"></i>
                                    <i class="far fa-minus-square fa-hover-show"></i>
                                </a>
                                <div class="dialog-background js-dialog-background"></div>
                                <div class="dialog js-dialog">
                                    <h1>Usuwanie podgrupy</h1>
                                    <form action="/deleteSubgroup" method="post">
                                        <input type="hidden" name='groupId' value="<?= $group->getGroupId() ?>">
                                        <input type="hidden" name='subgroupId' value="<?= $subgroup->getSubgroupId() ?>">
                                        <input type="submit" class='button button-in-form' value="Zatwierdź">
                                    </form>
                                </div>
                            </li>
                            <ul class="group-list">
                                <?php foreach ($subgroup->getThreads() as $thread): ?>
                                <li><?= $thread->getName(); ?>
                                    <a href="#" class="js-dialog-activator">
                                        <i class="fas fa-minus-square fa-hover-hidden"></i>
                                        <i class="far fa-minus-square fa-hover-show"></i>
                                    </a>
                                    <div class="dialog-background js-dialog-background"></div>
                                    <div class="dialog js-dialog">
                                        <h1>Usuwanie wątku</h1>
                                        <p>Wszyscy użytkownicy zostaną wypisani z wątku, a następnie wątek zostanie usunięty</p>
                                        <form action="/deleteThread" method="post">
                                            <input type="hidden" name='groupId' value="<?= $group->getGroupId() ?>">
                                            <input type="hidden" name='threadId' value="<?= $thread->getThreadId() ?>">
                                            <input type="submit" class='button button-in-form' value="Zatwierdź">
                                        </form>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                                <li>
                                    <a href="#" class="js-dialog-activator">
                                        <i class="fas fa-plus-square fa-hover-hidden"></i>
                                        <i class="far fa-plus-square fa-hover-show"></i>
                                        Dodaj wątek
                                    </a>
                                    <div class="dialog-background js-dialog-background"></div>
                                    <div class="dialog js-dialog">
                                        <h1>Dodawanie wątku</h1>
                                        <form action="/createThread" method="post">
                                            <input type="hidden" name='subgroupId' value="<?= $subgroup->getSubgroupId() ?>">
                                            <input type="text" class='input-with-text' name='name' placeholder="Nazwa">
                                            <input type="submit" class='button button-in-form' value="Zatwierdź">
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <?php endforeach; ?>
                            <li>
                                <a href="#" class="js-dialog-activator">
                                    <i class="fas fa-plus-square fa-hover-hidden"></i>
                                    <i class="far fa-plus-square fa-hover-show"></i>
                                    Dodaj podgrupę
                                </a>
                                <div class="dialog-background js-dialog-background"></div>
                                <div class="dialog js-dialog">
                                    <h1>Dodawanie podgrupy</h1>
                                    <form action="/createSubgroup" method="post">
                                        <input type="hidden" name='groupId' value="<?= $group->getGroupId() ?>">
                                        <input type="text" class='input-with-text' name='fullName' placeholder="Pełna nazwa">
                                        <input type="text" class='input-with-text' name='shortName' placeholder="Skrócona nazwa">
                                        <input type="submit" class='button button-in-form' value="Zatwierdź">
                                    </form>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>


        <div class="settings-for-user-identities">
            <div class="widget-group-header">
                <h3 class="widget-group">Twoje dane osobowe</h3>
            </div>
            <div class="settings-widget widget">
                <a class="button action-button js-dialog-activator">Zmień dane</a>
                <div class="dialog-background js-dialog-background"></div>
                <div class="dialog js-dialog">
                    <h1>Zmiana danych osobowych</h1>
                        <form action="/changeUserData" method="post">
                            <input type="text" class='input-with-text' name='email' placeholder="Login" autofocus>
                            <input type="password" class='input-with-text' name="oldPassword" placeholder="Old Password">
                            <input type="password" class='input-with-text' name="newPassword" placeholder="New Password">
                            <input type="password" class='input-with-text' name="confirmedNewPassword" placeholder="Confirm new password">
                            <input type="text" class='input-with-text' name="firstName" placeholder="First name">
                            <input type="text" class='input-with-text' name="lastName" placeholder="Last name">
                            <input type="submit" class='button button-in-form' value="Zmień dane">
                        </form>
                </div>
                <p>Imię i nazwisko: <?= $userFirstname; ?> <?= $userLastName;?></p>
                <p>Adres email: <?= $userEmail; ?></p>
                <p>Hasło: ********</p>
            </div>
        </div>
    </main>
</body>
</html>