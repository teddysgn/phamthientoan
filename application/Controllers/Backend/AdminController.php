<?php

namespace App\Controllers\Backend;

use System\Core\BaseController;
use System\Libraries\Render;
use System\Libraries\Session;
use System\Drivers\Cache\UriCache;
use App\Models\PortfolioModel;

class AdminController extends BaseController
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
            
            redirect(auth_url('product'));
    }
}
