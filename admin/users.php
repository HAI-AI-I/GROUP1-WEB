<?php
session_start();
include "../config/config.php";

/* ===============================
   ADD USER
================================ */

if (isset($_POST["add"])) {

    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    $avatar = "default.png";

    if (
        isset($_FILES["avatar"]) &&
        $_FILES["avatar"]["error"] == 0
    ) {

        $allow = ["jpg", "jpeg", "png", "webp"];

        $ext = strtolower(
            pathinfo(
                $_FILES["avatar"]["name"],
                PATHINFO_EXTENSION
            )
        );

        if (in_array($ext, $allow)) {

            if (!file_exists("../uploads")) {
                mkdir("../uploads");
            }

            $avatar = time() . "_" . $_FILES["avatar"]["name"];

            move_uploaded_file(
                $_FILES["avatar"]["tmp_name"],
                "../uploads/" . $avatar
            );
        }
    }

    mysqli_query(
        $conn,
        "INSERT INTO users
        (
            fullname,
            email,
            role,
            avatar,
            status
        )
        VALUES
        (
            '$fullname',
            '$email',
            '$role',
            '$avatar',
            'active'
        )"
    );

    header("Location: users.php");
    exit();
}

/* ===============================
   UPDATE USER
================================ */

if (isset($_POST["update"])) {

    $id = (int)$_POST["editId"];

    $fullname = mysqli_real_escape_string(
        $conn,
        $_POST["editName"]
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST["editEmail"]
    );

    $role = mysqli_real_escape_string(
        $conn,
        $_POST["editRole"]
    );

    mysqli_query(

        $conn,

        "UPDATE users

        SET
        fullname='$fullname',

        email='$email',

        role='$role'

        WHERE id=$id"

    );

    header("Location: users.php");

    exit();
}
/* ===============================
   DELETE USER
================================ */

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    mysqli_query(
        $conn,
        "DELETE FROM users WHERE id=$id"
    );

    header("Location: users.php");
    exit();
}

/* ===============================
   LOCK / UNLOCK USER
================================ */

if (isset($_GET["lock"])) {

    $id = (int) $_GET["lock"];

    $check = mysqli_query(
        $conn,
        "SELECT status
         FROM users
         WHERE id=$id"
    );

    $row = mysqli_fetch_assoc($check);

    if ($row["status"] == "active") {

        mysqli_query(
            $conn,
            "UPDATE users
             SET status='locked'
             WHERE id=$id"
        );
    } else {

        mysqli_query(
            $conn,
            "UPDATE users
             SET status='active'
             WHERE id=$id"
        );
    }

    header("Location: users.php");
    exit();
}

/* ===============================
   SEARCH
================================ */

$keyword = "";

if (isset($_GET["search"])) {

    $keyword = trim($_GET["search"]);
}

$sql = "
SELECT *
FROM users
WHERE fullname LIKE '%$keyword%'
OR email LIKE '%$keyword%'
ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

$totalUser = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container">

        <?php include "sidebar.php"; ?>

        <main>

            <?php include "header.php"; ?>

            <div class="page-header">

                <div>

                    <h2>User Management</h2>

                    <p><?= $totalUser ?> Total Users</p>

                </div>

                <div class="top-action">

                    <form method="GET">

                        <div class="search-user">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>"
                                placeholder="Search users...">

                        </div>

                    </form>

                    <button class="add-btn" onclick="openAddModal()">

                        <i class="fa-solid fa-user-plus"></i>

                        Add User

                    </button>

                </div>

            </div>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>User</th>

                            <th>Email</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <!-- ID -->
                                <td>
                                    <?= $row["id"]; ?>
                                </td>

                                <!-- USER -->
                                <td>

                                    <div class="user-info">

                                        <?php
                                        $avatar = "../uploads/default.png";

                                        if (!empty($row["avatar"]) && file_exists("../uploads/" . $row["avatar"])) {
                                            $avatar = "../uploads/" . $row["avatar"];
                                        }
                                        ?>

                                        <img src="<?= $avatar; ?>" alt="Avatar">

                                        <div>

                                            <h4><?= htmlspecialchars($row["fullname"]); ?></h4>

                                            <small>ID:
                                                <?= $row["id"]; ?>
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <!-- EMAIL -->
                                <td>

                                    <?= htmlspecialchars($row["email"]); ?>

                                </td>

                                <!-- ROLE -->
                                <td>

                                    <?php

                                    if ($row["role"] == "admin") {

                                        echo "<span class='role-admin'>Admin</span>";
                                    } elseif ($row["role"] == "premium") {

                                        echo "<span class='role-premium'>Premium</span>";
                                    } else {

                                        echo "<span class='role-user'>User</span>";
                                    }

                                    ?>

                                </td>

                                <!-- STATUS -->
                                <td>

                                    <?php

                                    if ($row["status"] == "active") {

                                        echo "<span class='active-status'>Active</span>";
                                    } else {

                                        echo "<span class='locked-status'>Locked</span>";
                                    }

                                    ?>

                                </td>

                                <!-- CREATED -->
                                <td>

                                    <?= date("d/m/Y", strtotime($row["created_at"])); ?>

                                </td>

                                <!-- ACTION -->
                                <td>

                                    <div class="action-btn">

                                        <!-- VIEW -->

                                        <button class="view-btn" title="View"
                                            data-name="<?= htmlspecialchars($row["fullname"]); ?>"
                                            data-email="<?= htmlspecialchars($row["email"]); ?>"
                                            data-role="<?= $row["role"]; ?>" data-status="<?= $row["status"]; ?>"
                                            data-avatar="<?= $avatar; ?>" onclick="viewUser(this)">

                                            <i class="fa-solid fa-eye"></i>

                                        </button>

                                        <!-- EDIT -->

                                        <button class="edit-btn" title="Edit" data-id="<?= $row["id"]; ?>"
                                            data-name="<?= htmlspecialchars($row["fullname"]); ?>"
                                            data-email="<?= htmlspecialchars($row["email"]); ?>"
                                            data-role="<?= $row["role"]; ?>" onclick="editUser(this)">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>

                                        <!-- LOCK -->

                                        <a href="?lock=<?= $row["id"]; ?>" class="lock-btn" title="Lock / Unlock"
                                            onclick="return confirm('Change user status?')">

                                            <i class="fa-solid fa-lock"></i>

                                        </a>

                                        <!-- DELETE -->

                                        <a href="?delete=<?= $row["id"]; ?>" class="delete-btn" title="Delete"
                                            onclick="return confirm('Delete this user?')">

                                            <i class="fa-solid fa-trash"></i>

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

            <!-- ======================================
            ADD USER MODAL
