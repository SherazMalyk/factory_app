<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Options') }}</title>
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
                        <h1 class="m-0">Options</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/part">Options</a></li>
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
                      <h5 class="m-0">Options</h5>
                  </div><!--card-header-->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <form action="/PartController" method="post" class="formData">
                        @csrf
                        <div class="form-group">
                          <div class="label">Option Name</div>
                            <input id="part_name" placeholder="front bari, back bari etc." required class="form-control " type="text" name="part_name">
                          </div><!-- form-group -->
                          <div class="form-group">
                            <div class="label">Option Status</div>
                            <select name="part_status" id="part_status"  required class="form-control ">
                              <option value="">--SELECT--</option>
                              <option value="1">Active</option>
                              <option value="0">Deactive</option>
                            </select>
                          </div><!-- form-group -->
                        </div><!--col-6-->
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="label">Layer Name</div>
                            <select name="layer_id" id="layer_id" required class="form-control  part_layer_idS2">
                            </select>
                          </div><!-- form-group -->
                          <div class="form-group">
                            <div class="label">Option Weight</div>

                            <div class="input-group mb-2">
                              <input id="part_unit" required placeholder="2000, 3000 etc." class="form-control " type="number" name="part_unit">
                              <div class="input-group-append">
                                <div class="input-group-text">Grams</div>
                              </div>
                            </div>
                      
                          </div><!-- form-group -->
                        </div><!--col-6-->
                      </div><!--row-->
                        <button class="btn btn-info btn-sm center" type="submit">Submit</button>
                      </form>
                      <br>
                    <div class="row">
                      <div class="col-md-12">
                        <table id="optionTable" class="table table-sm">
                          <thead>
                            <tr>
                              <th>ID#</th>
                              <th>Option Name</th>
                              <th>Option Model</th>
                              <th>Option Weight</th>
                              <th>Option Status</th>
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
        var optionTable
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
                $('.formData').attr('action','/PartController')
                optionTable.ajax.reload(null, false)
                $('.part_layer_idS2').val('').trigger("change");
                Toast.fire({
                  icon: response.sts,
                  title: response.msg
                })
              }//success
          });//ajax Call
          })//formData

          optionTable = $("#optionTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            ajax: ({
              type: "post",
              url: "/loadPart",
              data: "data",
              dataType: "json",
            }),
            columns: [
              { data : 'part_id' },
              { data : 'part_name' },
              { data : 'model' },
              { data : 'part_unit' },
              { data : 'status' },
              { data : 'action' },
            ]
          })//Datatable

          $(document).on('click', '.editPart', function(){
            var part_id = $(this).attr('id')
            $.ajax({
              type: "POST",
              url: "/editPart",
              data: {part_id: part_id},
              dataType: "json",
              success: function (response) {
                $('.formData').attr('action','updatePart/'+response[2])
                var option = new Option(response[1].text, response[1].id, true, true);
                console.log(response[1].text);
                $(".part_layer_idS2").append(option).trigger('change');
                $('#part_name').val(response[0].part_name)
                $('#part_status').val(response[0].part_status)
                $('#part_unit').val(response[0].part_unit)
              }
            });
          })//editLayer

          $(document).on('click', '.deletePart', function(){
            var part_id = $(this).attr('id')
            var i = confirm('Do You Really Want To Delete')
            if (i) {
              $.ajax({
                type: "post",
                url: "/deletePart",
                data: {part_id: part_id},
                dataType: "json",
                success: function (response) {
                  $('.formData')[0].reset()
                  $('.part_layer_idS2').val('').trigger("change");
                  Toast.fire({
                    icon: response.sts,
                    title: response.msg
                  })
                  optionTable.ajax.reload(null, false)
                }//success
              });//ajaxCall
            }//if
          })//Delete

          $('.part_layer_idS2').select2({
            theme: 'bootstrap4',
            ajax:({
              url:'/loadPartS2',
              dataType:'json',
              type:'post',
              data: function(action)
                {
                  return {
                    action: '/loadPartS2',
                    searchTerm: action.term
                  };
                },
              processResults:function(response){
                  return {results: response};
              }
            })//ajax
          })//Select2

        })//Document Ready Function
      </script>
  </body>
</html>
