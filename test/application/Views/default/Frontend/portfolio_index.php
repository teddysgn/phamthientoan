
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portfolio - Projects</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/uploads/logo/logo.png">
    <!-- CSS FILE -->
    <?= \System\Libraries\Render::renderAsset('head', 'frontend') ?>

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
        <!-- JS FILE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   
</head>
</head>
<body data-spy="scroll" data-target="#pb-navbar" data-offset="200">
<div>
    <div class="indicator" style="z-index: 10001"></div>
</div>
<nav class="navbar navbar-expand-lg site-navbar navbar-light bg-light" style="z-index: 10000;" id="pb-navbar">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09"
                aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample09">
            <ul class="menu">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/')?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/portfolio')?>" style="color: #ff7d6a !important;">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/resume')?>">Resume</a></li>
            </ul>
        </div>
    </div>
</nav>





<section class="site-section" id="section-portfolio">
    <div class="container">
        <div class="row">
            <div class="section-heading text-center col-md-12">
                <h2>Featured <strong>Portfolio</strong></h2>
            </div>
        </div>
        <div class="filters">
            <ul>
                <li class="active" data-filter="*">All</li>
                <li data-filter=".visual-identity">Visual Identity</li>
                <li data-filter=".advertising">Advertising</li>
                <li data-filter=".social-post">Social Post</li>
                <li data-filter=".publication">Publication</li>
                <li data-filter=".packaging">Packaging</li>
                <li data-filter=".clothing-design">Apparel</li>
                <li data-filter=".mentor">Mentor</li>
                <li data-filter=".art-illustration">Art Illustration</li>
            </ul>
        </div>
        <div class="filters-content">
            <div class="row grid">
                <!-- Visual Identity -->
                 <?php foreach($items as $item):?>
                    <?php
                        switch($item['category_id']){
                            case 1:
                                $cate = "visual-identity";
                                break;
                            case 2:
                                $cate = "advertising";
                                break;
                            case 3:
                                $cate = "social-post";
                                break;
                            case 4:
                                $cate = "publication";
                                break;
                            case 5:
                                $cate = "packaging";
                                break;
                            case 6:
                                $cate = "clothing-design";
                                break;
                            case 7:
                                $cate = "mentor";
                                break;
                            case 8:
                                $cate = "art-illustration";
                                break;
                            default:
                                $cate = "";
                        }
                    ?>
                    <div class="single-portfolio col-sm-4 all <?= $cate; ?>">
                        <div class="relative">
                            <div class="thumb portfolio-item">
                                <div class="overlay overlay-bg"></div>
                                <a class="portfolio-link" href="<?= base_url('/detail/' . create_slug($item['name']) . '/' . $item['id']); ?>">
                                    <img class="img-fluid" src="<?= '/uploads/' . $cate . '/' . $item['folder_name'] . '/' . $item['img_background']?>" alt="img" />
                                </a>
                            </div>
                        </div>
                        <div class="p-inner">
                            <h4><?= $item['name']; ?></h4>
                            <div class="cat"><?= $item['name_under']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
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