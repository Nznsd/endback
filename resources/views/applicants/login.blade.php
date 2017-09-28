@extends('applicants.layouts.master')
  @section('title', 'Login')
  @section('content')
    @include('applicants.layouts.register-nav')
    <main class="mynti-main-section">
            <section class="mynti-login-container">
                    <div class="mynti-login-suspended-box clearfix ultrapill">
                        <aside class="mynti-aside-box">
                            <h1 class="mynti-heading heading-regular mynti-board">Continue Application</h1>
                            <p class="mynti-subheading subheading-light mynti-caption"></p>
                        </aside>
                        <div class="mynti-panel-box relative right-half-ultrapill">
                            <h3 class="mynti-heading heading-light mynti-board text-titlecase">sign in</h3>
                            
                            <p class="mynti-box mynti-alert small-text relative strong hidden absolute snap-left pill text-titlecase"></p>
                            <form class="mynti-login-form" name="loginform" method="post" action="/applicants/login" target="_self">
                            @include('applicants.layouts.status')
                            @include('applicants.layouts.errors')
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box email-input-box">
                                        <label for="email" class="lighter-size text-together">Email</label>
                                        <input type="text" class="text-field tablet" name="email" tabindex="6" placeholder="Type Email here" autocomplete="off">
                                    </span>
                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">Password</label>
                                        <input type="password" class="password-field tablet" name="password" tabindex="7" placeholder="Type Password here" autocomplete="off">
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box">
                                        <input type="checkbox" name="remember" tabindex="7">&nbsp;<label for="remember" class="lighter-size text-together">Remember My Email</label>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box button-box">
                                        <a class="btn mynti-button-calm mynti-button-login-submit mynti-submission-invalid lighter-size tablet" href="javascript:void(0);" disabled="disabled" tabindex="8"><b class="">SignIn</b></a>
                                        <button class="btn mynti-button-calm mynti-button-login-submit mynti-submission-invalid lighter-size tablet" type="submit" tabindex="8"><b class="">Sign In</b></button>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box">
                                        If this is your first time, kindly <a href="/applicants" class="text-titlecase">create your account here.</a>
                                    </span>
                                    <span class="mynti-box punched-up">
                                        I forgot my <a href="/password/reset" class="text-titlecase">password </a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <footer class="mynti-main-footer">
                        <div class="mynti-copyright-info-container">
                            <span class="mynti-box text-center lighter-size"> Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniwsift.com" tabindex="17">Omniswift <b class="">|</b></a> <a href="/" tabindex="9">Terms of Use <b class="">|</b></a> <a href="/" tabindex="10">Privacy Policy</a> </span>
                        </div>
                    </footer>
            </section>
        </main>

		@include('applicants.scripts.login_scripts')

        @section('scripts')

          @parent

          <script type="text/javascript" src="{{ asset('assets/js/applicants/login-formunit.js')}}"></script>

          <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js')}}"></script>

        @endsection
	@endsection