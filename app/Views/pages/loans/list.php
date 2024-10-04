<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card rounded-0">
    <div class="card-header">
        <div class="d-flex w-100 justify-content-between">
            <div class="col-auto">
                <div class="card-title h4 mb-0 fw-bolder">List of Loans</div>
            </div>
            <div class="col-auto">
            <button type="button" class="btn btn-primary bg-gradient rounded-0" data-toggle="modal" data-target="#addLoanModal">
                <i class="fa fa-plus-square"></i> Add Loans
            </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-stripped table-bordered">
                <colgroup>
                    <col width="15%">
                    <col width="15%">
                    <col width="25%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <th class="p-1 text-center">Loans</th>
                    <th class="p-1 text-center">Amount</th>
                    <th class="p-1 text-center">Employee</th>
                    <th class="p-1 text-center">Interest <sup>%</sup></th>
                    <th class="p-1 text-center">Weeks to Pay</th>
                    <th class="p-1 text-center">Total Loan/ <sup>week</sup></th>
                    <!-- <th class="p-1 text-center">Balance</th> -->
                    <th class="p-1 text-center">Action</th>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                    <tr>
                        <td class="p-1 text-center"><?= $loan['loan_name'] ?></td>
                        <td class="p-1 text-center"><?= '₱'. number_format($loan['loan_amount'], 2) ?></td>
                        <td class="p-1 text-center"><?= $loan['employee_name'] ?></td>
                        <td class="p-1 text-center"><?= number_format($loan['interest'], 0) ?></td>
                        <td class="p-1 text-center"><?= number_format($loan['weeks_pay'], 0) ?></td>
                        <td class="p-1 text-center"><?= '₱'. number_format($loan['total_loan'], 2) ?></td>
                        <!-- <td class="p-1 text-center"><?= '₱' ?></td> -->
                        <td class="p-1 text-center">
                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $loan['id'] ?>"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <?= $pager->makeLinks($page, $perPage, $total, 'custom_view') ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLoanModalLabel">Add Loan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addLoanForm">
                    <div class="form-group">
                        <label for="loanName">Loan Name</label>
                        <input type="text" class="form-control" id="loanName" name="loanName" required>
                    </div>
                    <div class="form-group">
                        <label for="loanAmount">Loan Amount</label>
                        <input type="number" class="form-control" id="loanAmount" name="loanAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="employee">Employee</label>
                          <select class="form-control" id="employee" name="employee" required>
                              <option value="">Select an Employee</option>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee['id']; ?>"><?= $employee['last_name'] . ', ' . $employee['first_name'] . ($employee['middle_name'] ? ' ' . $employee['middle_name'] : ''); ?></option>
                                <?php endforeach; ?>
                          </select>
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest <sup>%</sup></label>
                        <input type="number" class="form-control" id="interest" name="interest" required>
                    </div>
                    <div class="form-group">
                        <label for="weeks_pay">Weeks to Pay</label>
                        <input type="number" class="form-control" id="weeks_pay" name="weeks_pay" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Add Loan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#addLoanForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '/ci4_payroll/Main/addLoan',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addLoanModal').modal('hide');
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    alert('An error occurred');
                }
            });
        });

        $('.btn-delete').on('click', function() {
            if (confirm('Are you sure you want to delete this loan?')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '/ci4_payroll/Main/deleteLoan/' + id,
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(error) {
                        alert('An error occurred');
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>
