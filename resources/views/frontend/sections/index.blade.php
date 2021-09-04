@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>{{ __($course->title . ' ' . 'Sections') }}</div>
                            <div>
                                <a href="javascript:void(0)"
                                   data-toggle="modal"
                                   class="btn btn-primary btn-sm" id="openModelNewSection">
                                    Create section
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="sectionTable"
                               class="table table-small table-hover small row-border hover order-column"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th style="width: 3%">#</th>
                                <th>Title</th>
                                <th>Objective</th>
                                <th>Order</th>
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

    <!-- Modal for creating a new section -->
    <div class="modal fade" id="newSectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSectionForm">
                        @csrf
                        <div class="form-group">
                            <label for="inputTitle">Title</label>
                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   id="inputTitle"
                                   aria-describedby="titleHelp">
                            <small id="i_title" class="form-text"></small>
                        </div>
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <div class="form-group">
                            <label for="inputObjective">Objective</label>
                            <textarea id="inputObjective" rows="3" name="objective" class="form-control"></textarea>
                            <small id="i_objective" class="form-text"></small>
                        </div>

                        <div class="float-right">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" type="text/javascript" defer></script>
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js" type="text/javascript"
            defer></script>

    <script>
        $(document).ready(function () {
            const id = '{{$course->id}}'
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function format(d) {
                const {lessons} = d;
                let htmlBuild = `
                    <table class="table table-sm table-striped"  style="padding-left:50px;">
                    <thead>
                        <tr>
                        <th>Title</th>
                        <th>Content Type</th>
                        <th>Lesson Type</th>
                        <th>Duration</th>
                        <th>Preview</th>
                        <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                lessons.forEach(function (lesson) {
                    const {content_type, title, description, duration, preview, sortOrder} = lesson;
                    htmlBuild += `<tr>
                                    <td>${title}</td>
                                    <td>${description}</td>
                                    <td>${content_type}</td>
                                    <td>${duration}</td>
                                    <td>${preview ? 'Yup' : 'Nope'}</td>
                                    <td>${sortOrder}</td>
                                </tr>`;
                })
                htmlBuild += " </tbody></table>"
                return htmlBuild;
            }

            var $table = $('#sectionTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '60vh',
                search: {
                    return: true
                },
                // dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                scrollCollapse: true,
                ajax: "/course/" + id + "/sections",
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'objective',
                        name: 'objective',
                    },
                    {
                        data: 'sortOrder',
                        name: 'sortOrder',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data) => new Date(data).toLocaleDateString()
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                    },
                ]
            });
            const $sectionTable = $('#sectionTable tbody');

            // Add event listener for opening and closing details
            $sectionTable.on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = $table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            $sectionTable.on('mouseenter', 'td', function () {
                var colIdx = $table.cell(this).index().column;
                $($table.cells().nodes()).removeClass('highlight');
                $($table.column(colIdx).nodes()).addClass('highlight');
            });

            ///////// OPEN A NEW MODEL FOR CREATING SECTION
            $("#openModelNewSection").on('click', function (e) {
                const btnSubmit = $("#btnSubmit");
                btnSubmit.prop('disabled', false);
                btnSubmit.text('Submit');
                $(document).find(`[id*='input']`).removeClass('is-invalid');
                $(document).find(`small[id*='i_']`).removeClass('text-danger').text('');
                $("#createSectionForm")[0].reset();
                $("#newSectionModal").modal('show');
            });

            ///////// SUBMIT A NEW SECTION FOR COURSE
            $("#createSectionForm").on('submit', function (e) {
                e.preventDefault();
                const btnSubmit = $("#btnSubmit");
                $.ajax({
                    url: "{{ route('section.store') }}",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: () => {
                        btnSubmit.prop('disabled', true);
                        btnSubmit.text('creating...');
                        $(document).find(`[id*='input']`).removeClass('is-invalid');
                        $(document).find(`small[id*='i_']`).removeClass('text-danger').text('');
                    },
                    success: _ => {
                        $("#newSectionModal").modal('hide');
                        $('#sectionTable').DataTable().ajax.reload(null, false);
                    },
                    error:  ({status, responseJSON}) => {
                        btnSubmit.prop('disabled', false);
                        btnSubmit.text('Submit');
                        if (parseInt(status) === 422) {
                            $.each(responseJSON.errors, function (i, error) {
                                $(document).find(`[name=${i}]`).addClass('is-invalid');
                                $(document).find(`#i_${i}`).addClass('text-danger').text(error[0]);
                            })
                        }
                    },
                })
            })
        });
    </script>
@endpush
