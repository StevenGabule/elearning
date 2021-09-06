@extends('backend.layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 pink">
        <h1 class="h2">Categories</h1>
    </div>
    <div class="card text-dark">
        <div class="card-body p-4">
            <table id="categoriesTable" class="table table-striped table-bordered small" style="width:100%">
                <thead>
                <tr>
                    <th style="width: 3%">#</th>
                    <th>Name</th>
                    <th>Live</th>
                    <th>Sort Order</th>
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
            const asset_path= "{{ asset('storage/uploads/users/thumbnail') }}"
            const no_image = "{{ asset('images/frontend/no-image-found.png') }}"

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '60vh',
                scrollCollapse: true,
                ajax: "{{ route('admin.dashboard.categories') }}",
                order: [[0, "desc"]],
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'live',
                        name: 'live',
                        render: data => `${data ? 'Active' : 'Un-active'}`
                    },
                    {
                        data: 'sortOrder',
                        name: 'sortOrder',
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

            $(document).on('click', '.btn-live', function(e) {
                const id = this.id;
                swal({
                    title: "Confirmation",
                    text: "Are you sure to continue this process?",
                    icon: "warning",
                    dangerMode: true,
                    buttons: [true, "Yes, Confirm"],
                    closeModal: false
                }).then((willConfirm) => {
                    if (willConfirm) {
                        $.ajax({
                            url: "{{ route('admin.category.live') }}",
                            method: 'POST',
                            data: {id: id},
                            success: _ => {
                                $('#categoriesTable').DataTable().ajax.reload(null, false);
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
