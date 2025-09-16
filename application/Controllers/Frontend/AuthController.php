<?php

namespace App\Controllers\Frontend;

use System\Core\BaseController;
use App\Models\UsersModel;
use System\Libraries\Security;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Assets;
use App\Libraries\Fastmail;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Validate;
use Google_Client;
use Google_Service_Oauth2;

class AuthController extends BaseController
{

    protected $usersModel;
    protected $assets;
    protected $mailer;

    public function __construct()
    {
        load_helpers(['frontend']);
        $this->usersModel = new UsersModel();
        $this->assets = new Assets();
        Flang::load('login', LANG);
        Flang::load('home', LANG);
        $this->data('header', '');
        $this->data('footer', '');
    }


    public function logout()
    {
        // Xóa session và cookie
        Session::destroy();
        redirect(base_url('user/login'));
    }

    public function login($back_url = '')
    {
        //Buoc validate neu co request login.
        if (HAS_POST('username')) {
            echo $username = $_POST['username'];
            echo $password = $_POST['password'];

            $mainModel = new UsersModel;
            $user = $mainModel->getUserByQuery('*', "WHERE username = '" . $username .  "'  AND password = '" . $password . "'");
            
            if(empty($user)){
                redirect(base_url('user/login'));
            } else {
                Session::set('user_id', $user['id']);
                redirect(auth_url());
            }
           
        }

        echo Render::html('Frontend/auth/login', $this->data);
    }


}
