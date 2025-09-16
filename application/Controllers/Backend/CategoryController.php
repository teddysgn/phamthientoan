<?php

namespace App\Controllers\Backend;

use System\Core\BaseController;
use System\Libraries\Render;
use System\Libraries\Session;
use System\Drivers\Cache\UriCache;
use App\Models\PortfolioModel;

class CategoryController extends BaseController
{
    protected $assets;
    protected $cache;
    protected $slug;

    public function __construct()
    {
        error_reporting(0);
        load_helpers(['images']);
        load_helpers(['frontend']);
        //init cache for all function at this Controller.
        $cache_gzip = option('cache_gzip') ?? 0;
        $this->cache = new UriCache($cache_gzip, 'html');
        $this->cache->cacheLogin(true); //Van cho Caching cho du nguoi dung Login
        $this->cache->cacheMobile(false); //Dung response thi ko can cache mobile tru khi viet 2 giao dien mobile pc khac nhau.
        
        $this->slug = 'category';
    }

    public function index($paged = 1)
    {
            Render::asset('css', '/css/nucleo-icons.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/bootstrap.min.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/black-dashboard.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/demo/demo.css',    ['area' => 'backend', 'location' => 'head']);

         
            Render::asset('js', 'js/custom.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/popper.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/bootstrap.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/perfect-scrollbar.jquery.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/chartjs.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/black-dashboard.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/demo/demo.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/notify.min.js', ['area' => 'backend', 'location' => 'footer']);

            $mainModel = new PortfolioModel('category');

            $where = "id > ?";
            $params = array(0);
            if($_GET['keyword'] != null){
                $where .= " AND (name LIKE ?)";
                $params[] = "%" . $_GET['keyword'] . "%";
            }

            $items = $mainModel->ListsFields('*', $where, $params, 'id ASC', $paged, 10);
        

            $this->data('title', 'Categories');
            $this->data('cart_title', 'Categories List');
            $this->data('items', $items);
            $this->data('keyword', $_GET['keyword'] ?? '');
            $result = Render::html('Backend/category_index', $this->data);
            echo $result;
    }

    public function category($slug = 'category', $paged = 1)
    {
            Render::asset('css', '/css/nucleo-icons.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/bootstrap.min.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/black-dashboard.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/demo/demo.css',    ['area' => 'backend', 'location' => 'head']);

         
            Render::asset('js', 'js/custom.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/popper.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/bootstrap.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/perfect-scrollbar.jquery.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/chartjs.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/black-dashboard.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/demo/demo.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/notify.min.js', ['area' => 'backend', 'location' => 'footer']);

            $mainModel = new PortfolioModel('category');

            $where = "id > ?";
            $params = array(0);
            if($_GET['keyword'] != null){
                $where .= " AND (name LIKE ?)";
                $params[] = "%" . $_GET['keyword'] . "%";
            }

            $items = $mainModel->ListsFields('*', $where, $params, 'id ASC', $paged, 10);
        

            $this->data('title', 'Categories');
            $this->data('cart_title', 'Categories List');
            $this->data('items', $items);
            $this->data('slug', $slug);
            $this->data('keyword', $_GET['keyword'] ?? '');
            $result = Render::html('Backend/category_index', $this->data);
            echo $result;
    }

    public function paged($slug = 'category', $paged = 1)
    {
            Render::asset('css', '/css/nucleo-icons.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/bootstrap.min.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/black-dashboard.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/demo/demo.css',    ['area' => 'backend', 'location' => 'head']);

         
            Render::asset('js', 'js/custom.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/popper.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/bootstrap.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/perfect-scrollbar.jquery.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/chartjs.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/black-dashboard.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/demo/demo.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/notify.min.js', ['area' => 'backend', 'location' => 'footer']);

            $mainModel = new PortfolioModel('category');
            $where = "id > ?";
            $params = array(0);
            if($_GET['keyword'] != null){
                $where .= " AND (name LIKE ?)";
                $params[] = "%" . $_GET['keyword'] . "%";
            }

            if($_GET['category_id'] != null){
                $where .= " AND (category_id = ?)";
                $params[] = $_GET['category_id'];
            }
            

            $items = $mainModel->ListsFields('*', $where, $params, 'ordering ASC', $paged, 10);
        
            $items['paged'] = $paged;
            $this->data('title', 'Categories');
            $this->data('cart_title', 'Categories List');
            $this->data('items', $items);
            $this->data('slug', $slug);
            $result = Render::html('Backend/admin_index', $this->data);
            echo $result;
    }

    public function form($slug = 'category', $id = null)
    {
            Render::asset('css', '/css/nucleo-icons.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/bootstrap.min.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/black-dashboard.css',    ['area' => 'backend', 'location' => 'head']);
            Render::asset('css', '/css/demo/demo.css',    ['area' => 'backend', 'location' => 'head']);
    
            
            Render::asset('js', 'js/custom.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/popper.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/core/bootstrap.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/perfect-scrollbar.jquery.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/plugins/chartjs.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/black-dashboard.min.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/demo/demo.js', ['area' => 'backend', 'location' => 'footer']);
            Render::asset('js', 'js/notify.min.js', ['area' => 'backend', 'location' => 'footer']);
    
            $mainModel = new PortfolioModel('category');
            $item = $mainModel->getById( $id);
    
            $cart_title = $id != null? 'Edit Category' : 'Add Category';
        
            $this->data('title', 'Categories - Details');
            $this->data('cart_title', $cart_title);
            $this->data('item', $item);
            $this->data('slug', $slug);
            $result = Render::html('Backend/'.$this->slug.'_form', $this->data);
            echo $result;
    }

    public function save($slug = 'category')
    {
        $id = $_POST["id"];
        $data = [
            'name'  => $_POST['name'],
            'slug'  => create_slug($_POST['name'])
        ];

        $mainModel = new PortfolioModel('category');
        if($id > 0 ){
            $mainModel->editPost('category', $id, $data);
            $item = $mainModel->getById( $id);
            Session::set('success', 'Edited category successfully!');
        } else {
            $id = $mainModel->addPost('category', $data);
            Session::set('success', 'Added new category successfully!');
        }

        redirect(auth_url($slug . '/form/' . $id));
    }

    public function delete($slug, $id)
    {
        $mainModel = new PortfolioModel('category');
        $mainModel->deletePost( 'category', $id);
        Session::set('success', 'Deleted category successfully!');
       
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

}
