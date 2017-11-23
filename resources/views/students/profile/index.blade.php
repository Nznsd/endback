@extends('students.layouts.app')

@section('banner-tab')
    @include('students.profile.bannerTemplate')    
@endsection

@section('content')

    <div class="mynti-tabbed-content-section">

                            <div class="mynti-tab-content-details scrollable-y">

                                <section id="viewprofile">
                                    <div class="container mynti-tab-container">

                                        <div class="row">

                                            <div class="fixed-width mynti-col">

                                                <div class="mynti-col-header">
                                                    
                                                    <h4 class="light-size text-propped">Student Information</h4>

                                                </div>
                                                
                                                <div class="mynti-col-body text-center">

                                                    <div class="mynti-col-section" style="margin-top: 15px;">
                                                        <span style="background-color: #46C35F; padding: 20px; font-size: 2em; color: #ffffff">{{ @$avatar }}</span>
														<!--<img src="../public/assets/img/png/profiler.png" width="208" height="208" class="img-circle img-responsive img-stroked">-->
													</div>
                                                    
													<div class="mynti-col-section" style="margin-top: 25px;">
														 <h5>{{ @$fullname }}</h5>
														 <p class="mynti-col-section-pack">
															<strong class="mynti-col-section-text">Reg Number</strong>
															<span class="mynti-col-section-text">{{ @strtoupper($myProfile->regNo) }}</span>
														 </p>
														 
														 <p class="mynti-col-section-pack">
															<strong class="mynti-col-section-text">Programme</strong>
															<span class="mynti-col-section-text">{{ @strtoupper($NTIServices::getInfo('programmes', 'id', $myProfile->programmeId)->abbr) }}</span>
														 </p>
														 
                                                         <p class="mynti-col-section-pack">
															<strong class="mynti-col-section-text">Specialization</strong>
															<span class="mynti-col-section-text">{{ @strtoupper($NTIServices::getInfo('specializations', 'id', $myProfile->specializationId)->abbr) }}</span>
														 </p>
														 
														 <p class="mynti-col-section-pack">
															<strong class="mynti-col-section-text">Level</strong>
															<span class="mynti-col-section-text">{{ @$level }}</span>
														 </p>
														 
													</div>
                                                </div>

                                            </div>

                                            <div class="flexible-width mynti-col">

                                                <div class="mynti-col-header">
                                                    
                                                    <h4 class="light-size text-propped">Profile Information</h4>

                                                </div>

                                                <div class="mynti-col-body">

                                                        <!-- Error-->

                                                    @if ($errors->any())
                                                        <div class="alert alert-danger" style="margin: 15px;">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif


													<form action="{{ url('students/profile/update') }}" class="pad-content" method="POST" enctype="multipart/form-data" id="profileForm">
													
                                                        {{ csrf_field() }}
														<div class="table-responsive">

                                                                <table class="table table-striped">

                                                                    
                                                                    <tbody class="profileBody" style="color: inherit">

                                                                        <tr>
                                                                            <td>Email:</td>
                                                                            <td>{{ @$myProfile->email }}</td>
                                                                            <td>Gender:</td>
                                                                            <td>
                                                                                <select name="gender">
                                                                                    <option>Not Specified</option>
                                                                                    @foreach($genderArray as $item)
                                                                                        <option value="{{ $item }}" 
                                                                                        @if( $item == $myProfile->gender)
                                                                                             selected
                                                                                        @endif>{{ $item }}</option>
                                                                                    @endforeach
                                                                                </select>    
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>Phone No:</td>
                                                                            <td><input type="input" name="phoneNo" maxlength="11" value="{{@$myProfile->phoneNo}}" /></td>
                                                                            <td>DOB:</td>
                                                                            <td>
                                                                                <input type="date" name="dob" value="{{@$myProfile->dob}}" placeholder="mm/dd/yyyy" /></td>
                                                                            </td>
                                                                            
                                                                        </tr>

                                                                        <tr>
                                                                            
                                                                            <td>Recovery Email:</td>
                                                                            <td><input type="email" required name="recoveryEmail" value="{{@$myProfile->recoveryEmail}}" /></td>
                                                                            
                                                                            
                                                                            <td>Marital Status:</td>
                                                                            <td>
                                                                                <select name="maritalStatus">
                                                                                    @foreach($maritalStatusArray as $item)
                                                                                        <option value="{{ $item }}" 
                                                                                        @if( $item == $myProfile->maritalStatus)
                                                                                             selected
                                                                                        @endif>{{ $item }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>Address:</td>
                                                                            <td colspan="3"><textarea style="width: 100%;" name="address">{{@ucwords($myProfile->address)}}</textarea></td>
                                    
                                                                        </tr>

                                                                        <tr>
                                                                            <td>Study Center:</td>
                                                                            <td colspan="3">{{ @ucwords($NTIServices::getInfo('study_centers', 'id', $myProfile->studyCenter)->name) }}</td>
                                                                            
                                                                        </tr>

                                                                        <tr>
                                                                            <td>State Of Residence:</td>
                                                                            <td>{{ @ucwords($NTIServices::getInfo('states', 'id', $myProfile->sor)->name) }}</td>
                                                                            <td>LGA:</td>
                                                                            <td>{{ @ucwords($NTIServices::getInfo('lga', 'id', $myProfile->sor_lga)->name) }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>State Of Origin:</td>
                                                                            <td>{{ @ucwords($NTIServices::getInfo('states', 'id', $myProfile->soo)->name) }}</td>
                                                                            <td>LGA:</td>
                                                                            <td>{{ @ucwords($NTIServices::getInfo('lga', 'id', $myProfile->soo_lga)->name) }}</td>
                                                                        </tr>

                                                                    </tbody>

                                                                </table>
                                                    
                                                    	</div>
														
                                                    </form>
													
                                                </div>

                                                <div class="mynti-col-header">
                                                    <p style="text-align: right; margin-right: 30px;">
                                                        <button class="btn btn-primary" style="padding: 5px" onclick="document.getElementById('profileForm').submit(); return false;">Update Profile</button>
                                                    </p>
                                                </div>

                                                                                                        <!-- Success-->
                                                    @if(session('status') !== null)
                                                        <script>alert('Profile updated successfully')</script>
                                                    @endif



                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </section>
                            
                            </div>
                                
                        </div>

    
@endsection