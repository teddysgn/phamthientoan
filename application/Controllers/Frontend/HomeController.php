<?php

namespace App\Controllers\Frontend;

use System\Core\BaseController;
use System\Libraries\Render;
use App\Libraries\Fastlang as Flang;
use System\Drivers\Cache\UriCache;
use App\Models\PortfolioModel;

class HomeController extends BaseController
{
    protected $assets;
    protected $cache;

    public function __construct()
    {
        error_reporting(0);
        load_helpers(['images']);
        load_helpers(['frontend']);
        Flang::load('home', LANG);
        //init cache for all function at this Controller.
        $cache_gzip = option('cache_gzip') ?? 0;
        $this->cache = new UriCache($cache_gzip, 'html');
        $this->cache->cacheLogin(true); //Van cho Caching cho du nguoi dung Login
        $this->cache->cacheMobile(false); //Dung response thi ko can cache mobile tru khi viet 2 giao dien mobile pc khac nhau.
    }

    public function index()
    {
        $cachedata = $this->cache->get();
        if (!empty($cachedata)) {
            $this->cache->headers();
            echo $cachedata;
            exit();
        } else {
            Render::asset('css', '/css/bootstrap.css',          ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/css/style.css',              ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/fonts/icomoon/style.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/cdn/js/glightbox.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/cdn/js/swiper.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/cdn/js/aos.css',    ['area' => 'frontend', 'location' => 'head']);

            Render::asset('js', 'cdn/js/glightbox.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/swiper.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/purecounter.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/aos.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/vendor/jquery.waypoints.min.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/custom.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/bar.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', '/assets/js/main.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'carousel/js/bootstrap.min.js', ['area' => 'frontend', 'location' => 'footer']);
           
            $result = Render::html('Frontend/home_index', $this->data);
            echo $result;

            $cachedata = $this->cache->set($result, true); //true de nhan ve du lieu gzip cho $cachedata, neu false thi nhan ve du lieu raw html.
        }
    }

    public function portfolio()
    {
       
            Render::asset('css', '/css/bootstrap.css',          ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/css/style.css',              ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/owl.carousel.min.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/owl.theme.default.min.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/animate.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/style.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/cdn/js/aos.css',    ['area' => 'frontend', 'location' => 'head']);

            Render::asset('js', 'cdn/js/glightbox.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/swiper.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/purecounter.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/aos.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/isotope.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/vendor/jquery.waypoints.min.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/custom.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/bar.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', '/assets/js/main.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'carousel/js/bootstrap.min.js', ['area' => 'frontend', 'location' => 'footer']);


            $mainModel = new PortfolioModel('projects');
            $items = $mainModel->ListsFields('*', 'status = ?', array('active'), 'ordering ASC', 1, 200);

            $categoryModel = new PortfolioModel('category');
            $categories = $categoryModel->getAllPosts( 'category');

           
            $this->data('items', $items['data']);
            $this->data('categories', $categories);
            $result = Render::html('Frontend/portfolio_index', $this->data);
            echo $result;

           
    }

    public function detail($slug, $id)
    {
        $mainModel = new PortfolioModel('projects');
        $item = $mainModel->getById( $id);

        if(!empty($item)){
            $mainModel->editPost('projects', $id, ['view' => $item['view'] + 1]);

            $cachedata = $this->cache->get();
            if (!empty($cachedata)) {
                $this->cache->headers();
                echo $cachedata;
                exit();
            } else {
                Render::asset('css', '/css/bootstrap.css',          ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/css/style.css',              ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/carousel/css/owl.carousel.min.css',    ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/carousel/css/owl.theme.default.min.css',    ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/carousel/css/animate.css',    ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/carousel/css/style.css',    ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/cdn/css/aos.css',    ['area' => 'frontend', 'location' => 'head']);
                Render::asset('css', '/cdn/css/fancybox.css',    ['area' => 'frontend', 'location' => 'head']);

                Render::asset('js', 'cdn/js/glightbox.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'cdn/js/swiper.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'cdn/js/purecounter.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'cdn/js/aos.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'cdn/js/isotope.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'cdn/js/fancybox.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'js/vendor/jquery.waypoints.min.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'js/custom.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'js/bar.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'carousel/js/bootstrap.min.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'carousel/js/owl.carousel.min.js', ['area' => 'frontend', 'location' => 'footer']);
                Render::asset('js', 'carousel/js/main.js', ['area' => 'frontend', 'location' => 'footer']);            
                Render::asset('js', '/assets/js/main.js', ['area' => 'frontend', 'location' => 'footer']);

            
                $item = $mainModel->getById( $id);

                $relatedItems = $mainModel->ListsFields('*', 'id <> ? AND category_id = ?', array($id, $item['category_id']), '', '', 6);

                $checkID = $mainModel->getPostByQuery('projects', 'MIN(id) AS min, MAX(id) AS max', '');
                $minId = $checkID['min'];
                $maxId = $checkID['max'];

                

                if($id == $minId){
                    $itemNext = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id > '.$id.' AND status = "active" ORDER BY id ASC limit 3');
                    $linkNext = base_url('/detail/' . create_slug($itemNext['name']) . '/' . $itemNext['id']);

                    $itemPrev = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id = '.$maxId.' AND status = "active"');
                    $linkPrev = base_url('/detail/' . create_slug($itemPrev['name']) . '/' . $itemPrev['id']);
                } else if($id == $maxId) {
                    $itemNext = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id = '.$minId.' AND status = "active"');
                    $linkNext = base_url('/detail/' . create_slug($itemNext['name']) . '/' . $itemNext['id']);

                    $itemPrev = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id < '.$id.' AND status = "active" ORDER BY id DESC limit 3');
                    $linkPrev = base_url('/detail/' . create_slug($itemPrev['name']) . '/' . $itemPrev['id']);
                } else {
                    $itemNext = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id > '.$id.' AND status = "active" ORDER BY id ASC limit 3');
                    $linkNext = base_url('/detail/' . create_slug($itemNext['name']) . '/' . $itemNext['id']);

                    $itemPrev = $mainModel->getPostByQuery('projects', 'id, name', 'WHERE id < '.$id.' AND status = "active" ORDER BY id DESC limit 3');
                    $linkPrev = base_url('/detail/' . create_slug($itemPrev['name']) . '/' . $itemPrev['id']);
                }

                $categoryModel = new PortfolioModel('category');
                $categories = $categoryModel->getAllPosts( 'category');
                
                $this->data('item', $item);
                $this->data('categories', $categories);
                $this->data('relatedItems', $relatedItems['data']);
                $this->data('minId', $minId);
                $this->data('maxId', $maxId);
                $this->data('linkNext', $linkNext);
                $this->data('linkPrev', $linkPrev);
                $result = Render::html('Frontend/portfolio_detail', $this->data);
                echo $result;

                $cachedata = $this->cache->set($result, true); //true de nhan ve du lieu gzip cho $cachedata, neu false thi nhan ve du lieu raw html.
            }
        } else {
            redirect(base_url('portfolio'));
        }
    }

    public function slide()
    {
        $cachedata = $this->cache->get();
        if (!empty($cachedata)) {
            $this->cache->headers();
            echo $cachedata;
            exit();
        } else {
           
            Render::asset('css', '/slide/css/style-liberty.css', ['area' => 'frontend', 'location' => 'head']); 

            Render::asset('js', '/slide/js/momentum-slider.min.js', ['area' => 'frontend', 'location' => 'footer']); 
            Render::asset('js', '/slide/js/main.js', ['area' => 'frontend', 'location' => 'footer']); 

            
         
           
            $result = Render::html('Frontend/home_slide', $this->data);
            echo $result;

            $cachedata = $this->cache->set($result, true); //true de nhan ve du lieu gzip cho $cachedata, neu false thi nhan ve du lieu raw html.
        }
        
    }
    public function resume()
    {
        $cachedata = $this->cache->get();
        if (!empty($cachedata)) {
            $this->cache->headers();
            echo $cachedata;
            exit();
        } else {
            Render::asset('css', '/css/bootstrap.css',          ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/css/style.css',              ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/fact/css/style.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/owl.carousel.min.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/owl.theme.default.min.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/style.css',    ['area' => 'frontend', 'location' => 'head']);
            Render::asset('css', '/carousel/css/animate.css',    ['area' => 'frontend', 'location' => 'head']);

         
            Render::asset('js', 'cdn/js/glightbox.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/swiper.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/purecounter.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'cdn/js/aos.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/vendor/jquery.waypoints.min.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'carousel/js/bootstrap.min.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'fact/js/swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'footer']);
           
           
            Render::asset('js', '/assets/js/main.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/bar.js', ['area' => 'frontend', 'location' => 'footer']);
            Render::asset('js', 'js/custom.js', ['area' => 'frontend', 'location' => 'footer']);

            $mainModel = new PortfolioModel('projects');
            $items = $mainModel->getPostByQuery('projects', 'COUNT(id) AS `count`', 'WHERE id > 0');

            $clientModel = new PortfolioModel('client');
            $clients = $clientModel->getPostByQuery('client', 'COUNT(id) AS `count`', 'WHERE id > 0');
            $clientsList = $clientModel->ListsFields('id, img, style', '', array(), 'id ASC', 1, 20);

            $this->data('items', $items);
            $this->data('clients', $clients);
            $this->data('clientsList', $clientsList['data']);
            $result = Render::html('Frontend/home_resume', $this->data);
            $cachedata = $this->cache->set($result, true); //true de nhan ve du lieu gzip cho $cachedata, neu false thi nhan ve du lieu raw html.
      
            echo $result;

        }
    }
}
