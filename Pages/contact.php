<?php
error_reporting(E_ERROR | E_PARSE);

include_once("../PHP/database-handler.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST["first-name"]) &&
        isset($_POST["last-name"]) &&
        isset($_POST["mobile"]) &&
        isset($_POST["email"]) &&
        isset($_POST["msg"])
    ) {

       sendMessage($_POST["first-name"], $_POST["last-name"], $_POST["email"], $_POST["mobile"], $_POST["msg"]); 
        $message = "Your message has been sent successfully.";
    } else {

        if(isset($_POST["newsletter"])) {
            subscribeToNewsletter($_POST["newsletter"]);
            $message = "Thank you for subscribing!";
        } else {
            // input validation not satisified
            header("Location: ../Pages/contact.php");
            exit;
        }
    }

}

function subscribeToNewsletter($email) {
    $db_handler = new Database();

    $admins_list = $db_handler->getAdminsList();

    $message = "Hi there,\n
    A visitor has subscribed to the newsletter:\n
    \n
    Email: " . htmlspecialchars($email, ENT_QUOTES) . "\n
    \n
    Kind regards,\n
    SpaceTech.";

    foreach($admins_list as $admin) {
      mail($admin["user_email"], "SpaceTech - New Newsletter Subscription", $message);
    }

}

function sendMessage($forename, $surname, $email, $mobile, $msg) {
    $db_handler = new Database();

    $admins_list = $db_handler->getAdminsList();

    foreach($admins_list as $admin) {
      $message = "Hi there,\n
      A visitor has submitted a new contact form:\n
      \n
      Name: " . htmlspecialchars($forename, ENT_QUOTES) . " " . htmlspecialchars($surname, ENT_QUOTES) . "\n
      Email: " . htmlspecialchars($email, ENT_QUOTES) . "\n
      Mobile: " . htmlspecialchars($mobile, ENT_QUOTES) . "\n
      Message: " . htmlspecialchars($msg, ENT_QUOTES) . "\n
      \n
      Kind regards,\n
      SpaceTech.";

      mail($admin["user_email"], "SpaceTech - New Contact Form Submission", $message);
    }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>


<body>
<?php include_once("../PHP/navbar.php"); ?>
    <div class="contactUs container">
        <div class="title">
            <h1 class="mb-4">Get in Touch</h1>
        </div>
        <div class="container mt-5">
            <? if(isset($message)): ?>
                <div class="content row">
                    <h4><?= htmlspecialchars($message, ENT_QUOTES); ?></h4>
                </div>
            <? endif; ?>
            <div class="contact form ">
                <h3>Send a Message</h3>
                <br>
                <form method="POST">  
                    <div class="content row">
                        <div class="col sidebar">
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input class="form-control" type="text" name="first-name" placeholder="john" id="first-name" required>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input class="form-control" type="text" name="last-name" placeholder="doe" id="last-name" required>
                            </div>
                        </div>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input class="form-control" type="email" name="email" placeholder="johndoe@gmail.com" id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input class="form-control" type="text" name="mobile" placeholder="+99 123 345 6789" id="mobile" required>
                            </div>
                        </div>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="msg">Message</label>
                                <textarea class="form-control" name="msg" placeholder="Write your message here..." id="msg" required></textarea>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <button class="btn btn-primary mt-2" type="submit">Send message</button>
                            </div>
                        </div>
</div>
                </form>
            <div class="row row-cols-1 row-cols-md-1 w-100 g-4">
                <h3>For any inquiries, please contact us using the information below:</h3>
                <p>Email: info@spacetech.com<br>
                Phone: +1 (123) 456-7890<br>
                Address: Aston St, Birmingham B4 7ET</p>
                <div class="icon">
                    <img style="max-width: 50px;" src="../images/facebook.png">
                    <img style="max-width: 50px;" src="../images/twitter.png">
                    <img style="max-width: 50px;" src="../images/tiktok.png">
                    <img style="max-width: 50px;" src="../images/linkedin.png">
                    <img style="max-width: 50px;" src="../images/instagram.png">
                    <!-- <a href="https://www.facebook.com"><img src="images/facebook.png"></a>
            <a href="https://twitter.com"><img src="images/twitter.png"></a>
            <a href="https://www.tiktok.com"><img src="images/tiktok.png"></a>
            <a href="https://www.linkedin.com"><img src="images/linkedin.png"></a>
            <a href="https://www.instagram.com"><img src="images/instagram.png"></a> -->
                </div>
            </div>
            <div class="news row mt-5">
                <h2>Subscribe to Our Newsletter!</h2>
                <div class="form-group mb-4">
                    <form method="POST">
                        <input class="form-control" name="newsletter" type="email" placeholder="Your Email Address" required>
                        <button class="btn btn-primary ml-2" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="other row mt-5">
                <h2>Find us on Google Maps!</h2><br>
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2429.581441569735!2d-1.8908227233208668!3d52.4867137720525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc9ae4f2e4b3%3A0x9a670ba18e08a084!2sAston%20University!5e0!3m2!1sen!2suk!4v1700408746118!5m2!1sen!2suk"
                    width="100%" height="450" style="border:0;" allowfullscreen=""></iframe>
            </div>
</div>
</div>
        </div>
    </div>
    </div>
    </div>
    <?php include_once("../PHP/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

