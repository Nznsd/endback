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
                                                        <input type="text" class="form-control" value="{{ isset($education) ? json_decode($education->school)->name : '' }}" placeholder="Enter School Name" name="sittings[first][school_name]" tabindex="">
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
                                                                            <option value="{{ $year }}"
                                                                            {{ isset($education) && json_decode($education->school)->year == $year ? 'selected="selected"' : '' }}>
                                                                            {{ $year }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="sittings[first][result_type]">Result Type<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" name="sittings[first][result_type]" data-casacade-select-target="[cascade-select-dropdown=first_grade_type]" tabindex="">
                                                                    <option value="-">Select Result Type</option>
                                                                    <option value="degree"
                                                                    {{ isset($education) && json_decode($education->school)->type == 'degree' ? 'selected="selected"' : '' }}>Degree</option>
                                                                    <option value="coe"
                                                                    {{ isset($education) && json_decode($education->school)->type == 'coe' ? 'selected="selected"' : '' }}>College of Education</option>
                                                                    <option value="tcii"
                                                                    {{ isset($education) && json_decode($education->school)->type == 'tcii' ? 'selected="selected"' : '' }}>TCII</option>
                                                                    <option value="poly"
                                                                    {{ isset($education) && json_decode($education->school)->type == 'poly' ? 'selected="selected"' : '' }}>Polythecnic</option>
                                                                    <option value="pttp"
                                                                    {{ isset($education) && json_decode($education->school)->type == 'pttp' ? 'selected="selected"' : '' }}>PTTP</option>
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                     <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="address">Certificate File</label>
                                                            <div class="input-text form-group-disabled relative pill">
                                                                <input type="text" class="form-control" readonly="readonly" value="[No Certificate File Selected]" name="sittings[first][cert_file_name]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <input type="file" hidden name="sittings[first][certificate]">
                                                            <a href="javascript:void(0);" class="btn mynti-button-calm pill upload-cert">Attach Certificate</a>
                                                        </div>
                                                     </div>
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
                                                                    <select class="form-control" name="sittings[first][grades][0]" tabindex="" cascade-select-dropdown="first_grade_type"   data-select-loaded="false">
                                                                        <option value="">Select Grade</option>
                                                                        @foreach($grades as $grade)
                                                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                                 </span>
                                                                 <span class="mynti-box stretchable relative pill">
                                                                    <div class="input-text pill">
                                                                        <input type="text" class="form-control" placeholder="Enter Major, eg B.ED Mathematics Education" name="sittings[first][subjects][0]" tabindex="">
                                                                    </div>
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
                                                    <a href="/applicants/experience" class="btn mynti-button-calm pill skip" data-toggle="popover" data-placement="top" data-content="If you don't have this record for entry. Simply Click this button to continue" title="Skip Past This Page" data-trigger="focus"><i class="mynti-spinner-white"></i><b class="">Skip</b></a>
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