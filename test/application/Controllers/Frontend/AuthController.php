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


    public function logout($back_url = '')
    {
        // Xóa session và cookie
        Session::destroy();
        setcookie('fastcms_logged', '', time() - 3600, '/');
        $back_url = base64_decode($back_url);
        redirect(base_url($back_url));
    }

    public function login($back_url = '')
    {
        Render::asset('css', 'swiper-bundle.min.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'style.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'loginuser.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('js', 'jfast.1.1.5.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'main.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'header']);

        $footer_js = <<<JS
                function touchScroll(el) {
                    return {
                        startX: 0,
                        scrollLeft: 0,
                        isScrolling: false,
                        init() {
                            el.addEventListener('mousedown', this.onMouseDown.bind(this));
                            document.addEventListener('mousemove', this.onMouseMove.bind(this));
                            document.addEventListener('mouseup', this.onMouseUp.bind(this));
                            const images = el.querySelectorAll('img');
                            images.forEach(img => {
                                img.setAttribute('draggable', 'false');
                            });
                        },
                        onMouseDown(event) {
                            event.preventDefault(); // Ngăn hành vi mặc định gây ảnh hưởng
                            this.isScrolling = true;
                            this.startX = event.clientX; // Sử dụng clientX thay vì pageX - offsetLeft
                            this.scrollLeft = el.scrollLeft;
                        },
                        onMouseMove(event) {
                            if (!this.isScrolling) return;
                            const x = event.clientX;
                            const walk = (x - this.startX) * 2; // Tốc độ cuộn có thể điều chỉnh
                            el.scrollLeft = this.scrollLeft - walk;
                        },
                        onMouseUp(event) {
                            this.isScrolling = false;
                            // el.style.cursor = 'grab';
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const scrollContainers = document.querySelectorAll('[data-attr="scroll-container"]');
                    scrollContainers.forEach(container => {
                        const scrollInstance = touchScroll(container);
                        scrollInstance.init();
                    });
                });

        JS;

        Render::inline('js', $footer_js, ['area' => 'frontend', 'location' => 'footer']);

        // $decodedUrl = '';
        // $decodedUrl = base64_decode($back_url);
        // echo $back_url;
        // // ket qua L2VuL21vdmllL3RoZS1saW9uLWtpbmctMjAyNC8
        // echo '<br>';
        // echo $decodedUrl;
        // $this->_login($input, $back_url);    

        //Buoc validate neu co request login.
        if (HAS_POST('email')) {
            // echo "Back URL received in login: " . $back_url; // Kiểm tra giá trị của $back_url
            // die();
            // echo 'co request login';
            // $csrf_token = S_POST('csrf_token') ?? '';
            // if (!Session::csrf_verify($csrf_token)){
            //     Session::flash('error', Flang::_e('csrf_failed') );
            //     redirect(auth_url('login'));
            // }
            $input = [
                'email'  =>  S_POST('email') ?? '',
                'password'  =>  S_POST('password') ?? ''
            ];

            $rules = [
                'email' => [
                    'rules' => [Validate::alnum("@._"), Validate::length(5, 150)],
                    'messages' => [Flang::_e('username_invalid'), Flang::_e('username_length', 5, 30)]
                ],
                'password' => [
                    'rules' => [Validate::length(5, null)],
                    'messages' => [Flang::_e('password_length', 5)]
                ]
            ];
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                $errors = $validator->getErrors();
                $this->data('errors', $errors);
            } else {

                return $this->_login($input, $back_url);
            }
        }

        // Hiển thị trang đăng nhập: Nếu ko có request login, or validate that bai
        $this->data('title', Flang::_e('login_welcome'));
        $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.
        $this->data('back_url', $back_url);


        echo Render::html('Frontend/auth/login', $this->data);
    }


    public function _login($input, $back_url = '')
    {
        $back_url = base64_decode($back_url);

        //$2y$10$jJzcVXtMuqC3LKSjtX2I0ulknNZCZmJuP8lIlKBq0vaTWAJYFZamu la admin
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $user = $this->usersModel->getUserByUsername($input['email']);
        } else {
            $user = $this->usersModel->getUserByEmail($input['email']);
        }

        // echo Security::hashPassword($input['password']);die;
        if ($user && Security::verifyPassword($input['password'], $user['password'])) {
            if ($user['status'] !== 'active') {
                Session::flash('error', Flang::_e('users_noactive', $input['email']));
                redirect(auth_url('login'));
                exit();
            }
            // Set thông tin đăng nhập vào session
            setcookie('fastcms_logged', $user['id'], time() + 86400, '/');
            Session::set('user_id', $user['id']);
            Session::set('role', $user['role']);
            Session::set('permissions', json_decode($user['permissions'], true));
            // Tái tạo session ID để tránh session fixation
            Session::regenerate();

            // // Print session data
            // echo '<pre>';
            // print_r($_SESSION);
            // echo '</pre>';
            // echo LANG;
            // echo auth_url('/userinfo/'. $user['id']);

            // // $this->render('auth', 'frontend/auth/ho');
            // // redirect(auth_url('/'));
            // echo 'Dang nhap thanh cong';
            // redirect(base_url('/') . LANG);

            redirect(base_url($back_url));


            // redirect(auth_url('/userinfo/'. $user['id']));
        } else {
            // echo 'Dang nhap that bai';

            Session::flash('error', Flang::_e('login_failed', $input['email']));
            echo auth_url('login');
            redirect(auth_url('login'));
        }
    }


    public function register()
    {
        Render::asset('css', 'swiper-bundle.min.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'loginuser.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'style.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('js', 'jfast.1.1.5.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'main.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'header']);

        $footer_js = <<<JS
                function touchScroll(el) {
                    return {
                        startX: 0,
                        scrollLeft: 0,
                        isScrolling: false,
                        init() {
                            el.addEventListener('mousedown', this.onMouseDown.bind(this));
                            document.addEventListener('mousemove', this.onMouseMove.bind(this));
                            document.addEventListener('mouseup', this.onMouseUp.bind(this));
                            const images = el.querySelectorAll('img');
                            images.forEach(img => {
                                img.setAttribute('draggable', 'false');
                            });
                        },
                        onMouseDown(event) {
                            event.preventDefault(); // Ngăn hành vi mặc định gây ảnh hưởng
                            this.isScrolling = true;
                            this.startX = event.clientX; // Sử dụng clientX thay vì pageX - offsetLeft
                            this.scrollLeft = el.scrollLeft;
                        },
                        onMouseMove(event) {
                            if (!this.isScrolling) return;
                            const x = event.clientX;
                            const walk = (x - this.startX) * 2; // Tốc độ cuộn có thể điều chỉnh
                            el.scrollLeft = this.scrollLeft - walk;
                        },
                        onMouseUp(event) {
                            this.isScrolling = false;
                            // el.style.cursor = 'grab';
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const scrollContainers = document.querySelectorAll('[data-attr="scroll-container"]');
                    scrollContainers.forEach(container => {
                        const scrollInstance = touchScroll(container);
                        scrollInstance.init();
                    });
                });

        JS;

        Render::inline('js', $footer_js, ['area' => 'frontend', 'location' => 'footer']);

        //Buoc validate neu co request register.
        if (HAS_POST('username')) {
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)) {
                echo 'csrf_failed';
                die();
                Session::flash('error', Flang::_e('csrf_failed'));
                redirect(auth_url('register'));
            }
            $input = [
                'username' => S_POST('username'),
                'fullname' => S_POST('fullname'),
                'email' => S_POST('email'),
                'password' => S_POST('password'),
                'password_repeat' => S_POST('confirm-password'),
            ];

            $rules = [
                'username' => [
                    'rules' => [
                        // Chỉnh sửa regex để cho phép ký tự tiếng Trung và tiếng Ấn Độ
                        // \p{L} cho phép tất cả các ký tự chữ trong Unicode (bao gồm các ký tự tiếng Trung, tiếng Ấn Độ)

                        Validate::length(6, 30),
                    ],
                    'messages' => [
                        Flang::_e('username_invalid'),
                        Flang::_e('username_length', 6, 30),
                    ]
                ],
                'fullname' => [
                    'rules' => [
                        Validate::length(6, 30),
                        // Chỉnh sửa regex cho fullname để hỗ trợ các ký tự Unicode (tiếng Trung, tiếng Ấn Độ)
                        // Cho phép ký tự chữ và khoảng trắng
                    ],
                    'messages' => [
                        Flang::_e('fullname_length', 6, 50),
                    ]
                ],
                'email' => [
                    'rules' => [
                        Validate::email(),
                        Validate::length(6, 150),
                    ],
                    'messages' => [
                        Flang::_e('email_invalid'),
                        Flang::_e('email_length', 6, 150),
                    ]
                ],
                'password' => [
                    'rules' => [
                        Validate::length(6, 60),
                    ],
                    'messages' => [
                        Flang::_e('password_length', 6, 60),
                    ]
                ],
                'password_repeat' => [
                    'rules' => [
                        Validate::equals($input['password']),
                    ],
                    'messages' => [
                        Flang::_e('password_repeat_invalid', $input['password_repeat']),
                    ]
                ],
            ];

            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                $errors = $validator->getErrors();
                $this->data('errors', $errors);
            } else {
                $errors = [];
                if ($this->usersModel->getUserByUsername($input['username'])) {
                    $errors['username'] = [
                        Flang::_e('username_double', $input['username'])
                    ];
                    $isExists = true;
                }
                if ($this->usersModel->getUserByEmail($input['email'])) {
                    $errors['email'] = [
                        Flang::_e('email_double', $input['email'])
                    ];
                    $isExists = true;
                }
                if (!isset($isExists) && empty($errors)) {
                    $input['password'] = Security::hashPassword($input['password']);
                    $input['avatar'] = '';
                    $input['role'] = 'member';
                    $input['permissions'] = config('member', 'Roles');
                    $input['permissions'] = json_encode($input['permissions']);
                    $input['status'] = 'inactive';
                    $input['created_at'] = DateTime();
                    $input['updated_at'] = DateTime();
                    return $this->_register($input);
                } else {
                    $this->data('errors', $errors);
                }
            }
        }


        // Hiển thị trang đăng nhập: Nếu ko có request login, or validate that bai
        $this->data('title', Flang::_e('register_welcome'));
        $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.



        // $this->render('auth', 'Frontend/Auth/register');
        echo Render::html('Frontend/auth/register', $this->data);
    }

    private function _register($input)
    {

        // echo "<pre>";
        // print_r($input);
        // echo "</pre>";
        // die();
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào
        $activationNo = strtoupper(random_string(6)); // Tạo mã gồm 6 ký tự
        // Tạo mã kích hoạt riêng cho URL
        $activationCode = strtolower(random_string(20)); // Tạo mã gồm 20 ký tự
        $optionalData = [
            'activation_no' => $activationNo,
            'activation_code' => $activationCode,
            'activation_expires' => time() + 86400,
        ];
        $input['optional'] = json_encode($optionalData);
        //Them Data Nguoi Dung Vao Du Lieu
        $user_id = $this->usersModel->addUser($input);

        if ($user_id) {

            // Gửi email kích hoạt
            $activationLink = auth_url('activation/' . $user_id . '/' . $activationCode . '/');

            // echo $activationLink ;
            // echo '<br>';
            // echo $activationNo;
            // die();


            // die();
            //$emailContent = Render::component('Common/Email/activation', ['username' => $input['username'], 'activation_link' => $activationLink]);
            //echo $emailContent;die;
            $this->mailer = new Fastmail();
            $this->mailer->send($input['email'], Flang::_e('active_account'), 'activation', ['username' => $input['username'], 'activation_link' => $activationLink, 'activation_no' => $activationNo]);

            Session::flash('success', Flang::_e('regsiter_success'));
            $this->data('csrf_token', Session::csrf_token(600));



            redirect(auth_url("activation/{$user_id}/"));
        } else {
            Session::flash('error', Flang::_e('register_error'));
            redirect(auth_url('register'));
        }
    }

    public function activation($user_id = '', $activationCode = null)
    {
        Render::asset('css', 'swiper-bundle.min.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'loginuser.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'style.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('js', 'jfast.1.1.5.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'main.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'header']);

        $footer_js = <<<JS
                function touchScroll(el) {
                    return {
                        startX: 0,
                        scrollLeft: 0,
                        isScrolling: false,
                        init() {
                            el.addEventListener('mousedown', this.onMouseDown.bind(this));
                            document.addEventListener('mousemove', this.onMouseMove.bind(this));
                            document.addEventListener('mouseup', this.onMouseUp.bind(this));
                            const images = el.querySelectorAll('img');
                            images.forEach(img => {
                                img.setAttribute('draggable', 'false');
                            });
                        },
                        onMouseDown(event) {
                            event.preventDefault(); // Ngăn hành vi mặc định gây ảnh hưởng
                            this.isScrolling = true;
                            this.startX = event.clientX; // Sử dụng clientX thay vì pageX - offsetLeft
                            this.scrollLeft = el.scrollLeft;
                        },
                        onMouseMove(event) {
                            if (!this.isScrolling) return;
                            const x = event.clientX;
                            const walk = (x - this.startX) * 2; // Tốc độ cuộn có thể điều chỉnh
                            el.scrollLeft = this.scrollLeft - walk;
                        },
                        onMouseUp(event) {
                            this.isScrolling = false;
                            // el.style.cursor = 'grab';
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const scrollContainers = document.querySelectorAll('[data-attr="scroll-container"]');
                    scrollContainers.forEach(container => {
                        const scrollInstance = touchScroll(container);
                        scrollInstance.init();
                    });
                });

JS;
        Render::inline('js', $footer_js, ['area' => 'frontend', 'location' => 'footer']);

        // Lấy thông tin người dùng từ ID
        $user = $this->usersModel->getUserById($user_id);
        if (!$user) {
            Session::flash('error', Flang::_e('acccount_does_exist'));
            redirect(auth_url('login'));
            return;
        }
        if ($user['status'] != 'inactive') {
            Session::flash('success', Flang::_e('account_active'));
            redirect(auth_url('login'));
            return;
        }

        $user_optional = @json_decode($user['optional'], true);

        $user_active_expires = $user_optional['activation_expires'] ?? 0;

        // Nếu người dùng yêu cầu gửi lại mã
        if (HAS_POST('activation_resend')) {
            return $this->_activation_resend($user_id, $user_optional, $user);
        }

        if ($user_active_expires < time()) {
            $this->data('error', Flang::_e('token_out_time'));
            return $this->_activation_form($user_id);
        }

        // Trường hợp người dùng truy cập qua URL
        if ($activationCode) {
            $user_active_code = $user_optional['activation_code'] ?? '';
            if (!empty($user_active_code) && strtolower($user_active_code) === strtolower($activationCode)) {
                // Kích hoạt tài khoản
                return $this->_activation($user_id);
            } else {
                $this->data('error', Flang::_e('token_invalid'));
                return $this->_activation_form($user_id);
            }
        }

        // Trường hợp người dùng nhập mã vào form
        if (HAS_POST('activation_no')) {
            $activationNo = S_POST('activation_no');
            $user_active_no = $user_optional['activation_no'] ?? '';
            if (!empty($user_active_no) && strtoupper($user_active_no) === strtoupper($activationNo)) {
                // Kích hoạt tài khoản
                $this->_activation($user_id);
            } else {
                $this->data('error', Flang::_e('token_invalid'));
                $this->_activation_form($user_id);
            }
        } else {
            // Hiển thị form nhập mã kích hoạt
            $this->_activation_form($user_id);
        }
    }

    private function _activation_form($user_id)
    {
        Render::asset('css', 'swiper-bundle.min.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'loginuser.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'style.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('js', 'jfast.1.1.5.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'main.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'header']);

        $footer_js = <<<JS
                function touchScroll(el) {
                    return {
                        startX: 0,
                        scrollLeft: 0,
                        isScrolling: false,
                        init() {
                            el.addEventListener('mousedown', this.onMouseDown.bind(this));
                            document.addEventListener('mousemove', this.onMouseMove.bind(this));
                            document.addEventListener('mouseup', this.onMouseUp.bind(this));
                            const images = el.querySelectorAll('img');
                            images.forEach(img => {
                                img.setAttribute('draggable', 'false');
                            });
                        },
                        onMouseDown(event) {
                            event.preventDefault(); // Ngăn hành vi mặc định gây ảnh hưởng
                            this.isScrolling = true;
                            this.startX = event.clientX; // Sử dụng clientX thay vì pageX - offsetLeft
                            this.scrollLeft = el.scrollLeft;
                        },
                        onMouseMove(event) {
                            if (!this.isScrolling) return;
                            const x = event.clientX;
                            const walk = (x - this.startX) * 2; // Tốc độ cuộn có thể điều chỉnh
                            el.scrollLeft = this.scrollLeft - walk;
                        },
                        onMouseUp(event) {
                            this.isScrolling = false;
                            // el.style.cursor = 'grab';
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const scrollContainers = document.querySelectorAll('[data-attr="scroll-container"]');
                    scrollContainers.forEach(container => {
                        const scrollInstance = touchScroll(container);
                        scrollInstance.init();
                    });
                });

JS;
        Render::inline('js', $footer_js, ['area' => 'frontend', 'location' => 'footer']);

        $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.

        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('title', Flang::_e('active_welcome'));

        $this->data('user_id', $user_id);
        // $this->render('auth', 'Frontend/Auth/activation');
        echo Render::html('Frontend/auth/activation', $this->data);
    }

    private function _activation($user_id)
    {
        $this->usersModel->updateUser($user_id, [
            'status' => 'active',
            'optional' => null
        ]);

        Session::flash('success', Flang::_e('active_email_success'));
        redirect(auth_url('login'));
    }

    private function _activation_resend($user_id, $user_optional, $user)
    {
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào
        $activationNo = strtoupper(random_string(6)); // Tạo mã gồm 6 ký tự
        // Tạo mã kích hoạt riêng cho URL
        $activationCode = strtolower(random_string(32)); // Tạo mã gồm 32 ký tự
        if (empty($user_optional)) {
            $user_optional = [];
        }/*  */
        $user_optional['activation_no'] = $activationNo;
        $user_optional['activation_code'] = $activationCode;
        $user_optional['activation_expires'] = time() + 86400;
        $this->usersModel->updateUser($user_id, ['optional' => json_encode($user_optional)]);

        // Gửi email mã kích hoạt mới
        $activationLink = auth_url('activation/' . $user_id . '/' . $activationCode . '/');
        $this->mailer = new Fastmail();
        $this->mailer->send($user['email'], Flang::_e('code_active_account'), 'activation', ['username' => $user['username'], 'activation_link' => $activationLink, 'activation_no' => $activationNo]);
        Session::flash('success', Flang::_e('active_send_email'));

        redirect(auth_url('activation/' . $user_id));
    }


    public function forgotpass($user_id = '', $token = '')
    {
        Render::asset('css', 'swiper-bundle.min.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'loginuser.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('css', 'style.css', ['area' => 'frontend', 'location' => 'head']);
        Render::asset('js', 'jfast.1.1.5.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'main.js', ['area' => 'frontend', 'location' => 'footer']);
        Render::asset('js', 'swiper-bundle.min.js', ['area' => 'frontend', 'location' => 'header']);

        $footer_js = <<<JS
                function touchScroll(el) {
                    return {
                        startX: 0,
                        scrollLeft: 0,
                        isScrolling: false,
                        init() {
                            el.addEventListener('mousedown', this.onMouseDown.bind(this));
                            document.addEventListener('mousemove', this.onMouseMove.bind(this));
                            document.addEventListener('mouseup', this.onMouseUp.bind(this));
                            const images = el.querySelectorAll('img');
                            images.forEach(img => {
                                img.setAttribute('draggable', 'false');
                            });
                        },
                        onMouseDown(event) {
                            event.preventDefault(); // Ngăn hành vi mặc định gây ảnh hưởng
                            this.isScrolling = true;
                            this.startX = event.clientX; // Sử dụng clientX thay vì pageX - offsetLeft
                            this.scrollLeft = el.scrollLeft;
                        },
                        onMouseMove(event) {
                            if (!this.isScrolling) return;
                            const x = event.clientX;
                            const walk = (x - this.startX) * 2; // Tốc độ cuộn có thể điều chỉnh
                            el.scrollLeft = this.scrollLeft - walk;
                        },
                        onMouseUp(event) {
                            this.isScrolling = false;
                            // el.style.cursor = 'grab';
                        }
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const scrollContainers = document.querySelectorAll('[data-attr="scroll-container"]');
                    scrollContainers.forEach(container => {
                        const scrollInstance = touchScroll(container);
                        scrollInstance.init();
                    });
                });

JS;
        Render::inline('js', $footer_js, ['area' => 'frontend', 'location' => 'footer']);
        if (empty($user_id) || empty($token)) {
            if (HAS_POST('email')) {
                $input = [
                    'email' => S_POST('email')
                ];
                $rules = [
                    'email' => [
                        'rules' => [
                            Validate::email(),
                            Validate::length(6, 150)
                        ],
                        'messages' => [
                            Flang::_e('email_invalid'),
                            Flang::_e('email_length', 6, 150)
                        ]
                    ],
                ];
                $validator = new Validate();
                if (!$validator->check($input, $rules)) {
                    $errors = $validator->getErrors();
                    $this->data('errors', $errors);
                } else {
                    $user = $this->usersModel->getUserByEmail($input['email']);
                    if (!$user) {
                        $errors['email'] = array(
                            Flang::_e('email_exist', $input['email'])
                        );
                        $this->data('errors', $errors);
                    } else {
                        $user_optional = @json_decode($user['optional'], true);
                        $this->_forgot_send($user);
                    }
                }
            }

            $this->data('csrf_token', Session::csrf_token(600));
            $this->data('title', Flang::_e('forgotpassw_welcome'));


            // $this->render('auth', 'Frontend/auth/forgotpassword');
            echo Render::html('Frontend/auth/forgotpassword', $this->data);
        } else {
            $user_id = clean_input($user_id);
            $user = $this->usersModel->getUserById($user_id);
            if (!$user) {
                $errors['email'] = array(
                    Flang::_e('user_exist')
                );
                $this->data('errors', $errors);
                $this->data('title', Flang::_e('forgotpassw_welcome'));
                $this->data('assets_header', $this->assets->header('backend'));
                $this->data('assets_footer', $this->assets->footer('backend'));
                // $this->render('auth', 'Frontend/Auth/forgotpassword');
                echo Render::html('Frontend/auth/forgotpassword', $this->data);
            } else {
                return $this->_forgot_password($user, $token);
            }
        }
    }

    private function _forgot_password($user, $token)
    {
        $user_id = $user['id'];
        $user_optional = @json_decode($user['optional'], true);

        $token_db = $user_optional['token_reset_password'] ?? '';
        $token_expires = $user_optional['token_reset_password_expires'] ?? 0;

        if ($token !== $token_db) {
            $error = Flang::_e('token_fotgot_invalid');
        }
        if ($token_expires <= time()) {
            $error = Flang::_e('token_fotgot_out_time');
        }
        if (!empty($error)) {
            $this->data('error', $error);
            $this->data('title', Flang::_e('forgotpassw_welcome'));
            // $this->data('assets_footer', $this->assets->footer('backend'));
            // $this->data('assets_header', $this->assets->header('backend'));
            $this->render('auth', 'Frontend/Auth/forgotpassword');
        } else {
            if (HAS_POST('password')) {
                $input = [
                    'password' => S_POST('password'),
                ];
                $rules = [
                    'password' => [
                        'rules' => [
                            Validate::length(6, 60),
                        ],
                        'messages' => [
                            Flang::_e('password_length', 6, 60),
                        ]
                    ]
                ];
                $validator = new Validate();
                if (!$validator->check($input, $rules)) {
                    $errors = $validator->getErrors();
                    $this->data('errors', $errors);
                } else {
                    $input['password'] = Security::hashPassword($input['password']);
                    if (isset($user_optional['token_reset_password'])) {
                        unset($user_optional['token_reset_password']);
                    }
                    if (isset($user_optional['token_reset_password_expires'])) {
                        unset($user_optional['token_reset_password_expires']);
                    }
                    $input['optional'] = json_encode($user_optional); //remove ma reset sau khi set passs.
                    // echo "chuan bi update";
                    // die();
                    $this->usersModel->updateUser($user_id, $input);


                    $success = Flang::_e('reset_password_success');
                    $this->data('success', $success);
                    $this->data('csrf_token', Session::csrf_token(600));
                    $this->data('title', Flang::_e('login_welcome'));
                    // $this->data('assets_header', $this->assets->header('backend'));
                    // $this->data('assets_footer', $this->assets->footer('backend'));

                    // $this->render('auth', 'Frontend/Auth/login');
                    echo Render::html('Frontend/auth/login', $this->data);
                }
            }
            $this->data('title', Flang::_e('update_password_welcome'));
            // $this->data('assets_footer', $this->assets->footer('backend'));
            // $this->data('assets_header', $this->assets->header('backend'));
            // $this->render('auth', 'Frontend/Auth/forgot_setpassword');
            echo Render::html('Frontend/auth/forgot_setpassword', $this->data);
        }
    }


    private function _forgot_send($user)
    {
        $user_id = $user['id'];
        // tạo token forgot password
        $token = strtolower(random_string(32));
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào
        $user_optional = @json_decode($user['optional'], true);
        if (empty($user_optional)) {
            $user_optional = [];
        }
        $user_optional['token_reset_password'] = $token;
        $user_optional['token_reset_password_expires'] = time() + 86400;
        $this->usersModel->updateUser($user_id, ['optional' => json_encode($user_optional)]);

        // Construct reset link 
        $reset_link = auth_url('forgot_password/' . $user_id . '/' . $token . '/');
        // Gửi email link reset password
        $this->mailer = new Fastmail();
        $this->mailer->send($user['email'], Flang::_e('title_email_link_reset'), 'reset_password', ['username' => $user['username'], 'reset_link' => $reset_link]);

        Session::flash('success', Flang::_e('link_reset_password') . $user['email']);
        // echo "Link reset password: " . $reset_link;
    }
}
