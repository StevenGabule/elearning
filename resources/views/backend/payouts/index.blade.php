@extends('backend.layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 pink">
        <h1 class="h2">Payouts</h1>
    </div>
    <div class="card text-dark">
        <div class="card-body p-4">
            <table id="paymentTable"
                   class="table table-striped table-bordered small"
                   style="width:100%">
                <thead>
                <tr>
                    <th style="width: 3%">#</th>
                    <th>Earner</th>
                    <th>Net Earnings</th>
                    <th>Total Author Earnings</th>
                    <th>Total Refunds</th>
                    <th>Payment Address</th>
                    <th>Processing</th>
                    <th>Process Date</th>
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

            $('#paymentTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '60vh',
                scrollCollapse: true,
                ajax: "{{ route('admin.dashboard.payout') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
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
                                            <span class="text-capitalize font-weight-bold">${first_name + " "+ last_name }</span>
                                            <span>${username}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: 'net_earnings',
                        name: 'net_earnings',
                    },
                    {
                        data: 'total_author_earnings',
                        name: 'total_author_earnings',
                    },

                    {
                        data: 'total_refunds',
                        name: 'total_refunds',
                    },
                    {
                        data: 'payment_address',
                        name: 'payment_address',
                    },
                    {
                        data: 'is_processing',
                        name: 'is_processing',
                    },
                    {
                        data: 'processed_at',
                        name: 'processed_at',
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
