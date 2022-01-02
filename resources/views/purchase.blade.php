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
                        <h1 class="m-0">Purchase</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/purchase">Purchase</a></li>
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
                     <form action="/addPurchase" method="post" class="formData">
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
                                 </div>{{-- innerCol --}}
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <div class="label">Supplier Name</div>
                                       <select name="supplier_id" id="supplier_id" required class="form-control supplier_idS2">
                                       </select>
                                    </div><!-- form-group -->
                                 </div>{{-- innerCol --}}
                              </div>{{-- innerRow --}}
                              <div class="row">
                                 <div class="col-md-3"></div>
                                 <div class="col-md-6" id="supplierAccounts">

                                 </div>{{-- col --}}
                              </div>{{-- innerRow --}}
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <div class="label">Quantity</div>
                                       <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                             <div class="btn btn-outline-secondary btn-minus">
                                               <i class="fa fa-minus"></i>
                                             </div>
                                           </div>
                                          <input id="stock_qty" required class="form-control " type="number" name="stock_qty" min="0" step="1">
                                          <div class="input-group-append">
                                                <div class="btn btn-outline-secondary btn-plus">
                                                <i class="fa fa-plus"></i>
                                             </div>
                                          </div>    
                                       </div>
                                    </div><!-- form-group -->
                                 </div>{{-- col --}}
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <div class="label">Weight <small class="text-muted">per bag</small></div>
                                       <div class="input-group mb-2">
                                          <input id="stock_unit" required class="form-control " type="number" step="any" name="stock_unit">
                                          <div class="input-group-append">
                                             <div class="input-group-text">Grams</div>
                                          </div>
                                       </div>
                                    </div><!-- form-group -->
                                 </div><!--col-4-->
                                 <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <div class="label">Total Weight</div>
                                          <input id="totalWeight" class="form-control " type="number" step="any" name="totalWeight">
                                       </div><!-- form-group -->
                                    </div>{{-- col --}}
                                 </div>
                              </div><!--row-->
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <div class="label">Description</div>
                                       <textarea name="stock_description" class="form-control" id="stock_description"  rows="3"></textarea>
                                    </div><!-- form-group -->
                                 </div> {{-- col --}}
                              </div>{{-- row --}}      
                           </div><!--col-8-->
                           <div class="col-md-4" style="background-color:#E6E6E6; border-radius:8px;box-shadow: 5px 9px 15px grey; padding:24px;">
                              <div class="form-row" style="padding-bottom:18px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <div class="label">Total Price</div>
                                 </div>
                                 <div class="col">
                                    <input name="stock_price" required id="stock_price" type="number" class="form-control">
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
                              <div class="form-row" style="padding-bottom:125px">
                                 <div class="col-5" style="display:flex; flex-direction: row; justify-content: flex-end; align-items: center">
                                    <label>Remaining Dues</label>
                                 </div>
                                 <div class="col">
                                    <input name="tran_due_amount" readonly id="tran_due_amount" type="number" class="form-control">
                                 </div>
                              </div><!-- form-row -->
                           </div> {{-- col --}}
                        </div> {{-- row --}}
                        <button class="btn btn-info btn-sm center" type="submit">Submit</button>
                     </form>
                     <br>
                    <div class="row">
                      <div class="col-md-12">
                        <table id="purchaseTable" class="table table-sm">
                          <thead>
                            <tr>
                              <th>ID#</th>
                              <th>Product Name</th>
                              <th>Product Qty</th>
                              <th>Product Weight</th>
                              <th>Product Price</th>
                              <th>Paid Amount</th>
                              <th>Remaining Due</th>
                              <th>Supplier Name</th>
                              <th>Created By</th>
                              <th>Description</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div><!--col-12-->
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
         var purchaseTable
         $(document).ready( function() {

            var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
            });

            $('.formData').on('submit', function(e){
               e.preventDefault()
               $.ajax({
                  type: "POST",
                  url: $(this).attr('action'),
                  processData: false,
                  contentType: false,
                  data: new FormData(this),
                  dataType: "json",
                  success: function (response) {
                     $('.formData')[0].reset()
                     $('.formData').attr('action','/addPurchase')
                     purchaseTable.ajax.reload(null, false)
                     $('#product_id').val('').trigger("change");
                     $('#supplier_id').val('').trigger("change");
                     $('#supplierAccounts').html('')
                     $('#tran_prev_amount').css({'background-color':'#E9ECEF','color':'black'})
                     $('#stock_gt_price').css({'background-color':'#E9ECEF','color':'black'})
                     $('#tran_due_amount').css({'background-color':'#E9ECEF','color':'black'})
                     Toast.fire({
                     icon: response.sts,
                     title: response.msg
                     })
                  }//success
               });//ajax Call
            })//formData

            purchaseTable = $("#purchaseTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            ajax: ({
               type: "post",
               url: "/loadPurchase",
               data: "data",
               dataType: "json",
            }),
            columns: [
               { data : 'stock_id' },
               { data : 'product_id' },
               { data : 'stock_qty' },
               { data : 'stock_unit' },
               { data : 'stock_price' },
               { data : 'paid_amount' },
               { data : 'remaining_amount' },
               { data : 'supplier_id' },
               { data : 'created_by' },
               { data : 'stock_description' },
               { data : 'action' },
            ]
            })//Datatable

            $(document).on('click', '.editPurchase', function(){
               var stock_id = $(this).attr('id')
               $.ajax({
                  type: "POST",
                  url: "/editPurchase",
                  data: {stock_id: stock_id},
                  dataType: "json",
                  success: function (response) {
                     $('.formData').attr('action','updatePurchase/'+response[0].stock_id)
                     $('#stock_qty').val(response[0].stock_qty)
                     $('#stock_unit').val(response[0].stock_unit).trigger('keyup')
                     $('#stock_price').val(response[0].stock_price)
                     $('#stock_description').val(response[0].stock_description)

                     var product = new Option(response[1].text, response[1].id, true, true);
                     $("#product_id").append(product).trigger('change');
                     
                     var supplier = new Option(response[2].text, response[2].id, true, true);
                     $("#supplier_id").append(supplier).trigger('change');

                     var account = new Option(response[4].text, response[4].id, true, true);
                     $("#account_id").append(account).trigger('change');

                     $('#tran_paid_amount').val(response[3].tran_paid_amount)
                     $('#tran_due_amount').val(response[3].tran_due_amount)
                  }
               });
            })//editLayer

            $(document).on('click', '.deletePurchase', function(){
            var stock_id = $(this).attr('id')
            var i = confirm('Do You Really Want To Delete')
            if (i) {
               $.ajax({
                  type: "post",
                  url: "/deletePurchase",
                  data: {stock_id: stock_id},
                  dataType: "json",
                  success: function (response) {
                     $('.formData')[0].reset()
                     $('.formData').attr('action','/addPurchase')
                     purchaseTable.ajax.reload(null, false)
                     $('#product_id').val('').trigger("change");
                     $('#supplier_id').val('').trigger("change");
                     Toast.fire({
                        icon: response.sts,
                        title: response.msg
                     })
                  }//success
               });//ajaxCall
            }//if
            })//Delete
            
            $('.product_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/PurcLoadProductS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                     return {
                        action: '/PurcLoadProductS2',
                        searchTerm: action.term
                     };
                     },
                  processResults:function(response){
                     return {results: response};
                  }
               })//ajax
            })//ProductSelect2

            $('.supplier_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/PurcLoadSupplierS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                        return {
                        action: '/PurcLoadSupplierS2',
                        searchTerm: action.term,
                        };
                     },
                  processResults:function(response)
                     {
                        return {results: response};
                     }
               })//ajax
            })//SupplierSelect2

            $(document).on('keyup','#stock_unit', function (){
               var stock_qty = $('#stock_qty').val()
               var stock_unit = $('#stock_unit').val()
               var val = stock_unit*stock_qty
               $('#totalWeight').val(val)
            })

            $(document).on('keyup','#stock_qty', function (){
               var stock_qty = $('#stock_qty').val()
               var stock_unit = $('#stock_unit').val()
               var val = stock_unit*stock_qty
               $('#totalWeight').val(val)
            })

            $(document).on('keyup','#totalWeight', function (){
               var stock_qty = $('#stock_qty').val()
               var total = $(this).val()
               var val = total/stock_qty;
               $('#stock_unit').val(val)
            })

            $(document).on('keyup','#stock_price', function (){
               var stock_price = $('#stock_price').val()
               var tran_paid_amount = $('#tran_prev_amount').val()
               var due = 0
               var due = (+stock_price) + (+tran_paid_amount);
               $('#stock_gt_price').val(due)
            })

            $(document).on('keyup','#tran_paid_amount', function (){
               $('#stock_price').trigger('keyup')
               var stock_price = $('#stock_gt_price').val()
               var tran_paid_amount = $('#tran_paid_amount').val()
               var due = stock_price - tran_paid_amount;
               $('#tran_due_amount').val(due)
            })

            $(document).on('change', '#supplier_id', function(){
               var user_id = $('#supplier_id').val()
               if (user_id != null) {

                  $('#supplierAccounts').html('<div class="form-group">\
                                 <div class="label">Supplier Account</div>\
                                 <select name="account_id" id="account_id" required class="form-control">\
                                 </select>\
                              </div>')
                  /* $('#PrevDueGT').html('<div class="form-group">\
                                 <label>Previous Dues</label>\
                                 <input name="tran_due_amount" readonly id="tran_due_amount" type="number" class="form-control form-control-sm">\
                              </div>\
                              <div class="form-group">\
                                 <label>Grand Total</label>\
                                 <input name="stock_gt_price" readonly id="stock_gt_price" type="number" class="form-control form-control-sm">\
                              </div>') */
                  $.ajax({
                     type: "post",
                     url: "/purSuppPrevD",
                     data: {user_id:user_id},
                     dataType: "json",
                     success: function (response) {
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
                        url: "/purchaseSupAcc",
                        dataType:'json',
                        data: function(action)
                           {
                              return {
                              action: '/purchaseSupAcc',
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

               /* $.ajax({
                  data: {user_id:user_id},
                  success: function (response) {
                     var user = new Option(response.text, response.id, true, true);
                     console.log(response);
                     $("#user_id").append(user).trigger('change');
                  }
               });
             }) */  

         })//Document Ready Function
      </script>
  </body>
</html>
