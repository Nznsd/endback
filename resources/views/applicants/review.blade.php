@extends('applicants.layouts.master')
  @section('title', 'Review')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Review Application</h3>
                                <p class="mynti-subheading subheading-regular light-size">You have completed your application. Please, take some time to go over it.</p>
                            </div>
                            <div class="mynti-greetings-container pull-right">
                                <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                            </div>
                        </div>
                    </article>
                    <div class="mynti-breadcrumbs">
                            <ul class="mynti-breadcrumbs-list">
                                <li><a href="{{ route('start') }}" tabindex="-1"><span class="numbering">0</span><span class="label"></span></a></li>
                                <li><a href="{{ route('programme') }}" tabindex="-1"><span class="numbering badge">1</span><span class="label">Programme</span></a></li>
                                <li><a href="{{ route('pay') }}" tabindex="-1"><span class="numbering badge">2</span><span class="label">Payments</span></a></li>
                                <li><a href="{{ route('verify') }}" tabindex="-1"><span class="numbering badge">3</span><span class="label">Verify</span></a></li>
                                <li><a href="{{ route('biodata') }}" tabindex="-1"><span class="numbering badge">4</span><span class="label">Personal Info</span></a></li>
                                <li><a href="{{ route('certificate') }}" tabindex="-1"><span class="numbering badge">5</span><span class="label">Certificates</span></a></li>
                                <li><a href="{{ route('experience') }}" tabindex="-1"><span class="numbering badge">6</span><span class="label">Work Experience</span></a></li>
                                <li><a href="{{ route('uploads') }}" tabindex="-1"><span class="numbering badge">7</span><span class="label">Upload Docs</span></a></li>
                                <li><a href="#" tabindex="-1"><span class="numbering badge">8</span><span class="label">Review</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            @include('applicants.layouts.errors')
                    </div>
                    
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                  <div class="stretchable" style="">   
                                            <form class="mynti-context-form" id="nosubmit" action="/applicants/review" method="POST" target="_top" name="nosubmit" novalidate>
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">Review Application</h2>
                                                </div>
                                                <div class="table-container container">
                                                    <h2 class="mynti-subheading light-size table-heading">Application Details</h2>
                                                               
                                                    <table class="table pill">
                                                    
                                                    <tbody>
                                                      <tr>
                                                        <td><b>Application Number</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->app_no }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Full Name</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Email</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->email }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Phone</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->phone }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Date of Birth</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->dob }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Gender</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->gender }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Marital Status</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->marital_status }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Address</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->address }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>State of Origin</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['soo']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>LGA of Origin</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['soo_lga']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>State of Residence</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['sor']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>LGA of Residence</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['sor_lga']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Programme</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['programme']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>First Choice</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['first_choice']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Second Choice</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['second_choice']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Entry Level</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->entry_level }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Entry Type</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $applicant->entry_type }}" readonly="readonly"></td>
                                                      </tr>
                                                      <tr>
                                                        <td><b>Study Center</b></td>
                                                        <td><input type="text" class="form-control" value="{{ $data['study_center']->name }}" readonly="readonly"></td>
                                                      </tr>
                                                    </tbody>
                                                    </table>
                                            </div>
                                                <p class="punched-up">
                                                    <input type="checkbox" name="certify_info" tabindex=""><span class="">I hereby certify that all the information i have provided in the NTI Prospective Student Application is correct and true.</span>
                                                </p>
                                                <div class="form-group mynti-input-container">
                                                    <a href="javascript:void(0);" class="btn mynti-button-calm pill continue" tabindex="18" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Submit Application &rsaquo;</b></a>
                                                    <a href="javascript:void(0);" class="btn mynti-button-groovy print-application pill" tabindex="19" disabled="disabled"><b class="">Print Application</b></a>
                                                </div>
                                        </form>
                                </div>
                            </section>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="20">Omniswift <b class="">|</b></a> <a href="/" tabindex="21">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>
        
		@include('applicants.scripts.review_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/review-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection