<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = "Super Admin";
}

$adminName = $_SESSION['admin_name'];
$adminEmail = "admin@tuneflow.vn";

$message = "";

if (isset($_POST["saveProfile"])) {
    $_SESSION['admin_name'] = trim($_POST["display_name"]);
    $adminName = $_SESSION['admin_name'];
    $adminEmail = trim($_POST["email"]);
    $message = "Profile updated successfully!";
}

if (isset($_POST["updatePassword"])) {
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        $message = "New password and confirm password do not match!";
    } else {
        $message = "Password updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/settings.css">
</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php
            $pageTitle = "Settings";
            include "header.php";
            ?>

            <h2 class="settings-title">Settings</h2>

            <?php if (!empty($message)): ?>
                <div class="settings-message">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- ADMIN PROFILE -->

            <section class="settings-card">

                <h3>
                    <i class="fa-solid fa-user-gear"></i>
                    Admin Profile
                </h3>

                <div class="admin-profile">

                    <img src="https://i.pravatar.cc/120" alt="Admin">

                    <div>
                        <h2><?= htmlspecialchars($adminName); ?></h2>
                        <p>Administrator</p>
                        <span>Administrator</span>
                    </div>

                </div>

                <form method="POST">

                    <div class="form-group">

                        <label>DISPLAY NAME</label>

                        <input type="text" name="display_name" value="<?= htmlspecialchars($adminName); ?>" required>

                    </div>

                    <div class="form-group">

                        <label>EMAIL</label>

                        <input type="email" name="email" value="<?= htmlspecialchars($adminEmail); ?>" required>

                    </div>

                    <button type="submit" name="saveProfile" class="add-btn">

                        Save Profile

                    </button>

                </form>

            </section>

            <!-- CHANGE PASSWORD -->

            <section class="settings-card">

                <h3>
                    <i class="fa-solid fa-shield-halved"></i>
                    Change Password
                </h3>

                <form method="POST">

                    <div class="form-group">

                        <label>CURRENT PASSWORD</label>

                        <input type="password" name="current_password" placeholder="••••••••" required>

                    </div>

                    <div class="form-group">

                        <label>NEW PASSWORD</label>

                        <input type="password" name="new_password" placeholder="••••••••" required>

                    </div>

                    <div class="form-group">

                        <label>CONFIRM PASSWORD</label>

                        <input type="password" name="confirm_password" placeholder="••••••••" required>

                    </div>

                    <button type="submit" name="updatePassword" class="add-btn">

                        Update Password

                    </button>

                </form>

            </section>

            <!-- NOTIFICATION SETTINGS -->

            <section class="settings-card">

                <h3>
                    <i class="fa-regular fa-bell"></i>
                    Notification Settings
                </h3>

                <div class="setting-row">

                    <div>
                        <h4>New User Registration</h4>
                        <p>Get notified when new users sign up</p>
                    </div>

                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>

                </div>

                <div class="setting-row">

                    <div>
                        <h4>New Song Added</h4>
                        <p>Get notified when new songs are uploaded</p>
                    </div>

                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>

                </div>

                <div class="setting-row">

                    <div>
                        <h4>New Comments</h4>
                        <p>Get notified for each new comment</p>
                    </div>

                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>

                </div>

                <div class="setting-row">

                    <div>
                        <h4>Security Alerts</h4>
                        <p>Important account and system alerts</p>
                    </div>

                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>

                </div>

            </section>

            <!-- SYSTEM SETTINGS -->

            <section class="settings-card">

                <h3>
                    <i class="fa-solid fa-gear"></i>
                    System Settings
                </h3>

                <div class="system-actions">

                    <button type="button">
                        <i class="fa-solid fa-download"></i>
                        Export Data
                    </button>

                    <button type="button">
                        <i class="fa-solid fa-rotate"></i>
                        Clear Cache
                    </button>

                </div>

            </section>

        </main>

    </div>

</body>

</html>