@extends('students.layouts.guest')

@section('content')
                    <div class="mynti-login-suspended-box clearfix ultrapill">

                        <aside class="mynti-aside-box">
                            <h1 class="mynti-heading heading-regular mynti-board">Returning Students</h1>
                            <p class="mynti-subheading subheading-light mynti-caption"></p>
                        </aside>

                        <div class="mynti-panel-box relative right-half-ultrapill">
                            <h3 class="mynti-heading heading-light mynti-board text-titlecase">Confirm Reg Number</h3>
                            <p class="mynti-box mynti-alert small-text relative strong hidden absolute snap-left pill text-titlecase"></p>
                            <form class="mynti-login-form" name="loginform" method="post" target="_top" novalidate>
                                <div class="mynti-form-input-container clearfix">

                                    <span class="mynti-box email-input-box">
                                        <label for="email" class="lighter-size text-together">Surname</label>
                                        <input type="text" class="text-field tablet" name="surname" tabindex="6" placeholder="Type Surname here" required>
                                    </span>

                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">Entry Year</label>
                                        <select class="form-control" name="entryYear">
                                            @foreach(range(2017, 2007) as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    
                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">Programme</label>
                                        <select class="form-control" name="programme">
                                            @foreach($programmes as $item)
                                                <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                    
                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">States</label>
                                        <select class="form-control" name="states">
                                            <option>Select State</option>
                                            @foreach($states as $item)
                                                <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>

                                    <span class="mynti-box pass-input-box">
                                        <label for="password" class="lighter-size text-together">Study Center</label>
                                        <select class="form-control" name="studyCenter" required>
                                            <option>Select Center</option>
                                        </select>
                                    </span>

                                </div>
                                <div class="mynti-form-input-container clearfix">
                                    <span class="mynti-box button-box">
                                        <a class="btn mynti-button-calm mynti-button-login-submit mynti-submission-invalid lighter-size tablet" href="#" tabindex="8" id="searchNow"><b class="">Search Now</b></a>
                                    </span>
                                </div>
                                <div style="margin-top: 20px">
                                        <div class="mynti-form-input-container clearfix">
                                            <small class="mynti-box">
                                                Already have an account? <a href="{{ url('/students/login') }}">Login</a>
                                            </small>
                                        </div>
                                    </div>
                                <br>
                                <br>
                            </form>
                        </div>

                    </div>

    @endsection                  