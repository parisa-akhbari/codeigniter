<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
<div class="container mt-5">

    <h2 class="mb-4 text-center">مدیریت دسته بندی تراکنش ها</h2>

    <!-- پیام موفقیت -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>

    <!-- فرم افزودن تراکنش -->
    <div class="card mb-5">
        <div class="card-body">
            <h5 class="card-title">Add New Transaction</h5>
            <?php echo form_open('transaction/store', ['id'=>'addForm']); ?>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter transaction title" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Transaction</button>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- جدول تراکنش‌ها -->
    <table id="transactionTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($transactions)) : ?>
            <?php foreach($transactions as $t): ?>
            <tr>
                <td><?php echo $t->id; ?></td>
                <td><?php echo $t->title; ?></td>
                <td>
                    <button class="btn btn-sm btn-info viewBtn" data-id="<?php echo $t->id; ?>" data-title="<?php echo $t->title; ?>">View</button>
                    <button class="btn btn-sm btn-warning editBtn" data-id="<?php echo $t->id; ?>" data-title="<?php echo $t->title; ?>">Edit</button>
                    <a href="<?php echo site_url('transaction/delete/'.$t->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3" class="text-center">No transactions found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal نمایش تراکنش -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" method="post">
            <input type="hidden" name="id" id="modalId">
            <div class="mb-3">
                <label for="modalTitleInput" class="form-label">Title</label>
                <input type="text" name="title" id="modalTitleInput" class="form-control" required>
            </div>
        </form>
        <p><strong>ID:</strong> <span id="modalIdText"></span></p>
        <p><strong>Title:</strong> <span id="modalTitleText"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" id="saveEditBtn">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- اسکریپت‌ها -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // DataTable
    if (!$.fn.DataTable.isDataTable('#transactionTable')) {
        $('#transactionTable').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 10
        });
    }

    // نمایش Modal برای مشاهده تراکنش
    $('.viewBtn').on('click', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#modalIdText').text(id);
        $('#modalTitleText').text(title);
        $('#transactionModal .modal-title').text('Transaction Details');
        $('#saveEditBtn').hide();
        $('#modalTitleInput, #modalId').hide();
        $('#modalTitleText, #modalIdText').show();
        var modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
    });

    // نمایش Modal برای ویرایش تراکنش
    $('.editBtn').on('click', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        $('#modalId').val(id);
        $('#modalTitleInput').val(title);
        $('#transactionModal .modal-title').text('Edit Transaction');
        $('#saveEditBtn').show();
        $('#modalTitleInput, #modalId').show();
        $('#modalTitleText, #modalIdText').hide();
        var modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
    });

    // ذخیره تغییرات ویرایش
    $('#saveEditBtn').on('click', function() {
        var id = $('#modalId').val();
        var title = $('#modalTitleInput').val();
        if(title == '') { alert('Title is required'); return; }
        $.ajax({
            url: '<?php echo site_url("transaction/update"); ?>/' + id,
            method: 'POST',
            data: {title: title},
            success: function(response) {
                location.reload(); // بعد از تغییر، صفحه رفرش شود
            },
            error: function() { alert('Error updating transaction'); }
        });
    });
});
</script>

</body>
</html>
