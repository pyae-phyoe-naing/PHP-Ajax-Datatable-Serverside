<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css" />
    <title>PHP-Ajax-Datatable-Server-Side</title>
</head>

<body>
    <h4 class="text-center mt-3">Data Table Server-Side</h4>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            Add User
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <table class="table table-hover" id='datatable'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>City</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <script type="text/javascript">
        $('#datatable').DataTable({
            'serverSide': true,
            'processing': true,
            'paging': true,
            'order': [],
            "ajax": {
                "url": "fetch_data.php",
                "type": "POST"
            },
            'fnCreatedRow': function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'columnDefs': [{
                'target': [0, 5],
                'orderable': false
            }]
        });
        // Add User
        $(document).on('submit', '#saveUserForm', function(event) {
            event.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            if (name != '' && email != '' && phone != '' && city != '') {
                $.ajax({
                    url: 'add_user.php',
                    data: {
                        name: name,
                        email: email,
                        phone: phone,
                        city: city
                    },
                    type: 'post',
                    success: function(response) {
                        var data = JSON.parse(response)
                        status = data.status;
                        if (status == 'success') {
                            var table = $('#datatable').DataTable();
                            table.draw(); //add,delete,update => display to reflect these changes
                            alert('succcessfully user add');
                            $('#name').val('');
                            $('#email').val('');
                            $('#phone').val('');
                            $('#city').val('');
                            $('#addUserModal').modal('hide');
                        }
                    }
                })
            } else {
                alert('Please fill all the Required fields');
            }
        });
        // Update User
        $(document).on('click', '.editBtn', function(event) {
            var id = $(this).data('id');
            var trid = $(this).closest('tr').attr('id'); // get current parent tr id attribute use this
            // var trid = $('tr').attr('id'); // not get current parent tr id attribute

            $.ajax({
                url: 'get_single_user.php',
                data: {
                    id: id
                },
                type: 'post',
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#id').val(data.id);
                    $('#trid').val(trid);
                    $('#_name').val(data.name);
                    $('#_email').val(data.email);
                    $('#_phone').val(data.phone);
                    $('#_city').val(data.city);
                    $('#editUserModal').modal('show')
                }
            });
        });

        $(document).on('submit', '#updateUserForm', function(event) {
            event.preventDefault();
            var id = $('#id').val();
            var trid = $('#trid').val();
            var name = $('#_name').val();
            var email = $('#_email').val();
            var phone = $('#_phone').val();
            var city = $('#_city').val();

            $.ajax({
                url: 'update_user.php',
                data: {
                    id: id,
                    name: name,
                    email: email,
                    phone: phone,
                    city: city
                },
                type: 'post',
                success: function(response) {
                    var data = JSON.parse(response);
                    status = data.status;
                    if (status == 'success') {
                        var table = $('#datatable').DataTable();
                        var button = '<a href="javascript:void();" data-id="' + id + '" class="editBtn btn btn-sm btn-info">Edit</a> <a href="javascript:void();" data-id="' + id + '" class="deleteBtn btn btn-sm btn-danger mr-3" >Delete</a>';
                        var row = table.row("[id='" + trid + "']");
                        row.row("[id='" + trid + "']").data([id, name, email, phone, city, button]);
                        $('#editUserModal').modal('hide');
                    } else {
                        alert('Fail');
                    }
                }
            })
        })

        // Delete User
        $(document).on('click', '.deleteBtn', function(event) {
            var id = $(this).data('id'); //data-id=''
            if (confirm('Are you sure delete?')) {
                $.ajax({
                    url: 'delete_user.php',
                    data: {
                        id: id
                    },
                    type: 'post',
                    success: function(response) {
                        var data = JSON.parse(response);
                        var status = data.status;
                        if (status == 'success') {
                            $('#' + id).closest('tr').remove();
                        } else {
                            alert('failed');
                        }
                    }
                });
            }

        });
    </script>
    <!-- Add User Modal  -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id='saveUserForm' action="javascript:void();" method="POST">
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="number" name="phone" class="form-control" id="phone">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" name="city" class="form-control" id="city">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add User Modal End -->
    <!-- Edit User Modal  -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id='updateUserForm' action="javascript:void();" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="trid" name="trid">
                        <div class="mb-3 row">
                            <label for="_name" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="_name" id="_name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="_email" name="_email" class="form-control" id="_email">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="number" name="_phone" class="form-control" id="_phone">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" name="_city" class="form-control" id="_city">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add User Modal End -->
</body>

</html>