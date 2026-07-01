<header class="topbar">

    <div class="header-title">

        <h2><?= $pageTitle ?? "Dashboard"; ?></h2>

        <p>Welcome back, <?= $_SESSION['admin_name'] ?? "Super Admin"; ?> 👋</p>

    </div>

    <div class="header-right">

        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search anything...">
        </div>

        <div class="notification-box">

            <button class="notification-btn" id="notificationBtn" type="button">
                <i class="fa-regular fa-bell"></i>
                <span class="notification-dot"></span>
            </button>

            <div class="notification-dropdown" id="notificationDropdown">

                <div class="notification-header">
                    <h3>Notifications</h3>
                    <span>4 new</span>
                </div>

                <div class="notification-item">
                    <span class="dot purple"></span>
                    <div>
                        <h4>New user Nguyen Van A registered</h4>
                        <p>2m ago</p>
                    </div>
                </div>

                <div class="notification-item">
                    <span class="dot purple"></span>
                    <div>
                        <h4>"Summer Vibes" reached 300K plays</h4>
                        <p>15m ago</p>
                    </div>
                </div>

                <div class="notification-item">
                    <span class="dot yellow"></span>
                    <div>
                        <h4>New comment flagged for review</h4>
                        <p>1h ago</p>
                    </div>
                </div>

                <div class="notification-item">
                    <span class="dot green"></span>
                    <div>
                        <h4>System backup completed</h4>
                        <p>3h ago</p>
                    </div>
                </div>

            </div>

        </div>

        <div class="avatar">

            <img src="https://i.pravatar.cc/45" alt="Admin">

            <div>
                <h4><?= $_SESSION['admin_name'] ?? "Super Admin"; ?></h4>
                <span>Administrator</span>
            </div>

        </div>

    </div>

</header>

<script>
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdown = document.getElementById("notificationDropdown");

    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle("show");
        });

        notificationDropdown.addEventListener("click", function(e) {
            e.stopPropagation();
        });

        document.addEventListener("click", function() {
            notificationDropdown.classList.remove("show");
        });
    }
</script>