<?php
include "dashboard_data.php";
?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TuneFlow Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <h2>Dashboard Overview</h2>

            <!-- Cards -->

            <section class="cards">

                <div class="card">

                    <i class="fa-solid fa-users"></i>

                    <h3><?= number_format($totalUsers); ?></h3>

                    <p>Total Users</p>

                </div>

                <div class="card">

                    <i class="fa-solid fa-music"></i>

                    <h3><?= number_format($totalSongs); ?></h3>

                    <p>Total Songs</p>

                </div>

                <div class="card">

                    <i class="fa-solid fa-user"></i>

                    <h3><?= number_format($totalSingers); ?></h3>

                    <p>Total Singers</p>

                </div>

                <div class="card">

                    <i class="fa-solid fa-layer-group"></i>

                    <h3><?= number_format($totalGenres); ?></h3>

                    <p>Total Genres</p>

                </div>

                <div class="card">

                    <i class="fa-solid fa-play"></i>

                    <h3><?= $totalPlays; ?></h3>

                    <p>Total Plays</p>

                </div>

            </section>

            <section class="charts">

                <div class="chart-card">

                    <h3>Monthly Users</h3>

                    <canvas id="lineChart"></canvas>

                </div>

                <div class="chart-card">

                    <h3>Genre Distribution</h3>

                    <canvas id="pieChart"></canvas>

                </div>

            </section>

            <!-- BIỂU ĐỒ PHÍA DƯỚI -->

            <section class="charts-bottom">

                <div class="chart-card">

                    <h3>Song Plays Per Month</h3>

                    <canvas id="barChart"></canvas>

                </div>

                <div class="chart-card">

                    <h3>Top Songs</h3>

                    <div class="song">

                        <span>Summer Vibes</span>

                        <div class="progress">

                            <div style="width:95%"></div>

                        </div>

                    </div>

                    <div class="song">

                        <span>Neon Lights</span>

                        <div class="progress">

                            <div style="width:80%"></div>

                        </div>

                    </div>

                    <div class="song">

                        <span>Forever Young</span>

                        <div class="progress">

                            <div style="width:65%"></div>

                        </div>

                    </div>

                    <div class="song">

                        <span>Midnight Dream</span>

                        <div class="progress">

                            <div style="width:55%"></div>

                        </div>

                    </div>

                </div>

            </section>

            <!-- HOẠT ĐỘNG GẦN ĐÂY -->

            <section class="activity">

                <h3>Recent Activity</h3>

                <ul>

                    <li>New user Nguyen Van A registered</li>

                    <li>New song added by Luna Sky</li>

                    <li>User upgraded to Premium</li>

                    <li>Summer Vibes reached 300K plays</li>

                    <li>New playlist created by user</li>

                </ul>

            </section>

        </main>

    </div>

    <!-- Chart JS -->

    <script>
        Chart.defaults.color = "#ffffff";

        new Chart(document.getElementById("lineChart"), {

            type: "line",

            data: {

                labels: [
                    "Jan", "Feb", "Mar", "Apr",
                    "May", "Jun", "Jul", "Aug",
                    "Sep", "Oct", "Nov", "Dec"
                ],

                datasets: [{

                    label: "Users",

                    data: [
                        1200, 1800, 2400, 2200,
                        3500, 4300, 4700, 4500,
                        5200, 5800, 6500, 7300
                    ],

                    borderColor: "#8b5cf6",

                    backgroundColor: "rgba(139,92,246,.2)",

                    fill: true,

                    tension: .4

                }]

            },

            options: {

                responsive: true

            }

        });

        new Chart(document.getElementById("pieChart"), {

            type: "doughnut",

            data: {

                labels: [

                    "Pop",
                    "EDM",
                    "Rock",
                    "Rap",
                    "Ballad"

                ],

                datasets: [{

                    data: [

                        284,
                        217,
                        196,
                        143,
                        198

                    ]

                }]

            }

        });

        new Chart(document.getElementById("barChart"), {

            type: "bar",

            data: {

                labels: [
                    "Jan", "Feb", "Mar", "Apr",
                    "May", "Jun", "Jul", "Aug",
                    "Sep", "Oct", "Nov", "Dec"
                ],

                datasets: [{

                    label: "Plays",

                    data: [
                        50, 80, 120, 110,
                        150, 190, 210, 200,
                        230, 250, 290, 320
                    ]

                }]

            },

            options: {

                responsive: true

            }

        });
    </script>

</body>

</html>