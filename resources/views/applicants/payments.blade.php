@extends('applicants.layouts.master')
  @section('title', 'Payments')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Pay {{  isset($type) ? ucfirst($type) : 'Application Form'  }} Fees</h3>
                                <p class="mynti-subheading subheading-regular light-size">Step 2</p>
                            </div>
                            <div class="mynti-greetings-container pull-right">
                                <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                            </div>
                        </div>
                    </article>
                    @if(!isset($type))
                        <div class="mynti-breadcrumbs">
                            <ul class="list-inline mynti-breadcrumbs-list">
                                <li><a href="{{ route('start') }}"><span class="numbering">0</span><span class="label"></span></a></li>
                                <li><a href="{{ route('programme') }}"><span class="numbering badge">1</span><span class="label">Programme</span></a></li>
                                <li><a href="#"><span class="numbering badge">2</span><span class="label">Payments</span></a></li>
                            </ul>
                    </div>
                    @endif
                    <section class="container mynti-section-offsetted">
                       @include('applicants.layouts.status')
                       @include('applicants.layouts.errors')
                        <article class="mynti-payments-form" name="py-form" method="post" target="_self">
                            <div class="mynti-form-caption form-heading-frame">
                                <h2 class="heading-placeholder-size">Payment Details</h2>
                            </div>
                            <div class="form-group mynti-input-container">
                                <label class="mynti-box lighter-size" for="full_name">Full Name</label>
                                <div class="input-text form-group-disabled relative pill">
                                    <input type="text" class="form-control" placeholder="" value="{{ $paymentDetails['payerName'] }}" readonly="readonly" name="full_name" tabindex="9">
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="email">Email</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="email" class="form-control" placeholder="" value="{{ $paymentDetails['payerEmail'] }}" readonly="readonly" name="email" tabindex="10">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="phone_number">Phone Number</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="phone" class="form-control" placeholder="" value="{{ $paymentDetails['payerPhone'] }}" readonly="readonly" name="phone_number" tabindex="11">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="programme">Programme</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ $programme }}" readonly="readonly" name="programme" tabindex="12">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="specialization">Specialization</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ $specialization }}" readonly="readonly" name="specialization" tabindex="13">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="amount">Amount</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ $paymentDetails['totalAmount'] }}" readonly="readonly" name="amount" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="orderid">Order ID</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ $paymentDetails['orderId'] }}" readonly="readonly" name="orderid" tabindex="15">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <label class="mynti-box lighter-size" for="rrr">RRR</label>
                                <div class="input-text form-group-disabled relative pill">
                                    <input type="number" class="form-control" placeholder="" value="{{ $remita['rrr'] }}" readonly="readonly" name="rrr" tabindex="16">
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <form action="{{ $remita['url'] }}" name="SubmitRemitaForm" method="POST" style="display:inline-block;margin-right:7px;"> 
                                        <input name="merchantId" value="{{ $remita['merchantId'] }}" type="hidden"> 
                                        <input name="hash" value="{{ $remita['hash'] }}" type="hidden"> 
                                        <input name="rrr" value="{{ $remita['rrr'] }}" type="hidden"> 
                                        <input name="responseurl" value="{{ $paymentDetails['responseUrl']}}" type="hidden"> 
                                        <input type="submit" name="submit_btn" class="btn mynti-button-calm continue pill" tabindex="17" value="Pay Via Remita &rsaquo;">
                                </form>
                               <a href="javascript:void(0);" class="btn mynti-button-calm save-and-print pill" tabindex="18"><b class="">Save Invoice</b></a>
                                <br><br><p>If you have made payment <strong>via bank</strong>, <a class="verify" href="{{ '/applicants/verify/' . $type }}">click here</a> to verify your RRR</p>
                            </div>
                            <div class="form-group mynti-input-container">
                                <p class="mynti-remita-mast"></p>
                            </div>
                        </article>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="19">Omniswift <b class="">|</b></a> <a href="/" tabindex="">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>

		@include('applicants.scripts.payments_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/payments-formunit.js')}}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js')}}"></script>

        @endsection
	  @endsection