<?php
session_start();
include "../config/config.php";

/* ============================
   ADD NEW SONG
============================ */

if (isset($_POST["addSong"])) {

    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $singer = mysqli_real_escape_string($conn, $_POST["singer"]);
    $genre = mysqli_real_escape_string($conn, $_POST["genre"]);

    $duration = "00:00";
    $plays = 0;

    /* ---------- Upload Cover ---------- */

    $cover = "default-song.png";

    if (isset($_FILES["cover"]) && $_FILES["cover"]["error"] == 0) {

        $ext = strtolower(pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION));

        if (in_array($ext, ["jpg", "jpeg", "png"])) {

            $cover = time() . "_cover." . $ext;

            move_uploaded_file(
                $_FILES["cover"]["tmp_name"],
                "../uploads/covers/" . $cover
            );
        }
    }

    /* ---------- Upload Audio ---------- */

    $audio = "";

    if (isset($_FILES["audio"]) && $_FILES["audio"]["error"] == 0) {

        $ext = strtolower(pathinfo($_FILES["audio"]["name"], PATHINFO_EXTENSION));

        if ($ext == "mp3") {

            $audio = time() . "_song.mp3";

            move_uploaded_file(
                $_FILES["audio"]["tmp_name"],
                "../uploads/songs/" . $audio
            );
        }
    }

    $sql = "INSERT INTO songs
    (
        title,
        singer,
        genre,
        duration,
        plays,
        cover,
        audio
    )
    VALUES
    (
        '$title',
        '$singer',
        '$genre',
        '$duration',
        '$plays',
        '$cover',
        '$audio'
    )";

    mysqli_query($conn, $sql);

    header("Location: songs.php");
    exit();
}

/* ============================
   UPDATE SONG
============================ */

if (isset($_POST["updateSong"])) {

    $id = (int)$_POST["editId"];

    $title = mysqli_real_escape_string($conn, $_POST["editTitle"]);
    $singer = mysqli_real_escape_string($conn, $_POST["editSinger"]);
    $genre = mysqli_real_escape_string($conn, $_POST["editGenre"]);

    $sql = "UPDATE songs
            SET
                title='$title',
                singer='$singer',
                genre='$genre'
            WHERE id=$id";

    mysqli_query($conn, $sql);

    header("Location: songs.php");
    exit();
}

/* ============================
   DELETE SONG
============================ */

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    $old = mysqli_query(
        $conn,
        "SELECT cover,audio FROM songs WHERE id=$id"
    );

    if ($song = mysqli_fetch_assoc($old)) {

        if (
            $song["cover"] != "" &&
            $song["cover"] != "default-song.png"
        ) {

            @unlink("../uploads/covers/" . $song["cover"]);
        }

        if ($song["audio"] != "") {

            @unlink("../uploads/songs/" . $song["audio"]);
        }
    }

    mysqli_query(
        $conn,
        "DELETE FROM songs WHERE id=$id"
    );

    header("Location: songs.php");
    exit();
}

/* ============================
   SEARCH
============================ */

$keyword = "";

if (isset($_GET["search"])) {

    $keyword = trim($_GET["search"]);
}

/* ============================
   LOAD SONG
============================ */

$sql = "SELECT *
        FROM songs
        WHERE
            title LIKE '%$keyword%'
            OR singer LIKE '%$keyword%'
            OR genre LIKE '%$keyword%'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

$totalSongs = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Song Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/songs.css">

</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <div class="page-header">

                <div>

                    <h2>Song Management</h2>

                    <p>

                        <?= $totalSongs; ?>

                        Songs in library

                    </p>

                </div>

                <div class="top-action">

                    <form method="GET" class="search-user">

                        <i class="fa-solid fa-search"></i>

                        <input type="text" name="search" placeholder="Search song..."
                            value="<?= htmlspecialchars($keyword); ?>">

                    </form>

                    <button class="add-btn" onclick="openAddSong()">

                        <i class="fa-solid fa-plus"></i>

                        Add Song

                    </button>

                </div>

            </div>

            <!-- =========================================
                SONG TABLE
