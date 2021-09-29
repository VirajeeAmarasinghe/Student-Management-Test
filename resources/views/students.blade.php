<!DOCTYPE html>

<html>

<head>

    <title>Student Management App</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

</head>

<body>

    

<div class="container">

    <h1>Student Management</h1>

    <a class="btn btn-success" href="javascript:void(0)" id="createNewStudent"> Create New Student</a>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="filter-by-date-of-birth">
        <label class="form-check-label" for="filter-by-date-of-birth">
          Filter of Date-of-Birth
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="filter-by-percentage">
        <label class="form-check-label" for="filter-by-percentage">
          Filter by Percentage
        </label>
      </div>
    <table class="table table-bordered data-table" id="data-table">

        <thead>

            <tr>

                <th>ID</th>
                <th>Profile Pic</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Percentage</th>
                <th width="280px">Action</th>

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

</div>

   

<div class="modal fade" id="ajaxModel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="modelHeading"></h4>

            </div>

            <div class="modal-body">

                <form id="studentForm" name="studentForm" class="form-horizontal" enctype="multipart/form-data">

                   <input type="hidden" name="id" id="id">

                    <div class="form-group">

                        <label for="name" class="col-sm-2 control-label">First Name</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="" maxlength="50" required="">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="last_name" class="col-sm-2 control-label">Last Name</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="" maxlength="50" required="">

                        </div>

                    </div>

                    
                    <div class="form-group">

                        <label for="date_of_birth" class="col-sm-2 control-label">Date of Birth</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="date-of-birth" name="date_of_birth" placeholder="Enter Date of Birth" value="" required="">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="percentage" class="col-sm-2 control-label">Percentage</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="percentage" name="percentage" placeholder="Percentage" value="" required="">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="percentage" class="col-sm-2 control-label">Profile Picture</label>

                        <div class="col-sm-12">

                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" value="" required="">

                        </div>

                    </div>
      

                    <div class="col-sm-offset-2 col-sm-10">

                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes

                     </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

    

</body>

    

<script type="text/javascript">

  $(function () {

     

      $.ajaxSetup({

          headers: {

              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

          }

    });

    

    var table = $('.data-table').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('students.index') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            
            {data: 'profile_picture', name: 'profile_picture',"render": function (data, type, row, meta) {
                            return '<img src="photos/'+data+'">';
                        }
                },
            
            

            {data: 'first_name', name: 'first_name'},

            {data: 'last_name', name: 'last_name'},
            {data: 'date_of_birth', name: 'date_of_birth'},
            {data: 'percentage', name: 'percentage'},
            
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]

    });

     

    $('#createNewStudent').click(function () {

        $('#saveBtn').val("create-student");

        $('#id').val('');

        $('#studentForm').trigger("reset");

        $('#modelHeading').html("Create New Student");

        $('#ajaxModel').modal('show');

    });

    

    $('body').on('click', '.editStudent', function () {

      var student_id = $(this).data('id');

      $.get("{{ route('students.index') }}" +'/' + student_id +'/edit', function (data) {

          $('#modelHeading').html("Edit Student");

          $('#saveBtn').val("edit-student");

          $('#ajaxModel').modal('show');

          $('#id').val(data.id);

          $('#first_name').val(data.first_name);

          $('#last_name').val(data.last_name);
          $('#date-of-birth').val(data.date_of_birth);
          $('#percentage').val(data.percentage);
          $('#profile_picture').val(data.profile_picture);
      })

   });

    

    $('#saveBtn').click(function (e) {

        e.preventDefault();

        $(this).html('Sending..');

        var postData=new FormData($("#studentForm")[0])

        $.ajax({

          data: postData,

          url: "{{ route('students.store') }}",

          type: "POST",

          dataType: 'json',
          cache:false,

        contentType: false,

        processData: false,


          success: function (data) {

     

              $('#studentForm').trigger("reset");

              $('#ajaxModel').modal('hide');

              table.draw();

         

          },

          error: function (data) {

              console.log('Error:', data);

              $('#saveBtn').html('Save Changes');

          }

      });

    });

    

    $('body').on('click', '.deleteStudent', function () {

     

        var student_id = $(this).data("id");

        confirm("Are You sure want to delete !");

      

        $.ajax({

            type: "DELETE",

            url: "{{ route('students.store') }}"+'/'+student_id,

            success: function (data) {

                table.draw();

            },

            error: function (data) {

                console.log('Error:', data);

            }

        });

    });

     

  });

  
  $("#filter-by-date-of-birth").click(function(e){ 
    var table = $('#data-table').DataTable();
 
 
 table
     .order( [ 4, 'desc' ] )
     .draw();
  });

  $("#filter-percentage").click(function(e){ 
    var table = $('#data-table').DataTable();
 
 
 table
     .order( [ 5, 'desc' ] )
     .draw();
  });

$('#date-of-birth').datepicker({  

   format: 'yyyy-mm-dd'

 });  

 

</script>

</html>