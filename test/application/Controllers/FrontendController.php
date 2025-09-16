<?php

namespace App\Controllers;

use System\Core\BaseController;
use System\Libraries\Render;
use System\Libraries\Assets;

class FrontendController extends BaseController
{
    protected $assets;

    public function __construct()
    {
        parent::__construct();
        load_helpers(['frontend']);

        $this->assets = new Assets();
        // Em dung file css js nao cho frontend thi e goi vo. Vd jfast.
        $this->assets->add('js', 'js/jfast.1.1.5.js', 'footer');

    }
}
