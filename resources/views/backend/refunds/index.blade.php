@extends('backend.layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 pink">
        <h1 class="h2">Refunds</h1>
    </div>
    <div class="card text-dark">
        <div class="card-body p-4">
            <table id="refundsTable"
                   class="table table-striped table-bordered small"
                   style="width:100%">
                <thead>
                <tr>
                    <th>Course</th>
                    <th>Requester</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Refund Deadline</th>
                    <th>Processed</th>
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
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js" type="text/javascript"
            defer></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function () {
            const asset_path = "{{ asset('storage/uploads/courses/thumbnail') }}"
            const no_image = "{{ asset('images/frontend/no-image-found.png') }}"

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#refundsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '60vh',
                scrollCollapse: true,
                ajax: "{{ route('admin.dashboard.refund') }}",
                columns: [
                    {
                        data: null,
                        name: 'course',
                        render: ({course}) => {
                            const {image, title, price, level} = course;
                            return `<div class='d-flex'>
                                       <div class="position-relative">
                                          <img src=${image != null ? asset_path + "/" + image : no_image} alt="No image found!" width="50" class="img-rounded"/>
                                        </div>
                                        <div class="d-flex flex-column ml-2">
                                            <span class="text-capitalize font-weight-bold">${title}</span>
                                            <span>Price: ${price} | Level: ${level}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: null,
                        name: 'user',
                        render: ({user}) => {
                            const {avatar, username, first_name, last_name} = user;
                            return `<div class='d-flex'>
                                       <div class="position-relative">
                                          <img src=${avatar != null ? asset_path + "/" + avatar : no_image} alt="No image found!" width="50" class="img-rounded"/>
                                        </div>
                                        <div class="d-flex flex-column ml-2">
                                            <span class="text-capitalize font-weight-bold">${first_name + " " + last_name}</span>
                                            <span>${username}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: null,
                        name: 'transaction_id',
                        render: ({transaction}) => `<p>Type: ${transaction.description} | ${transaction.type}</p>`
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: null,
                        name: 'payment',
                        render: ({payment}) => `${new Date(payment.refund_deadline).toLocaleDateString()}`
                    },
                    {
                        data: 'processed_at',
                        name: 'processed_at',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data) => new Date(data).toLocaleDateString()
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ]
            });

            // approval of courses
            $(document).on('click', '.btn-refund', function(e) {
                const id = this.id;
                const pid = $(this).data('id')

                swal({
                    title: "Confirmation",
                    text: "Are you sure to refund?",
                    icon: "warning",
                    dangerMode: true,
                    buttons: [true, "Confirm"],
                    closeModal: false
                }).then((willConfirm) => {
                    if (willConfirm) {
                        $.ajax({
                            url: "{{ route('admin.payment.refund') }}",
                            method: 'POST',
                            data: {id: id, pid: pid},
                            beforeSend: () => {
                                swal({
                                    title: "Processing...",
                                    text: "Please wait for a seconds to complete the process.",
                                    icon: "success",
                                    buttons: false,
                                    closeModal: false,
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                    timer: 5000,
                                })
                            },
                            success: data => {
                                console.log(data)
                                $('#refundsTable').DataTable().ajax.reload(null, false);
                            },
                            error: ({status, responseText}) => {
                                console.log(responseText)
                                if (status === 500) {
                                    const messages = $.parseJSON(responseText);
                                    console.log(messages)
                                    alert('Cannot process the refund!')
                                    // swal("Oops..something went wrong!", messages.message, "error");
                                }
                            }
                        });
                    }
                });
            })
        });
    </script>
@endpush
