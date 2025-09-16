<?php

namespace System\Libraries;

Render::block('Frontend\Headers\Header', ['layout' => 'layout_3', 'title' => 'Trang chủ', 'code'   => '']);

use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;

if (Session::has_flash('success')) {
    $success = Session::flash('success');
}
if (Session::has_flash('error')) {
    $error = Session::flash('error');
}

?>

<div class="container">
    <div class="top-nav relative mt-3 flex justify-between">
        <div class="back">
            <a href="<?= auth_url('login') ?>"
                class="flex items-center justify-center w-10 h-10 rounded-full bg-grey text-white">
                <svg width="11" height="18" viewBox="0 0 11 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.225806 9L8.97581 17.75L10.2008 16.525L2.67581 9L10.2008 1.475L8.97581 0.25L0.225806 9Z"
                        fill="white" />
                </svg>
            </a>
        </div>
        <div class="setting">
            <a href="#" class="flex items-center justify-center w-10 h-10 rounded-full bg-grey text-white">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.9999 8.75C14.9664 8.75 15.7499 7.9665 15.7499 7C15.7499 6.0335 14.9664 5.25 13.9999 5.25C13.0334 5.25 12.2499 6.0335 12.2499 7C12.2499 7.9665 13.0334 8.75 13.9999 8.75Z"
                        fill="white" />
                    <path
                        d="M13.9999 15.75C14.9664 15.75 15.7499 14.9665 15.7499 14C15.7499 13.0335 14.9664 12.25 13.9999 12.25C13.0334 12.25 12.2499 13.0335 12.2499 14C12.2499 14.9665 13.0334 15.75 13.9999 15.75Z"
                        fill="white" />
                    <path
                        d="M13.9999 22.75C14.9664 22.75 15.7499 21.9665 15.7499 21C15.7499 20.0335 14.9664 19.25 13.9999 19.25C13.0334 19.25 12.2499 20.0335 12.2499 21C12.2499 21.9665 13.0334 22.75 13.9999 22.75Z"
                        fill="white" />
                </svg>
            </a>
        </div>
    </div>

    <div class="form p-4">
        <div class="head-form p-6">
            <div class="title font-semibold text-2xl text-center text-gray-200 mb-3">
                <?= Flang::_e('comfirm_account') ?>
            </div>
            <div class="desc max-w-[286px] mx-auto font-normal text-sm text-center text-grey-light">
                <?= Flang::_e('comfirm_account_description') ?>
            </div>
        </div>
        <form action="<?php echo auth_url('activation/' . $user_id); ?>" name="forgotpassForm"
            class="py-6 flex flex-col gap-4" method="post">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
            <div class="fieldset">
                <div class="field email">
                    <input class='px-3' type="text" name="email" id="email" class=""
                        placeholder="<?= Flang::_e('activation_code') ?>" required="">
                </div>
            </div>
            <?php if (!empty($errors['email'])): ?>
                <div class="text-red-500 mt-2 text-sm">
                    <?php foreach ($errors['email'] as $error): ?>
                        <p><?= $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="fieldset flex flex-col gap-4">
                <div class="action flex justify-center">
                    <button type="submit" class="btn btn-primary rounded-full">
                        Xác minh
                    </button>
                </div>
            </div>
        </form>
        <form action="<?php echo auth_url('activation/' . $user_id); ?>" method="POST" class="mt-4">
            <button type="submit" name="activation_resend" class="btn btn-primary w-full">
                <?php Flang::_('gửi lại mã') ?>
            </button>
        </form>
    </div>
</div>
