
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="/uploads/logo/logo.png">
  <title>Pham Thien Toan - Resume</title>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  
  <style>
    html {
	    scroll-behavior: smooth;
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .swiper-slide img {
      display: block;
      width: 85%;
      height: 100%;
      object-fit: cover;
    }
</style>
<!-- Bootstrap Icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
    <!-- JS FILE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <?= \System\Libraries\Render::renderAsset('head', 'frontend') ?>
  
</head>
<body>

<body data-spy="scroll" data-target="#pb-navbar" data-offset="200">
<div>
    <div class="indicator" style="z-index: 10001"></div>
</div>
<nav class="navbar navbar-expand-lg site-navbar navbar-light bg-light" id="pb-navbar">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09"
                aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample09">
            <ul class="menu">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('')?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/portfolio')?>">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="#section-resume" style="color: #ff7d6a !important;">Resume</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-about')">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-skill')">Skill</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-fact')">Fact</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-services')">Service</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="scrollToElement('section-client')">Client</a></li>
            </ul>
        </div>
    </div>
</nav>


<section class="site-section" id="section-about">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-7 pr-lg-5 mb-lg-0" data-aos="fade-right">
                <img src="/uploads/about/avatar.jpg" alt="Image placeholder" class="img-fluid">
            </div>
            <div class="col-lg-5 pl-lg-5" data-aos="fade-left">
                <div class="section-heading">
                    <h2>About<strong> Me</strong></h2>
                </div>
                <p style="color: #ff7d6a;" class="lead">With over 10+ years of experience, I am a creative and knowledgeable designer with a background in developing and executing visual and fashion design concepts.</p>
                <p class="mb-3">I have had the opportunities to work with many brands in categories such as brand identity, advertising, packaging and printing.</p>
                <p class="mb-5">I also have experience teaching fashion design at university as well as collaborating on designs for a number of domestic fashion brands effectively.</p>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-5">
            </div>
            <div class="col-md-6">
                <h2 data-aos="fade-up"><strong style="font-weight: 900;">Education</strong></h2>
                <div class="resume-item mb-4" data-aos="fade-up">
                    <span class="date">2018 - 2020</span>
                    <h3>DPI Graphic Center</h3>
                    <span class="school">Saigon</span>
                </div>
                <div class="resume-item mb-4" data-aos="fade-up">
                    <span class="date">1999 - 2004</span>
                    <h3>University of Architecture HCMC</h3>
                    <span class="school">Saigon</span>
                </div>
            </div>
            <div class="col-md-6">
                <h2 data-aos="fade-up"><strong style="font-weight: 900;">Experience</strong></h2>
                <div class="resume-item mb-4" data-aos="fade-up" data-aos-delay="150">
                    <span class="date">2016 - Present</span>
                    <h3>Graphic & Fashion Design Freelancer</h3>
                    <span class="school">Saigon</span>
                </div>
                <div class="resume-item mb-4" data-aos="fade-up" data-aos-delay="150">
                    <span class="date">2011 - Present</span>
                    <h3>Fashion Design Lecturer at STU</h3>
                    <span class="school">Saigon</span>
                </div>
                <div class="resume-item mb-4" data-aos="fade-up" data-aos-delay="150">
                    <span class="date">2022 - 2023</span>
                    <h3>Graphic Designer at HOANGNAM GROUP</h3>
                    <span class="school">Saigon</span>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ======= Skills Section ======= -->
<section id="section-skill">
    <section id="skills" class="skills section-bg site-section">
        <div class="container">

            <div class="section-heading">
                <h2><strong>Skills</strong></h2>
            </div>

            <div class="row skills-content">

                <div class="col-lg-6" data-aos="fade-up">

                    <div class="progress">
                        <span class="skill">Illustrator <i class="val">90%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                 aria-valuemax="80"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">Photoshop <i class="val">90%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                 aria-valuemax="90"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">Indesign <i class="val">90%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                 aria-valuemax="90"></div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

                    <div class="progress">
                        <span class="skill">Hand Drawing <i class="val">80%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                 aria-valuemax="80"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">Procreate <i class="val">85%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0"
                                 aria-valuemax="85"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">Blender <i class="val">60%</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                 aria-valuemax="60"></div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section><!-- End Skills Section -->
</section>