====================================== -->

            <div class="modal" id="addModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Add New User</h2>

                        <span onclick="closeAddModal()">

                            <i class="fa-solid fa-xmark"></i>

                        </span>

                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="form-group">

                            <label>Full Name</label>

                            <input type="text" name="fullname" required>

                        </div>

                        <div class="form-group">

                            <label>Email</label>

                            <input type="email" name="email" required>

                        </div>

                        <div class="form-group">

                            <label>Role</label>

                            <select name="role">

                                <option value="user">User</option>

                                <option value="premium">Premium</option>

                                <option value="admin">Admin</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Avatar</label>

                            <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp">

                        </div>

                        <div class="preview-box">

                            <img src="../uploads/default.png" id="previewImage">

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeAddModal()">

                                Cancel

                            </button>

                            <button type="submit" name="add" class="add-btn">

                                <i class="fa-solid fa-user-plus"></i>

                                Add User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- ======================================
            VIEW USER
====================================== -->

            <div class="modal" id="viewModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>User Information</h2>

                        <span onclick="closeViewModal()">

                            <i class="fa-solid fa-xmark"></i>

                        </span>

                    </div>

                    <div class="preview-box">

                        <img src="../uploads/default.png" id="viewAvatar">

                    </div>

                    <p><strong>Full Name:</strong> <span id="viewName"></span></p>

                    <br>

                    <p><strong>Email:</strong> <span id="viewEmail"></span></p>

                    <br>

                    <p><strong>Role:</strong> <span id="viewRole"></span></p>

                    <br>

                    <p><strong>Status:</strong> <span id="viewStatus"></span></p>

                </div>

            </div>

            <!-- ======================================
            EDIT USER
====================================== -->

            <div class="modal" id="editModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2>Edit User</h2>

                        <span onclick="closeEditModal()">

                            <i class="fa-solid fa-xmark"></i>

                        </span>

                    </div>

                    <form method="POST">

                        <input type="hidden" id="editId" name="editId">

                        <div class="form-group">

                            <label>Full Name</label>

                            <input type="text" id="editName" name="editName">

                        </div>

                        <div class="form-group">

                            <label>Email</label>

                            <input type="email" id="editEmail" name="editEmail">

                        </div>

                        <div class="form-group">

                            <label>Role</label>

                            <select id="editRole" name="editRole">

                                <option value="user">User</option>

                                <option value="premium">Premium</option>

                                <option value="admin">Admin</option>

                            </select>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="cancel-btn" onclick="closeEditModal()">

                                Cancel

                            </button>

                            <button type="submit" class="add-btn" name="update">

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- ======================================
            JAVASCRIPT
====================================== -->

            <script>
                function openAddModal() {

                    document.getElementById("addModal").style.display = "flex";

                }

                function closeAddModal() {

                    document.getElementById("addModal").style.display = "none";

                }

                function closeViewModal() {

                    document.getElementById("viewModal").style.display = "none";

                }

                function closeEditModal() {

                    document.getElementById("editModal").style.display = "none";

                }

                /* Preview Avatar */

                document.getElementById("avatar").onchange = function() {

                    if (this.files.length > 0) {

                        document.getElementById("previewImage").src =

                            URL.createObjectURL(this.files[0]);

                    }

                }

                /* View User */

                function viewUser(btn) {

                    document.getElementById("viewModal").style.display = "flex";

                    document.getElementById("viewName").innerHTML =

                        btn.dataset.name;

                    document.getElementById("viewEmail").innerHTML =

                        btn.dataset.email;

                    document.getElementById("viewRole").innerHTML =

                        btn.dataset.role;

                    document.getElementById("viewStatus").innerHTML =

                        btn.dataset.status;

                    document.getElementById("viewAvatar").src =

                        btn.dataset.avatar;

                }

                /* Edit User */

                function editUser(btn) {

                    document.getElementById("editModal").style.display = "flex";

                    document.getElementById("editId").value =

                        btn.dataset.id;

                    document.getElementById("editName").value =

                        btn.dataset.name;

                    document.getElementById("editEmail").value =

                        btn.dataset.email;

                    document.getElementById("editRole").value =

                        btn.dataset.role;

                }

                /* Click ngoài Modal */

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