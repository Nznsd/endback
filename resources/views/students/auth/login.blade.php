@extends('students.layouts.guest')

@section('content')
<div class="mynti-login-suspended-box clearfix ultrapill">
                        <aside class="mynti-aside-box">
                            <h1 class="mynti-heading heading-regular mynti-board">Returning Students</h1>
                            <p class="mynti-subheading subheading-light mynti-caption"></p>
                        </aside>
                        <div class="mynti-panel-box relative right-half-ultrapill" style="min-height:450px;">
                            <h3 class="mynti-heading heading-light mynti-board text-titlecase">Sign in</h3>
                            <p style="margin-left: 32px;">
                                
                                    <small class="text-danger">{{ session('status') }}</small>

                            </p>    

                                    
                                
                            <form class="mynti-login-form-o" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box email-input-box">
                                        <label for="email" class="lighter-size text-together">Email</label>
                                        <input type="email" class="text-field tablet {{ $errors->has('email') ? ' has-error' : '' }}" name="email" value="{{ old('email') }}" tabindex="6" placeholder="Enter email address"  autofocus="true" required="required">
                                        @if ($errors->has('email'))
                                    <span class="help-block">
                                        <small class="text-danger">
                                        {{ $errors->first('email') }}
                                        </small>
                                    </span>
                                @endif

                                    </span>
                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">Password</label>
                                        <input type="password" class="password-field tablet {{ $errors->has('password') ? ' has-error' : '' }}" name="password" tabindex="7" placeholder="Enter password" required="required">
                                       @if ($errors->has('password'))
                                    <span class="help-block">
                                        <small class="text-danger">
                                        {{ $errors->first('password') }}
                                        </small>
                                    </span>
                                @endif

                                    </span>
                                     <small>
                                        <a href="{{ url('/password/reset') }}">Reset your password.</a>
                                    </small>
                                   
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box">
                                        <input type="checkbox" id="remember_me" tabindex="7" name="remember" {{ old('remember') ? 'checked' : '' }}>&nbsp;<label for="remember_me" class="lighter-size text-together"><span class="">Keep me signed in</span></label>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="">
                                        <button class="btn btn-primary" data-toggle="popover" data-trigger="focus" data-placement="left" title="Login With NTI Office Email" data-content="If you don't have an NTI Email, click Create Account to get one.">Login</button>
                                    </span>
                                    <span class="">
                                        <a href="{{ url('/oauth') }}" class="btn btn-danger">Log in with Office 365</a>
                                    </span>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <small class="mynti-box">
                                    </small>
                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <small class="mynti-box">
                                        Don't have an Office 365 account? <a href="{{ url('/students/create') }}">Create your account</a>
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

    
@endsection                    