<!-- ======= Facts Section ======= -->
<section id="section-fact" class="facts site-section">
    <div class="container">

        <div class="section-heading">
            <h2><strong>Facts</strong></h2>
        </div>
        <div class="row no-gutters">
                        <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch" data-aos="fade-up">
                <div class="count-box">
                    <i class="bi bi-journal-richtext"></i>
                    <span data-purecounter-start="0" data-purecounter-end="<?= $items['count']; ?>" data-purecounter-duration="1"
                          class="purecounter"></span>
                    <p><strong class="span-fact">Projects</strong></p>
                </div>
            </div>
                        <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch" data-aos="fade-up" data-aos-delay="100">
                <div class="count-box">
                    <i class="bi bi-people"></i>
                    <span data-purecounter-start="0" data-purecounter-end="<?= $clients['count']; ?>" data-purecounter-duration="1"
                          class="purecounter"></span>
                    <p><strong class="span-fact">Clients</strong></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch" data-aos="fade-up" data-aos-delay="200">
                <div class="count-box">
                    <i class="bi bi-laptop"></i>
                    <span data-purecounter-start="0" data-purecounter-end="<?php $date = getdate(); echo $date['year'] - 2016 ?>" data-purecounter-duration="1"
                          class="purecounter"></span>
                    <p><strong class="span-fact">Years of Experience</strong></p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch" data-aos="fade-up" data-aos-delay="300">
                <div class="count-box">
                    <i class="bi bi-mouse"></i>
                    <span data-purecounter-start="0" data-purecounter-end="7" data-purecounter-duration="1"
                          class="purecounter"></span>
                    <p><strong class="span-fact">Tools</strong></p>
                </div>
            </div>

        </div>

    </div>
</section><!-- End Facts Section -->

<section class="site-section pb-0" id="section-services">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="section-heading">
                    <h2><strong>Services</strong></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-eye" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Visual Identity</strong></h3>
                    <p>A brand identity is how the organization communicates its personality, tone and essence, as well
                        as memories, emotions and experiences.</p>

                    <p style="margin-bottom: 0">Logo design</p>
                    <p style="margin-bottom: 0">Brand guidelines</p>
                    <p style="margin-bottom: 0">Corporate identity program</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-badge-ad" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Advertising</strong></h3>
                    <p>Great marketing engages people based on the wants, needs, awareness and satisfaction they have
                        about a product, service or brand.</p>
                    <p style="margin-bottom: 0">Postcards</p>
                    <p style="margin-bottom: 0">Flyers and brochures</p>
                    <p style="margin-bottom: 0">Posters, banners and billboards</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-globe2" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Social Post</strong></h3>
                    <p>Social media is a digital technology that facilitates the sharing of text and multimedia through
                        virtual networks and communities.</p>
                    <p style="margin-bottom: 0">Promotion posts</p>
                    <p style="margin-bottom: 0">Product posts</p>
                    <p style="margin-bottom: 0">Greeting posts</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-newspaper" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Publication</strong></h3>
                    <p>Publication design is a classic type of design that communicates with an audience through public
                        distribution.</p>
                    <p style="margin-bottom: 0">Layout design</p>
                    <p style="margin-bottom: 0">Books and newspapers</p>
                    <p style="margin-bottom: 0">Magazines and catalogs</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-box-seam" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Packaging</strong></h3>
                    <p>Most products require some form of packaging to protect and prepare them for storage,
                        distribution, and sale.</p>
                    <p style="margin-bottom: 0">Labels</p>
                    <p style="margin-bottom: 0">Boxes and bags</p>
                    <p style="margin-bottom: 0">Containers</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 text-center mb-5">
                <div class="site-service-item site-animate" data-animate-effect="fadeIn">
						<span class="icon">
							<span class="bi bi-tags" style="display: flex; justify-content: center"></span>
						</span>
                    <h3 class="mb-4"><strong>Apparel</strong></h3>
                    <p>Apparel design relates the physical properties of textiles to our human need for functional and
                        fashionable clothing.</p>
                    <p style="margin-bottom: 0">Uniform</p>
                    <p style="margin-bottom: 0">T shirt print artworks</p>
                    <p style="margin-bottom: 0">Apparel illustrations</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section pb-0" id="section-client">
    <div class="container">
        <div class="row mb-4" style="margin-bottom: 0 !important;">
            <div class="col-md-12">
                <div class="section-heading" style="margin-bottom: 0 !important;">
                    <h2><strong>Clients</strong></h2>
                </div>
            </div>
        </div>
    </div>
</section>

<div #swiperRef="" class="swiper mySwiper text-center" style="padding: 10px 0 30px">
    <div class="swiper-wrapper" style="align-items: center">
        <?php foreach($clientsList as $client):?>
            <div class="swiper-slide" style="margin: 10px; display: flex"><img src="/uploads/clients/<?= $client['img']?>" alt="" <?= $client['style'] != '' ? 'style="'.$client['style'].'"' : ''?>></div>
        <?php endforeach; ?>
    </div>
  </div>



<div class="sign" style="z-index: 100000">
    <img src="/uploads/background/sign.png" alt="">
</div>

<!-- Back to top -->
<a href="#" id="progress" title="Go to top" class="back-to-top d-flex align-items-center justify-content-center"><i
            id="progress-value"></i></a>
            <?= \System\Libraries\Render::renderAsset('footer', 'frontend') ?>
<!-- Modal  -->
<?= \System\Libraries\Render::renderAsset('footer', 'frontend') ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  

<script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true, 
      breakpoints: {
        // Khi màn hình nhỏ hơn 768px (mobile), hiển thị 3 phần tử
        768: {
            slidesPerView: 10,
            spaceBetween: 15
        }
    }
    });
  </script>
  <script>
function scrollToElement(id) {
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}
</script>


</html>