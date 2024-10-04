<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .page {
            width: 21cm;
            height: 29.7cm;
            padding: 1cm;
            box-sizing: border-box;
            page-break-after: always;
        }
        .payslip {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin: 0.5%;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }
        .section-title {
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }
        dl {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }
        dt {
            font-weight: bold;
        }
        .earnings, .deductions {
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php foreach ($payslips as $details): ?>
    <div class="page">
        <?php for ($i = 0; $i < 4; $i++): ?>
        <div class="payslip">
            <div class="section-title">Employee's Payslip</div>
            
            <dl>
                <dt>Name:</dt>
                <dd><?= $details['name'] ?? 'N/A' ?></dd>
            </dl>
            <dl>
                <dt>Department:</dt>
                <dd><?= $details['department'] ?? 'N/A' ?></dd>
            </dl>
            <dl>
                <dt>Designation:</dt>
                <dd><?= $details['designation'] ?? 'N/A' ?></dd>
            </dl>
            <dl>
                <dt>Payroll:</dt>
                <dd><?= ($details['payroll_code'] ?? 'N/A') . " - " . ($details['payroll_name'] ?? 'N/A') ?></dd>
            </dl>
            <dl>
                <dt>Basic Rate/Week:</dt>
                <dd><?= isset($details['salary']) ? number_format($details['salary'], 2) : 'N/A' ?></dd>
            </dl>
            <dl>
                <dt>Late/Undertime (mins):</dt>
                <dd><?= isset($details['late_undertime']) ? (int)$details['late_undertime'] : 'N/A' ?></dd>
            </dl>
            <dl>
                <dt>Deduction:</dt>
                <dd><?= isset($details['late_undertime_deduction']) ? '₱' . number_format($details['late_undertime_deduction'], 2) : 'N/A' ?></dd>
            </dl>
            <div class="earnings">
                <div class="section-title">Earnings</div>
                <dl>
                    <dt>Regular Hours:</dt>
                    <dd><?= isset($details['present']) ? (int) $details['present'] : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Total:</dt>
                    <dd><?= isset($details['total_present']) ? number_format($details['total_present'], 2) : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Overtime Hours:</dt>
                    <dd><?= isset($details['ot_hr']) ? (int) $details['ot_hr'] : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Total:</dt>
                    <dd><?= isset($details['total_overtime']) ? number_format($details['total_overtime'], 2) : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Legal Hours:</dt>
                    <dd><?= isset($details['leg_hr']) ? (int) $details['leg_hr'] : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Total:</dt>
                    <dd><?= isset($details['total_legal']) ? number_format($details['total_legal'], 2) : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Special Hours:</dt>
                    <dd><?= isset($details['sp_hr']) ? (int) $details['sp_hr'] : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Total:</dt>
                    <dd><?= isset($details['total_special']) ? number_format($details['total_special'], 2) : 'N/A' ?></dd>
                </dl>
            </div>
            <div class="deductions">
                <div class="section-title">Deductions</div>
                <dl>
                    <dt>Withholding Tax:</dt>
                    <dd><?= isset($details['witholding_tax']) ? number_format($details['witholding_tax'], 2) : 'N/A' ?></dd>
                </dl>
                <dl>
                    <dt>Net:</dt>
                    <dd><?= isset($details['net']) ? number_format($details['net'], 2) : 'N/A' ?></dd>
                </dl>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    <?php endforeach; ?>
</body>
</html>
