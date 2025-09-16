<?php

use System\Libraries\Session;
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <link rel="shortcut icon" href="<?= option('upload_folder'); ?>logo/logo.png">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <title>
         Pham Thien Toan | Admin | <?= $title; ?>
      </title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
      <?= \System\Libraries\Render::renderAsset('head', 'backend') ?>
      <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.2.1/ckeditor5.css">
   </head>
   <body class="">
      <div class="wrapper">
         <!-- Sidebar -->
         <div class="sidebar" data-color="white">
            <div class="sidebar-wrapper">
               <div class="logo">
                  <a href="<?= base_url(); ?>"><img style="width: 80%; margin: 15px" src="<?= option('upload_folder'); ?>logo/logo.png" alt=""></a>
               </div>
               <ul class="nav">
                  <li id="products">
                     <a href="<?= auth_url('product'); ?>">
                     <i class="bi bi-cast"></i>
                        <p>Projects</p>
                     </a>
                  </li>
                  <li id="categories">
                  <a href="<?= auth_url('category'); ?>">
                     <i class="bi bi-tag"></i>
                        <p>Categories</p>
                     </a>
                  </li>
                  <li id="clients">
                     <a href="<?= auth_url('client'); ?>">
                     <i class="bi bi-person-check-fill"></i>
                        <p>Clients</p>
                     </a>
                  </li>
                  <li>
                     <a href="<?= base_url('user/logout'); ?>">
                     <i class="bi bi-box-arrow-left"></i>
                        <p>Logout</p>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
         <!-- End Sidebar -->
         <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
               <div class="container-fluid">
                  <div class="navbar-wrapper">
                     <div class="navbar-toggle d-inline">
                        <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                        </button>
                     </div>
                     <a class="navbar-brand" href="#pablo"><?= $title; ?></a>
                  </div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                     aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navigation">
                     <ul class="navbar-nav ml-auto ">
                        <li class="dropdown nav-item">
                           <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                              <div class="photo">
                                 <img src="<?= option('upload_folder'); ?>logo/logo.png">
                              </div>
                              <b class="caret d-none d-lg-block d-xl-block"></b>
                              <p class="d-lg-none">
                                 Admin
                              </p>
                           </a>
                           <ul class="dropdown-menu dropdown-navbar">
                              <li class="nav-link">
                                 <a href="<?= base_url('user/logout'); ?>" class="nav-item dropdown-item">Logout</a>
                              </li>
                           </ul>
                        </li>
                        <li class="separator d-lg-none"></li>
                     </ul>
                  </div>
               </div>
            </nav>
            <!-- End Navbar -->
            <!-- Content -->
            <div class="content">
               <form id="main-form" name="main-form" method="POST" action="<?= auth_url('/'.$slug.'/save'); ?>"
                  accept-charset="UTF-8" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="BC9OmeZg51Z8BcUXosLxQJ1tNkDAcfqO1PuQ1QgX" autocomplete="off">            
                  <div class="row">
                     <div class="col-md-6">
                        <div class="card">
                           <div class="card-header">
                              <h4 class="card-title"> <?= $cart_title?></h4>
                              <a style="padding: 10px 18px" href="<?= auth_url($slug); ?>"
                                 class="btn btn-sm btn-simple">Back to List
                              </a>
                              <?php if (Session::get('success')):?>
                                <div class="alert alert-success">
                                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="bi bi-x-lg"></i>
                                    </button>
                                    <?= Session::get('success');?>
                                </div>
                            <?php endif;?>
                           </div>
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="name">Name (*)</label>
                                       <input type="text" id="name" name="name" class="form-control" required
                                          placeholder="Name Category" value="<?= $item['name']; ?>">
                                       <small class="text-danger" id="error-name">
                                       </small>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="card-footer row text-center justify-content-center">
                                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                                <input type="hidden" name="cate" value="<?= $cate; ?>">
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <?php if($item['img' . $i] != ''):?>
                                        <input type="hidden" name="hidden_picture<?= $i; ?>" value="<?= $item['img' . $i]; ?>">
                                    <?php endif;?>
                                <?php endfor;?>
                              <input type="submit" name="save" class="btn btn-fill btn-primary col-md-3" value="Save">
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <footer class="footer">
         <div class="container-fluid">
         <div class="copyright float-left">
            <a href="<?= base_url(); ?>" target="_blank">phamthientoan.com</a>
         </div>
         <div class="copyright float-right">
            ©
            <script>
               document.write(new Date().getFullYear())
            </script> made by
            <a href="https://hoangvupcx.com" target="_blank">hoangvupcx.com</a>
         </div>
      </footer>
      <!--   Core JS Files   -->
      <!--   Core JS Files   -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
         integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
         crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <?= \System\Libraries\Render::renderAsset('footer', 'backend') ?>
      <script>
         $(document).ready(function() {
           $().ready(function() {
             $sidebar = $('.sidebar');
             $navbar = $('.navbar');
         
             $full_page = $('.full-page');
         
             $sidebar_responsive = $('body > .navbar-collapse');
             sidebar_mini_active = true;
             white_color = false;
         
             window_width = $(window).width();
         
             fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
         
         
         
             $('.fixed-plugin a').click(function(event) {
               // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
               if ($(this).hasClass('switch-trigger')) {
                 if (event.stopPropagation) {
                   event.stopPropagation();
                 } else if (window.event) {
                   window.event.cancelBubble = true;
                 }
               }
             });
         
             $('.fixed-plugin .background-color span').click(function() {
               $(this).siblings().removeClass('active');
               $(this).addClass('active');
         
               var new_color = $(this).data('color');
         
               if ($sidebar.length != 0) {
                 $sidebar.attr('data-color', new_color);
               }
         
               if ($navbar.length != 0) {
                 $navbar.attr('data-color', new_color);
               }
         
               if ($full_page.length != 0) {
                 $full_page.attr('filter-color', new_color);
               }
         
               if ($sidebar_responsive.length != 0) {
                 $sidebar_responsive.attr('data-color', new_color);
               }
             });
         
             $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
               var $btn = $(this);
         
               if (sidebar_mini_active == true) {
                 $('body').removeClass('sidebar-mini');
                 sidebar_mini_active = false;
                 blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
               } else {
                 $('body').addClass('sidebar-mini');
                 sidebar_mini_active = true;
                 blackDashboard.showSidebarMessage('Sidebar mini activated...');
               }
         
               // we simulate the window Resize so the charts will get updated in realtime.
               var simulateWindowResize = setInterval(function() {
                 window.dispatchEvent(new Event('resize'));
               }, 180);
         
               // we stop the simulation of Window Resize after the animations are completed
               setTimeout(function() {
                 clearInterval(simulateWindowResize);
               }, 1000);
             });
         
             $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
               var $btn = $(this);
         
               if (white_color == true) {
         
                 $('body').addClass('change-background');
                 setTimeout(function() {
                   $('body').removeClass('change-background');
                   $('body').removeClass('white-content');
                 }, 900);
                 white_color = false;
               } else {
         
                 $('body').addClass('change-background');
                 setTimeout(function() {
                   $('body').removeClass('change-background');
                   $('body').addClass('white-content');
                 }, 900);
         
                 white_color = true;
               }
         
         
             });
         
             $('.light-badge').click(function() {
               $('body').addClass('white-content');
             });
         
             $('.dark-badge').click(function() {
               $('body').removeClass('white-content');
             });
           });
         });
      </script>
      <script>
         var element = document.getElementById('categories').className = 'active';
      </script>
   </body>
</html>
<?= Session::del('success');?>