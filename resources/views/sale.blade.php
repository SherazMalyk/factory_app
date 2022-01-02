<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Purchase') }}</title>
  </head>
  <body class="sidebar-mini sidebar-collapsed layout-fixed sidebar-closed sidebar-collapse">
    <div class="wrapper">
        
      <!-- Navbar -->
      @include('layouts.topbar')

      <!-- Main Sidebar Container -->
      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sale</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/sale">Sale</a></li>
                        </ol>
                    </div><!-- col -->
                </div><!-- row -->
            </div><!-- container-fluid -->
        </div><!-- content-header -->

        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary card-outline">
                  <div class="card-body">
                     <form action="/addSale" method="post" class="formData">
                        @csrf
                        <div class="row">
                           <div class="col-md-8">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <div class="label">Product Name</div>
                                       <select name="product_id" id="product_id" required class="form-control  product_idS2">
                                       </select>
                                    </div><!-- form-group -->
                                 </div>{{-- inner-col --}}
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <div class="label">Customer Name</div>
                                       <select name="customer_id" id="customer_id" required class="form-control customer_idS2">
                                       </select>
                                    </div><!-- form-group -->
                                 </div>{{-- inner-col --}}
                              </div>{{-- inner-row --}}

                              <div class="row">
                                 <div class="col-md-3"></div>
                                 <div class="col-md-6" id="customerAccounts">
      
                                 </div>{{-- col --}}
                              </div>{{-- row --}}

                              <div class="form-group">
                                 <div class="label">Product Quantity</div>
                                 <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                       <div class="btn btn-outline-secondary btn-minus">
                                         <i class="fa fa-minus"></i>
                                       </div>
                                     </div>
                                    <input id="stock_qty" required class="form-control " type="number"  name="stock_qty" min="0" step="1">
                                    <div class="input-group-append">
                                       <div class="btn btn-outline-secondary btn-plus">
                                          <i class="fa fa-plus"></i>
                                        </div>
                                       <div class="input-group-text" id="BgColor"> &emsp; &emsp;<span id="readyTosell"></span> <span id="r2sHTML">  &emsp; </span> Ready To Sell &emsp; &emsp; </div>
                                    </div>
                                 </div>
                              </div><!-- form-group -->
                              <div class="form-group">
                                 <div class="label">Description</div>
                                 <textarea name="tran_description" class="form-control" id="tran_description" rows="2"></textarea>
                              </div><!-- form-group -->
                           </div><!--col-8-->
                           <div class="col-md-4" style="background-color:#E6E6E6; border-radius:8px;box-shadow: 5px 9px 15px grey; padding:24px;">
                              <div class="form-row" style="padding-bottom:18px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <div class="label">Total Price</div>
                                 </div>
                                 <div class="col">
                                    <input name="sale_price" required id="sale_price" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                              <div class="form-row" style="padding-bottom:18px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <label>Previous Dues</label>
                                 </div>
                                 <div class="col">
                                    <input name="tran_prev_amount" readonly id="tran_prev_amount" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                              <div class="form-row" style="padding-bottom:18px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <label>Grand Total</label>
                                 </div>
                                 <div class="col">
                                    <input name="stock_gt_price" readonly id="stock_gt_price" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                              <span id="PrevDueGT"></span>
                              <div class="form-row" style="padding-bottom:18px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <div class="label">Paid Amount</div>
                                 </div>
                                 <div class="col">
                                    <input name="tran_paid_amount" required id="tran_paid_amount" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                              <div class="form-row" style="padding-bottom:26px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <label>Remaining Dues</label>
                                 </div>
                                 <div class="col">
                                    <input name="tran_due_amount" readonly id="tran_due_amount" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                           </div> {{-- col --}}
                        </div> {{-- row --}}
                        <button class="btn btn-info" type="submit">Submit</button>
                     </form>
                     <br>
                     <div class="row">
                        <section class="col-md-12">
                           <table class="table table-hover table-striped" id="saleTable">
                           <thead>
                              <tr>
                                 <th>ID#</th>
                                 <th>Product Name</th>
                                 <th>Layers</th>
                                 <th>Ready To Sell</th>
                              </tr>
                           </thead>
                           <tbody></tbody>
                           </table>
                        </section><!-- col -->
                    </div><!--row-->
                  </div><!--card-body-->
                </div><!--card-->
              </div><!--col-md-12 -->
            </div><!-- row -->
          </div><!-- container-fluid -->
        </div><!-- content -->
      </div><!-- content-wrapper -->
    </div><!-- wrapper -->

      <!-- REQUIRED SCRIPTS -->
      @include('layouts.footer')
      <script>
         var saleTable
         $(document).ready( function() {

            var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
            });

            $('.formData').on('submit', function(e){
               e.preventDefault()
               var sale_qty = $('#stock_qty').val()
               var r2s_qtyS = $('#readyTosell').html()
               var r2s_qtyI = parseInt(r2s_qtyS);
               if (sale_qty <= r2s_qtyI && sale_qty != 0) {
                  $.ajax({
                     type: "POST",
                     url: $(this).attr('action'),
                     processData: false,
                     contentType: false,
                     data: new FormData(this),
                     dataType: "json",
                     success: function (response) {
                        $('.formData')[0].reset()
                        $('.formData').attr('action','/addSale')
                        saleTable.ajax.reload(null, false)
                        $('#product_id').val('').trigger("change");
                        $('#customer_id').val('').trigger("change");
                        $('#customerAccounts').html('')
                        $('#readyTosell').html('')
                        $('#r2sHTML').html('&emsp; ')
                        $('#BgColor').css({'background-color':'#E9ECEF','color':'black'})
                        $('#tran_prev_amount').css({'background-color':'#E9ECEF','color':'black'})
                        $('#stock_gt_price').css({'background-color':'#E9ECEF','color':'black'})
                        $('#tran_due_amount').css({'background-color':'#E9ECEF','color':'black'})
                        Toast.fire({
                        icon: response.sts,
                        title: response.msg
                        })
                     }//success
                  });//ajax Call   
               }else{
                  Toast.fire({
                     icon: 'error',
                     title: ' &nbsp; Sorry! Only '+r2s_qtyI+' Products Are Ready TO Sell!',
                  })
               }
            })//formData

            saleTable = $("#saleTable").DataTable({
               "responsive": true,
               "lengthChange": false,
               "autoWidth": false,
               ajax: ({
                  type: "post",
                  url: "/saleTable",
                  data: "data",
                  dataType: "json",
               }),
               columns: [
                  { data : 'product_id' },
                  { data : 'product_name' },
                  { data : 'layers' },
                  { data : 'r2s' },
               ]
            })//Datatable
            
            $('.product_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/SaleLoadProductS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                     return {
                        action: '/SaleLoadProductS2',
                        searchTerm: action.term
                     };
                     },
                  processResults:function(response){
                     return {results: response};
                  }
               })//ajax
            })//ProductSelect2

            $('.customer_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/SaleLoadCustomerS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                        return {
                        action: '/SaleLoadCustomerS2',
                        searchTerm: action.term,
                        };
                     },
                  processResults:function(response)
                     {
                        return {results: response};
                     }
               })//ajax
            })//SupplierSelect2

            $(document).on('keyup','#sale_price', function (){
               var sale_price = $('#sale_price').val()
               var tran_paid_amount = $('#tran_prev_amount').val()
               var due = 0
               var due = (+sale_price) + (+tran_paid_amount);
               $('#stock_gt_price').val(due)

            })

            $(document).on('keyup','#tran_paid_amount', function (){
               $('#sale_price').trigger('keyup')
               var sale_price = $('#stock_gt_price').val()
               var tran_paid_amount = $('#tran_paid_amount').val()
               var due = sale_price - tran_paid_amount;
               $('#tran_due_amount').val(due)
            })

            $(document).on('change', '#product_id', function(){
               var product_id = $(this).val()
               if (product_id != null) {
                  $.ajax({
                     type: "post",
                     url: "/getR2Sproducts",
                     data: {product_id:product_id},
                     dataType: "json",
                     success: function (response) {
                        $('#readyTosell').html(response)
                        $('#r2sHTML').html(' &nbsp; Products Are &nbsp; ')
                        $('#BgColor').css({'background-color':'#343A40','color':'#fff'})
                     }
                  });
               }
            })//onChange #Product_id

            $(document).on('change', '#customer_id', function(){
               var user_id = $('#customer_id').val()
               if (user_id != null) {
                  $('#customerAccounts').html('<div class="form-group">\
                                 <div class="label">Customer Account</div>\
                                 <select name="account_id" id="account_id" required class="form-control">\
                                 </select>\
                              </div>')
                  $.ajax({
                     type: "post",
                     url: "/saleCustPrevD",
                     data: {user_id:user_id},
                     dataType: "json",
                     success: function (response) {
                        console.log(response);
                        $('#tran_prev_amount').val(response)
                        $('#tran_prev_amount').css({'background-color':'#343A40','color':'#fff'})
                        $('#stock_gt_price').css({'background-color':'#343A40','color':'#fff'})
                        $('#tran_due_amount').css({'background-color':'#343A40','color':'#fff'})
                     }
                  });

                  $('#account_id').select2({
                     theme: 'bootstrap4',
                     ajax:({
                        type: "post",
                        url: "/saleCustomerAcc",
                        dataType:'json',
                        data: function(action)
                           {
                              return {
                              action: '/saleCustomerAcc',
                              searchTerm: action.term,
                              user_id:user_id
                              };
                           },
                        processResults:function(response)
                           {
                              return {results: response};
                           }
                     })//ajax
                  })//SupplierSelect2
               }
            })//onchange

         })//Document Ready Function
      </script>
  </body>
</html>
