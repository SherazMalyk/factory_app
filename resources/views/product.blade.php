<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Products') }}</title>
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
                        <h1 class="m-0">Products</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/product">Products</a></li>
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
                  <div class="card-header">
                      <p class="m-0" style="text-align:center;" id="editmode"></p>
                  </div><!--card-header-->
                  <div class="card-body">
                    <form action="/ProductController" method="post" class="formData">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <div class="label">Product Name</div>
                              <input id="product_name" required class="form-control" type="text" name="product_name">
                            </div><!-- form-group -->
                        </div><!--col-6-->
                          <div class="col-md-6">
                            <div class="form-group">
                              <div class="label">Product Status</div>
                              <select name="product_status" id="product_status"  required class="form-control">
                                <option value="">--SELECT--</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                              </select>
                            </div><!-- form-group -->
                          </div><!--col--->
                      </div><!--row-->
                      <div class="row">
                        <div class="col-md-3"></div>{{-- col --}}
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="label">Product Type</div>
                            <select name="product_type" id="product_type"  required class="form-control">
                              <option value="">--SELECT--</option>
                              <option value="raw">Raw</option>
                              <option value="product">Product</option>
                            </select>
                          </div><!-- form-group -->  
                        </div>{{-- col --}}
                      </div>{{-- row --}}
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group add_row" count="1">
                            <label>Select Layers & Options</label>
                            <button type="button" onclick="addRow();" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                          </div><!-- form-group-->
                          <button class="btn btn-info btn-sm" type="submit">Submit</button>
                        </div><!-- col -->
                      </div><!-- row -->
                    </form>
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
          var productTable
          $(document).ready( function() {

            addRow();

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
                      $('#editmode').html('')
                        $('.formData')[0].reset()
                        $('.formData').attr('action','/ProductController')
                        $('.layerS2').val('').trigger("change");
                        $('.partS2').val('').trigger("change");
                        var count = $(".add_row").attr('count');
                        count --;
                        while (count != 1) {
                          deleteRow(count);
                          count --;
                        }
                        Toast.fire({
                            icon: response.sts,
                            title: response.msg
                        })
                        window.location.href='/productDetail'
                    }//success
                });//ajax Call
            })//formData

            var product_id = '{{@$product_id}}'
            if (product_id != '') {
              $('#editmode').html('<span class="alert alert-light"><span class="far fa-edit"></span> Edit Mode</span>')
              $.ajax({
                type: "post",
                url: "/editProduct",
                data: {product_id: product_id},
                dataType: "json",
                success: function (response) {
                  $('.formData').attr('action', '/updateProduct/'+response[0].product_id)
                  $('#product_name').val(response[0].product_name)
                  $('#product_status').val(response[0].product_status)
                  $('#product_type').val(response[0].product_type)
                  deleteRow(1)
                  //console.log(response);

                  $.each(response[2], function(index, val) {
                    addRow()
                    //console.log(response[1][index].product_detail_id);
                    $("#product_detail_id_"+(index+2)).val(response[1][index].product_detail_id)
                    var option = new Option(val.text, val.id, true, true);
                    $("#layerS2_"+(index+2)).append(option).trigger('change');
                    
                    var option = new Option(response[3][index].text, response[3][index].id, true, true);
                    $("#partS2_"+(index+2)).append(option).trigger('change');
                  });


                  /* for (let index = 0; index < response[1].length; index++) {
                    addRow()
                    var option = new Option(response[2][index].text, response[2][index].id, true, true);
                    console.log(option);
                    $('#layerS2_'+index).append(option).trigger("change");
                  } */
                }
              });
            }
          
          })//Document Ready Function

          function addRow() {
            var count = $(".add_row").attr('count')
            var row = '<div class="row dynamic_row_'+count+' form-group">\
                        <div class="col-md-6">\
                          <input id="product_detail_id_'+count+'" type="text" name="product_detail_id[]" style="display:none;">\
                          <select class="form-control layerS2" required id="layerS2_'+count+'" onchange="changePartS2('+count+')" name="layerS2[]" style="width: 100%;">\
                          </select>\
                        </div> <!-- inner col -->\
                        <div class="col-md-5" id="partS2Col_'+count+'">\
                        </div>\
                        <div class="col-md-1">\
                          <button type="button" onclick="deleteRow('+count+')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>\
                          </div>\
                      </div>';
                      $('.add_row').append(row)
                      $('#layerS2_'+count).select2({
                        theme: 'bootstrap4',
                        ajax:({
                           url:'/loadLayerInS2',
                           dataType:'json',
                           type:'post',
                           data: function(action)
                              {
                                 return {
                                 action: '/loadLayerInS2',
                                 searchTerm: action.term
                                 };
                              },
                           processResults:function(response){
                              return {results: response};
                           }
                        })//ajax
                     })//layerS2_

            count++;
            $('.add_row').attr('count', count);
            // $('.add_row').attr('count', count).append(row)
          }//Addrow

          function changePartS2(count) {
            var layer_id = $('#layerS2_'+count).val()
            var  partS2Col = '<select class="form-control partS2" required id="partS2_'+count+'" name="partS2[]" style="width: 100%;"></select>'
            $('#partS2Col_'+count).html(partS2Col)
            $('#partS2_'+count).select2({
                        theme: 'bootstrap4',
                        ajax:({
                           url:'/loadPartInS2',
                           dataType:'json',
                           type:'post',
                           data: function(action)
                              {
                                return {
                                action: '/loadPartInS2',
                                searchTerm: action.term,
                                layer_id: layer_id
                                }; 
                              },
                           processResults:function(response){
                              return {results: response};
                           }
                        })//ajax
                     })//partS2_
         }//changePartS2


         function deleteRow(count) {
            $(".dynamic_row_"+count).remove();
         }//DeleteRow
          
      </script>
  </body>
</html>
