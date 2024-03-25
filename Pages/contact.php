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

    <div class="container">
        <div class="contactUs">
            <div class="row">
                <div class="col-md-6">
                    <div class="contact form">
                        <h2>Send a Message</h2>
                        <form>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="First Name" style="padding: 10px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Last Name" style="padding: 10px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" placeholder="Email" style="padding: 10px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Mobile" style="padding: 10px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Message" rows="5"
                                                style="padding: 10px;" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="side">
                        <h2>Contact Us:</h2>
                        <p>For any inquiries, please contact us using the information below:</p>
                        <p>Email: info@spacetech.com</p>
                        <p>Phone: +1 (123) 456-7890</p>
                        <p>Address: Aston St, Birmingham B4 7ET</p>
                        <div class="icon">
                            <img src="../images/facebook.png">
                            <img src="../images/twitter.png">
                            <img src="../images/tiktok.png">
                            <img src="../images/linkedin.png">
                            <img src="../images/instagram.png">
                        </div>
                    </div>
                    <div class="news">
                        <h2>Subscribe to Our Newsletter:</h2>
                        <form>
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your Email Address" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Subscribe</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="other">
                        <h2>Location:</h2>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2429.581441569735!2d-1.8908227233208668!3d52.4867137720525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc9ae4f2e4b3%3A0x9a670ba18e08a084!2sAston%20University!5e0!3m2!1sen!2suk!4v1700408746118!5m2!1sen!2suk"
                            width="100%" height="300" style="border:0;" allowfullscreen=""></iframe>
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