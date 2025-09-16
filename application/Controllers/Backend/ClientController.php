<?php

namespace App\Controllers\Backend;

use System\Core\BaseController;
use System\Libraries\Render;
use System\Libraries\Session;
use System\Drivers\Cache\UriCache;
use App\Models\PortfolioModel;

class ClientController extends BaseController
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
        
        $this->slug = 'client';
    }

    public function client($slug = 'client', $paged = 1)
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

            $mainModel = new PortfolioModel('client');

            $where = "id > ?";
            $params = array(0);

            $items = $mainModel->ListsFields('*', $where, $params, 'id ASC', $paged, 20);


      
            $this->data('title', 'Clients');
            $this->data('cart_title', 'Clients List');
            $this->data('items', $items);
            $this->data('slug', $slug);
            $this->data('keyword', $_GET['keyword'] ?? '');
            $this->data('category_id', $_GET['category_id'] ?? '');
            $result = Render::html('Backend/client_index', $this->data);
            echo $result;
    }

    public function paged($slug = 'client', $paged = 1)
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

            $mainModel = new PortfolioModel('client');
            $where = "id > ?";
            $params = array(0);
           

            $items = $mainModel->ListsFields('*', $where, $params, 'ordering ASC', $paged, 10);

            $items['paged'] = $paged;
            $this->data('title', 'Clients');
            $this->data('cart_title', 'Clients List');
            $this->data('items', $items);;
            $this->data('slug', $slug);
            $result = Render::html('Backend/admin_index', $this->data);
            echo $result;
    }

    public function form($slug = 'client', $id = null)
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
    
            $mainModel = new PortfolioModel('client');
            $item = $mainModel->getById( $id);

    
            $cart_title = $id != null? 'Edit Client' : 'Add Client';
        
            $this->data('title', 'Client - Details');
            $this->data('cart_title', $cart_title);
            $this->data('item', $item);
            $this->data('slug', $slug);
            $result = Render::html('Backend/'.$this->slug.'_form', $this->data);
            echo $result;
    }

    public function save($slug = 'client')
    {
        $mainModel = new PortfolioModel('category');
        $category = $mainModel->getById( $_POST['category_id']);
        $cate = $category['slug'];

        $id = $_POST["id"];
        $data = [
            'name'  => $_POST['name'],
            'style'  => $_POST['style'],
            'img'  => $_FILES['img']['name'],
        ];

        foreach($_FILES as $file => $value){
            if ($_FILES[$file]["error"] == 0 && $_FILES[$file]["name"] != '') {
                $targetDir = "uploads/clients/";

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
            $mainModel->editPost('client', $id, $data);
            $item = $mainModel->getById( $id);
            Session::set('success', 'Edited client successfully!');
        } else {
            $id = $mainModel->addPost('client', $data);
            Session::set('success', 'Added new client successfully!');
        }

        redirect(auth_url($slug . '/form/' . $id));
    }

    public function delete($slug, $id)
    {
        $mainModel = new PortfolioModel('client');
        $mainModel->deletePost( 'client', $id);
        Session::set('success', 'Deleted client successfully!');
       
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function status($slug, $status, $id)
    {
        $current_status = $status == 'active' ? 'inactive' : 'active';
        $mainModel = new PortfolioModel('client');
        $mainModel->editPost('client', $id, ['status' => $current_status]);
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }


}
