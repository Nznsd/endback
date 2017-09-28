@extends('applicants.layouts.master')
  @section('title', 'Certificates')
  @section('content')
    @include('applicants.layouts.nav')

    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Educational Information</h3>
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
                                <li><a href="#" tabindex="-1"><span class="numbering badge">5</span><span class="label">Certificates</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            
                    </div>
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                 <div class="stretchable"> 
                                             
                                            <form class="mynti-context-form" id="eduinfo-thunk" name="eduinfo-thunk" method="post" action="/applicants/certificate/tertiary" enctype="multipart/form-data" target="_top">
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">Academic Details</h2>
                                                </div>
                                                @include('applicants.layouts.errors')
                                                <section class="mynti-edu-details">
                                                    <div class="form-group mynti-input-container">
                                                    <label class="mynti-box lighter-size" for="sittings[first][school_name]">Tertiary Institution</label>
                                                    <div class="input-text relative pill">
                                                        <input type="text" class="form-control" placeholder="Enter School Name" name="sittings[first][school_name]" tabindex="">
                                                    </div>
                                                </div>
                                                    
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="sittings[first][year]">Year of Graduation<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" name="sittings[first][year]" tabindex="">
                                                                    <option value="-">Select Year</option>
                                                                    @foreach($years as $year)
                                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                                        @endforeach
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="sittings[first][result_type]">Result Type<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" name="sittings[first][result_type]" tabindex="">
                                                                    <option value="-">Select Result Type</option>
                                                                    <option value="degree">Degree</option>
                                                                    <option value="coe">College of Education</option>
                                                                    <option value="tcii">TCII</option>
                                                                    <option value="poly">Polythecnic</option>
                                                                    <option value="pttp">PTTP</option>
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                            <input type="file" hidden name="sittings[first][certificate]">
                                                            <a href="javascript:void(0);" class="btn mynti-button-calm pill upload-cert">Upload Certificate</a>
                                                </div>
                                                <div class="mynti-results-box">
                                                    <div class="mynti-form-caption form-heading-frame">
                                                        <h2 class="heading-regular light-size"><b>Academic Information Higher Institution</b></h2>
                                                    </div>
                                                    <p class="clearfix">
                                                        <span class="mynti-box fixed-width-200 relative">
                                                            <b>Grade</b>
                                                        </span>
                                                        <span class="mynti-box stretchable relative">
                                                            <b>Major</b>
                                                        </span>
                                                    </p>
                                                    <ol start="1" class="mynti-result-subjects">
                                                        <li class="mynti-result-subjects-container">
                                                            <div class="clearfix">
                                                                 <span class="mynti-box fixed-width-200 input-dropdown relative pill">
                                                                    <select name="sittings[first][grades][0]" tabindex="" class="form-control">
                                                                        <option value="-">Select Grade</option>
                                                                        @foreach($grades as $grade)
                                                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                                 </span>
                                                                 <span class="mynti-box stretchable input-dropdown relative pill">
                                                                    <select name="sittings[first][subjects][0]" tabindex="" class="form-control">
                                                                        <option value="-">Select Certificate</option>
                                                                        @foreach($majors as $key => $value)
                                                                            <option value="{{ $value }}">{{ $key }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                                </span>
                                                            </div>
                                                        </li>
                                                      </ol>
                                                    
                                                </div>
                                                </section>
                                                
                                                <div class="form-group mynti-input-container">
                                                    <input type="checkbox" name="badge"><span>I attest that this is the complete information on my academic certificates</span>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <a href="javascript:void(0);" rel="next" class="btn mynti-button-calm pill continue" tabindex="" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                                                    <!--<a href="javascript:void(0);" class="btn mynti-button-groovy pill add" tabindex="" data-toggle="popover" title="Add Exam Sittings" data-content="You can add more certificate exam sittings if you have any" data-trigger="focus" disabled="disabled">Add Sitting</a>-->
                                                </div>
                                            </form>
                                    </div>
                            </section>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="24">Omniswift <b class="">|</b></a> <a href="/" tabindex="25">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>


        @include('applicants.scripts.biodata_scripts')


    @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/educate-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection