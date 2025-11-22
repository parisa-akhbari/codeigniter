<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>تراکنش‌ها</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { direction: rtl; text-align: right; }
        table th, table td { vertical-align: middle; }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">تراکنش‌ها</h2>
    <a href="<?= site_url('transactions/create') ?>" class="btn btn-primary mb-3">افزودن تراکنش جدید</a>

    <!-- فرم جستجو -->
    <form method="get" class="mb-4">
    <div class="form-row">
        <div class="col-md-3 mb-2">
            <input type="text" name="title" class="form-control" placeholder="عنوان" value="<?= $filters['title'] ?? '' ?>">
        </div>
        <div class="col-md-2 mb-2">
            <select name="type" class="form-control">
                <option value="">همه نوع‌ها</option>
                <option value="income" <?= isset($filters['type']) && $filters['type'] == 'income' ? 'selected' : '' ?>>درآمد</option>
                <option value="expense" <?= isset($filters['type']) && $filters['type'] == 'expense' ? 'selected' : '' ?>>هزینه</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?? '' ?>">
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?? '' ?>">
        </div>
        <div class="col-md-3 mb-2 d-flex">
            <button type="submit" class="btn btn-primary mr-2">جستجو</button>
            <a href="<?= site_url('transactions') ?>" class="btn btn-secondary">پاک کردن</a>
        </div>
    </div>
    </form>


    <!-- جدول تراکنش‌ها -->
    <table class="table table-bordered table-striped text-right">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>مبلغ</th>
                <th>نوع</th>
                <th>دسته‌بندی</th>
                <th>تاریخ</th>
                <th>عملیات</th>

            </tr>
        </thead>
        <tbody>
            <?php if(!empty($transactions)): ?>
                <?php foreach($transactions as $t): ?>
                    <tr>
                        <td><?= $t->title ?></td>
                        <td><?= number_format($t->amount) ?></td>
                        <td><?= $t->type == 'income' ? 'درآمد' : 'هزینه' ?></td>
                        <td><?= $t->category_title ?></td>
                        <td><?= $t->transaction_date ?></td>
                        <td>
                        <a href="<?= site_url('transactions/edit/'.$t->id) ?>" class="btn btn-sm btn-warning">ویرایش</a>
                        <a href="<?= site_url('transactions/delete/'.$t->id) ?>" 
                        onclick="return confirm('آیا مطمئن هستید می‌خواهید این تراکنش را حذف کنید؟');" 
                        class="btn btn-sm btn-danger">حذف</a>
                        </td>

                    </tr>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">هیچ تراکنشی یافت نشد</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?= $pagination ?? '' ?>
</div>

</body>
</html>
