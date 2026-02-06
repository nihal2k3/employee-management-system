<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Management</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div id="s_notify" class="alert alert-success d-none"></div>
        <div id="d_notify" class="alert alert-danger d-none"></div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employees</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal">
                + Add Employee
            </button>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="search" class="form-control" placeholder="Search by name, email, phone">
            </div>
        </div>


        <!----------------- Employee Table ----------->
        <table class="table table-bordered table-striped" id="employeeTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="employeeData">

            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-end" id="paginationLinks"></ul>
        </nav>
    </div>


    <!-- Add Employee Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="employeeForm">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="name">Name :</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <span class="text-danger name_err">

                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="email">Email :</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Email">
                                    <span class="text-danger email_err">

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="phone">Phone :</label>
                                    <input type="number" name="phone" id="phone" class="form-control"
                                        placeholder="Phone">
                                    <span class="text-danger phone_err">

                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="salary">Salary :</label>
                                    <input type="number" name="salary" id="salary" class="form-control"
                                        placeholder="Salary">
                                    <span class="text-danger salary_err">

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editEmployeeForm">
                <input type="hidden" name="id" id="employee_id">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-2">
                            <label for="name">Name :</label>
                            <input type="text" name="name" id="e_name" class="form-control"
                                placeholder="Name">
                            <span class="text-danger e_name_err"></span>
                        </div>

                        <div class="mb-2">
                            <label for="email">Email :</label>
                            <input type="email" name="email" id="e_email" class="form-control"
                                placeholder="Email">
                            <span class="text-danger e_email_err"></span>
                        </div>

                        <div class="mb-2">
                            <label for="phone">Phone :</label>
                            <input type="number" name="phone" id="e_phone" class="form-control"
                                placeholder="Phone">
                            <span class="text-danger e_phone_err"></span>
                        </div>

                        <div class="mb-2">
                            <label for="salary">Salary :</label>
                            <input type="number" name="salary" id="e_salary" class="form-control"
                                placeholder="Salary">
                            <span class="text-danger e_salary_err"></span>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary update_btn">Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <script>
        function showErrorMessage(msg) {
            const el = document.getElementById('d_notify');
            el.innerText = msg;
            el.classList.remove('d-none');
            setTimeout(() => {
                el.classList.add('d-none');
            }, 3000);
        }

        function showSuccessMessage(msg) {
            const el = document.getElementById('s_notify');
            el.innerText = msg;
            el.classList.remove('d-none');
            setTimeout(() => {
                el.classList.add('d-none');
            }, 3000);
        }
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!---------------------- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* CSRF Setup */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let baseUrl = "{{ route('employees.list') }}";
        loadEmployees(baseUrl);
        $("#employeeForm").submit(function(e) {
            e.preventDefault();
            $('#email_error').text('');
            $('#password_error').text('');

            if (!$('#name').val()) {
                $('.name_err').text('Name Required');
                return false;
            }

            if (!$('#email').val()) {
                $('.email_err').text('Email Required');
                return false;
            }

            if (!$('#phone').val() || $('#phone').val().length !== 10) {
                $('.phone_err').text('Phone Required and must be of 10 digit only');
                return false;
            }

            if (!$('#salary').val()) {
                $('.salary_err').text('Salary Required');
                return false;
            }
            $.ajax({
                url: "{{ route('storeEmployee') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(response) {
                    if (response.status) {
                        $('#employeeModal').hide();
                        showSuccessMessage(response.message);
                        loadEmployees(baseUrl);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;

                    if (errors.name) {
                        $('#name').addClass('is-invalid');
                        $('.name_err').text(errors.name[0]);
                    }

                    if (errors.email) {
                        $('#email').addClass('is-invalid');
                        $('.email_err').text(errors.email[0]);
                    }

                    if (errors.phone) {
                        $('#phone').addClass('is-invalid');
                        $('.phone_err').text(errors.phone[0]);
                    }

                    if (errors.salary) {
                        $('#salary').addClass('is-invalid');
                        $('.salary_err').text(errors.salary[0]);
                    }
                }
            });
        });

        function loadEmployees(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    let rows = '';

                    let startIndex = (res.current_page - 1) * res.per_page;
                    if (res.data.length === 0) {
                        rows = `<tr>
                                    <td colspan="9" align="center">No records found</td>
                                </tr>`;
                    } else {
                        $.each(res.data, function(index, emp) {
                            rows += `
                        <tr>
                            <td>${startIndex + index + 1}</td>
                            <td>${emp.name}</td>
                            <td>${emp.email}</td>
                            <td>${emp.phone}</td>
                            <td>${emp.salary}</td>
                            <td>${emp.status}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtn"
                                    data-id="${emp.id}">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger deleteBtn"
                                    data-id="${emp.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                        });
                    }

                    $('#employeeData').html(rows);

                    let pagination = '';

                    $.each(res.links, function(index, link) {
                        if (link.url !== null) {
                            pagination += `
                        <li class="page-item ${link.active ? 'active' : ''}">
                            <a class="page-link" href="#" data-url="${link.url}">
                                ${link.label}
                            </a>
                        </li>`;
                        }
                    });

                    $('#paginationLinks').html(pagination);
                },
                error: function() {
                    alert('Failed');
                }
            });
        }

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            loadEmployees(url);
        });

        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');

            if (!confirm('Are you sure you want to delete this employee?')) return;

            $.ajax({
                url: "{{ route('employees.delete') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        showSuccessMessage(response.message);
                        loadEmployees(baseUrl);
                    } else {
                        showErrorMessage(response.message);
                    }
                }
            });
        });

        $(document).on('click', '.editBtn', function() {

            let id = $(this).data('id');

            if (!id) {
                alert('Invalid employee ID');
                return;
            }

            $.ajax({
                url: "{{ route('employees.edit') }}",
                type: 'POST',
                data: {
                    id: id
                },
                success: function(emp) {
                    $('#employee_id').val(emp.id);
                    $('#e_name').val(emp.name);
                    $('#e_email').val(emp.email);
                    $('#e_phone').val(emp.phone);
                    $('#e_salary').val(emp.salary);

                    $('.text-danger').html('');

                    $('#editEmployeeModal').modal('show');
                },
                error: function() {
                    alert('Failed to load employee data');
                }
            });
        });

        $("#editEmployeeForm").submit(function(e) {
            e.preventDefault();
            $('#email_error').text('');
            $('#password_error').text('');

            if (!$('#e_name').val()) {
                $('.e_name_err').text('Name Required');
                return false;
            }

            if (!$('#e_email').val()) {
                $('.e_email_err').text('Email Required');
                return false;
            }

            if (!$('#e_phone').val() || $('#e_phone').val().length !== 10) {
                $('.e_phone_err').text('Phone number must be 10 digits');
                return false;
            }

            if (!$('#e_salary').val()) {
                $('.e_salary_err').text('Salary Required');
                return false;
            }
            $.ajax({
                url: "{{ route('employees.update') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(response) {
                    if (response.status) {
                        $('#editEmployeeModal').hide();
                        showSuccessMessage(response.message);
                        loadEmployees(baseUrl);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;

                    if (errors.name) {
                        $('#e_name').addClass('is-invalid');
                        $('.e_name_err').text(errors.name[0]);
                    }

                    if (errors.email) {
                        $('#e_email').addClass('is-invalid');
                        $('.e_email_err').text(errors.email[0]);
                    }

                    if (errors.phone) {
                        $('#e_phone').addClass('is-invalid');
                        $('.e_phone_err').text(errors.phone[0]);
                    }

                    if (errors.salary) {
                        $('#e_salary').addClass('is-invalid');
                        $('.e_salary_err').text(errors.salary[0]);
                    }
                }
            });
        });

        $('#search').on('keyup', function() {
            let value = $(this).val();

            $.ajax({
                url: "{{ route('employees.search') }}",
                type: "POST",
                data: {
                    search: value
                },

                success: function(data) {
                    let rows = '';

                    if (data.length === 0) {
                        rows = `<tr><td colspan="5" class="text-center">No records found</td></tr>`;
                    }

                    $.each(data, function(key, emp) {
                        rows += `
                    <tr>
                        <td>${key + 1}</td>
                        <td>${emp.name}</td>
                        <td>${emp.email}</td>
                        <td>${emp.phone}</td>
                        <td>${emp.salary}</td>
                        <td>${emp.status}</td>
                        <td>
                            <button class="btn btn-sm btn-primary editBtn"
                                data-id="${emp.id}">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger deleteBtn"
                                data-id="${emp.id}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
                    });

                    $('#employeeTable tbody').html(rows);
                }
            });
        });
    </script>

</body>

</html>
