@extends('applicants.layouts.master')
  @section('title', 'Uploads')
  @section('content')
    @include('applicants.layouts.nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Other Uploads</h3>
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
                                <li><a href="{{ route('experience') }}" tabindex="-1"><span class="numbering badge">6</span><span class="label">Work Experience</span></a></li>
                                <li><a href="#" tabindex="-1"><span class="numbering badge">7</span><span class="label">Upload Docs</span></a></li>
                            </ul>
                            @include('applicants.layouts.status')
                            @include('applicants.layouts.errors')
                    </div>
                    <section class="container mynti-section-balanced">
                        <section class="clearfix">
                                 @include('applicants.layouts.side_nav')
                                 <div class="stretchable" style="">   
                                            <form class="mynti-context-form" name="" method="post" target="_top" action="/applicants/uploads/save/other" novalidate>
                                                <div class="mynti-form-caption form-heading-frame">
                                                    <h2 class="heading-placeholder-size">Upload Other Documents</h2>
                                                </div>

                                                <div class="mynti-select-upload-form">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-12">
                                                            <div class="clearfix text-left mynti-rrr-verify-container stretchable">
                                                                 <a href="javascript:void(0);" tabindex="19" class="mynti-box mynti-select-upload-link mynti-button-calm pill text-center" unselectable><i class="mynti-spinner-white"></i><b class="">Upload</b></a>
                                                                 <span class="mynti-box mynti-upload-text stretchable input-dropdown relative pill">
                                                                    <select tabindex="18" class="form-control" name="documents">
                                                                        <option value="-">Select Upload Document</option>
                                                                        <option value="marriage_cert">Marriage Certificate</option>
                                                                        <option value="change_of_name">Change of Name</option>
                                                                        <option value="nysc">NYSC</option>           
                                                                    </select>
                                                                    <b class="input-dropdown-addon chevron-btn"><i class="mynti-icon relative chevron"></i></b>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 col-md-12">
                                                            <section id="mynti-upload-forms-batch">

                                                            </section>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mynti-input-container">
                                                    <a href="/applicants/uploads/continue" rel="next" class="btn mynti-button-calm pill continue" tabindex="20" data-toggle="popover" title="Quick Info" data-content="You can click this button to skip if you don't have any other documents to upload" data-trigger="focus"><i class="mynti-spinner-white"></i><b class="">Continue &rsaquo;</b></a>
                                                    
                                                </div>
                                            </form>
                                    </div>
                            </section>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="21">Omniswift <b class="">|</b></a> <a href="/" tabindex="22">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>

        @include('applicants.scripts.uploads_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/docsupload-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection