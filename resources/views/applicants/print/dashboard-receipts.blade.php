@extends('applicants.layouts.master')
  @section('title', 'View Receipts')
  @section('content')
    @include('applicants.layouts.nav')
        <main class="mynti-main-section">
            <section class="mynti-main-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Receipts</h3>
                                <p class="mynti-subheading subheading-regular light-size">View Receipts</p>
                            </div>
                            <div class="mynti-greetings-container pull-right">
                                <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                            </div>
                        </div>
                    </article>
                    <div class="mynti-breadcrumbs">
                            <span class="mynti-reg-number">
                                <a href="javascript:window.history.go(-1);" class="btn mynti-button-calm pill">&laquo; Go Back</a>
                            </span> 
                    </div>
                    <section class="mynti-section-placid">
                    @include('applicants.layouts.status')
                    @include('applicants.layouts.errors')
                        <div class="container mynti-tabular-box flexible-till-max">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" summary="list of payments made by NTI applicant">
                                            <caption>
                                                <b>{{ $applicant->app_no }}</b>
                                            </caption>
                                            <thead class="table-head top-half-pill">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Receipt Description</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody  class="table-body bottom-half-pill">
                                                 @if(isset($transaction_application))
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td>NTI Application Form Fees for {{ $data['academicSessionInfo']->academicSession }} Session</td>
                                                        <td>{{ $transaction_application->created_at}}</td>
                                                        <td><b>₦{{ $transaction_application->amount }}</b></td>
                                                        <td>{{ $transaction_application->status}}</td>
                                                        <td><i class="mynti-icon arrowbulb"></i><a href="/applicants/receipt/application-form" target="_blank"><span class="">View Receipt</span></a></td>
                                                    </tr>
                                                @endif
                                                @if(isset($transaction_tuition))
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td>Tuition Fees for {{ $data['academicSessionInfo']->academicSession }} Session</td>
                                                        <td>{{ $transaction_tuition->created_at}}</td>
                                                        <td><b>₦{{ $transaction_tuition->amount }}</b></td>
                                                        <td>{{ $transaction_tuition->status}}</td>
                                                        <td><i class="mynti-icon arrowbulb"></i><a href="/applicants/receipt/tuition" target="_blank"><span class="">View Receipt</span></a></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                        <span class="hidden mynti-alert mynti-box"></span>
                                </div>
                            </div>

                        </div>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; 2016 National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="20">Omniswift <b class="">|</b></a> <a href="/" tabindex="21">Terms of Use <b class="">|</b></a> <a href="/" tabindex="22">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>
    
         @include('applicants.scripts.review_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection
                