<?php
session_start();

include "../config/config.php";

/*=================================
        ADD GENRE
=================================*/

if (isset($_POST["addGenre"])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST["name"]
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST["description"]
    );

    mysqli_query(

        $conn,

        "INSERT INTO genres(

            name,
            description,
            songs_count

        )

        VALUES(

            '$name',
            '$description',
            0

        )"

    );

    header("Location: genres.php");
    exit();
}

/*=================================
        UPDATE GENRE
=================================*/

if (isset($_POST["updateGenre"])) {

    $id = (int)$_POST["editId"];

    $name = mysqli_real_escape_string(
        $conn,
        $_POST["editName"]
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST["editDescription"]
    );

    mysqli_query(

        $conn,

        "UPDATE genres

        SET
            name='$name',
            description='$description'

        WHERE id=$id"

    );

    header("Location: genres.php");
    exit();
}

/*=================================
        DELETE GENRE
=================================*/

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    mysqli_query(

        $conn,

        "DELETE FROM genres
         WHERE id=$id"

    );

    header("Location: genres.php");
    exit();
}

/*=================================
        SEARCH
=================================*/

$keyword = "";

if (isset($_GET["search"])) {

    $keyword = trim($_GET["search"]);
}

/*=================================
        LOAD DATA
=================================*/

$result = mysqli_query(

    $conn,

    "SELECT *

    FROM genres

    WHERE
        name LIKE '%$keyword%'
        OR description LIKE '%$keyword%'

    ORDER BY id DESC"

);

$totalGenre = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Genre Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/genres.css">

</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <div class="page-header">

                <div>

                    <h2>Genre Management</h2>

                    <p>
                        <?= $totalGenre ?>
                        genres available
                    </p>

                </div>

                <div class="top-action">

                    <form method="GET" class="search-user">

                        <i class="fa fa-search"></i>

                        <input type="text" name="search" placeholder="Search genres..."
                            value="<?= htmlspecialchars($keyword); ?>">

                    </form>

                    <button class="add-btn" onclick="openAddGenre()">

                        <i class="fa fa-plus"></i>

                        Add Genre

                    </button>

                </div>

            </div>

            <!-- GENRE CARDS -->

            <div class="genre-grid">

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php
                    $songsCount = $row["songs_count"] ?? 0;

                    $progress = min(100, $songsCount / 3);

                    $colors = [
                        "#8b5cf6",
                        "#ef4444",
                        "#3b82f6",
                        "#f59e0b",
                        "#06b6d4"
                    ];

                    $color = $colors[$row["id"] % count($colors)];
                    ?>

                    <div class="genre-card">

                        <div class="genre-card-top">

                            <div class="genre-icon" style="background: <?= $color ?>22; color: <?= $color ?>;">

                                <i class="fa-solid fa-music"></i>

                            </div>

                            <div class="genre-action">

                                <button class="edit-btn" onclick="editGenre(
                                    '<?= $row['id']; ?>',
                                    '<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($row['description'], ENT_QUOTES); ?>'
                                )">

                                    <i class="fa fa-pen"></i>

                                </button>

                                <a class="delete-btn" href="?delete=<?= $row['id']; ?>"
                                    onclick="return confirm('Delete this genre?')">

                                    <i class="fa fa-trash"></i>

                                </a>

                            </div>

                        </div>

                        <div class="genre-content">

                            <h3>
                                <?= htmlspecialchars($row["name"]); ?>
                            </h3>

                            <span style="color: <?= $color ?>;">

                                <?= $songsCount ?>

                                songs

                            </span>

                            <p>
                                <?= htmlspecialchars($row["description"]); ?>
                            </p>

                        </div>

                        <div class="genre-progress">

                            <div style="width: <?= $progress ?>%; background: <?= $color ?>;">
                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- ADD GENRE MODAL -->

            <div class="modal" id="addGenreModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Add New Genre</h2>

                        <span onclick="closeAddGenre()">&times;</span>

                    </div>

                    <form method="POST">

                        <div class="form-group">

                            <label>Genre Name</label>

                            <input type="text" name="name" placeholder="e.g. Jazz" required>

                        </div>

                        <div class="form-group">

                            <label>Description</label>

                            <textarea name="description" placeholder="Describe this genre..." required></textarea>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeAddGenre()">

                                Cancel

                            </button>

                            <button type="submit" name="addGenre" class="add-btn">

                                Add Genre

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- EDIT GENRE MODAL -->

            <div class="modal" id="editGenreModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Edit Genre</h2>

                        <span onclick="closeEditGenre()">&times;</span>

                    </div>

                    <form method="POST">

                        <input type="hidden" name="editId" id="editId">

                        <div class="form-group">

                            <label>Genre Name</label>

                            <input type="text" name="editName" id="editName" required>

                        </div>

                        <div class="form-group">

                            <label>Description</label>

                            <textarea name="editDescription" id="editDescription" required></textarea>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeEditGenre()">

                                Cancel

                            </button>

                            <button type="submit" name="updateGenre" class="add-btn">

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>
            <script>
                function openAddGenre() {
                    document.getElementById("addGenreModal").style.display = "flex";
                }

                function closeAddGenre() {
                    document.getElementById("addGenreModal").style.display = "none";
                }

                function editGenre(id, name, description) {
                    document.getElementById("editGenreModal").style.display = "flex";

                    document.getElementById("editId").value = id;
                    document.getElementById("editName").value = name;
                    document.getElementById("editDescription").value = description;
                }

                function closeEditGenre() {
                    document.getElementById("editGenreModal").style.display = "none";
                }

                window.onclick = function(e) {
                    if (e.target.classList.contains("modal")) {
                        e.target.style.display = "none";
                    }
                }
            </script>

        </main>

    </div>

</body>

</html>