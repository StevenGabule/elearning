@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card rounded-0 py-3" style="margin-top: 15%;background: #222222;color: white;">
                    <div class="card-header text-center  bg-transparent border-bottom-0">
                        <h3>{{ __('WinLoop') }}</h3>
                        <p>Create a new account</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="username"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                                <div class="col-md-6">
                                    <input id="username"
                                           type="text"
                                           class="form-control  rounded-0 @error('username') is-invalid @enderror"
                                           name="username" value="{{ old('username') }}"
                                           required
                                           autocomplete="username"
                                           autofocus>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="first_name"
                                       class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="first_name"
                                           type="text"
                                           class="form-control  rounded-0 @error('first_name') is-invalid @enderror"
                                           name="first_name"
                                           value="{{ old('first_name') }}"
                                           required autocomplete="first_name">

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                <div class="col-md-6">
                                    <input id="last_name"
                                           type="text"
                                           class="form-control  rounded-0 @error('last_name') is-invalid @enderror"
                                           name="last_name"
                                           value="{{ old('last_name') }}"
                                           required autocomplete="last_name">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control  rounded-0 @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control  rounded-0 @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control  rounded-0"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"></label>

                                <div class="col-md-6 form-check" style="padding-left: 16px">
                                    <label class="form-check-label small" for="user-policy">
                                        By signing up, you agree to our
                                        <a href="javascript:void(0)"
                                           data-target="#userPolicyModal"
                                           data-toggle="modal"
                                           class="btn-link">user policy</a></label>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn button-darker btn-block rounded-0">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userPolicyModal" tabindex="-1" aria-labelledby="userPolicyModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">User Policy - Refunds</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        If the content you purchased is not what you were expecting, you can request, within 30 days of
                        your purchase of the content, that WinLoop apply a refund to your account. This refund option
                        does
                        not apply to Subscription Plan purchases, which are covered in Section 8.4 below. We reserve the
                        right to apply your refund as a refund credit or a refund to your original payment method, at
                        our discretion, depending on capabilities of our payment service providers, the platform from
                        which you purchased your content (website, mobile or TV app), and other factors. No refund is
                        due to you if you request it after the 30-day guarantee time limit has passed. However, if the
                        content you previously purchased is disabled for legal or policy reasons, you are entitled to a
                        refund beyond this 30-day limit. WinLoop also reserves the right to refund students beyond the
                        30-day limit in cases of suspected or confirmed account fraud.
                    </p>
                    <p>
                        If we decide to issue refund credits to your account, they will be automatically applied towards
                        your next content purchase on our website, but can’t be used for purchases in our mobile or TV
                        applications. Refund credits may expire if not used within the specified period and have no cash
                        value, in each case unless otherwise required by applicable law.
                    </p>
                    <p>
                        At our discretion, if we believe you are abusing our refund policy, such as if you’ve consumed a
                        significant portion of the content that you want to refund or if you’ve previously refunded the
                        content, we reserve the right to deny your refund, restrict you from other future refunds, ban
                        your account, and/or restrict all future use of the Services. If we ban your account or disable
                        your access to the content due to your violation of these Terms or our Trust & Safety
                        Guidelines, you will not be eligible to receive a refund. Additional information on our refund
                        policy is available here.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
