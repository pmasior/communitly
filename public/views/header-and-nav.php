<div class="header-and-nav">
    <header>
        <a class="logo" href="/dashboard">Communitly</a>
    </header>
    <nav>
        <button class="hamburger-menu-icon"></button>
        <ul class="nav-menu">
            <li><a class="dashboard menu-link" href="/dashboard">Dashboard</a></li>
            <?php foreach ($groups as $group): ?>
                <li><a class="menu-group" href=""><?= $group->getFullName(); ?></a></li>
                <?php 
                    $subgroups = $group->getSubgroups();
                    foreach ($subgroups as $subgroup): 
                ?>
                    <li><a class="subgroup menu-link" href="/subgroup/<?= $subgroup->getSubgroupId(); ?>"><?= $subgroup->getShortName(); ?></a></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <p class="gap"></p>
            <li><a class="account menu-link" href="/settings"><?= $_SESSION['user_first_name'];?></a></li>
        </ul>
    </nav>
</div>