<?php

namespace App\Controllers\Backend;

use System\Core\BaseController;
use System\Libraries\Render;
use System\Libraries\Session;
use System\Drivers\Cache\UriCache;
use App\Models\PortfolioModel;

class ProductController extends BaseController
{
    protected $assets;
    protected $cache;

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


            $mainModel = new PortfolioModel('projects');
            $items = $mainModel->ListsFields('*', null, null, 'ordering ASC', $paged, 10);

            $categoryModel = new PortfolioModel('category');
            $categories = $categoryModel->getAllPosts('category');
            
            

      
            $this->data('title', 'Projects');
            $this->data('cart_title', 'Projects List');
            $this->data('slug', 'product');
            $this->data('categories', $categories);
            $this->data('items', $items);
            $result = Render::html('Backend/admin_index', $this->data);
            echo $result;
    }

    public function product($slug = 'product', $paged = 1)
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

            $mainModel = new PortfolioModel('projects');

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

            $categoryModel = new PortfolioModel('category');
            $categories = $categoryModel->getAllPosts('category');

      
            $this->data('title', 'Projects');
            $this->data('cart_title', 'Projects List');
            $this->data('items', $items);
            $this->data('categories', $categories);
            $this->data('slug', $slug);
            $this->data('keyword', $_GET['keyword'] ?? '');
            $this->data('category_id', $_GET['category_id'] ?? '');
            $result = Render::html('Backend/admin_index', $this->data);
            echo $result;
            
        
    }

    public function paged($slug = 'product', $paged = 1)
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

            $mainModel = new PortfolioModel('projects');
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

            $categoryModel = new PortfolioModel('category');
            $categories = $categoryModel->getAllPosts('category');
        
            $items['paged'] = $paged;
            $this->data('title', 'Projects');
            $this->data('cart_title', 'Projects List');
            $this->data('items', $items);
            $this->data('categories', $categories);
            $this->data('slug', $slug);
            $result = Render::html('Backend/admin_index', $this->data);
            echo $result;
    }

    public function form($slug = 'product', $id = null)
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
    
            $mainModel = new PortfolioModel('projects');
            $item = $mainModel->getById( $id);

            $categoryModel = new PortfolioModel('category');
            $categories = $categoryModel->getAllPosts('category');
    
            $cart_title = $id != null? 'Edit Project' : 'Add Project';
        
            $this->data('title', 'Projects - Details');
            $this->data('cart_title', $cart_title);
            $this->data('item', $item);
            $this->data('categories', $categories);
            $this->data('slug', $slug);
            $result = Render::html('Backend/product_form', $this->data);
            echo $result;
    }

    public function save($slug = 'product')
    {
        $mainModel = new PortfolioModel('category');
        $category = $mainModel->getById( $_POST['category_id']);
        $cate = $category['slug'];

        $id = $_POST["id"];
        $data = [
            'name'  => $_POST['name'],
            'name_under'  => $_POST['name_under'],
            'folder_name'  => $_POST['folder_name'],
            'ordering'  => $_POST['ordering'],
            'status'  => $_POST['status'],
            'category_id'  => $_POST['category_id'],
            'category_inside'  => $_POST['category_inside'],
            'client_inside'  => $_POST['client_inside'],
            'date_inside'  => $_POST['date_inside'],
            'tool_inside'  => $_POST['tool_inside'],
            'social_network_inside'  => $_POST['social_network_inside'],
            'img_background'  => $_FILES['img_background']['name'],
            'img1'  => $_FILES['img1']['name'],
            'img2'  => $_FILES['img2']['name'],
            'img3'  => $_FILES['img3']['name'],
            'img4'  => $_FILES['img4']['name'],
            'img5'  => $_FILES['img5']['name'],
            'img6'  => $_FILES['img6']['name'],
            'img7'  => $_FILES['img7']['name'],
            'img8'  => $_FILES['img8']['name'],
            'img9'  => $_FILES['img9']['name'],
            'img10'  => $_FILES['img10']['name'],
            'img11'  => $_FILES['img11']['name'],
            'img12'  => $_FILES['img12']['name'],
        ];

        foreach($_FILES as $file => $value){
            if ($_FILES[$file]["error"] == 0 && $_FILES[$file]["name"] != '') {
                $targetDir = "uploads/" . $cate . '/' . $_POST['folder_name'] . '/';

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = basename($_FILES[$file]["name"]);
                $targetFile = $targetDir . $fileName;

                move_uploaded_file($_FILES[$file]["tmp_name"], $targetFile); // Save Image
            } 

            if( $_FILES[$file]["name"] == ''){
                unset($data[$file]);
            }
        }

        
        if($id > 0 ){
            $mainModel->editPost('projects', $id, $data);
            $item = $mainModel->getById( $id);
            Session::set('success', 'Edited project successfully!');
        } else {
            $id = $mainModel->addPost('projects', $data);
            Session::set('success', 'Added new project successfully!');
        }

        redirect(auth_url($slug . '/form/' . $id));
    }

    public function delete($slug, $id)
    {
        $mainModel = new PortfolioModel('projects');
        $mainModel->deletePost( 'projects', $id);
        Session::set('success', 'Deleted project successfully!');
       
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function status($slug, $status, $id)
    {
        $current_status = $status == 'active' ? 'inactive' : 'active';
        $mainModel = new PortfolioModel('projects');
        $mainModel->editPost('projects', $id, ['status' => $current_status]);
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }


}
