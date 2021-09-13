@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card text-white mt-5" style="background: #222;">
                    <div class="card-header h3 bg-transparent border-bottom-0">Your Profile</div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="row row-cols-1 row-cols-md-4">
                                @forelse($courses as $course)
                                    <div class="col mb-4" style="display: flex;flex-flow: row wrap">
                                        <div class="card border-0 shadow-sm rounded-0" style="background: #222222">
                                            <img src="{{ $course->image != null ? asset('storage/uploads/courses/thumbnail') . '/' . $course->image : asset('images/frontend/no-image-found.png') }}"
                                                class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <a href="{{ route('student.course.fetch', $course->slug)  }}"
                                                       class="text-white custom-hover">{{ $course->title }}</a>
                                                </h5>
                                                <p class="card-text"
                                                   style="color: #aaa">{{ $course->subTitle }}</p>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <div>
                                                            <img width="30" height="30" class="rounded-circle mr-2 ml-2"
                                                                 src="{{ $course->avatar != null ?
                                                    asset('storage/uploads/users/thumbnail') . '/' . $course->avatar :
                                                    asset('images/frontend/no-image-found.png') }}" alt="">
                                                        </div>
                                                        <div>
                                                            <h6 class="small text-white">{{ $course->author->first_name . ' ' . $course->author->last_name }}</h6>
                                                            <h6 class="small text-white">{{ date_format($course->created_at, 'j F, Y')}}</h6>
                                                        </div>
                                                    </div><!-- first d-flex -->

                                                    <div class="d-flex">
                                                        <div class="text-right mr-2">
                                                            <h6 class="small text-white">1.4 hours</h6>
                                                            <h6 class="small text-white">{{ $course->category->name }}</h6>
                                                        </div>
                                                    </div><!-- first d-flex -->
                                                </div>
                                                <div class="d-flex flex-column pt-3">
                                                    <div class="btn btn-sm button-darker btn-block rounded-0">
                                                        Enrolled: {{ $course->enrollments->count() }} Students
                                                    </div>
                                                    <div class="btn btn-sm button-darker btn-block rounded-0">
                                                        @php
                                                            $sales=0;
                                                        @endphp
                                                        @foreach($course->payments as $sale)
                                                            @if($sale->status == 'finalized')
                                                                @php
                                                                    $sales += $sale->author_earning
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        Sales: {{ number_format($sales, 2)}}
                                                    </div>

                                                    <a href="javascript:void(0)"
                                                       data-toggle="modal"
                                                       id="{{ $course->id }}"
                                                       class="btn btn-sm button-darker py-2 btn-block rounded-0 btnPayout">Payout</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ////////////////////////////// end of col mb-3 ////////////////////////////// -->
                                @empty
                                    <p>No learning videos!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="requestPayout" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request a Refund</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="course_id" id="course_id" />
                    <div class="form-group">
                        <label for="message" class="col-form-label">Comment:</label>
                        <textarea class="form-control" rows="5" id="message" name="comment"></textarea>
                        <small id="i_comment" class="form-text"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSent">Sent</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click','.btnPayout',function() {
                var course_id = this.id;
                $("#requestPayout")[0].reset();
                $("#course_id").val(course_id);
                $("#refundModal").modal('show')
            })

            $("#requestPayout").on('submit', function(e) {
                e.preventDefault();
                const btn = $("#btnSent");
                $.ajax({
                    url: '{{ route('instructor.course.payout') }}',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: () => {
                        btn.prop('disabled', true).html('Sending...');
                        $(document).find(`[id*='Input']`).removeClass('is-invalid');
                        $(document).find(`small[id*='i_']`).removeClass('text-danger').text('');
                    },
                    success: _ => {
                        btn.prop('disabled', false).html('Sent');
                        $("#refundModal").modal('hide');
                    },
                    error: ({status, responseJSON}) => {
                        btn.prop('disabled', false).html('Sent');
                        if (parseInt(status) === 422) {
                            $.each(responseJSON.errors, function (i, error) {
                                $(document).find(`[name=${i}]`).addClass('is-invalid');
                                $(document).find(`#i_${i}`).addClass('text-danger').text(error[0]);
                            })
                        }
                    }
                });
            })

            // approval of courses
            $(document).on('click', '.button-refund', function(e) {
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
        })
    </script>
@endpush

