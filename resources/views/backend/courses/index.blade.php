@extends('backend.layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 pink">
        <h1 class="h2">Courses</h1>
    </div>
    <div class="card text-dark">
        <div class="card-body p-4">
            <table id="courseTable" class="table table-striped table-bordered small" style="width:100%">
                <thead>
                <tr>
                    <th style="width: 3%">#</th>
                    <th>Instructor</th>
                    <th>Course</th>
                    <th>Price</th>
                    <th>Level</th>
                    <th>Publish</th>
                    <th>Approval</th>
                    <th>Created</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('custom_script')
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" type="text/javascript" defer></script>
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js" type="text/javascript" defer></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready( function () {
            const asset_path= "{{ asset('storage/uploads/courses/thumbnail') }}"
            const no_image = "{{ asset('images/frontend/no-image-found.png') }}"

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#courseTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '60vh',
                scrollCollapse: true,
                ajax: "{{ route('admin.dashboard.course') }}",
                order: [[0, 'desc']],
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: null,
                        name: 'author',
                        render: ({author}) => {
                            const {avatar, username, first_name, last_name} = author;
                            return `<div class='d-flex'>
                                       <div class="position-relative">
                                          <img src=${avatar != null ? asset_path + "/" + avatar : no_image} alt="No image found!" width="50" class="img-rounded"/>
                                        </div>
                                        <div class="d-flex flex-column ml-2">
                                            <span class="text-capitalize font-weight-bold">${first_name + " "+ last_name }</span>
                                            <span>${username}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: null,
                        name: 'course',
                        render: ({title, image}) => {
                            return `<div class='d-flex'>
                                       <div class="position-relative">
                                          <img src=${image != null ? asset_path + "/" + image : no_image} alt="No image found!" style="width: 36px;height: 30px;"/>
                                        </div>
                                        <div class="d-flex flex-column ml-2">
                                            <span class="text-capitalize font-weight-bold">${title}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'level',
                        name: 'level',
                        render: data => {
                            switch (data) {
                                case "all":
                                    return `<div class="font-weight-bold small badge-light-success py-1 px-2 text-capitalize text-center rounded-pill">${data}</div>`
                                case "beginner":
                                    return `<div class="font-weight-bold small badge-light-primary py-1 px-2 text-capitalize text-center rounded-pill">${data}</div>`
                                case "intermediate":
                                    return `<div class="font-weight-bold small badge-light-warning py-1 px-2 text-capitalize text-center rounded-pill">${data}</div>`
                                case "advanced":
                                    return `<div class="font-weight-bold small badge-light-danger py-1 px-2 text-capitalize text-center rounded-pill">${data}</div>`
                                default:
                                    return null
                            }
                        }
                    },
                    {
                        data: 'published',
                        name: 'published',
                        render: data => `<div class="${data ? 'button-success' : 'button-danger'} rounded-pill">${data ? 'Published' : 'Pending'}</div>`
                    },
                    {
                        data: 'approved',
                        name: 'approved',
                        render: data => `<div class="${data ? 'button-success' : 'button-danger'} rounded-pill">${data ? 'Approved' : 'Waiting'}</div>`
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data) =>  new Date(data).toLocaleDateString()
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ]
            });

            // approval of courses
            $(document).on('click', '.btn-approval', function(e) {
                const id = this.id;
                swal({
                    title: "Confirmation",
                    text: "Are you sure to approved this course?",
                    icon: "warning",
                    dangerMode: true,
                    buttons: [true, "Confirm"],
                    closeModal: false
                }).then((willConfirm) => {
                    if (willConfirm) {
                        $.ajax({
                            url: "{{ route('admin.course.approve') }}",
                            method: 'POST',
                            data: {id: id},
                            success: _ => {
                                $('#courseTable').DataTable().ajax.reload(null, false);
                            },
                            error: ({status, responseText}) => {
                                console.log(responseText)
                                if (status === 500) {
                                    const messages = $.parseJSON(responseText);
                                    swal("Oops..something went wrong!", messages.message, "error");
                                }
                            }
                        });
                    }
                });
            })
        } );
    </script>
@endpush
