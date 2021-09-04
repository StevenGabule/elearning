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
                    <th style="width: 3%">#</th>
                    <th>Requester</th>
                    <th>Course</th>
                    <th>Payment</th>
                    <th>Transaction</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Refunded</th>
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
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js" type="text/javascript" defer></script>
    <script>
        $(document).ready( function () {
            const asset_path= "{{ asset('storage/uploads/courses/thumbnail') }}"
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
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'requested_id',
                        name: 'requested_id',
                    },
                    {
                        data: 'course_id',
                        name: 'course_id',
                    },

                    {
                        data: 'payment_id',
                        name: 'payment_id',
                    },
                    {
                        data: 'transaction_id',
                        name: 'transaction_id',
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
                        data: 'refunded_to',
                        name: 'refunded_to',
                    },
                    {
                        data: 'processing_at',
                        name: 'processing_at',
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
        } );
    </script>
@endpush
