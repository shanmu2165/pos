
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
</head>
 
<body>
    <div id="container">
        <h1 style="text-align:center;"><?= $_SESSION['cart']['content'][0]->title; ?></h1>
        <hr style="width: 100%;background-color: #000;display: block;height: 2px;">
        <div id="body">
            <h2>Order ID#: <?= $trans_data[0]->id; ?></h2>
            <!--<p><b>DATE:</b> 04/11/2021 9:52 pm<p><br/>-->
            <p><b>DATE: </b><?= date('d/m/Y h:i a', strtotime($trans_data[0]->timestamp)); ?><p><br/>
            <p>Transaction completed at Theatre in POS Machine.</p>
            <h2>Customer Information</h2>
            <p><?= $trans_data[0]->name; ?><br/>
            <?= $trans_data[0]->email; ?><br/>
            <?= $trans_data[0]->phone; ?>
            </p><br/>
            <h2>Customer Information</h2>
            <table style="width:100%">
                <tr>
                    <th>Items</th>
                    <th>Quantity</th>
                </tr>
                <?php for($i = 1; $i<= $_SESSION['cart']['pcount']; $i++) {//echo"<pre>"; print_r($_SESSION['cart']); "<pre>"; die;
                    if($_SESSION['cart']['item'][$i]['qty'] > 0) {?>
                <tr>
                    <td><?= $_SESSION['cart']['content'][0]->title; ?> - <?= date('M d, Y',strtotime($_SESSION['cart']['item'][$i]['date'])); ?>, <?= date('h:i a',strtotime($_SESSION['cart']['item'][$i]['time'])); ?><br/><?= $_SESSION['cart']['item'][$i]['name']; ?>- Preferred</td>
                    <td><?= $_SESSION['cart']['item'][$i]['qty']; ?></td>
                </tr>
                <?php } } ?>
            </table><br/>
            <?php if($trans_data[0]->type == "Cash") { ?>
                <p>The customer's payment has been completed via Cash; transaction ID: <?= $trans_data[0]->randid; ?></p>
            <?php  } else { ?>
                <p>The customer's payment has been completed via amex **3002; Credit card transaction ID: ch_1IfFtFBoTggLZWcqXvX5tsP9</p>
            <?php } ?>
            
        </div>
    </div>
</body>
 
</html>