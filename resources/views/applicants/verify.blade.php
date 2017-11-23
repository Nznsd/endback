@extends('applicants.layouts.master')
  @section('title', 'Verify RRR')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Verify RRR</h3>
                                <p class="mynti-subheading subheading-regular light-size">Please verify RRR to continue with your application</p>
                            </div>
                            <div class="mynti-greetings-container pull-right">
                                <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                            </div>
                        </div>
                    </article>
                    <div class="mynti-breadcrumbs">
                            <ul class="mynti-breadcrumbs-list">
                                <li><a href="{{ route('start') }}"><span class="numbering">0</span><span class="label"></span></a></li>
                                <li><a href="{{ route('programme') }}"><span class="numbering badge">1</span><span class="label">Programme</span></a></li>
                                <li><a href="{{ route('pay') }}"><span class="numbering badge">2</span><span class="label">Payments</span></a></li>
                                <li><a href="#"><span class="numbering badge">3</span><span class="label">Verify</span></a></li>
                            </ul>
                    </div>
                    <section class="container mynti-section-offsetted">
                        <div class="mynti-verify-form">
                         @include('applicants.layouts.status')
                         @include('applicants.layouts.errors')
                            <div class="mynti-form-caption form-heading-frame">
                                <h2 class="heading-placeholder-size">RRR Information</h2>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                    <div class="clearfix text-left mynti-rrr-verify-container stretchable">
                                         <a href="javascript:void(0);" tabindex="10" class="mynti-box mynti-verify-link mynti-button-calm pill text-center" unselectable><i class="mynti-spinner-white"></i><b class="">Verify</b></a>
                                         <span class="mynti-box mynti-verify-text stretchable input-text relative pill">
                                            <input type="number" tabindex="9" autocomplete="off" class="form-control" placeholder="Enter yourr RRR number">
                                        </span>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                        <span class="hidden mynti-alert mynti-box" style="margin-top:25px;"></span>
                                </div>
                            </div>
                        </div>
                        <form class="mynti-rrr-verify-form" name="" method="post" target="_top" novalidate>
                            <div class="form-group mynti-input-container">
                                <label class="mynti-box lighter-size" for="fullname">Full Name</label>
                                <div class="input-text form-group-disabled relative pill">
                                    <input type="text" value="{{ $applicant->surname . " " . $applicant->firstname }}" class="form-control" placeholder="" readonly="readonly" name="fullname" tabindex="11">
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="programme">Programme</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" value="{{ $data['programme'] }}" class="form-control" placeholder="" readonly="readonly" name="programme" tabindex="12">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="specialization">Specialization</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" value="{{ $data['specialization'] }}" class="form-control" placeholder="" readonly="readonly" name="specialization" tabindex="13">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="paystatus">Payment Status</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" value="{{ strtoupper($transaction->status) }}" class="form-control" placeholder="" readonly="readonly" name="paystatus" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="amount">Amount</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ '₦' . $transaction->amount }}" readonly="readonly" name="amount" tabindex="15">
                                        </div>
                                    </div>    
                               </div>
                            </div>
                            <div class="form-group mynti-input-container">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="rrr">RRR</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="number" class="form-control" placeholder="" value="{{ json_decode($transaction->remitaBefore)->RRR }}" readonly="readonly" name="rrr" tabindex="16">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                        <label class="mynti-box lighter-size" for="orderid">Order ID</label>
                                        <div class="input-text form-group-disabled relative pill">
                                            <input type="text" class="form-control" placeholder="" value="{{ $transaction->orderId }}" readonly="readonly" name="orderid" tabindex="17">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mynti-input-container">
                            @if($transaction->status == 'paid')
                                <a href="{{ '/applicants/verified/' . $type }}" rel="next" class="btn mynti-button-calm pill continue" tabindex="18"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                                 <a href="javascript:void(0);" class="btn mynti-button-calm pill save-and-print" tabindex="19"><b class="">Save Receipt</b></a>
                            @else
                                 <a href="{{ '/applicants/payments/' . $type }}" class="btn mynti-button-calm pill" tabindex="18"><b class="">Pay Now</b></a>
                            @endif
                            </div>
                        </form>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="20">Omniswift <b class="">|</b></a> <a href="/" tabindex="21">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>
     
     @include('applicants.scripts.verify_scripts')
        
        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/verify-formunit.js') }}"></script>

		  <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
	  @endsection