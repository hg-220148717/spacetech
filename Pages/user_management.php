<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserAdmin($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
}

$user_details = $db_handler->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include '../PHP/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">User Management</h2>
        <div id="alertPlaceholder"></div>
        <?php
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'emptyName':
                    $error_message = 'Please enter a name for the user.';
                    break;
                case 'emailExists':
                    $error_message = 'A user account already exists with that email address.';
                    break;
                case 'creationfailed':
                    $error_message = 'Failed to create the user.';
                    break;
                default:
                    $error_message = 'An unknown error occurred.';
            }
            echo "<script>
                var alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';
                var alertText = document.createTextNode('$error_message');
                alertDiv.appendChild(alertText);
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                alertPlaceholder.appendChild(alertDiv);
            </script>";
        } else if(isset($_GET["success"])) {
            echo "<script>
                var alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success';
                var alertText = document.createTextNode('Changes made successfully.');
                alertDiv.appendChild(alertText);
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                alertPlaceholder.appendChild(alertDiv);
            </script>";
        }
        ?>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">Create New
            User</button>
        <!-- Table to display products -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Is Staff?</th>
                        <th scope="col">Is Admin?</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_details as $user): ?>
                        <tr id="user-row-<?= $user['user_id']; ?>">

                            <th scope="row">
                                <?= htmlspecialchars($user["user_id"], ENT_QUOTES); ?>
                            </th>
                            <td>
                                <?= htmlspecialchars($user["user_name"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user["user_email"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if ($user["user_isstaff"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user["user_isadmin"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" data-user-id="<?= $user["user_id"] ?>"
                                    data-user-email="<?= $user["user_email"] ?>"
                                    data-user-name="<?= htmlspecialchars($user["user_name"], ENT_QUOTES); ?>">
                                    Edit
                                </button>
                                <?php if(!$user["user_isstaff"]): ?>
                                    <button class="btn btn-success btn-sm promote-to-staff" data-bs-toggle="modal"
                                    data-bs-target="#promoteToStaffModal" data-user-id="<?= $user["user_id"] ?>">
                                    Promote to Staff
                                    </button>
                                <?php elseif(!$user["user_isadmin"]): ?>
                                    <button class="btn btn-success btn-sm promote-to-admin" data-bs-toggle="modal"
                                    data-bs-target="#promoteToAdminModal" data-user-id="<?= $user["user_id"] ?>">
                                    Promote to Admin
                                    </button>
                                    <button class="btn btn-warning btn-sm demote-to-user" data-bs-toggle="modal"
                                    data-bs-target="#demoteToUserModal" data-user-id="<?= $user["user_id"] ?>">
                                    Demote to User
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-warning btn-sm demote-to-staff" data-bs-toggle="modal"
                                    data-bs-target="#demoteToStaffModal" data-user-id="<?= $user["user_id"] ?>">
                                    Demote to Staff
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-danger btn-sm delete-user" data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal"
                                    data-user-id="<?= $user["user_id"] ?>">
                                    Delete
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" action="../PHP/create_user.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Form fields -->
                        <div class="mb-3">
                            <label for="userName" class="form-label">User's Name</label>
                            <input type="text" class="form-control" id="userName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">User's Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">User's Password</label>
                            <input type="password" class="form-control" id="userPassword" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create New User</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="../PHP/edit_user.php" class="row" id="editUserForm" enctype="multipart/form-data">
                    <!-- enctype added for file upload -->
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="user_id">
                        <!-- User's Name -->
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">User's Name</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">User's Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="editUserPwd" class="form-label">User's Password</label>
                            <input type="password" class="form-control" id="editUserPwd" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Promote to Staff Modal -->
    <div class="modal fade" id="promoteToStaffModal" tabindex="-1" aria-labelledby="promoteToStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoteToStaffModalLabel">Promote to Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to promote this user to Staff?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPromotionToStaff">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Demote to User Modal -->
    <div class="modal fade" id="demoteToUserModal" tabindex="-1" aria-labelledby="demoteToUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoteToUserModalLabel">Demote to User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to demote this user to User?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDemotionToUser">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Demote to Staff Modal -->
    <div class="modal fade" id="demoteToStaffModal" tabindex="-1" aria-labelledby="demoteToStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoteToStaffModalLabel">Demote to Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to demote this user to Staff?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDemotionToStaff">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Promote to Admin Modal -->
    <div class="modal fade" id="promoteToAdminModal" tabindex="-1" aria-labelledby="promoteToAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoteToAdminModalLabel">Promote to Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to promote this user to Admin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPromotionToAdmin">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal JS -->
    <script src="../Scripts/users.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>