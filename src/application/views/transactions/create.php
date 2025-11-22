<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن تراکنش</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">افزودن تراکنش جدید</h2>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <form action="<?= site_url('transactions/create') ?>" method="post">
        <div class="form-group">
            <label>عنوان</label>
            <input type="text" name="title" class="form-control" value="<?= set_value('title') ?>">
        </div>

        <div class="form-group">
            <label>مبلغ</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= set_value('amount') ?>">
        </div>

        <div class="form-group">
            <label>نوع</label>
            <select name="type" class="form-control">
                <option value="income" <?= set_select('type', 'income') ?>>دریافت</option>
                <option value="expense" <?= set_select('type', 'expense') ?>>پرداخت</option>
            </select>
        </div>

        <div class="form-group">
            <label>دسته‌بندی</label>
            <select name="category_id" class="form-control">
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= set_select('category_id', $c->id) ?>><?= $c->title ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>تاریخ</label>
            <input type="date" name="transaction_date" class="form-control" value="<?= set_value('transaction_date') ?>">
        </div>

        <button type="submit" class="btn btn-success">ذخیره</button>
        <a href="<?= site_url('transactions') ?>" class="btn btn-secondary">بازگشت</a>
    </form>
</div>

</body>
</html>
