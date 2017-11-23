@extends('students.layouts.guest')

@section('content')
                    <div class="mynti-login-suspended-box clearfix ultrapill">

                        <aside class="mynti-aside-box">
                            <h1 class="mynti-heading heading-regular mynti-board">Returning Students</h1>
                            <p class="mynti-subheading subheading-light mynti-caption"></p>
                        </aside>

                        <div class="mynti-panel-box relative right-half-ultrapill" style="min-height:450px;">
                            <h3 class="mynti-heading heading-light text-titlecase" style="padding: 0 32px 20px 32px">Create Account</h3>
                           
                                <!--Validate-->    
                                    <div style="padding: 0 32px;">
                                        <p><b>Enter Reg No.</b></p>
                                        <form action="#">
                                            <input type="text" class="text-field tablet" name="regNo" tabindex="6" placeholder="Eg: NTI/NCE/1960/001" value="{{ @request()->regNo }}" autofocus="true" style="max-width: 200px;">
                                            <button class="btn btn-primary" id="validate" data-toggle="popover" data-trigger="focus" data-placement="top" title="Click To Validate Reg Number" data-content="If you have the new NTI Reg Number, enter it here to an click Validate.">Validate</button>
                                        </form>    
                                        <p id="info" style="margin-top: 5px;"></p>
                                    </div>
                               
                            <form style="padding: 0 32px;" method="POST" action="{{ url('/create') }}" id="createAccountForm">

                                {{ csrf_field() }}

                                <div class="clearfix">
     
                                    <!--Display: None-->
                                    <div style="margin-top: 20px;">

                                        <div style="overflow: scroll; overflow-x: hidden; max-height: 250px; display:none" id="validatedForm">
                                           
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Full name</label>
                                                <input type="text" name="fullname" class="text-field tablet" tabindex="7" disabled>
                                                <input type="hidden" name="surname" class="text-field tablet" tabindex="7">
                                                <input type="hidden" name="firstname" class="text-field tablet" tabindex="7">
                                                <input type="hidden" name="regNo" class="text-field tablet" tabindex="7">
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Programme</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="programme" disabled>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Specialization</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="specialization" disabled>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Entry Year</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="entryYear" disabled>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">State of Residence</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="sor" disabled>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Study Center</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="studyCenter" disabled>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Recovery Email</label>
                                                <input type="email" class="text-field tablet" tabindex="7" name="recoveryEmail" placeholder="Enter email address (required)" required>
                                            </div>
                                            <div class="mynti-box-o">
                                                <label class="lighter-size text-together">Phone</label>
                                                <input type="text" class="text-field tablet" tabindex="7" name="phone" maxlength="11" placeholder="Enter phone number (required)" required>
                                            </div>
                                            <br/>

                                            <div class="mynti-box-o">
                                                <input type="checkbox" id="confirm" tabindex="7" name="confirm">&nbsp;<label for="confirm" class="lighter-size text-together"><span class="">I certify that the above data is correct.</span></label>
                                            </div>
                                            <div class="mynti-box-o">
                                                <button class="btn btn-primary" id="submitFormBtn" style="display: none;">Create Account</button>
                                                <button class="btn btn-primary" id="submitFormBtnDisabled" disabled>Create Account</button>
                                            </div>    
                                        
                                        </div>

                          
                                    </div>

                                    <!--Bottom-->
                                    <div style="margin-top: 20px">
                                        <div class="mynti-form-input-container clearfix">
                                            <small class="mynti-box">
                                                Already have an account? <a href="{{ url('/students/login') }}">Login</a>
                                            </small>
                                            <small class="mynti-box" style="margin-left:0;padding-left:0;margin-top:15px;">
                                                If you don't know your Registration Number, click <a href="{{ url('/students/confirm') }}">here</a>
                                            </small>
                                        </div>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                    </div>

    @endsection                  

