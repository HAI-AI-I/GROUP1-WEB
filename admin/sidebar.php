<aside class="sidebar">

    <div class="logo">

        <i class="fa-solid fa-music"></i>

        <div>
            <h3>TuneFlow</h3>
            <span>Admin Panel</span>
        </div>

    </div>

    <ul class="menu">

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <a href="index.php">
                <i class="fa-solid fa-chart-pie"></i>
                Dashboard
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <a href="users.php">
                <i class="fa-solid fa-users"></i>
                Users
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'songs.php' ? 'active' : ''; ?>">
            <a href="songs.php">
                <i class="fa-solid fa-music"></i>
                Songs
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'singers.php' ? 'active' : ''; ?>">
            <a href="singers.php">
                <i class="fa-solid fa-user"></i>
                Singers
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'genres.php' ? 'active' : ''; ?>">
            <a href="genres.php">
                <i class="fa-solid fa-layer-group"></i>
                Genres
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'statistics.php' ? 'active' : ''; ?>">
            <a href="statistics.php">
                <i class="fa-solid fa-chart-line"></i>
                Statistics
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <a href="settings.php">
                <i class="fa-solid fa-gear"></i>
                Settings
            </a>
        </li>

    </ul>

    <a href="logout.php" class="logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
    </a>

</aside>