<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
 <?php include_once("header.php"); ?>
<body>
<div class="contactUs">
    <div class="title">
        <h2>Get in Touch</h2>
    </div>
    <div class="whole">

    <div class="contact form ">
    <h3>Send a Message</h3>
    <br>
    <form>
        <div class="boxes">
            <div class="another">
                <div class="basic">
                    <span>First Name</span>
                    <input type="text" placeholder="john"required>
                </div>
                <div class="basic">
                    <span>Last Name</span>
                    <input type="text" placeholder="doe"required>
                </div>
            </div>
            <div class="another">
                <div class="basic">
                    <span>Email</span>
                    <input type="text" placeholder="johndoe@gmail.com"required>
                </div>
                <div class="basic">
                    <span>Mobile</span>
                    <input type="text" placeholder="+99 123 345 6789"required>
                </div>
            </div>
            <div class="row1">
                <div class="basic">
                    <span>Message</span>
                    <textarea placeholder="Write your message here..."required></textarea>
                </div>
            </div>
            <div class="row1">
            <div class="basic">
                <br><br>
            <input type="submit" value="Send">
            </div>
        </div>
    </form>
</div>
<div class="side">
    <h2>Contact Us:</h2><br>
        <p>For any inquiries, please contact us using the information below:</p>
        <p>Email: info@spacetech.com</p>
        <p>Phone: +1 (123) 456-7890</p>
        <p>Address:  Aston St, Birmingham B4 7ET</p>
        <div class="icon">
            <img src="images/facebook.png">
                <img src="images/twitter.png">
                <img src="images/tiktok.png">
                <img src="images/linkedin.png">
                <img src="images/instagram.png">
            <!-- <a href="https://www.facebook.com"><img src="images/facebook.png"></a>
            <a href="https://twitter.com"><img src="images/twitter.png"></a>
            <a href="https://www.tiktok.com"><img src="images/tiktok.png"></a>
            <a href="https://www.linkedin.com"><img src="images/linkedin.png"></a>
            <a href="https://www.instagram.com"><img src="images/instagram.png"></a> -->
</div>
</div>
<div class="news">
    <h2>Subscribe to Our Newsletter:</h2>
    <br>
    <form>
        <input type="email" placeholder="Your Email Address" required>
        <button type="submit">Subscribe</button>
    </form>
</div>
<div class="other">
    <h2>Location:</h2><br>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2429.581441569735!2d-1.8908227233208668!3d52.4867137720525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc9ae4f2e4b3%3A0x9a670ba18e08a084!2sAston%20University!5e0!3m2!1sen!2suk!4v1700408746118!5m2!1sen!2suk" width="600" height="450" style="border:0;" allowfullscreen=""></iframe>
</div>
</div>
</div>
</body>
</html>
