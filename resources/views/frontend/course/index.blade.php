@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        {!! \Session::get('success') !!}
                    </div>
                @endif
                <div class="card mt-4">
                    <div class="card-header bg-transparent border-bottom-0">
                        <div class="d-flex justify-content-between">
                            <div class="h3">{{ __('Courses') }}</div>
                            <div>
                                <a href="{{ route('course.create') }}" class="btn btn-primary btn-sm">Create Course</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="courseTable" class="table table-striped table-bordered small" style="width:100%">
                            <thead>
                            <tr>
                                <th style="width: 3%">#</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Published</th>
                                <th>Approved</th>
                                <th>Created</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" type="text/javascript" defer></script>
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js" type="text/javascript" defer></script>
    <script>
        $(document).ready( function () {
            const asset_path= "{{ asset('storage/uploads/courses/thumbnail') }}"

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
                ajax: "{{ route('instructor.course.fetch') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: null,
                        name: 'image',
                        render: ({title, price, image}) => {
                            return `<div class='d-flex'>
                                       <div class="position-relative">
                                          <img src=${asset_path + "/" + image} alt="No image found!" width="50" class="img-rounded"/>
                                        </div>
                                        <div class="d-flex flex-column ml-2">
                                            <span class="text-capitalize font-weight-bold">${title}</span>
                                            <span>Price: ${price}</span>
                                        </div>
                                  </div>`
                        }
                    },
                    {
                        data: null,
                        name: 'category.name',
                        render: ({category}) => category.name
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
        } );
    </script>
@endpush