========================================= -->

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>COVER</th>

                            <th>SONG</th>

                            <th>SINGER</th>

                            <th>GENRE</th>

                            <th>DURATION</th>

                            <th>PLAYS</th>

                            <th>ACTION</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {

                        ?>

                                <tr>

                                    <td>

                                        <?= $row["id"]; ?>

                                    </td>

                                    <td>

                                        <img src="../uploads/covers/<?= htmlspecialchars($row["cover"]); ?>" class="song-cover"
                                            alt="cover">

                                    </td>

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars($row["title"]); ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row["singer"]); ?>

                                    </td>

                                    <td>

                                        <span class="genre-badge">

                                            <?= htmlspecialchars($row["genre"]); ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row["duration"]); ?>

                                    </td>

                                    <td>

                                        <?= number_format($row["plays"]); ?>

                                    </td>

                                    <td>

                                        <div class="action-btn">

                                            <!-- VIEW -->

                                            <button class="view-btn" onclick="viewSong(

                        '<?= htmlspecialchars($row['title'], ENT_QUOTES); ?>',

                        '<?= htmlspecialchars($row['singer'], ENT_QUOTES); ?>',

                        '<?= htmlspecialchars($row['genre'], ENT_QUOTES); ?>',

                        '<?= $row['duration']; ?>',

                        '<?= number_format($row['plays']); ?>',

                        '<?= $row['cover']; ?>',

                        '<?= $row['audio']; ?>'

                        )">

                                                <i class="fa-solid fa-eye"></i>

                                            </button>

                                            <!-- EDIT -->

                                            <button class="edit-btn" onclick="editSong(

                        '<?= $row['id']; ?>',

                        '<?= htmlspecialchars($row['title'], ENT_QUOTES); ?>',

                        '<?= htmlspecialchars($row['singer'], ENT_QUOTES); ?>',

                        '<?= htmlspecialchars($row['genre'], ENT_QUOTES); ?>'

                        )">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>

                                            <!-- DOWNLOAD -->

                                            <a class="download-btn" href="../uploads/songs/<?= urlencode($row['audio']); ?>"
                                                download>

                                                <i class="fa-solid fa-download"></i>

                                            </a>

                                            <!-- DELETE -->

                                            <a class="delete-btn" href="?delete=<?= $row['id']; ?>"
                                                onclick="return confirm('Delete this song?')">

                                                <i class="fa-solid fa-trash"></i>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php

                            }
                        } else {

                            ?>

                            <tr>

                                <td colspan="8" style="text-align:center;padding:40px;">

                                    <i class="fa-solid fa-music"></i>

                                    <br><br>

                                    No songs found.

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <!-- =========================================
                PAGINATION
========================================= -->

            <div class="pagination">

                <a href="#" class="active">

                    1

                </a>

                <a href="#">

                    2

                </a>

                <a href="#">

                    3

                </a>

            </div>
            <!-- =====================================================
                    ADD SONG MODAL
====================================================== -->

            <div class="modal" id="addSongModal">

                <div class="modal-content song-modal">

                    <div class="modal-header">

                        <h2>Add New Song</h2>

                        <span onclick="closeAddSong()">
                            <i class="fa-solid fa-xmark"></i>
                        </span>

                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <!-- Song Title -->

                        <div class="form-group">

                            <label>Song Title</label>

                            <input type="text" name="title" placeholder="Enter song title" required>

                        </div>

                        <!-- Singer + Genre -->

                        <div class="row">

                            <div class="form-group">

                                <label>Singer</label>

                                <input type="text" name="singer" placeholder="Singer name" required>

                            </div>

                            <div class="form-group">

                                <label>Genre</label>

                                <select name="genre">

                                    <option value="Pop">Pop</option>
                                    <option value="Rock">Rock</option>
                                    <option value="Ballad">Ballad</option>
                                    <option value="Rap">Rap</option>
                                    <option value="EDM">EDM</option>

                                </select>

                            </div>

                        </div>

                        <!-- Upload -->

                        <div class="upload-grid">

                            <!-- Cover -->

                            <div class="upload-box">

                                <label>Cover Image</label>

                                <div class="upload-area">

                                    <img id="coverPreview" src="../uploads/covers/default-song.png"
                                        class="preview-image" alt="Cover">

                                    <label class="upload-btn">

                                        <i class="fa-solid fa-image"></i>

                                        Choose Image

                                        <input type="file" name="cover" id="coverInput" accept=".jpg,.jpeg,.png" hidden>

                                    </label>

                                    <small id="coverName">

                                        No image selected

                                    </small>

                                </div>

                            </div>

                            <!-- Audio -->

                            <div class="upload-box">

                                <label>Audio File</label>

                                <div class="upload-area">

                                    <i class="fa-solid fa-music upload-icon">
                                    </i>

                                    <label class="upload-btn">

                                        <i class="fa-solid fa-upload"></i>

                                        Choose MP3

                                        <input type="file" name="audio" id="audioInput" accept=".mp3" hidden>

                                    </label>

                                    <small id="audioName">

                                        No audio selected

                                    </small>

                                </div>

                            </div>

                        </div>

                        <!-- Buttons -->

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeAddSong()">

                                Cancel

                            </button>

                            <button type="submit" name="addSong" class="add-btn">

                                <i class="fa-solid fa-plus"></i>

                                Add Song

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- ============================
        PREVIEW FILE
============================= -->

            <script>
                const coverInput = document.getElementById("coverInput");

                const coverPreview = document.getElementById("coverPreview");

                const coverName = document.getElementById("coverName");

                coverInput.onchange = function() {

                    if (this.files.length) {

                        coverName.innerHTML = this.files[0].name;

                        coverPreview.src = URL.createObjectURL(this.files[0]);

                    }

                }

                const audioInput = document.getElementById("audioInput");

                const audioName = document.getElementById("audioName");

                audioInput.onchange = function() {

                    if (this.files.length) {

                        audioName.innerHTML = this.files[0].name;

                    }

                }
            </script>

            <!-- =====================================================
                    VIEW SONG MODAL
====================================================== -->

            <div class="modal" id="viewSongModal">

                <div class="modal-content song-modal">

                    <div class="modal-header">

                        <h2>Song Information</h2>

                        <span onclick="closeViewSong()">

                            <i class="fa-solid fa-xmark"></i>

                        </span>

                    </div>

                    <div class="view-song">

                        <img id="viewCover" src="../uploads/covers/default-song.png" class="view-cover" alt="Cover">

                        <div class="view-info">

                            <h2 id="viewTitle">
                                Song Title
                            </h2>

                            <p>

                                <strong>Singer :</strong>

                                <span id="viewSinger"></span>

                            </p>

                            <p>

                                <strong>Genre :</strong>

                                <span id="viewGenre"></span>

                            </p>

                            <p>

                                <strong>Duration :</strong>

                                <span id="viewDuration"></span>

                            </p>

                            <p>

                                <strong>Plays :</strong>

                                <span id="viewPlays"></span>

                            </p>

                        </div>

                    </div>

                    <audio id="viewAudio" controls style="width:100%;margin-top:25px;">

                    </audio>

                </div>

            </div>

            <!-- =====================================================
                    EDIT SONG MODAL
====================================================== -->

            <div class="modal" id="editSongModal">

                <div class="modal-content song-modal">

                    <div class="modal-header">

                        <h2>Edit Song</h2>

                        <span onclick="closeEditSong()">

                            <i class="fa-solid fa-xmark"></i>

                        </span>

                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" id="editId" name="editId">

                        <div class="form-group">

                            <label>Song Title</label>

                            <input type="text" id="editTitle" name="editTitle" required>

                        </div>

                        <div class="row">

                            <div class="form-group">

                                <label>Singer</label>

                                <input type="text" id="editSinger" name="editSinger" required>

                            </div>

                            <div class="form-group">

                                <label>Genre</label>

                                <select id="editGenre" name="editGenre">

                                    <option value="Pop">Pop</option>
                                    <option value="Rock">Rock</option>
                                    <option value="Ballad">Ballad</option>
                                    <option value="Rap">Rap</option>
                                    <option value="EDM">EDM</option>

                                </select>

                            </div>

                        </div>

                        <div class="upload-grid">

                            <!-- Cover -->

                            <div class="upload-box">

                                <label>Change Cover</label>

                                <div class="upload-area">

                                    <img id="editCoverPreview" src="../uploads/covers/default-song.png"
                                        class="preview-image">

                                    <label class="upload-btn">

                                        <i class="fa-solid fa-image"></i>

                                        Choose Image

                                        <input type="file" id="editCover" name="editCover" accept=".jpg,.jpeg,.png"
                                            hidden>

                                    </label>

                                </div>

                            </div>

                            <!-- Audio -->

                            <div class="upload-box">

                                <label>Change Audio</label>

                                <div class="upload-area">

                                    <i class="fa-solid fa-music upload-icon"></i>

                                    <label class="upload-btn">

                                        <i class="fa-solid fa-upload"></i>

                                        Choose MP3

                                        <input type="file" id="editAudio" name="editAudio" accept=".mp3" hidden>

                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeEditSong()">

                                Cancel

                            </button>

                            <button type="submit" class="add-btn" name="updateSong">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>
            <script>
                /*==============================
    ADD SONG MODAL
===============================*/

                function openAddSong() {

                    document.getElementById("addSongModal").style.display = "flex";

                }

                function closeAddSong() {

                    document.getElementById("addSongModal").style.display = "none";

                }


                /*==============================
                    VIEW SONG MODAL
                ===============================*/

                function viewSong(

                    title,

                    singer,

                    genre,

                    duration,

                    plays,

                    cover,

                    audio

                ) {

                    document.getElementById("viewSongModal").style.display = "flex";

                    document.getElementById("viewTitle").innerHTML = title;

                    document.getElementById("viewSinger").innerHTML = singer;

                    document.getElementById("viewGenre").innerHTML = genre;

                    document.getElementById("viewDuration").innerHTML = duration;

                    document.getElementById("viewPlays").innerHTML = plays;

                    document.getElementById("viewCover").src =
                        "../uploads/covers/" + cover;

                    document.getElementById("viewAudio").src =
                        "../uploads/songs/" + audio;

                }


                /*==============================
                    CLOSE VIEW
                ===============================*/

                function closeViewSong() {

                    document.getElementById("viewSongModal").style.display = "none";

                }


                /*==============================
                    EDIT SONG
                ===============================*/

                function editSong(

                    id,

                    title,

                    singer,

                    genre

                ) {

                    document.getElementById("editSongModal").style.display = "flex";

                    document.getElementById("editId").value = id;

                    document.getElementById("editTitle").value = title;

                    document.getElementById("editSinger").value = singer;

                    document.getElementById("editGenre").value = genre;

                }


                /*==============================
                    CLOSE EDIT
                ===============================*/

                function closeEditSong() {

                    document.getElementById("editSongModal").style.display = "none";

                }


                /*==============================
                    PREVIEW COVER
                ===============================*/

                const coverInput = document.getElementById("coverInput");

                if (coverInput) {

                    coverInput.onchange = function() {

                        if (this.files.length > 0) {

                            document.getElementById("coverPreview").src =
                                URL.createObjectURL(this.files[0]);

                            document.getElementById("coverName").innerHTML =
                                this.files[0].name;

                        }

                    };

                }


                /*==============================
                    PREVIEW AUDIO
                ===============================*/

                const audioInput = document.getElementById("audioInput");

                if (audioInput) {

                    audioInput.onchange = function() {

                        if (this.files.length > 0) {

                            document.getElementById("audioName").innerHTML =
                                this.files[0].name;

                        }

                    };

                }


                /*==============================
                    EDIT COVER PREVIEW
                ===============================*/

                const editCover = document.getElementById("editCover");

                if (editCover) {

                    editCover.onchange = function() {

                        if (this.files.length > 0) {

                            document.getElementById("editCoverPreview").src =
                                URL.createObjectURL(this.files[0]);

                        }

                    };

                }


                /*==============================
                    CLICK OUTSIDE MODAL
                ===============================*/

                window.onclick = function(e) {

                    if (e.target.classList.contains("modal")) {

                        e.target.style.display = "none";

                    }

                };
            </script>

        </main>

    </div>

</body>

</html>