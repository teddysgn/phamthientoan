<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Eccentric portfolio - personal website template | Home : W3layouts</title>

  <!-- google fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap" rel="stylesheet">


  <?= \System\Libraries\Render::renderAsset('head', 'frontend') ?>
  <script src="https://cdn.jsdelivr.net/npm/momentum-slider@latest/dist/momentum-slider.min.js"></script>
</head>
<style>

    .ms--images .ms-slide:nth-child(1) .ms-slide__image {
        background-image: url("/uploads/rectangle/01.jpg");
        background-repeat: round;
    }

    .ms--images .ms-slide:nth-child(2) .ms-slide__image {
        background-image: url("/uploads/rectangle/02.jpg");
        background-repeat: round;
    }

    .ms--images .ms-slide:nth-child(3) .ms-slide__image {
        background-repeat: round;
        background-image: url("/uploads/rectangle/03.jpg");
    }

    .ms--images .ms-slide:nth-child(4) .ms-slide__image {
        background-repeat: round;
        background-image: url("/uploads/rectangle/04.jpg");
    }

    .ms--images .ms-slide:nth-child(5) .ms-slide__image {
        background-repeat: round;
        background-image: url("/uploads/rectangle/05.jpg");
    }

    .ms--images .ms-slide:nth-child(6) .ms-slide__image {
        background-repeat: round;
        background-image: url("/uploads/rectangle/06.jpg");
    }

    @media screen and (max-width: 415px) {
        .ms--numbers .ms-slide {
            font-size: 5em;
        }
        .ms--images .ms-slide:nth-child(1) .ms-slide__image {
            background-image: url("/uploads/square/01.jpg") !important;
            background-repeat: space;
            background-size: contain;
        }
        .ms--images .ms-slide:nth-child(2) .ms-slide__image {
            background-image: url("/uploads/square/02.jpg") !important;
            background-repeat: space;
            background-size: contain;
        }

        .ms--images .ms-slide:nth-child(3) .ms-slide__image {
            background-repeat: space;
            background-size: contain;
            background-image: url("/uploads/square/03.jpg") !important;
        }

        .ms--images .ms-slide:nth-child(4) .ms-slide__image {
            background-repeat: space;
            background-size: contain;
            background-image: url("/uploads/square/04.jpg") !important;
        }

        .ms--images .ms-slide:nth-child(5) .ms-slide__image {
            background-repeat: space;
            background-size: contain;
            background-image: url("/uploads/square/05.jpg") !important;
        }

        .ms--images .ms-slide:nth-child(6) .ms-slide__image {
            background-repeat: space;
            background-size: contain;
            background-image: url("/uploads/square/06.jpg") !important;
        }
        .pagination {
            top: calc(100% - 70px);
        }


        a.navbar-brand {
            font-size: 30px;
        }

        a.navbar-brand span.fa {
            font-size: 35px;
        }

        .ms--images .ms-slide {
            margin: 0 100px;
        }

        .ms--images .ms-slide__image-container {
            width: 80%;
        }
    }
</style>
<body>
<meta name="robots" content="noindex">
<body>

<div class="w3l-banner-slider">
    <div class="wrapper-container">
        <!-- Container for all sliders, and pagination -->
        <main class="sliders-container">
            <!-- Here will be injected sliders for images, numbers, titles and links -->

            <!-- Simple pagination for the slider -->
            <ul class="pagination">
                <li class="pagination__item"><a class="pagination__button"></a></li>
                <li class="pagination__item"><a class="pagination__button"></a></li>
                <li class="pagination__item"><a class="pagination__button"></a></li>
                <li class="pagination__item"><a class="pagination__button"></a></li>
                <li class="pagination__item"><a class="pagination__button"></a></li>
                <li class="pagination__item"><a class="pagination__button"></a></li>
            </ul>
            
        </main>
    </div>
</div>

<?= \System\Libraries\Render::renderAsset('footer', 'frontend') ?>




</html>