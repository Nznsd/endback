@extends('applicants.layouts.master')
@section('title', 'Experience')
  @section('content')
    @include('applicants.layouts.nav')

    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Workplace Experience</h3>
                                <p class="mynti-subheading subheading-regular light-size">Please continue your application</p>
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
                                <li><a href="#" tabindex="-1"><span class="numbering badge">6</span><span class="label">Workplace Info</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            
                    </div>
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                 <div class="stretchable" style="">   
                                            <form class="mynti-context-form" id="geoinfo-thunk" name="geoinfo-thunk" method="post" target="_top" novalidate>
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">{{ isset($experience) ? 'Add' : 'Enter' }} Workplace Experience</h2>
                                                </div>
                                                @include('applicants.layouts.errors')


                                            <section class="work_details_block" style="">
                                                <div class="mynti-heading-block">
                                                    <h4 class="lighter-size">first workplace</h4>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-8 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="workplaces[first][employer]">Employer</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" value="{{ isset($experience) ? $experience->employer : '' }}" class="form-control" name="workplaces[first][employer]" placeholder="Type Employer Name" tabindex="" autocomplete="off" spellcheck="true" autofocus="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-4 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="workplaces[first][position]">Position</label>
                                                            <div class="input-text relative pill">
                                                                <input  type="text" value="{{ isset($experience) ? $experience->position : '' }}" class="form-control" name="workplaces[first][position]" placeholder="Type Position" tabindex="" autocomplete="off" spellcheck="true">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="workplaces[first][from_date]">Work Start (Date)</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" aria-changed="false" placeholder="" value="{{ isset($experience) ? \Carbon\Carbon::parse($experience->startDate)->format('m/d/Y') : '11/07/' . strval(intval(date('Y')) - 5) }}" class="form-control date-control" name="workplaces[first][from_date]" tabindex="" autocomplete="off" readonly="readonly">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="workplaces[first][to_date]">Work End (Date)</label>
                                                            <div class="input-text relative pill">
                                                                <input type="text" aria-changed="false" placeholder="" value="{{ isset($experience) ? \Carbon\Carbon::parse($experience->endDate)->format('m/d/Y') : '11/08/' . strval(intval(date('Y')) - 5) }}" class="form-control date-control" name="workplaces[first][to_date]" tabindex="" autocomplete="off" readonly="readonly">
                                                            </div>
                                                            <div class="input-slot relative">
                                                                <input type="checkbox" name="workplaces[first][current_date]" class="current_date no-pick"><span class="text-bit">I currently work here</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <label class="mynti-box lighter-size" for="workplaces[first][job_description]">Job Description</label>
                                                    <div class="input-text relative pill">
                                                        <textarea class="form-control" placeholder="Type Short Description" name="workplaces[first][job_description]" tabindex="22" autocomplete="off" spellcheck="true">{{ isset($experience) ? $experience->desc : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </section>
                                                <div class="form-group mynti-input-container">

                                                    <a href="javascript:void(0);" rel="next" class="btn mynti-button-calm pill continue" tabindex="23" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Submit &rsaquo;</b></a>

                                                    <a href="/applicants/uploads" rel="next" class="btn mynti-button-calm pill skip" data-toggle="popover" data-placement="top" data-content="If you don't have this record for entry. Simply Click this button to continue" title="Skip Past This Page" data-trigger="focus" tabindex="24"><i class="mynti-spinner-white"></i><b class="">Skip</b></a>

                                                    <a href="javascript:void(0);" class="btn mynti-button-groovy pill add" tabindex="25" data-toggle="popover" title="Add Work Experience" data-content="You can add more work place experience if you have any more" data-trigger="focus" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Add Work Experience</b></a>
                                                    
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