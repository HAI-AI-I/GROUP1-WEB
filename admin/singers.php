<?php
session_start();

include "../config/config.php";

/*=================================
        ADD SINGER
=================================*/

if (isset($_POST["addSinger"])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST["name"]
    );

    $country = mysqli_real_escape_string(
        $conn,
        $_POST["country"]
    );

    $image = "default-avatar.png";

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] == 0
    ) {

        $extension = strtolower(
            pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            )
        );

        $allow = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        if (in_array($extension, $allow)) {

            if (!is_dir("../uploads/singers")) {
                mkdir("../uploads/singers", 0777, true);
            }

            $image = time() . "_" . basename($_FILES["image"]["name"]);

            move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                "../uploads/singers/" . $image
            );
        }
    }

    mysqli_query(

        $conn,

        "INSERT INTO singers(

            name,
            country,
            image

        )

        VALUES(

            '$name',
            '$country',
            '$image'

        )"

    );

    header("Location: singers.php");
    exit();
}

/*=================================
        UPDATE SINGER
=================================*/

if (isset($_POST["updateSinger"])) {

    $id = (int)$_POST["editId"];

    $name = mysqli_real_escape_string(
        $conn,
        $_POST["editName"]
    );

    $country = mysqli_real_escape_string(
        $conn,
        $_POST["editCountry"]
    );

    mysqli_query(

        $conn,

        "UPDATE singers

        SET

        name='$name',

        country='$country'

        WHERE id=$id"

    );

    header("Location: singers.php");
    exit();
}

/*=================================
        DELETE SINGER
=================================*/

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    $old = mysqli_fetch_assoc(

        mysqli_query(

            $conn,

            "SELECT image
             FROM singers
             WHERE id=$id"

        )

    );

    if (

        !empty($old["image"]) &&

        $old["image"] != "default-avatar.png"

    ) {

        $path = "../uploads/singers/" . $old["image"];

        if (file_exists($path)) {

            unlink($path);
        }
    }

    mysqli_query(

        $conn,

        "DELETE FROM singers
         WHERE id=$id"

    );

    header("Location: singers.php");
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

    FROM singers

    WHERE

    name LIKE '%$keyword%'

    OR country LIKE '%$keyword%'

    ORDER BY id DESC"

);

$totalSinger = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Singer Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/singer.css">

</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <!-- PAGE HEADER -->

            <div class="page-header">

                <div>

                    <h2>Singer Management</h2>

                    <p>

                        <?= $totalSinger ?>

                        singers registered

                    </p>

                </div>

                <div class="top-action">

                    <form method="GET" class="search-user">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" placeholder="Search singers..."
                            value="<?= htmlspecialchars($keyword); ?>">
                    </form>
                    <button class="add-btn" onclick="openAddSinger()">

                        <i class="fa fa-plus"></i>

                        Add Singer

                    </button>

                </div>

            </div>

            <!-- TABLE -->

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>SINGER</th>
                            <th>COUNTRY</th>
                            <th>CREATED</th>
                            <th>ACTIONS</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>

                                    <?= sprintf("%03d", $row["id"]); ?>

                                </td>

                                <td>

                                    <div class="user-info">

                                        <img src="../uploads/singers/<?= !empty($row["image"]) ? $row["image"] : "default-avatar.png"; ?>"
                                            class="song-cover" alt="Singer">

                                        <div>

                                            <h4>

                                                <?= htmlspecialchars($row["name"]); ?>

                                            </h4>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?= htmlspecialchars($row["country"]); ?>

                                </td>

                                <td>

                                    <?= $row["created_at"] ?? "-"; ?>

                                </td>

                                <td>

                                    <div class="action-btn">

                                        <!-- VIEW -->

                                        <button class="view-btn" onclick="viewSinger(

                                    '<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>',

                                    '<?= htmlspecialchars($row['country'], ENT_QUOTES); ?>',

                                    '<?= htmlspecialchars($row['image'], ENT_QUOTES); ?>'

                                    )">

                                            <i class="fa fa-eye"></i>

                                        </button>

                                        <!-- EDIT -->
                                        <button class="edit-btn" onclick="editSinger(

                                    '<?= $row['id']; ?>',

                                    '<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>',

                                    '<?= htmlspecialchars($row['country'], ENT_QUOTES); ?>'

                                    )">

                                            <i class="fa fa-pen"></i>

                                        </button>

                                        <!-- DELETE -->

                                        <a class="delete-btn" href="?delete=<?= $row["id"]; ?>"
                                            onclick="return confirm('Delete this singer?')">

                                            <i class="fa fa-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->

            <div class="pagination">

                <a href="#" class="active">1</a>

                <a href="#">2</a>

                <a href="#">3</a>

            </div>

            <!-- =====================================================
                    ADD SINGER MODAL
