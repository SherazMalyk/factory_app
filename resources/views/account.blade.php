<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Account') }}</title>
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
                        <h1 class="m-0">Accounts</h1>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/account">Accounts</a></li>
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
                     <form action="/addAccount" method="post" class="formData">
                        @csrf
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">User Name</div>
                                 <select name="user_id" id="user_id" required class="form-control user_idS2">
                                 </select>
                              </div><!-- form-group -->
                           </div><!--col-6-->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="label">Account Status</div>
                                 <select name="account_status" id="account_status" required class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                              </div><!-- form-group -->
                           </div>{{-- col-md-6 --}}
                        </div> {{-- row --}}
                        <div class="row">
                           <div class="col-md-3">
                           </div>{{-- col --}}
                           <div class="col-md-6">
                            <div class="form-group">
                                <div class="label">Account Type</div>
                                <select name="account_type" id="account_type" required class="form-control">
                                   <option value="">--Select--</option>
                                   <option value="bank">Bank</option>
                                   <option value="cash">Cash</option>
                               </select>
                             </div><!-- form-group -->
                           </div><!--col-6-->
                           <div class="col-md-4">
                           </div>{{-- col --}}
                        </div><!--row-->
                        <div class="row" id="accountType">
                        </div>{{-- row --}}
                        <button class="btn btn-info btn-sm center" type="submit">Submit</button>
                     </form>
                     <br>
                    <div class="row">
                      <div class="col-md-12">
                        <table id="accountTable" class="table table-sm">
                          <thead>
                            <tr>
                              <th>ID#</th>
                              <th>User Name</th>
                              <th>Account Type</th>
                              <th>Account Name</th>
                              <th>Account No</th>
                              <th>Account Status</th>
                              <th>Created By</th>
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
         var accountTable
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
                     $('.formData').attr('action','/addAccount')
                     $('#user_id').val('').trigger("change");
                     $('#accountType').html('')
                     Toast.fire({
                        icon: response.sts,
                        title: response.msg
                     })
                     accountTable.ajax.reload(null, false)
                  }//success
               });//ajax Call
            })//formData

            accountTable = $("#accountTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            ajax: ({
               type: "post",
               url: "/loadAccount",
               data: "data",
               dataType: "json",
            }),
            columns: [
               { data : 'account_id' },
               { data : 'user_id' },
               { data : 'account_type' },
               { data : 'account_name' },
               { data : 'account_no' },
               { data : 'account_status' },
               { data : 'created_by' },
               { data : 'action' },
            ]
            })//Datatable

            $(document).on('click', '.editAccount', function(){
               var account_id = $(this).attr('id')
               $.ajax({
                  type: "POST",
                  url: "/editAccount",
                  data: {account_id: account_id},
                  dataType: "json",
                  success: function (response) {
                     $('.formData').attr('action','updateAccount/'+response[0].account_id)
                     $('#account_status').val(response[0].account_status)
                     $('#account_type').val(response[0].account_type).trigger('change')
                     $('#account_name').val(response[0].account_name)
                     $('#account_no').val(response[0].account_no)

                     var user = new Option(response[1].text, response[1].id, true, true);
                     $("#user_id").append(user).trigger('change');
                  }
               });
            })//editLayer

            $(document).on('click', '.deleteAccount', function(){
            var account_id = $(this).attr('id')
            var i = confirm('Do You Really Want To Delete')
            if (i) {
               $.ajax({
                  type: "post",
                  url: "/deleteAccount",
                  data: {account_id: account_id},
                  dataType: "json",
                  success: function (response) {
                     $('.formData')[0].reset()
                     $('.formData').attr('action','/addAccount')
                     accountTable.ajax.reload(null, false)
                     $('#user_id').val('').trigger("change");
                     $('#accountType').html('')
                     Toast.fire({
                        icon: response.sts,
                        title: response.msg
                     })
                  }//success
               });//ajaxCall
            }//if
            })//Delete

            $('.user_idS2').select2({
               theme: 'bootstrap4',
               ajax:({
                  url:'/AccLoadUserS2',
                  dataType:'json',
                  type:'post',
                  data: function(action)
                     {
                        return {
                        action: '/AccLoadUserS2',
                        searchTerm: action.term,
                        };
                     },
                  processResults:function(response)
                     {
                        return {results: response};
                     }
               })//ajax
            })//SupplierSelect2

            $(document).on('change','#account_type', function() {
                var val = $('#account_type').val()
                if (val == 'bank') {
                    $('#accountType').html('<div class="col-md-6">\
                              <div class="form-group">\
                                 <div class="label">Account Name</div>\
                                 <input name="account_name" required id="account_name" type="text" class="form-control">\
                              </div>\
                           </div>\
                           <div class="col-md-6">\
                              <div class="form-group">\
                                 <div class="label">Account Number</div>\
                                 <input name="account_no" required id="account_no" type="text" class="form-control">\
                              </div>\
                           </div>')
                }else{
                    $('#accountType').html('')
                    $('#account_name').val('')
                    $('#account_no').val('')
                }
            })

         })//Document Ready Function
      </script>
  </body>
</html>
