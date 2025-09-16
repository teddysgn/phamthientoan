<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portfolio Pham Thien Toan</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="shortcut icon" href="/uploads/logo/logo.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <meta name="description" content="" />
   
   
    <meta name="keywords" content="" />
  
    <link rel="canonical" href="" />

    <?= \System\Libraries\Render::renderAsset('head', 'frontend') ?>
     <!-- JS FILE -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

   

</head>
<style>

</style>
<body data-spy="scroll" data-target="#pb-navbar" data-offset="200">
<div>
    <div class="indicator" style="z-index: 10001"></div>
</div>
<meta name="robots" content="noindex">
<nav class="navbar page navbar-expand-lg site-navbar navbar-light bg-light" id="pb-navbar">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09"
                aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" style="z-index: 10000;" id="navbarsExample09">
            <ul class="menu">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/')?>" style="color: #ff7d6a !important;">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/portfolio')?>">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/resume')?>">Resume</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-contact')">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- ======= Hero Section ======= -->
<section id="hero" class="page site-hero d-flex flex-column justify-content-center align-items-center" style="background-image: url('/uploads/background/background.jpg'); background-attachment: unset; background-size: cover; background-position: bottom;">
    <div class="hero-container col-md-10 text-center pt-5" data-aos="fade-in" style="padding-top: 0 !important; ">
        <h1 data-aos="fade-up" class="site-heading site-animate" style="color: #ff7d6a !important;">Hello! </h1>
        <h3 data-aos="fade-up" data-aos-delay="100"><strong style="color: #ff7d6a !important;font-weight: bold" class="d-block text-white text-uppercase letter-spacing">welcome
                to My Workspace</strong></h3>
        <h3 data-aos="fade-up" data-aos-delay="200" class="d-block" style="font-weight: 500;"> I am</h3>
        <h3 data-aos="fade-up" data-aos-delay="300" style="font-weight: 500;"> Pham Thien Toan</h3>
        <p data-aos="fade-up" data-aos-delay="400"><span class="typed" data-typed-items="Graphic Designer, Fashion Designer"></span></p>

    </div>
</section>
<!-- End Hero -->



<div data-aos="fade-right" class="page iframe text-center">
<iframe src="<?= base_url('/slide') ?>"></iframe>

    
</div>
<div data-aos="fade-up" data-aos-delay="400" class="text-center">
    <a href="<?= base_url('/portfolio'); ?>" class="ms-slide__link btn view">View More My Featured Portfolio</a>
</div>

<section class="site-section" id="section-contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5">
                <div data-aos="fade-up" class="section-heading text-center">
                    <h2>Get <strong>In Touch</strong></h2>
                </div>
            </div>
            <div id="form" class="col-md-7 mb-5 mb-md-0">
                <div class="site-form" id="contact-form" name="contact-form" >
                    <h3 data-aos="fade-up" class="mb-5">Your Infomation</h3>
                    <div class="form-group">
                        <input data-aos="fade-right" data-aos-delay="100" required type="text" class="form-control px-3 py-4" placeholder="Your Name" name="name"
                               id="name" required>
                    </div>
                    <div class="form-group">
                        <input data-aos="fade-right" data-aos-delay="200" required type="email" class="form-control px-3 py-4" placeholder="Your Email" name="email"
                               id="email">

                    </div>
                    <div class="form-group">
                        <input data-aos="fade-right" data-aos-delay="300" required title="Nhập số điện thoại từ 10 đến 11 số" type="phone"
                               class="form-control px-3 py-4" placeholder="Your Phone" name="phone" id="phone">
                    </div>
                    <div class="form-group mb-5">
							<textarea data-aos="fade-right" data-aos-delay="400" class="form-control px-3 py-4" cols="30" rows="10" placeholder="Write a Message"
                                      name="message" id="message"></textarea>
                    </div>
                    <div class="d-flex">
                        <div class="form-group">
                            <input data-aos="fade-right" data-aos-delay="500" onclick="sendMail(); customAlert.alert('Your message sent successfully!')" name="send" type="submit"
                                   class="btn btn-primary  px-4 py-3" value="Send Message" style="border-radius: 5px; margin-right: 20px;">
                        </div>
                        <div class="form-group">
                            <input data-aos="fade-right" data-aos-delay="500" onclick="reset()" name="send" type="submit"
                                   class="btn btn-primary  px-4 py-3" value="Reset"  style="border-radius: 5px">

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-5 pl-md-5">
                <h3 data-aos="fade-up" class="mb-5">My Contact Details</h3>
                <ul class="site-contact-details">
                    <li data-aos="fade-right" data-aos-delay="100">
                        <span class="text-uppercase">Email</span>
                        <a class="__cf_email__"
                           href="mailto:letter@phamthientoan.com">letter@phamthientoan.com</a>
                    </li>
                    <li data-aos="fade-right" data-aos-delay="200">
                        <span class="text-uppercase">Phone</span>
                        <a href="tel:+84903838081">+8490 3838 081</a>
                    </li>
                    <li data-aos="fade-right" data-aos-delay="300">
                        <span class="text-uppercase">Mesenger</span>
                        <a href="https://m.me/phamnguyenhoang.thientoan" class="elementor-button-link elementor-button elementor-size-sm">messenger/phamthientoan</a>
                    </li>
                    <li data-aos="fade-right" data-aos-delay="400">
                        <span class="text-uppercase">Telegram</span>
                        <a href="https://web.telegram.org/k/#900335102" class="elementor-button-link elementor-button elementor-size-sm">telegram/phamthientoan</a>
                    </li>
                    <li data-aos="fade-right" data-aos-delay="500">
                        <span class="text-uppercase">Address</span>
                        <a href="https://www.google.com/maps/place/10%C2%B050'10.8%22N+106%C2%B038'34.3%22E/@10.8360866,106.6426862,19.34z/data=!4m13!1m8!3m7!1s0x317529c06b4cd525:0x6b9bf709a513d7d4!2zNzQ1LzE1LzYgUXVhbmcgVHJ1bmcsIFBoxrDhu51uZyAxMiwgR8OyIFbhuqVwLCBUaMOgbmggcGjhu5EgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!3b1!8m2!3d10.8384842!4d106.6448766!16s%2Fg%2F11fsmwv_h0!3m3!8m2!3d10.836345!4d106.642868?hl=vi-VN&entry=ttu">Saigon - Vietnam</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>



<div class="sign" style="z-index: 100000">
    <img src="/uploads/background/sign.png" alt="">
</div>


<!-- Back to top -->
<a href="#" id="progress" title="Go to top" class="back-to-top d-flex align-items-center justify-content-center"><i
            id="progress-value"></i></a>

       
<?= \System\Libraries\Render::renderAsset('footer', 'frontend') ?>
<script>
function scrollToElement(id) {
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}
</script>

</body>
</html>

