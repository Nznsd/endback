@extends('applicants.layouts.master')
  @section('title', 'Biodata')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Personal information</h3>
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
                                <li><a href="#" tabindex="-1"><span class="numbering badge">4</span><span class="label">Personal Info</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            
                    </div>
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                 <div class="stretchable" style="">   
                                            <form class="mynti-context-form" id="geoinfo-thunk" name="geoinfo-thunk" action="/applicants/personal-information" method="post" target="_top" novalidate>
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">Personal Information</h2>
                                                </div>
                                                @include('applicants.layouts.errors')
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="state_origin">State Of Origin<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" id="state_origin" name="state_origin" tabindex="18" data-casacade-select-target="#lga_origin" data-select-loaded="false">
                                                                    <option value="-">Select State Of Origin</option>
                                                                    @foreach($states as $state)
                                                                        <option value="{{ $state->id }}" 
                                                                            {{ $applicant->soo == $state->id ? 'selected="selected"' : '' }}>
                                                                            {{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="lga_origin">Local Government Of Origin<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" id="lga_origin" name="lga_origin" tabindex="19" data-select-loaded="false">
                                                                    <option value="-">Select Local Government Of Origin</option>
                                                                    @if(isset($applicant->soo_lga))
                                                                        <option value="{{ $applicant->soo_lga }}" selected>{{ $lgas[$applicant->soo_lga - 1]->name }}</option>
                                                                    @endif
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="state_residence">State Of Residence<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" id="state_residence" name="state_residence" tabindex="20" data-casacade-select-target="#lga_residence" data-select-loaded="false">
                                                                    <option value="-">Select State Of Residence</option>
                                                                     @foreach($states as $state)
                                                                        <option value="{{ $state->id }}" 
                                                                            {{ $applicant->sor == $state->id ? 'selected="selected"' : '' }}>
                                                                            {{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 col-md-6 mynti-split-input">
                                                            <label class="mynti-box lighter-size" for="lga_residence">Local Government Of Residence<sup>*</sup></label>
                                                            <div class="input-dropdown relative pill">
                                                                <select class="form-control" id="lga_residence" name="lga_residence" tabindex="21" data-casacade-select-target="null" data-select-loaded="false">
                                                                    <option value="-">Select Local Government Of Residence</option>
                                                                    @if(isset($applicant->sor_lga))
                                                                        <option value="{{ $applicant->sor_lga }}" selected>{{ $lgas[$applicant->sor_lga - 1]->name }}</option>
                                                                    @endif
                                                                </select>
                                                                <span class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                    <label class="mynti-box lighter-size" for="address">Residential Address</label>
                                                    <div class="input-text relative pill">
                                                        <input type="text" class="form-control" value="{{ $applicant->address }}" placeholder="Enter Residential Address" name="address" tabindex="22">
                                                    </div>
                                                </div>
                                                <div class="form-group mynti-input-container">
                                                @if(!isset($applicant->address))
                                                    
                                                    <a href="javascript:void(0);" rel="next" class="btn mynti-button-calm pill continue" tabindex="23" disabled="disabled"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                                                    <button class="btn mynti-button-calm pill continue" tabindex="23"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></button>
                                
                                                @else
                                                    <button class="btn mynti-button-calm" tabindex="23"><b class="">Continue &rsaquo;</b></button>
                                                     {!! csrf_field() !!}
                                                @endif
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

          <script type="text/javascript" src="{{ asset('assets/js/applicants/personalinfo-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection