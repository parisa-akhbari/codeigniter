<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>فرم تراکنش</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm text-end">
        <div class="card-body">
            <h2 class="card-title mb-3"><?php echo ($action=='add') ? 'افزودن تراکنش' : 'ویرایش تراکنش'; ?></h2>

            <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>

            <form method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">عنوان</label>
                    <input type="text" class="form-control text-end" id="title" name="title" value="<?php echo set_value('title', $title); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary me-2">ذخیره</button>
                <a href="<?php echo site_url('transactionscategories'); ?>" class="btn btn-secondary">بازگشت</a>
            </form>
        </div>
    </div>

</div>

</body>
</html>
