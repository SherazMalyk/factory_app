<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fas fa-globe"></i> Factory App
          <small class="float-right"> Date: {{ date('d-m-Y') }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <div id="createdBy">
          
        </div>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <div id="supplier">

        </div>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <br>
        <b>Order ID:</b> 4F3S8J<br>
        <b>Paid Through:</b> <span id="paidThrough" class="text-capitalize"></span><br>
        <span id="accountDet">

        </span>
        <br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Qty</th>
            <th>Product</th>
            <th>Serial #</th>
            <th>Description</th>
            <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>1</td>
            <td>Call of Duty</td>
            <td>455-981-221</td>
            <td>El snort testosterone trophy driving gloves handsome</td>
            <td>$64.50</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Need for Speed IV</td>
            <td>247-925-726</td>
            <td>Wes Anderson umami biodiesel</td>
            <td>$50.00</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Monsters DVD</td>
            <td>735-845-642</td>
            <td>Terry Richardson helvetica tousled street art master</td>
            <td>$10.70</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Grown Ups Blue Ray</td>
            <td>422-568-642</td>
            <td>Tousled lomo letterpress</td>
            <td>$25.99</td>
          </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        <p class="lead">Payment Methods:</p>
        <img src="../../dist/img/credit/visa.png" alt="Visa">
        <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
        <img src="../../dist/img/credit/american-express.png" alt="American Express">
        <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
          Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr
          jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
        </p>
      </div>
      <!-- /.col -->
      <div class="col-6">
        <p class="lead">Amount Due 2/22/2014</p>

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>$250.30</td>
            </tr>
            <tr>
              <th>Tax (9.3%)</th>
              <td>$10.34</td>
            </tr>
            <tr>
              <th>Shipping:</th>
              <td>$5.80</td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>$265.24</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
@include('layouts.footer')
<script>
    $(document).ready(function () {
        var stock_id = '{{@$stock_id}}'
        $.ajax({
            type: "post",
            url: "/invoiceData",
            data: {stock_id:stock_id},
            dataType: "json",
            success: function (response) {
                console.log(response);
                $('#createdBy').html(response[0])
                $('#supplier').html(response[1])
                $('#paidThrough').html(response[2].account_type)
                if (response[2].account_type == 'bank') {
                    $('#accountDet').html('<b>Account Name:</b> <span id="paidThrough" class="text-capitalize"> '+response[2].account_name+'</span><br><b>Account No:</b> <span id="paidThrough" class="text-capitalize">'+response[2].account_no+'</span><br>')
                }
            }
        });
    });
  //window.addEventListener("load", window.print());
</script>
</body>
</html>
