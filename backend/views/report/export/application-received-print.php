<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Application Received Report';
?>
<html>

<head>
    <title><?= $this->title ?></title>
    <style>
    table,
    th,
    td {
        border: 1px solid black;
    }

    @media print {
        .printbtwrap {
            display: none;
        }
    }
    </style>
</head>

<body>
    <h2><?= $this->title ?></h2>
    <table class="table">
        <thead>
            <tr>
                <th>Sr.No.</th>
                <th>Advnumber</th>
                <th>Advt. Name</th>
                <th>Total Applications Received</th>
                <th>Paid Applications</th>
                <th>Unpaid Applications</th>
                <th>Cancelled Applications</th>
                <th>Reapply Applications</th>
                <th>Online Paid Consumed Payment</th>
                <th>Online Paid Unconsumed Payment</th>
                <th>Online Cancelled Consumed Payment</th>
                <th>Online Cancelled Unconsumed Payment</th>
                <th>Online Reapply Consumed Payment</th>
                <th>Online Reapply Unconsumed Payment</th>
                <th>Exempted</th>
                <th>Cancelled Exempted</th>
                <th>Exam Fees</th>
            </tr>
        </thead>
        <tbody>
            <?php
                                if (!empty($records)):
                                        $total=[];
                                        $total['total_applicants'] = 0;
                                        $total['paid'] = 0;
                                        $total['unpaid'] = 0;
                                        $total['cancelled'] = 0;
                                        $total['re_applied'] = 0;
                                        $total['paid_consumed_payment'] = 0;
                                        $total['paid_unconsumed_payment'] = 0;
                                        $total['cancelled_consumed_payment'] = 0;
                                        $total['cancelled_unconsumed_payment'] = 0;
                                        $total['reapplied_consumed_payment'] = 0;
                                        $total['reapplied_unconsumed_payment'] = 0;
                                    foreach ($records as $key => $record):
                                        echo "<tr data-key='1'>";
                                        echo "<td>" . ($key + 1) . "</td>";
                                        echo "<td>" . $record['advt_no'] . "</td>";
                                        echo "<td>" . $record['advt_name'] . "</td>";
                                        echo "<td>" . $record['total_applicants'] . "</td>";
                                        echo "<td>" . $record['paid'] . "</td>";
                                        echo "<td>" . $record['unpaid'] . "</td>";
                                        echo "<td>" . $record['cancelled'] . "</td>";
                                        echo "<td>" . $record['re_applied'] . "</td>";
                                        echo "<td>" . $record['paid_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['paid_unconsumed_payment'] . "</td>";
                                        echo "<td>" . $record['cancelled_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['cancelled_unconsumed_payment'] . "</td>";
                                        echo "<td>" . $record['reapplied_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['reapplied_unconsumed_payment'] . "</td>";
                                        echo "<td>0</td>";
                                        echo "<td>0</td>";
                                        echo "<td>0</td>";
                                        echo "</tr>";

                                        $total['total_applicants'] = isset($record['total_applicants']) ? ($total['total_applicants'] + $record['total_applicants']) : $total['total_applicants'];
                                        $total['paid'] = isset($record['paid']) ? ($total['paid'] + $record['paid']) : $total['paid'];
                                        $total['unpaid'] = isset($record['unpaid']) ? ($total['unpaid'] + $record['unpaid']) : $total['unpaid'];
                                        $total['cancelled'] = isset($record['cancelled']) ? ($total['cancelled'] + $record['cancelled']) : $total['cancelled'];
                                        $total['re_applied'] = isset($record['re_applied']) ? ($total['re_applied'] + $record['re_applied']) : $total['re_applied'];
                                        $total['paid_consumed_payment'] = isset($record['paid_consumed_payment']) ? ($total['paid_consumed_payment'] + $record['paid_consumed_payment']) : $total['paid_consumed_payment'];
                                        $total['paid_unconsumed_payment'] = isset($record['paid_unconsumed_payment']) ? ($total['paid_unconsumed_payment'] + $record['paid_unconsumed_payment']) : $total['paid_unconsumed_payment'];
                                        $total['cancelled_consumed_payment'] = isset($record['cancelled_consumed_payment']) ? ($total['cancelled_consumed_payment'] + $record['cancelled_consumed_payment']) : $total['cancelled_consumed_payment'];
                                        $total['cancelled_unconsumed_payment'] = isset($record['cancelled_unconsumed_payment']) ? ($total['cancelled_unconsumed_payment'] + $record['cancelled_unconsumed_payment']) : $total['cancelled_unconsumed_payment'];
                                        $total['reapplied_consumed_payment'] = isset($record['reapplied_consumed_payment']) ? ($total['reapplied_consumed_payment'] + $record['reapplied_consumed_payment']) : $total['reapplied_consumed_payment'];
                                        $total['reapplied_unconsumed_payment'] = isset($record['reapplied_unconsumed_payment']) ? ($total['reapplied_unconsumed_payment'] + $record['reapplied_unconsumed_payment']) : $total['reapplied_unconsumed_payment'];
                                    endforeach;
                                    echo "<tr>";
                                    echo "<td colspan='3'>Total</td>";
                                    echo "<td>".$total['total_applicants']."</td>";
                                    echo "<td>".$total['paid']."</td>";
                                    echo "<td>".$total['unpaid']."</td>";
                                    echo "<td>".$total['cancelled']."</td>";
                                    echo "<td>".$total['re_applied']."</td>";
                                    echo "<td>".$total['paid_consumed_payment']."</td>";
                                    echo "<td>".$total['paid_unconsumed_payment']."</td>";
                                    echo "<td>".$total['cancelled_consumed_payment']."</td>";
                                    echo "<td>".$total['cancelled_unconsumed_payment']."</td>";
                                    echo "<td>".$total['reapplied_consumed_payment']."</td>";
                                    echo "<td>".$total['reapplied_unconsumed_payment']."</td>";
                                    echo "<td>0</td>";
                                    echo "<td>0</td>";
                                    echo "<td>0</td>";
                                    echo "</tr>";
                                else:
                                    echo "<tr><td colspan='17'>No records found!</td></tr>";
                                endif;
                                ?>
        </tbody>
    </table>
    <p style="text-align: center;">
        <a onclick="window.print()" class="printbtwrap" href="javascript:;">PRINT</a>
        <a class="printbtwrap" href="/report/export/application-received">| RESET</a>
    </p>
</body>

</html>