<?= $this->extend('layouts/main') ?>

<?= $this->section('custom_css') ?>
<style>
    div#product-list {
        height: 25em;
        overflow: auto;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card rounded-0">
    <div class="card-header">
    <div class="d-flex w-100 justify-content-between">
                <div class="card-title h4 mb-0 fw-bolder">New Payslip</div>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="<?= base_url("Main/payslip_add") ?>" id="payslip-form" method="POST" onkeydown="return event.key != 'Enter';">
                <input type="hidden" name="salary" value="0">
                <fieldset class="border py-3 rounded-0 mb-3">
                    <div class="container-fluid">
                        <div class="row align-items-end">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="payroll_id" class="control-label">Payroll</label>
                                <select id="payroll_id" name="payroll_id" class="form-select rounded-0">
                                    <option value="" disabled selected></option>
                                    <?php
                                        foreach($payrolls as $row):
                                    ?>
                                        <option value="<?= $row['id'] ?>" ><?= $row['code']. " - " .$row['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="employee_id" class="control-label">Employee</label>
                                <select id="employee_id" name="employee_id" class="form-select rounded-0">
                                    <option value="" disabled selected></option>
                                    <?php foreach($employees as $row): ?>
                                        <option value="<?= $row['id'] ?>" data-salary="<?= $row['salary'] ?>"><?= $row['code']. " - " .$row['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="witholding_tax" class="control-label">Witholding Tax</label>
                                <input type="number" class="form-control rounded-0 text-end" id="witholding_tax" name="witholding_tax" min="0" step="any" required>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-8 col-xs-8 mb-3">
                                <label for="late_undertime" class="control-label">Late/Undertime <sup>mins</sup></label>
                                <input type="number" class="form-control rounded-0" id="late_undertime" name="late_undertime" min="0" step="any" required>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-8 col-xs-8 mb-3">
                                <div id="late_undertime_deduction_display" class="mt-2">Deduction: ₱0.00</div>
                                <input type="hidden" name="late_undertime_deduction" id="late_undertime_deduction" value="">
                            </div>

                        </div>
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <fieldset class="mb-3 py-3 border">
                            <legend class="w-auto px-3 mx-3">Earnings</legend>
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- salary -->
                                    <div class="col-lg-4 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="employee_id" class="control-label">Regular Rate/ <sup>week</sup></label>
                                        <input class="form-control" type="text" value="" id="dynamicSalary"/>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="present" class="control-label">Regular <sup>hours</sup></label>
                                        <input type="number" class="form-control rounded-0" id="present" name="present" min="0" step="any" required>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <div class="mt-2"><label>Total:&nbsp; ₱ </label><span id="total_amount">&nbsp;0.00</span></div>
                                    </div>
                                    <!-- overtime -->
                                    <div class="col-lg-4 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="employee_id" class="control-label">Overtime Rate/ <sup>hour</sup></label>
                                        <input class="form-control" type="text" value="" name="dynamicOvertime" id="dynamicOvertime"/>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="overtime" class="control-label">Overtime <sup>hours</sup></label>
                                        <input type="number" class="form-control rounded-0" id="overtime" name="overtime" min="0" step="any" required>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <div class="mt-2"><label>Total:&nbsp; ₱ </label><span id="overtime_amount">&nbsp;0.00</span></div>
                                    </div>
                                    <!-- legal -->
                                    <div class="col-lg-4 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="employee_id" class="control-label">Legal Rate/ <sup>hour</sup></label>
                                        <input class="form-control" type="text" value="" name="dynamicLegal" id="dynamicLegal"/>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="legal" class="control-label">Legal <sup>hours</sup></label>
                                        <input type="number" class="form-control rounded-0" id="legal" name="legal" min="0" step="any" required>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <div class="mt-2"><label>Total:&nbsp; ₱ </label><span id="legal_amount">&nbsp;0.00</span></div>
                                    </div>
                                    <!-- special -->
                                    <div class="col-lg-4 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="employee_id" class="control-label">Special Rate/ <sup>hours</sup></label>
                                        <input class="form-control" type="text" value="" name="dynamicSpecial" id="dynamicSpecial"/>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <label for="special" class="control-label">Special <sup>hours</sup></label>
                                        <input type="number" class="form-control rounded-0" id="special" name="special" min="0" step="any" required>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-4 mb-2">
                                        <div class="mt-2"><label>Total:&nbsp; ₱ </label><span id="special_amount">&nbsp;0.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <fieldset class="mb-3 py-3 border">
                            <legend class="w-auto px-3 mx-3">Deductions</legend>
                            <div class="container-fluid">

                                <div class="row">
                                    <dt class="col-auto">Loans:</dt>
                                        <dd class="col-auto flex-shrink-1 flex-grow-1 px-2">
                                            <div id="loan_list"></div>
                                        </dd>
                                </div>
                                <div>
                                    <dt class="col-auto">Weeks to pay:</dt>
                                    <dd class="col-auto flex-shrink-1 flex-grow-1 px-2">
                                        <div id="weeks_to_pay">0 <sup>weeks</sup> </div>
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-4">
                                        <label for="amount_pay" class="control-label">Amount to Pay:</label>
                                    </dt>
                                    <dd class="col-sm-4">
                                        <input type="number" class="form-control rounded-0" id="amount_pay" name="amount_pay" min="0" step="any" required>
                                    </dd>
                                </div>
     

                                <table class="table table-bordered table-striped" id="deductions-table">
                                    <thead>
                                        <tr class="bg-dark bg-gradient bg-opacity-75 text-light">
                                            <th class="p-1 text-center"></th>
                                            <th class="p-1 text-center">Name</th>
                                            <th class="p-1 text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="text-end">
                                    <button class="btn btn-light bg-gradient rounded-0 btn-sm border" id="add_deduction" type="button"><i class="fa fa-plus"></i> Add Deduction</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <fieldset class="mb-3 border rounded-0 py-3 mb-3">
                    <div class="container-fluid">
                        <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3"></div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="net" class="control-label">Net</label>
                                <input type="number" class="form-control rounded-0 text-end" id="net" name="net" min="0" step="any" required value="0" readonly>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="card-footer text-center">
        <button class="btn btn-primary rounded-0" id="save_payslip" type="button"><i class="fa fa-save"></i> Save Payslip</button>
    </div>
</div>
<noscript id="earning-clone">
    <tr>
        <td class="px-2 py-1 align-middle text-center">
            <button class="btn btn-outline-danger btn-sm rounded-0 rem-earning" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="px-2 py-1 align-middle">
            <input type="text" class="form-control form-control-sm rounded-0" name="earning_name[]">
        </td>
        <td class="px-2 py-1 align-middle">
            <input type="number" step="any" class="form-control form-control-sm rounded-0" name="earning_amount[]">
        </td>
    </tr>
</noscript>
<noscript id="deduction-clone">
    <tr>
        <td class="px-2 py-1 align-middle text-center">
            <button class="btn btn-outline-danger btn-sm rounded-0 rem-deduction" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="px-2 py-1 align-middle">
            <input type="text" class="form-control form-control-sm rounded-0" name="deduction_name[]">
        </td>
        <td class="px-2 py-1 align-middle">
            <input type="number" step="any" class="form-control form-control-sm rounded-0" name="deduction_amount[]">
        </td>
    </tr>
</noscript>
<?= $this->endSection() ?>
<?= $this->section('custom_js') ?>
<script>
    $(document).ready(function() {
    var salary;

    function compute_total(total_ot = 0, total_leg = 0, total_sp = 0) {
        var earnings = 0,
            deductions = 0,
            net = 0;

        var present = $('#present').val();
        var late_undertime = $('#late_undertime').val();
        var witholding_tax = $('#witholding_tax').val();
        // var amount_pay = $('#amount_pay').val();
        present = present > 0 ? present : 0;
        late_undertime = late_undertime > 0 ? late_undertime : 0;
        witholding_tax = witholding_tax > 0 ? witholding_tax : 0;

        // IF WORKING DAYS IS 6 DAYS per WEEK
        var daily = salary / 6;
        var hourly = daily / 8;
        var min = hourly / 60;

        $('#earnings-table tbody tr').each(function() {
            var amount = $(this).find('[name="earning_amount[]"]').val();
            earnings += parseFloat(amount > 0 ? amount : 0);
        });
        $('#deductions-table tbody tr').each(function() {
            var amount = $(this).find('[name="deduction_amount[]"]').val();
            deductions += parseFloat(amount > 0 ? amount : 0);
        });

        var present_amount = parseFloat(present) * parseFloat(hourly);
        var late_undertime_deduction = parseFloat(late_undertime) * min;

        net += present_amount;
        var total_amt = net;
        net -= late_undertime_deduction;
        net += parseFloat(earnings);
        net -= parseFloat(deductions);
        net -= parseFloat((witholding_tax));
        net += parseFloat(total_ot);
        net += parseFloat(total_leg);
        net += parseFloat(total_sp);

        // // Deduct amount_pay from total loan amount
        // var totalLoan = parseFloat($('#total_loan_amount').text().replace(/,/g, '')) || 0;
        //     var remainingLoan = totalLoan - parseFloat(amount_pay);
        //     $('#total_loan_amount').text(number_format(remainingLoan.toFixed(2)));

        $('#special_amount').html(parseFloat(total_sp).toFixed(2));
        $('#legal_amount').html(parseFloat(total_leg).toFixed(2));
        $('#overtime_amount').html(parseFloat(total_ot).toFixed(2));
        $('#total_amount').html(parseFloat(total_amt).toFixed(2));
        $('#net').val(parseFloat(net).toFixed(3));
        $('#late_undertime_deduction').val(late_undertime_deduction.toFixed(2));
        $('#late_undertime_deduction_display').text('Deduction: ₱' + late_undertime_deduction.toFixed(2));
    }

    function formatNumberWithCommas(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    

    // function fetchEmployeeLoans(employeeId) {
    //     $.ajax({
    //         url: '<?= base_url('Main/getEmployeeLoans') ?>/' + employeeId,
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(data) {
    //             let totalLoan = 0;
    //             let weeksToPay = 0;
    //             data.forEach(function(loan) {
    //                 totalLoan += parseFloat(loan.total_loan);
    //                 weeksToPay += parseInt(loan.weeks_pay);
    //             });
    //             $('#total_loan_amount').text(formatNumberWithCommas(totalLoan.toFixed(2)));
    //             $('#weeks_to_pay').text(weeksToPay);
    //             compute_total();
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error fetching employee loans:', error);
    //         }
    //     });
    // }


    function fetchEmployeeLoans($employee_id) {
    $.ajax({
        url: '<?= base_url('Main/getEmployeeLoans') ?>/' + employeeId,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let loanListHtml = '';

            data.forEach(function(loans) {
                loanListHtml += `
                    <div class="row">
                        <div class="col-auto">
                            <strong>Loan Name:</strong> ${loans.loan_name}
                        </div>
                        <div class="col-auto">
                            <strong>Weeks to Pay:</strong> ${loans.weeks_pay} <sup>weeks</sup>
                        </div>
                        <div class="col-auto">
                            <strong>Amount to Pay:</strong> ${formatNumberWithCommas(loans.total_loan.toFixed(2))}
                        </div>
                    </div>
                `;
            });

            $('#loan_list').html(loanListHtml);
            compute_total();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching employee loans:', error);
        }
    });
}

function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


    // $('#amount_pay').on('input change', function() {
    //         var amountPay = $(this).val();
    //         var totalLoan = $('#total_loan_amount').text().replace(/,/g, '');
    //         var remainingLoan = totalLoan - amountPay;
    //         $('#total_loan_amount').text(number_format(remainingLoan.toFixed(2)));
    //         compute_total();
    //     });

    $('#payroll_id, #employee_id').select2({
        placeholder: 'Please Select Here',
        width: '100%',
    });

    $('#present, #late_undertime, #witholding_tax').on('change input', function() {
        var dynamicOvertime = $('#dynamicOvertime').val();
        var overtime = $('#overtime').val();
        var dynamicLegal = $('#dynamicLegal').val();
        var legal = $('#legal').val();
        var dynamicSpecial = $('#dynamicSpecial').val();
        var special = $('#special').val();

        var total_ot = dynamicOvertime.length > 0 && overtime.length > 0 ? dynamicOvertime * overtime : 0;
        var total_leg = dynamicLegal.length > 0 && legal.length > 0 ? dynamicLegal * legal : 0;
        var total_sp = dynamicSpecial.length > 0 && special.length > 0 ? dynamicSpecial * special : 0;

        compute_total(total_ot, total_leg, total_sp);
    });

    $('#dynamicSalary').change(function() {
        var sal = $('#dynamicSalary').val();
        salary = sal > 0 ? parseFloat(sal) : 0;
        $('[name="salary"]').val(salary);
        compute_total();
    });

    $('#dynamicOvertime, #overtime, #dynamicLegal, #legal, #dynamicSpecial, #special').keyup(function() {
        var dynamicOvertime = $('#dynamicOvertime').val();
        var overtime = $('#overtime').val();
        var dynamicLegal = $('#dynamicLegal').val();
        var legal = $('#legal').val();
        var dynamicSpecial = $('#dynamicSpecial').val();
        var special = $('#special').val();

        var total_ot = dynamicOvertime.length > 0 && overtime.length > 0 ? dynamicOvertime * overtime : 0;
        var total_leg = dynamicLegal.length > 0 && legal.length > 0 ? dynamicLegal * legal : 0;
        var total_sp = dynamicSpecial.length > 0 && special.length > 0 ? dynamicSpecial * special : 0;

        compute_total(total_ot, total_leg, total_sp);
    });

    $('#employee_id').change(function() {
        var id = $(this).val();
        var sal = $('#employee_id option[value="'+id+'"]').data('salary');
        salary = sal > 0 ? parseFloat(sal) : 0;
        $('[name="salary"]').val(salary);
        compute_total();
        if (id) {
            fetchEmployeeLoans(id);
        } else {
            $('#total_loan_amount').text('0.00');
            $('#weeks_to_pay').text('0');
        }
    });

    $('#add_earning').click(function() {
        var tr = $($('noscript#earning-clone').html()).clone();
        $('#earnings-table tbody').append(tr);

        tr.find('.rem-earning').click(function() {
            tr.remove();
            compute_total();
        });
        tr.find('[name="earning_amount[]"]').on('change input', function() {
            compute_total();
        });
    });

    $('#add_deduction').click(function() {
        var tr = $($('noscript#deduction-clone').html()).clone();
        $('#deductions-table tbody').append(tr);

        tr.find('.rem-deduction').click(function() {
            tr.remove();
            compute_total();
        });
        tr.find('[name="deduction_amount[]"]').on('change input', function() {
            var dynamicOvertime = $('#dynamicOvertime').val();
            var overtime = $('#overtime').val();
            var dynamicLegal = $('#dynamicLegal').val();
            var legal = $('#legal').val();
            var dynamicSpecial = $('#dynamicSpecial').val();
            var special = $('#special').val();

            var total_ot = dynamicOvertime.length > 0 && overtime.length > 0 ? dynamicOvertime * overtime : 0;
            var total_leg = dynamicLegal.length > 0 && legal.length > 0 ? dynamicLegal * legal : 0;
            var total_sp = dynamicSpecial.length > 0 && special.length > 0 ? dynamicSpecial * special : 0;

            compute_total(total_ot, total_leg, total_sp);
        });
    });

    $('#save_payslip').click(function() {
        if ($('#net').val() <= 0) {
            alert('Invalid Payslip');
            return false;
        }
        $('#payslip-form').submit();
    });
});


</script>
<?= $this->endSection() ?>