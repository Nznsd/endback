@extends('applicants.layouts.master')
  @section('title', 'Dashboard')
  @section('content')
    @include('applicants.layouts.nav')
        <main class="mynti-main-section">
            <section class="mynti-main-container">
                    <article class="mynti-banner">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h3 class="mynti-heading heading-regular">Application Dashboard</h3>
                                <p class="mynti-subheading subheading-regular light-size">
                                     {{ count($admission) > 0 ? 'Issued' : 'Admission Pending' }}</p>
                            </div>
                             <div class="mynti-greetings-container pull-right">
                                <span class="mynti-box">Welcome, <b class="">{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b>
                            </div>
                        </div>
                    </article>
                    <div class="mynti-breadcrumbs">
                            <span class="mynti-reg-number"><b>{{ $applicant->app_no }}</b></span> 
                    </div>
                    
                    <section class="mynti-section-placid">
                    @include('applicants.layouts.status')
                    @include('applicants.layouts.errors')
                        <div class="container mynti-dashboard-box flexible-till-max">
                        @if(count($admission) > 0) 
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                    <section class="mynti-dashlet-container relative ultrapill" aria-show-particulars="true">
                                    <h6 class="text-uppercase mynti-dashlet-heading inverted progress-item">your student information</h6>
                                    <div class="mynti-dashlet-details">
                                        <div class="mynti-dashlet-details-preamble">
                                            <span class="myntibox pull-left dashlet-avatar">
                                                <img src="{{ asset('assets/img/png/userframe.png') }}" width="90" height="90" class="img-responsive">
                                            </span>
                                            <p class="pull-left text-left dashlet-lead"><!-- 270px -->
                                                <span>Please, use the information on this portal to login to the NTI Student Portal.</span>
                                            </p>
                                        </div>
                                        <article class="mynti-dashlet-carousel-panel stretchable relative">
                                            <ul class="mynti-dashlet-carousel">
                                                <li class="carousel-item">
                                                    <strong class="text-titlecase carousel-item-heading">login information</strong>
                                                    <span class="punched-up">
                                                        <dl>
                                                            <dd>Username</dd>
                                                            <dt>Salihu_Funtua</dt>
                                                        </dl>
                                                        <dl>
                                                            <dd>Password</dd>
                                                            <dt><i>************</i><input type="text" value="eR4377K_Q1bn#" mask></dt>
                                                        </dl>
                                                    </span>
                                                </li>
                                                <li class="carousel-item">
                                                    <strong class="text-titlecase carousel-item-heading">your new student email</strong>
                                                    <span class="punched-up">
                                                        <dl>
                                                            <dd>Email</dd>
                                                            <dt>s.funtua@nti.edu.ng</dt>
                                                        </dl>
                                                    </span>
                                                </li>
                                                <li class="carousel-item">
                                                    <strong class="text-titlecase carousel-item-heading">your new student <samp class="text-uppercase">nti</samp> number</strong>
                                                    <span class="punched-up">
                                                        <dl>
                                                            <dd>Number</dd>
                                                            <dt class="text-uppercase">nti/2018/pgde/000137</dt>
                                                        </dl>
                                                    </span>
                                                </li>
                                            </ul>
                                            <a href="javascript:void(0);" class="absolute snap-top-center circular text-center carousel-left-pin" unselectable="unselectable">&laquo;</a>
                                            <a href="javascript:void(0);" class="absolute snap-right-top-center circular text-center carousel-right-pin">&raquo;</a>
                                        </article>
                                    </div>
                                    <span class="mynti-box absolute snap-top-center inverted-box">
                                        <a href="#" class="text-titlecase dashlet-link inverted progress-item"><b class="circular text-icon-plus">&#43;</b><small>login to student portal</small></a>
                                    </span>
                                    </section>
                                </div>
                            </div>
                        @endif
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-3">
                                    <section class="mynti-dashlet-container ultrapill" aria-application-status="incomplete"><!-- incomplete | complete | ratified -->
                                        <h6 class="text-uppercase mynti-dashlet-heading progress-item" mask>application status</h6>
                                        <h6 class="text-uppercase mynti-dashlet-heading level" mask>level or semester</h6>
                                        <div class="text-left mynti-dashlet-details">
                                             <img src="{{ asset('assets/img/svg/mouse.svg') }}" id="mouse" alt="" longdesc="" width="150" height="100" class="img-responsive" mask>
                                             <img src="{{ asset('assets/img/svg/passport.svg') }}" id="passport" alt="" longdesc="" width="150" height="100" class="img-responsive" mask>
                                             <section class="offset-pane">
                                                 <p class="dashlet-info-pane">
                                                 @if($applicant->application_state > 8)
                                                     <big class="text-inflective progress-item" mask>100% Complete</big>
                                                 @else
                                                    <big class="text-inflective progress-item" mask>Incomplete</big>
                                                 @endif
                                                     <big class="text-inflective level" mask>{{ $applicant->entry_level }} Level</big>
                                                 </p>
                                                 <span class="mynti-box">
                                                    @if(count($admission) > 0)
                                                        <a href="#" class="text-titlecase dashlet-link progress-item" mask><b class="circular text-icon-plus">&#43;</b><small>go to student portal</small></a>
                                                    @else
                                                        @if($applicant->application_state > 8)
                                                            <a href="/applicants/review/print" target="blank" class="text-titlecase dashlet-link progress-item" mask><b class="circular text-icon-plus">&#43;</b><small>view application</small></a>
                                                        @else
                                                            <a href="/applicants/review" class="text-titlecase dashlet-link progress-item" mask><b class="circular text-icon-plus">&#43;</b><small>complete application</small></a>
                                                        @endif
                                                    @endif
                                                    
                                                 </span>
                                            </section>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-sm-12 col-xs-12 col-md-6">
                                    <section class="mynti-dashlet-container ultrapill" aria-application-fees="paid"><!-- unpaid | paid -->
                                        <h6 class="text-uppercase mynti-dashlet-heading">payments</h6>
                                        <div class="text-left mynti-dashlet-details">
                                             <img src="{{ asset('assets/img/svg/camera.svg') }}" alt="" longdesc="" width="150" height="100" class="img-responsive">
                                             <section class="offset-pane">
                                                 <p class="dashlet-info-pane">
                                                     @if(isset($payments['total']))
                                                        <big class="text-inflective" id="digits" mask>₦{{ $payments['total'] }}</big>
                                                     @endif
                                                     @if(isset($payments['feeslist']))
                                                     <div class="table-responsive" id="digits-table" mask>
                                                        <table class="inline-table table table-condensed">
                                                          <thead>
                                                            <tr>
                                                               <th class="text-uppercase">fee description</th>
                                                               <th class="text-uppercase">amount</th>
                                                               <th class="text-uppercase">status</th>
                                                            </tr>
                                                            <tbody>
                                                             @foreach($payments['feeslist'] as $fees)
                                                              <tr>
                                                                <td>{{ $fees->desc }}</td>
                                                                <td>{{ $fees->amount }}</td>
                                                                <td>{{ $fees->status }}</td>
                                                              </tr>
                                                             @endforeach
                                                            </tbody>
                                                          </thead>
                                                        </table>
                                                     </div>
                                                    @endif 
                                                 </p>
                                                 <span class="mynti-box">
                                                    @if(true)
                                                        @if(!isset($payments['feeslist']['tuition']))  {{-- if(isset($payments['feeslist']['tuition']) && $payments['feeslist']['tuition']->status != 'paid') --}}
                                                            <a href="/applicants/payments/tuition" target="blank" class="text-titlecase dashlet-link paid" mask><b class="circular text-icon-plus">&#43;</b><small>pay tuition fees</small></a>
                                                        @elseif($payments['feeslist']['tuition']->status != 'paid')
                                                            <a href="/applicants/verify/tuition" target="blank" class="text-titlecase dashlet-link paid" mask><b class="circular text-icon-plus">&#43;</b><small>verify tuition RRR</small></a>
                                                        @endif
                                                     @endif
                                                     <br>
                                                     <a href="/applicants/dashboard/receipts" target="_self" class="text-titlecase dashlet-link paid" mask><b class="circular text-icon-plus">&#43;</b><small>view receipts</small></a>
                                                 </span>
                                            </section>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-sm-12 col-xs-12 col-md-3">
                                    <section class="mynti-dashlet-container ultrapill" aria-admission-status="not-issued"><!-- not-issued | pending | issued -->
                                        <h6 class="text-uppercase mynti-dashlet-heading">admission status</h6>
                                        <div class="text-left mynti-dashlet-details">
                                             <img src="{{ asset('assets/img/svg/blank-paper.svg') }}" id="bp-1" alt="" longdesc="" width="150" height="100" class="img-responsive" mask>
                                             <img src="{{ asset('assets/img/svg/blank-paper-2.svg') }}" id="bp-2" alt="" longdesc="" width="150" height="100" class="img-responsive" mask>
                                             <img src="{{ asset('assets/img/svg/certificate.svg') }}" id="bp-3" alt="" longdesc="" width="150" height="100" class="img-responsive" mask>
                                             <section class="offset-pane">
                                                 <p class="dashlet-info-pane">
                                                    @if(count($admission) > 0)
                                                        <big class="text-inflective" id="noi" mask>Issued</big>
                                                    @else
                                                        <big class="text-inflective" id="noi" mask>Pending</big>
                                                        <!--<big class="text-inflective" id="noi" mask>Not Issued</big>-->
                                                    @endif
                                                 </p>
                                                 <span class="mynti-box">
                                                    @if(count($admission) > 0)
                                                        @if(isset($payments['feeslist']['tuition']) && $payments['feeslist']['tuition']->status == 'paid')
                                                            <a href="/applicants/dashboard/admission/print" target="blank" class="text-titlecase dashlet-link wait" mask><b class="circular text-icon-plus">&#43;</b><small>download admission letter</small></a>
                                                        @else
                                                            <p class="text-titlecase dashlet-link wait" mask><b class="circular text-icon-plus">&#43;</b><small>Pay tuition fees for admission letter</small></p>
                                                        @endif
                                                    @else
                                                        <a href="/applicants/review/print" target="blank" class="text-titlecase dashlet-link wait" mask><b class="circular text-icon-plus">&#43;</b><small>view application form</small></a>
                                                    @endif
                                                 </span>
                                            </section>
                                        </div>
                                    </section>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-12">
                                        <span class="hidden mynti-alert mynti-box"></span>
                                </div>
                            </div>

                            <form class="mynti-admission-letter-form hidden" name="" method="post" target="admission-letter-sink" novalidate>
                            
                                <div class="form-group mynti-input-container">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 col-md-6">
                                            
                                        </div>
                                    </div>
                                </div>
                            
                            </form>
                        </div>
                    </section>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }}  National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="20">Omniswift <b class="">|</b></a> <a href="/" tabindex="21">Terms of Use <b class="">|</b></a> <a href="/" tabindex="22">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>


        <iframe src="about:blank" frameborder="0" allowtransparency="true" id="admission-letter-sink" name="admission-letter-sink" class="absolute box-off" width="150" height="300"></iframe>
    
        @include('applicants.scripts.review_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/review-formunit.js') }}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        @endsection
    @endsection