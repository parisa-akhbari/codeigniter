<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>دسته بندی ها</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { direction: rtl; text-align: right; }
        table th, table td { vertical-align: middle; }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">دسته بندی ها</h2>
    <a href="<?= site_url('transactionscategories/create') ?>" class="btn btn-primary mb-3 ajax-link">افزودن دسته بندی جدید</a>

    <!-- فرم جستجو -->
    <form method="GET" class="mb-4 ajax-form" action="<?= site_url('transactionscategories/index'); ?>">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <input type="text" name="title" class="form-control" placeholder="عنوان" value="<?= $filters['title'] ?? '' ?>">
            </div>
            <div class="col-md-3 mb-2 d-flex">
                <button type="submit" class="btn btn-primary mr-2">جستجو</button>
                <a href="<?= site_url('transactionscategories/index') ?>" class="btn btn-secondary ajax-link">پاک کردن</a>
            </div>
        </div>
    </form>

    <!-- جدول دسته‌بندی‌ها -->
    <table class="table table-bordered table-striped text-right">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($categories)): ?>
                <?php foreach($categories as $c): ?>
                    <tr>
                        <td><?= $c->title ?></td>
                        <td>
                            <a href="<?= site_url('transactionscategories/edit/'.$c->id) ?>" class="btn btn-sm btn-warning ajax-link">ویرایش</a>
                            <a href="<?= site_url('transactionscategories/delete/'.$c->id) ?>" 
                               onclick="return confirm('آیا مطمئن هستید می‌خواهید این دسته‌بندی را حذف کنید؟');" 
                               class="btn btn-sm btn-danger ajax-link">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2" class="text-center">هیچ دسته‌بندی‌ای یافت نشد</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        <?= $pagination ?? '' ?>
    </div>
</div>

</body>
</html>
