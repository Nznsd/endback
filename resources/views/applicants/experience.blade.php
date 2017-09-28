@extends('applicants.layouts.master')
@section('title', 'Experience')
  @section('content')
    @include('applicants.layouts.nav')

    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">WorkPlace Experience</h3>
                                <p class="mynti-subheading subheading-regular light-size">Please complete your application</p>
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
                                <li><a href="#" tabindex="-1"><span class="numbering badge">6</span><span class="label">WorkPlace Info</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            @include('applicants.layouts.errors')
                    </div>
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                 <div class="stretchable" style="">   
                                            <form class="mynti-context-form" id="geoinfo-thunk" name="geoinfo-thunk" method="post" target="_top" novalidate>
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">Add WorkPlace Experience</h2>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-8 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="employer">Employer</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" class="form-control" name="employer" id="employer" placeholder="Type Employer Name" tabindex="" autocomplete="off" spellcheck="true" autofocus="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-4 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="position">Position</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" class="form-control" id="position" name="position" placeholder="Type Position" tabindex="" autocomplete="off" spellcheck="true">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="from_date">Work Start (Date)</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" placeholder="" value="11/08/2005" class="form-control" id="from_date" name="from_date" tabindex="" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="to_date">Work End (Date)</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" placeholder="" value="11/08/2010" class="form-control" id="to_date" name="to_date" tabindex="" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <label class="mynti-box lighter-size" for="job_description">Job Description</label>
                                                    <div class="input-text relative pill">
                                                        <textarea class="form-control" placeholder="Type Short Description" id="job_description" name="job_description" tabindex="22" autocomplete="off" spellcheck="true"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <a href="javascript:void(0);" rel="next" class="btn mynti-button-calm pill continue" tabindex="23" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                                                    <a href="uploads" class="btn mynti-button-calm pill" tabindex="24"><i class="mynti-spinner-white"></i><b class="">Skip</b></a>
                                                    
                                                </div>
                                            </form>
                                    </div>
                            </section>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; 2016 National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="24">Omniswift <b class="">|</b></a> <a href="/" tabindex="25">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>

        @include('applicants.scripts.biodata_scripts')

    @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/workplaceinfo-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>
        @endsection
    @endsection