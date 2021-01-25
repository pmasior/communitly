<div class="header-and-nav">
    <header>
        <a class="logo" href="/dashboard">Communitly</a>
    </header>
    <nav>
        <button class="hamburger-menu-icon"><i class="fas fa-bars"></i></button>
        <ul class="nav-menu">
            <li><a class="dashboard menu-link" href="/dashboard">
                    <i class="fas fa-sticky-note fa-hover-hidden"></i>
                    <i class="far fa-sticky-note fa-hover-show"></i>
                    Dashboard
                </a>
            </li>
            <?php foreach ($groups as $group): ?>
                <li><a class="menu-group" href=""><?= $group->getFullName(); ?></a></li>
                <?php 
                    $subgroups = $group->getSubgroups();
                    foreach ($subgroups as $subgroup): 
                ?>
                    <li><a class="subgroup menu-link" href="/subgroup/<?= $subgroup->getSubgroupId(); ?>">
                            <i class="fas fa-circle-notch"></i>
                            <?= $subgroup->getShortName(); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <p class="gap"></p>
            <li><a class="account menu-link" href="/settings">
                    <i class="fas fa-user-circle fa-hover-hidden"></i>
                    <i class="far fa-user-circle fa-hover-show"></i>
                    <?= $_SESSION['userFirstName'];?>
                </a>
            </li>
        </ul>
    </nav>
</div>