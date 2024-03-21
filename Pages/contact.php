<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/master-style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>


<body>
<?php include_once("../PHP/navbar.php"); ?>
    <div class="contactUs container">
        <div class="title">
            <h1 class="mb-4">Get in Touch</h1>
        </div>
        <div class="container mt-5">

            <div class="contact form ">
                <h3>Send a Message</h3>
                <br>
                <form>  
                    <div class="content row">
                        <div class="col sidebar">
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input class="form-control" type="text" placeholder="john" id="first-name" required>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input class="form-control" type="text" placeholder="doe" id="last-name" required>
                            </div>
                        </div>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input class="form-control" type="text" placeholder="johndoe@gmail.com" id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input class="form-control" type="text" placeholder="+99 123 345 6789" id="mobile" required>
                            </div>
                        </div>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <label for="msg">Message</label>
                                <textarea class="form-control" placeholder="Write your message here..." id="msg" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row g-6">
                            <div class="form-group">
                                <button class="btn btn-primary mt-2" type="submit">Send message</button>
                            </div>
                        </div>
</div>
                </form>
            <div class="row row-cols-1 row-cols-md-1 w-50 g-4">
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
                    <form>
                        <input class="form-control" type="email" placeholder="Your Email Address" required>
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
    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>