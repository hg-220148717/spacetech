<?php

session_start();

// check if user is logged in
if(!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    exit;
}

include_once("../PHP/database-handler.php");
$db_handler = new Database();

if($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["name"]) && isset($_POST["email"])) {
        $user_id = $_SESSION["user_id"];
        $new_name = trim($_POST["name"]);
        $new_email = trim($_POST["email"]);

        $db_handler->updateUserDetails($user_id, $new_name, $new_email);
        header("Location: ../Pages/my-profile.php?success=true");
    }

}

$user_details = $db_handler->getUserDetails($_SESSION["user_id"]);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn More</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>
<body>
    <?php include_once("../PHP/navbar.php"); ?>

    <!-- Checkout -->
    <div class="container mt-5">
        <div class="content col">
            <div class="col-md-12">
                <h2>My Profile</h2>
                <h4>View and edit your personal details.</h4>
                <?php if(isset($_GET["error"])): ?>
                    <div class="alert alert-danger">
                        <p>An error occurred updating your details. Please try again.</p>
                    </div>
                <?php elseif(isset($_GET["success"])): ?>
                    <div class="alert alert-success">
                        <p>Your details have been updated successfully.</p>
                    </div>
                <?php endif; ?>
            </div>
            <form method="POST" class="form align-items-end">
                <div class="col-md-5">
                        <div class="form-group row mt-2">
                            <label>Your Name</label>
                            <input class="form-control" type="text" value="<?= htmlspecialchars($user_details["user_name"], ENT_QUOTES); ?>" name="name">
                        </div>
                </div>
                <div class="col-md-5">
                        <div class="form-group row mt-2">
                            <label>Your Email Address</label>
                            <input class="form-control" type="email" value="<?= htmlspecialchars($user_details["user_email"], ENT_QUOTES); ?>" name="email">
                        </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary form-control" type="submit">Update Details</button>
                </div>
                </form>
            </div>

            <form id="changePwdForm" method="POST" action="../PHP/change_password.php" onsubmit="return changePassword();">
            <div class="content col-md-6 mt-5">
                <h3>Change Password</h3>
                <div class="form-group">
                    <label>Current Password</label>
                    <input id="currentPass" class="form-control" type="password" name="currentPass">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input id="newPass" class="form-control" type="password" name="newPass">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input id="confirmNewPass" class="form-control" type="password" name="confirmNewPass">
                </div>
                <div class="form-group mt-3">
                    <button class="btn btn-primary">Change Password</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function changePassword() {
            var currentPass = document.getElementById("currentPass");
            var newPass = document.getElementById("newPass");
            var confirmNewPass = document.getElementById("confirmNewPass");

            if(currentPass.value.trim().length > 0) {
                if(/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/.test(newPass.value)) {
                    if(confirmNewPass.value == newPass.value) {
                        return true;
                    }
                }
            }

            var errMsg = "Your password must contain at least one uppercase letter, one number, and one special character, and be at least 8 characters long.";

            document.getElementById("changePwdForm").innerHTML = document.getElementById("changePwdForm").innerHTML + ("<div id='errMsg' class='alert alert-danger'><p>"+errMsg+"</p></div>");
            
            setTimeout(function() {
                document.getElementById("errMsg").remove();
            }, 5000);

            return false;

        }
    </script>


    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>