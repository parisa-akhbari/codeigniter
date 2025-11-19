<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>داشبورد</title>
</head>
<body>

<h2>خوش آمدی <?= $user->username ?></h2>

<p>نام کاربری: <?= $user->username ?></p>
<p>تاریخ عضویت: <?= $user->created_at ?></p>

<a href="<?= site_url('auth/logout'); ?>">خروج</a>

</body>
</html>
