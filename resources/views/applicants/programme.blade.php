@extends('applicants.layouts.master')
  @section('title', 'Programme')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
        <section class="mynti-login-container">
            <article class="mynti-banner">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="mynti-heading heading-regular">Choose Programme</h3>
                        <p class="mynti-subheading subheading-regular light-size">Step 1</p>
                    </div>
                    <div class="mynti-greetings-container pull-right">
                        <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                    </div>
                </div>
            </article>
            <div class="mynti-breadcrumbs">
                <ul class="mynti-breadcrumbs-list">
                    <li><a href="{{ route('start') }}"><span class="numbering">0</span><span class="label"></span></a></li>
                    <li><a href="#"><span class="numbering badge">1</span><span class="label">Programme</span></a></li>
                </ul>
            </div>
            
            <section class="container mynti-section-offsetted">
                <form class="mynti-courses-form" name="pg-form" action="/applicants/programme" method="post" target="_top">
                    @include('applicants.layouts.status')
                    @include('applicants.layouts.errors')
                    <div class="mynti-form-caption form-heading-frame">
                        <h2 class="heading-placeholder-size">Course Selection</h2>
                    </div>
                    <div class="form-group mynti-input-container programme-div">
                        <label class="mynti-box light-size" for="programme">Select Programme</label>
                        <div class="input-dropdown relative pill">
                            <select class="form-control" name="programme" value="{{ old('programme') }}" data-casacade-select-target="[cascade-target-dropdown=courses-choice-select]" tabindex="9">
                                <option value="-">Select Programme</option>
                                @foreach($programmes as $programme)
                                    <option value="{{ $programme->id }}" 
                                    {{ old('programme') == $programme->id ? 'selected="selected"' : '' }}>
                                    {{ $programme->name }}</option>
                                @endforeach
                            </select>
                            <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                        </div>
                    </div>
            
                    <div class="form-group mynti-input-container">
                        <label class="mynti-box light-size" for="course-first">Select Course 1<sup>st</sup> Choice</label>
                        <div class="input-dropdown relative pill">
                            <select class="form-control" cascade-target-dropdown="courses-choice-select" data-select-loaded="false" name="first_choice" tabindex="10">
                                <option value="-">Select Specialization</option>
                            </select>
                            <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                        </div>
                    </div>
                    <div class="form-group mynti-input-container">
                        <label class="mynti-box light-size" for="course-second">Select Course 2<sup>nd</sup> Choice</label>
                        <div class="input-dropdown relative pill">
                            <select class="form-control"  cascade-target-dropdown="courses-choice-select" data-select-loaded="false" name="second_choice" tabindex="11">
                                <option value="-">Select Specialization</option>
                            </select>
                            <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                        </div>
                    </div>
                    <div class="form-group mynti-input-container">
                        <label class="mynti-box light-size" for="residence">Select State of Residence</label>
                        <div class="input-dropdown relative pill">
                            <select class="form-control" data-casacade-select-target="[cascade-target-dropdown=studycenter-choice-select]" name="residence" tabindex="12">
                                <option value="-">Select State Resident</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                        </div>
                    </div>
                    <div class="form-group mynti-input-container">
                        <label class="mynti-box light-size" for="study-center">Select Study Center</label>
                        <div class="input-dropdown relative pill">
                            <select class="form-control" cascade-target-dropdown="studycenter-choice-select" data-select-loaded="false" name="study_center" tabindex="13">
                                <option value="-">Select Study Center</option>
                            </select>
                            <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                        </div>
                    </div>
                    <div class="form-group mynti-input-container">
                        <label class="mynti-box light-size" for="application-fees">Application Form Fees</label>
                        <div class="input-text mynti-mega-input form-group-disabled relative not-flexible pill">
                            <input type="text" class="form-control" readonly="readonly" name="application-fees" tabindex="14">
                            <span id="fee" class="mynti-icon absolute snap-left-top-center currency-placeholder">₦</span>
                        </div>
                    </div>
                    
                    <div class="form-group mynti-input-container">
                        <a href="javascript:void(0);" rel="next" class="btn mynti-button-calm continue pill" tabindex="15" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                        <button type="submit" name="send" class="btn sub mynti-button-calm continue pill" tabindex="15"><b class="">Continue &rsaquo;</b></button>
                    </div>
                </form>
            </section>
            <footer class="mynti-main-footer">
                <div class="mynti-copyright-info-container">
                    <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="16">Omniswift <b class="">|</b></a> <a href="/" tabindex="">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                </div>
            </footer>
        </section>
    </main>

	 @include('applicants.scripts.programme_scripts')

    @section('scripts')

        @parent

        <script type="text/javascript" src="{{ asset('assets/js/applicants/programme-formunit.js')}}"></script>
      

        <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js')}}"></script>

    @endsection 
@endsection