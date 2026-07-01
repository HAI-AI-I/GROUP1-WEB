<?php
session_start();

include "../config/config.php";

/* ================================
   TOTAL DATA
================================ */

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))["total"] ?? 0;
$totalSongs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM songs"))["total"] ?? 0;
$totalComments = 86420;

/* Tổng lượt nghe */
$playsQuery = mysqli_query($conn, "SELECT SUM(plays) AS total FROM songs");
$totalPlays = mysqli_fetch_assoc($playsQuery)["total"] ?? 0;

/* ================================
   TOP 5 MOST PLAYED SONGS
================================ */

$topSongs = mysqli_query(
    $conn,
    "SELECT *
     FROM songs
     ORDER BY plays DESC
     LIMIT 5"
);

/* ================================
   TOP 5 SINGERS
================================ */

$topSingers = mysqli_query(
    $conn,
    "SELECT *
     FROM singers
     ORDER BY id DESC
     LIMIT 5"
);

/* ================================
   NEWEST USERS
================================ */

$newestUsers = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     ORDER BY id DESC
     LIMIT 4"
);

/* ================================
   MOST ACTIVE USERS
================================ */

$activeUsers = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     ORDER BY id ASC
     LIMIT 4"
);

function formatNumber($number)
{
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . "M";
    }

    if ($number >= 1000) {
        return round($number / 1000) . "K";
    }

    return $number;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Statistics</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/statistics.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <h2 class="stats-title">Statistics</h2>

            <!-- SUMMARY CARDS -->

            <section class="stats-summary">

                <div class="stats-summary-card">

                    <div class="summary-icon purple">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h3><?= number_format($totalUsers); ?></h3>

                    <p>Total Users</p>

                </div>

                <div class="stats-summary-card">

                    <div class="summary-icon violet">
                        <i class="fa-solid fa-music"></i>
                    </div>

                    <h3><?= number_format($totalSongs); ?></h3>

                    <p>Total Songs</p>

                </div>

                <div class="stats-summary-card">

                    <div class="summary-icon cyan">
                        <i class="fa-solid fa-play"></i>
                    </div>

                    <h3><?= formatNumber($totalPlays); ?></h3>

                    <p>Total Plays</p>

                </div>

                <div class="stats-summary-card">

                    <div class="summary-icon green">
                        <i class="fa-regular fa-comment"></i>
                    </div>

                    <h3><?= number_format($totalComments); ?></h3>

                    <p>Total Comments</p>

                </div>

            </section>

            <!-- CHARTS -->

            <section class="stats-charts">

                <div class="stats-card chart-box">

                    <h2>User Growth</h2>

                    <canvas id="userGrowthChart"></canvas>

                </div>

                <div class="stats-card chart-box">

                    <h2>Song Plays Per Month</h2>

                    <canvas id="playsChart"></canvas>

                </div>

            </section>

            <!-- LISTS -->

            <div class="stats-grid">

                <!-- TOP SONGS -->

                <div class="stats-card">

                    <h2>Top 5 Most Played Songs</h2>

                    <?php
                    $rank = 1;

                    while ($song = mysqli_fetch_assoc($topSongs)):

                        $songTitle = $song["title"] ?? "Unknown Song";
                        $singer = $song["singer"] ?? "Unknown Singer";
                        $plays = $song["plays"] ?? 0;
                        $cover = $song["cover"] ?? "default-song.png";
                        $percent = min(100, $plays / 3000);
                    ?>

                        <div class="stats-row">

                            <span class="rank">#<?= $rank; ?></span>

                            <img src="../uploads/covers/<?= htmlspecialchars($cover); ?>" class="stats-img" alt="song">

                            <div class="stats-info">

                                <h4><?= htmlspecialchars($songTitle); ?></h4>

                                <p><?= htmlspecialchars($singer); ?></p>

                            </div>

                            <div class="stats-progress">

                                <div style="width: <?= $percent; ?>%;"></div>

                            </div>

                            <span class="stats-number">
                                <?= formatNumber($plays); ?>
                            </span>

                        </div>

                    <?php
                        $rank++;
                    endwhile;
                    ?>

                </div>

                <!-- TOP SINGERS -->

                <div class="stats-card">

                    <h2>Top 5 Singers by Followers</h2>

                    <?php
                    $rank = 1;

                    while ($singer = mysqli_fetch_assoc($topSingers)):

                        $name = $singer["name"] ?? "Unknown Singer";
                        $country = $singer["country"] ?? "Unknown";
                        $image = $singer["image"] ?? "default-avatar.png";

                        $fakeFollowers = [
                            1 => "2.3M",
                            2 => "1.8M",
                            3 => "3.1M",
                            4 => "1.5M",
                            5 => "950K"
                        ];

                        $followers = $singer["followers"] ?? $fakeFollowers[$rank] ?? "720K";
                    ?>

                        <div class="stats-row">

                            <span class="rank">#<?= $rank; ?></span>

                            <img src="../uploads/singers/<?= htmlspecialchars($image); ?>" class="stats-img circle"
                                alt="singer">

                            <div class="stats-info">

                                <h4><?= htmlspecialchars($name); ?></h4>

                                <p><?= htmlspecialchars($country); ?></p>

                            </div>

                            <span class="follower-badge">
                                <?= htmlspecialchars($followers); ?>
                            </span>

                        </div>

                    <?php
                        $rank++;
                    endwhile;
                    ?>

                </div>

                <!-- NEWEST USERS -->

                <div class="stats-card">

                    <h2>Newest Users</h2>

                    <?php while ($user = mysqli_fetch_assoc($newestUsers)): ?>

                        <?php
                        $role = strtolower($user["role"] ?? "user");
                        ?>

                        <div class="stats-row">

                            <img src="https://i.pravatar.cc/50?u=<?= $user['id']; ?>" class="stats-img circle" alt="avatar">

                            <div class="stats-info">

                                <h4><?= htmlspecialchars($user["fullname"] ?? "Unknown User"); ?></h4>

                                <p><?= htmlspecialchars($user["email"] ?? "No email"); ?></p>

                            </div>

                            <?php if ($role == "premium"): ?>

                                <span class="role-premium">Premium</span>

                            <?php elseif ($role == "admin"): ?>

                                <span class="role-admin">Admin</span>

                            <?php else: ?>

                                <span class="role-user">User</span>

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                </div>

                <!-- MOST ACTIVE USERS -->

                <div class="stats-card">

                    <h2>Most Active Users</h2>

                    <?php while ($user = mysqli_fetch_assoc($activeUsers)): ?>

                        <?php
                        $role = strtolower($user["role"] ?? "user");
                        ?>

                        <div class="stats-row">

                            <img src="https://i.pravatar.cc/50?u=active<?= $user['id']; ?>" class="stats-img circle"
                                alt="avatar">

                            <div class="stats-info">

                                <h4><?= htmlspecialchars($user["fullname"] ?? "Unknown User"); ?></h4>

                                <p><?= htmlspecialchars($user["email"] ?? "No email"); ?></p>

                            </div>

                            <?php if ($role == "premium"): ?>

                                <span class="role-premium">Premium</span>

                            <?php elseif ($role == "admin"): ?>

                                <span class="role-admin">Admin</span>

                            <?php else: ?>

                                <span class="role-user">User</span>

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                </div>

            </div>

        </main>

    </div>

    <script>
        Chart.defaults.color = "#b8bfd8";

        new Chart(document.getElementById("userGrowthChart"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    data: [1200, 1900, 2400, 2100, 3100, 3800, 4200, 3900, 4600, 5100, 5800, 6400],
                    borderColor: "#8b5cf6",
                    backgroundColor: "rgba(139,92,246,.15)",
                    tension: .4,
                    fill: false,
                    pointRadius: 5,
                    pointBackgroundColor: "#8b5cf6"
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: "rgba(139,92,246,.12)"
                        }
                    },
                    y: {
                        grid: {
                            color: "rgba(139,92,246,.12)"
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById("playsChart"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    data: [45000, 72000, 98000, 85000, 125000, 155000, 178000, 165000, 190000, 215000,
                        250000, 290000
                    ],
                    backgroundColor: "#a855f7",
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: "rgba(139,92,246,.12)"
                        }
                    },
                    y: {
                        grid: {
                            color: "rgba(139,92,246,.12)"
                        },
                        ticks: {
                            callback: value => value / 1000 + "K"
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>