===================================================== -->

            <div class="modal" id="addSingerModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Add New Singer</h2>

                        <span onclick="closeAddSinger()">&times;</span>

                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="form-group">

                            <label>Singer Name</label>

                            <input type="text" name="name" placeholder="Enter singer name" required>

                        </div>

                        <div class="form-group">

                            <label>Country</label>

                            <input type="text" name="country" placeholder="Enter country" required>

                        </div>

                        <div class="form-group">

                            <label>Photo</label>

                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewSinger(event)" required>

                        </div>

                        <div style="text-align:center;margin:20px 0;">

                            <img id="previewSinger" src="../uploads/singers/default-avatar.png" class="preview-image"
                                alt="Preview">

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeAddSinger()">

                                Cancel

                            </button>

                            <button type="submit" class="add-btn" name="addSinger">

                                Add Singer

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- =====================================================
                    VIEW SINGER
===================================================== -->

            <div class="modal" id="viewSingerModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Singer Information</h2>

                        <span onclick="closeViewSinger()">&times;</span>

                    </div>

                    <div style="text-align:center;">

                        <img id="viewImage" class="preview-image" src="" alt="Singer">

                    </div>

                    <br>

                    <p>

                        <strong>Name :</strong>

                        <span id="viewName"></span>

                    </p>

                    <br>

                    <p>

                        <strong>Country :</strong>

                        <span id="viewCountry"></span>

                    </p>

                </div>

            </div>

            <!-- =====================================================
                    EDIT SINGER
===================================================== -->

            <div class="modal" id="editSingerModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Edit Singer</h2>

                        <span onclick="closeEditSinger()">&times;</span>

                    </div>

                    <form method="POST">

                        <input type="hidden" name="editId" id="editId">

                        <div class="form-group">

                            <label>Singer Name</label>

                            <input type="text" name="editName" id="editName" required>

                        </div>

                        <div class="form-group">

                            <label>Country</label>

                            <input type="text" name="editCountry" id="editCountry" required>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeEditSinger()">

                                Cancel

                            </button>

                            <button type="submit" name="updateSinger" class="add-btn">

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <script>
                /*==================================
        ADD SINGER
==================================*/

                function openAddSinger() {

                    document.getElementById("addSingerModal").style.display = "flex";

                }

                function closeAddSinger() {

                    document.getElementById("addSingerModal").style.display = "none";

                }

                /*==================================
                        VIEW SINGER
                ==================================*/

                function viewSinger(

                    name,

                    country,

                    image

                ) {

                    document.getElementById("viewSingerModal").style.display = "flex";

                    document.getElementById("viewName").innerHTML = name;

                    document.getElementById("viewCountry").innerHTML = country;

                    if (image == "") {

                        image = "default-avatar.png";

                    }

                    document.getElementById("viewImage").src = "../uploads/singers/" + image;

                }

                function closeViewSinger() {

                    document.getElementById("viewSingerModal").style.display = "none";

                }

                /*==================================
                        EDIT SINGER
                ==================================*/

                function editSinger(

                    id,

                    name,

                    country

                ) {

                    document.getElementById("editSingerModal").style.display = "flex";

                    document.getElementById("editId").value = id;

                    document.getElementById("editName").value = name;

                    document.getElementById("editCountry").value = country;

                }

                function closeEditSinger() {

                    document.getElementById("editSingerModal").style.display = "none";

                }

                /*==================================
                        PREVIEW IMAGE
                ==================================*/

                function previewSinger(event) {

                    const file = event.target.files[0];

                    if (!file) {

                        return;

                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {

                        document.getElementById("previewSinger").src = e.target.result;

                    }

                    reader.readAsDataURL(file);

                }

                /*==================================
                        CLOSE MODAL
                ==================================*/

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