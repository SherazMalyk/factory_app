<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Stock') }}</title>
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
                        <h1 class="m-0">Stock</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/stock">Stock</a></li>
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
                     <form action="/addStock" method="post" class="formData">
                        @csrf
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">Product Name</div>
                                 <select name="product_id" id="product_id" required class="form-control  product_idS2">
                                 </select>
                              </div><!-- form-group -->
                           </div><!--col-6-->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">Layer Name</div>
                                 <select name="layer_id" id="layer_id" required class="form-control form-contrl-sm layer_idS2">
                                 </select>
                              </div><!-- form-group -->
                           </div>{{-- col-md-6 --}}
                        </div> {{-- row --}}
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">Option Name</div>
                                 <div class="input-group mb-2">
                                    <select name="part_id" id="part_id" required class="form-control part_idS2">
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text" id="weight"> Weight </div>
                                    </div>
                                 </div>
                              </div><!-- form-group -->
                              <div class="form-group">
                                 <div class="label">Stock Status</div>
                                 <select name="stock_status" id="stock_status" required class="form-control form-contrl-sm">
                                    <option value="">--Select--</option>
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                 </select>
                              </div><!-- form-group -->
                           </div><!--col-6-->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">Quantity</div>
                                 <input id="stock_qty" required placeholder="2000, 3000 etc." class="form-control form-contrl-sm " type="number" name="stock_qty">
                              </div><!-- form-group -->
                              <div class="form-group">
                                 <div class="label">Description</div>
                                 <input id="stock_description" class="form-control form-contrl-sm " type="text" name="stock_description">
                                 {{-- <textarea name="stock_description" rows="1" id="stock_description" class="form-control "></textarea> --}}
                              </div><!-- form-group -->
                           </div> {{-- col --}}
                        </div><!--row-->
                        <button class="btn btn-info btn-sm center" type="submit">Submit</button>
                     </form>
                     <br>
                    <div class="row">
                      <div class="col-md-12">
                        <table id="stockTable" class="table table-sm">
                          <thead>
                            <tr>
                              <th>ID#</th>
                              <th>Product Name</th>
                              <th>Layer Name</th>
                              <th>Option Name</th>
                              <th>Option Qty</th>
                              <th>Created By</th>
                              <th>Description</th>
                              <th>Stock Status</th>
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
         var stockTable
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
                     $('.formData').attr('action','/addStock')
                     stockTable.ajax.reload(null, false)
                     $('#product_id').val('').trigger("change");
                     $('#layer_id').val('').trigger("change");
                     $('#part_id').val('').trigger("change");
                     $('#weight').html('Weight')
                     Toast.fire({
                     icon: response.sts,
                     title: response.msg
                     })
                  }//success
               });//ajax Call
            })//formData

            stockTable = $("#stockTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            ajax: ({
               type: "post",
               url: "/loadStock",
               data: "data",
               dataType: "json",
            }),
            columns: [
               { data : 'stock_id' },
               { data : 'product_id' },
               { data : 'layer_id' },
               { data : 'part_id' },
               { data : 'stock_qty' },
               { data : 'created_by' },
               { data : 'stock_description' },
               { data : 'stock_status' },
               { data : 'action' },
            ]
            })//Datatable

            $(document).on('click', '.editStock', function(){
            var stock_id = $(this).attr('id')
            console.log(stock_id);
            $.ajax({
               type: "POST",
               url: "/editStock",
               data: {stock_id: stock_id},
               dataType: "json",
               success: function (response) {
                  $('.formData').attr('action','updateStock/'+response[0].stock_id)
                  $('#stock_status').val(response[0].stock_status)
                  $('#stock_description').val(response[0].stock_description)

                  var product = new Option(response[1].text, response[1].id, true, true);
                  $("#product_id").append(product).trigger('change');
                  
                  var layer = new Option(response[2].text, response[2].id, true, true);
                  $("#layer_id").append(layer).trigger('change');

                  var part = new Option(response[3].text, response[3].id, true, true);
                  $("#part_id").append(part).trigger('change');

                  $('#stock_qty').val(response[0].stock_qty)                  
                  console.log($('.formData').attr('action'));
               }
            });
            })//editLayer

            $(document).on('click', '.deleteStock', function(){
            var stock_id = $(this).attr('id')
            var i = confirm('Do You Really Want To Delete')
            if (i) {
               $.ajax({
                  type: "post",
                  url: "/deleteStock",
                  data: {stock_id: stock_id},
                  dataType: "json",
                  success: function (response) {
                  $('.formData')[0].reset()
                  $('.layer_idS2').val('').trigger("change");
                  $('#layer_id').val('').trigger("change");
                  $('#part_id').val('').trigger("change");
                  Toast.fire({
                     icon: response.sts,
                     title: response.msg
                  })
                  stockTable.ajax.reload(null, false)
                  }//success
               });//ajaxCall
            }//if
            })//Delete
            
            $('.product_idS2').select2({
            theme: 'bootstrap4',
            ajax:({
               url:'/stockLoadProductS2',
               dataType:'json',
               type:'post',
               data: function(action)
                  {
                  return {
                     action: '/stockLoadProductS2',
                     searchTerm: action.term
                  };
                  },
               processResults:function(response){
                  return {results: response};
               }
            })//ajax
            })//ProductSelect2

            $('.layer_idS2').select2({
            theme: 'bootstrap4',
            ajax:({
               url:'/StockloadLayerS2',
               dataType:'json',
               type:'post',
               data: function(action)
                  {
                  return {
                     action: '/StockloadLayerS2',
                     searchTerm: action.term,
                     product_id: $('#product_id').val()
                  };
                  },
               processResults:function(response){
                  return {results: response};
               }
            })//ajax
            })//Select2

            $('.part_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/stockLoadPartS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                        return {
                        action: '/stockLoadPartS2',
                        searchTerm: action.term,
                        layer_id: $('#layer_id').val(),
                        product_id: $('#product_id').val()
                        };
                     },
                  processResults:function(response)
                     {
                        return {results: response};
                     }
               })//ajax
            })//partSelect2

            $(document).on('change', '.part_idS2', function(){
               var part_id = $(this).val()
               console.log(part_id);
               if (part_id != null) {
                  $.ajax({
                     type: "post",
                     url: "/getWeight",
                     data: {part_id: part_id},
                     dataType: "json",
                     success: function (response) {
                        $('#weight').html(response)
                     }
                  });  
               }
            })

         })//Document Ready Function
      </script>
  </body>
</html>
