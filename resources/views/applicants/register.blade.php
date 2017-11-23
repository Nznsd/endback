@extends('applicants.layouts.master')
  @section('title', 'Apply')
  @section('content')
    @include('applicants.layouts.register-nav')
    <main class="mynti-main-section">
            <section class="mynti-register-container">
                    <div class="mynti-register-suspended-box clearfix ultrapill">
                        <aside class="mynti-aside-box">
                            <h1 class="mynti-heading heading-regular mynti-board">New and Prospective Students</h1>
                            <p class="mynti-subheading subheading-light mynti-caption">Create your MyNTI Student Account to get started with the new student registration.</p>
                        </aside>
                        <div class="mynti-panel-box relative right-half-ultrapill">
                            <h3 class="mynti-heading heading-light mynti-board">Start Application</h3>
                            <p class="mynti-box mynti-alert small-text relative strong hidden absolute snap-left pill text-titlecase"></p>
                            
                            <form class="mynti-register-form" name="registerform" method="post" action="/applicants/create" target="_self">
                        
                            @include('applicants.layouts.status')
                            @include('applicants.layouts.errors')
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box text-input-box">
                                        <label for="firstname" class="lighter-size text-together">First Name<sup>*</sup></label>
                                        <input type="text" class="text-field tablet" minlength="2" maxlength="60" name="firstname" aria-validate-filter="firstname" tabindex="7" placeholder="Type Name" autocomplete="off" spellcheck autofocus>
                                    </span>
                                    <span class="mynti-box text-input-box">
                                        <label for="middlename" class="lighter-size text-together">Other Names</label>
                                        <input type="text" class="text-field tablet" minlength="2" maxlength="60" name="middlename" aria-validate-filter="middlename" tabindex="8" placeholder="Type Other Names" autocomplete="off" spellcheck>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box text-input-box">
                                        <label for="lastname" class="lighter-size text-together">Surname<sup>*</sup></label>
                                        <input type="text" class="text-field tablet" minlength="2" maxlength="60" name="lastname" aria-validate-filter="lastname" tabindex="9" placeholder="Type Surname" autocomplete="off" spellcheck>
                                    </span>
                                    <span class="mynti-box text-input-box">
                                        <label for="email" class="lighter-size text-together">Email Address<sup>*</sup></label>
                                        <input type="email" class="text-field tablet" maxlength="80" name="email" tabindex="10" placeholder="Type Email" autocomplete="off" spellcheck>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box text-input-box">
                                        <label for="phone" class="lighter-size text-together">Mobile Number<sup>*</sup></label>
                                        <input type="text" class="text-field tablet" minlength="11" name="phone" tabindex="11" placeholder="080 3453 0000" autocomplete="off" spellcheck>
                                    </span>
                                    <span class="mynti-box text-input-box relative">
                                        <label for="gender" class="lighter-size text-together">Gender<sup>*</sup></label>
                                        <select class="dropdown-field tablet" name="gender" aria-validate-filter='{"male":"Male","female":"Female"}' tabindex="12">
                                            <option value="-">--</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <var class="stub tablet absolute">Select Gender</var>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box text-input-box">
                                        <label for="birthdate" class="lighter-size text-together">Date of Birth<sup>*</sup></label>
                                        <input type="text" class="text-field tablet" name="birthdate" tabindex="13" placeholder="Enter Date Of Birth" value="" aria-validate-filter="^([01][0-9])\/([0-3][0-9])\/(?:[1-2][0-9]{3})$" autocomplete="off" spellcheck>
                                    </span>
                                    <span class="mynti-box text-input-box relative">
                                        <label for="gender" class="lighter-size text-together">Marital Status<sup>*</sup></label>
                                        <select class="dropdown-field tablet" name="maritalstatus" aria-validate-filter='{"married":"Married","single":"Single"}' tabindex="14">
                                            <option value="-">--</option>
                                            <option value="married">Married</option>
                                            <option value="single">Single</option>
                                        </select>
                                        <var class="stub tablet absolute">Select Marital Status</var>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box password-input-box">
                                        <label for="password_try" class="lighter-size text-together">Choose Password<sup>*</sup></label>
                                        <input type="password" class="password-field tablet" name="password_try" tabindex="15" placeholder="Type Password" autocomplete="off">
                                    </span>
                                    <span class="mynti-box password-input-box">
                                        <label for="password_confirm" class="lighter-size text-together">Confirm Password<sup>*</sup></label><!-- #f6f8fa -->
                                        <input type="password" class="password-field tablet" name="password_confirm" tabindex="16" placeholder="Retype Password" autocomplete="off">
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box button-box">
                                        <a class="btn mynti-button-calm mynti-button-register-submit mynti-submission-invalid lighter-size tablet" href="javascript:void(0);" disabled="disabled" tabindex="16"><b class="">Apply</b></a>
                                        <button class="btn mynti-button-calm mynti-button-register-submit mynti-submission-invalid lighter-size tablet" type="submit" disabled="disabled" tabindex="17"><b class="">Apply</b></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="http://www.omniswift.com" tabindex="18">Omniswift <b class="">|</b></a> <a href="/" tabindex="">Terms of Use <b class="">|</b></a> <a href="/" tabindex="">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>

		@include('applicants.scripts.register_scripts')

        @section('scripts')

            @parent

            <script type="text/javascript" src="{{ asset('assets/js/applicants/register-formunit.js')}}"></script>

            <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>
          
        @endsection
	@endsection