<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>لیست دسته بندی تراکنش ها</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body text-end">
            <h2 class="card-title mb-3">دسته بندی تراکنش ها</h2>

            <form class="row g-3 align-items-center mb-3 justify-content-end ajax-link" method="get" action="<?= site_url('transactionscategories') ?>">
                <div class="col-auto">
                    <input type="text" name="search" value="<?php echo $search; ?>" class="form-control" placeholder="جستجو عنوان">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">جستجو</button>
                <!-- دکمه پاک کردن -->
                    <a href="<?php echo site_url('transactionscategories'); ?>" class="btn btn-secondary ajax-link">پاک کردن</a>
                    <a href="<?php echo site_url('transactionscategories/add'); ?>" class="btn btn-success ajax-link">افزودن دسته بندی</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">عنوان</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($transactions) > 0): ?>
                            <?php foreach($transactions as $t): ?>
                                <tr>
                                    <td class="text-center"><?php echo $t->id; ?></td>
                                    <td class="text-end"><?php echo $t->title; ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('transactionscategories/edit/'.$t->id); ?>" class="btn btn-warning btn-sm me-1 ajax-link">ویرایش</a>
                                        <a href="<?php echo site_url('transactionscategories/delete/'.$t->id); ?>" class="btn btn-danger btn-sm ajax-link" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">هیچ داده‌ای یافت نشد.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>

</div>

</body>
</html>
