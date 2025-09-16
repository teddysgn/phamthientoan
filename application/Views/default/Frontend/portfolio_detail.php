
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portfolio - Projects</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= option('upload_folder'); ?>logo/logo.png">
    <!-- CSS FILE -->
    <?= \System\Libraries\Render::renderAsset('head', 'frontend') ?>

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
        <!-- JS FILE -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
   

</head>
<body data-spy="scroll" data-target="#pb-navbar" data-offset="200">
<div>
    <div class="indicator"></div>
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

<div class="modal-content">
    <div class="container">
        <div class="row justify-content-center mt-60">
            <div class="col-lg-8">
                <section class="modal-body">
                    <div class="ftco-section single-portfolio">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 single-portfolio">
                                    <div class="slider-hero">
                                        <div class="featured-carousel owl-carousel">
                                            <?php
                                                foreach($categories as $category => $value){
                                                    if($item['category_id'] == $value['id']){
                                                        $cate = $value['slug'];
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                <?php if($item['img' . $i] != ''):?>
                                                    <div class="item">
                                                        <div class="work">
                                                            <a href="<?= option('upload_folder') . $cate . '/' . $item['folder_name'] . '/' . $item['img' . $i]; ?>" style="background-image: url('<?= option('upload_folder') . $cate . '/' . $item['folder_name'] . '/' . $item['img' . $i]; ?>');" class="img d-flex align-items-center justify-content-center fancybox" data-fancybox="yca-logo">
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif;?>
                                            <?php endfor;?>
                                                                                   </div>
                                        <div class="text-center">
                                            <ul class="thumbnail carousel-caption">
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <?php if($item['img' . $i] != ''):?>
                                                    <li class="active img">
                                                        
                                                        <a href="<?= option('upload_folder') . $cate . '/' . $item['folder_name'] . '/' . $item['img' . $i]; ?>">
                                                            <img src="<?= option('upload_folder') . $cate . '/' . $item['folder_name'] . '/' . $item['img' . $i]; ?>" class="img-fluid">
                                                        </a>
                                                    </li>
                                                    <?php endif;?>
                                                <?php endfor;?>
                                            </ul>
                                        </div>

                                    </div>
                                    <div class="text-carousel-items" style="margin-top: 10.5rem; text-align: left;">
                                        <?php if ($item['category_id'] == 7): ?>
                                            <p style="margin-bottom: 0">Category: <?= $item['category_inside'] ?></p>
                                            <p style="margin-bottom: 0">Student: <?= $item['client_inside'] ?></p>
                                            <p style="margin-bottom: 0">Year: <?= $item['date_inside'] ?></p>
                                            <p style="margin-bottom: 0">Collection: <?= $item['tool_inside'] ?></p>
                                        <?php else: ?>
                                            <p style="margin-bottom: 0">Category: <?= $item['category_inside'] ?></p>
                                            <p style="margin-bottom: 0">Client: <?= $item['client_inside'] ?></p>
                                            <p style="margin-bottom: 0">Date: <?= $item['date_inside'] ?></p>
                                            <p style="margin-bottom: 0">Tool: <?= $item['tool_inside'] ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center">
                                <a href="<?= $linkPrev; ?>">
                                    <button title="Click letter 'P' or '&#8592;' to see the Previous Project" style="width: 35px; height: 35px" class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal"
                                            type="button">
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </button>
                                </a>
                                <a href="<?= base_url('portfolio')?>">
                                    <button class="btn btn-primary btn-xl text-uppercase" style="margin: 10px; height: 35px" data-bs-dismiss="modal"
                                        title="Click 'X' button to Close Project" type="button">
                                    <i class="fas fa-xmark me-1"></i>
                                    Close Project
                                    </button>
                                </a>
                                <a href="<?= $linkNext; ?>">
                                    <button title="Click letter 'N' or '&#8594;' to see the Next Project" style="width: 35px; height: 35px" class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal"
                                            type="button">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </a>
                            </div>
                </section>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Blog Section Begin -->
<section class="product spad" style="background-color: #001a46">
    </br>
    </br>
    </br>
    </br>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="trending__product">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="section-title d-flex">
                                <div class="container" style="margin: auto">
                                    <a style="color: #ff7d6a" class="primary-btn d-flex">RELATED PROJECT</a>

                                </div>
                                <div class="btn__all" style="text-align: end;margin: auto">
                                    <a style="width: 100px; color: #ff7d6a" href="<?= base_url('/portfolio'); ?>" class="primary-btn d-flex">VIEW ALL <strong> &nbsp;&#10230;</strong></a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <section class="blog spad">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                       
                                        <?php foreach($relatedItems as $related): ?>
                                            <div class="single-portfolio col-sm-4 all">
                                                <div class="relative">
                                                    <div class="thumb portfolio-item">
                                                        <div class="overlay overlay-bg"></div>
                                                        <a class="portfolio-link" href="<?= base_url('/detail/' . create_slug($related['name']) . '/' . $related['id']); ?>">
                                                            <img class="img-fluid" src="<?= option('upload_folder') . $cate . '/' . $related['folder_name'] . '/' . $related['img_background']; ?>" alt="img" />
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="p-inner">
                                                    <h4><?= $related['name']?></h4>
                                                    <div class="cat"><?= $related['name_under']?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>





<div class="sign" style="z-index: 100000">
    <img src="<?= option('upload_folder'); ?>background/sign.png" alt="">
</div>

<!-- Back to top -->
<a href="#" id="progress" title="Go to top" class="back-to-top d-flex align-items-center justify-content-center"><i
            id="progress-value"></i></a>
            <?= \System\Libraries\Render::renderAsset('footer', 'frontend') ?>