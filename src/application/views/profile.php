<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربری</title>

    <!-- Bootstrap RTL -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            direction: rtl;
            text-align: right;
            font-family: "Vazirmatn", sans-serif;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">پروفایل کاربری</h3>
                </div>

                <div class="card-body">

                    <!-- پیام موفقیت -->
                    <?php if($this->session->flashdata('message')): ?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- پیام خطا -->
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- اطلاعات کاربر -->
                    <div class="text-center mb-4">
                        <p><strong>نام کاربری:</strong> <?= $user->username; ?></p>

                        <?php if(!empty($user->profile_image)): ?>
                            <img src="<?= base_url($user->profile_image); ?>" 
                                 class="rounded-circle img-thumbnail" width="150">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150" 
                                 class="rounded-circle img-thumbnail">
                        <?php endif; ?>
                    </div>

                    <!-- تغییر نام -->
                    <div class="mb-4">
                        <h5 class="mb-3">تغییر نام</h5>

                        <form action="<?= site_url('profile/update_name'); ?>" method="post" class="row g-2">
                            <div class="col-9">
                                <input type="text" name="username" value="<?= $user->username; ?>"
                                       class="form-control" required>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary w-100">ذخیره</button>
                            </div>
                        </form>
                    </div>

                    <!-- تغییر رمز عبور -->
                    <div class="mb-4">
                        <h5 class="mb-3">تغییر رمز عبور</h5>

                        <form action="<?= site_url('profile/update_password'); ?>" method="post" class="row g-3">
                            <div class="col-12">
                                <input type="password" name="current_password" class="form-control"
                                       placeholder="رمز فعلی" required>
                            </div>

                            <div class="col-12">
                                <input type="password" name="new_password" class="form-control"
                                       placeholder="رمز جدید" required>
                            </div>

                            <div class="col-12">
                                <input type="password" name="confirm_password" class="form-control"
                                       placeholder="تکرار رمز جدید" required>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-warning w-100">تغییر رمز</button>
                            </div>
                        </form>
                    </div>

                    <!-- آپلود تصویر -->
                    <div class="mb-4">
                        <h5 class="mb-3">آپلود تصویر پروفایل</h5>

                        <form action="<?= site_url('profile/upload_image'); ?>" method="post" 
                              enctype="multipart/form-data" class="row g-2">

                            <div class="col-9">
                                <input type="file" name="profile_image" class="form-control" required>
                            </div>

                            <div class="col-3">
                                <button type="submit" class="btn btn-success w-100">آپلود</button>
                            </div>

                        </form>
                    </div>

                    <!-- خروج -->
                    <div class="text-center">
                        <a href="<?= site_url('auth/logout'); ?>" class="btn btn-danger">خروج از حساب</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
