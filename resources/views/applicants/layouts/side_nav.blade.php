<div class="mynti-asidemenu">
                                        <form class="mynti-upload-form pill" id="avatar_upload_thunk" name="avatar_upload_thunk" enctype="multipart/form-data" target="avatarupload_sink">
                                            
                                            <div class="mynti-profile-photo-container text-center" aria-has-uploaded="{{ isset($passport) ? 'true' : 'false' }}">
                                                <h5 class="mynti-asidemenu-heading top-half-pill"><b class="">Profile Picture</b></h5>
                                                <span class="mynti-box mynti-image-placeholder">
                                                @if(env('APP_ENV') == 'production')
                                                    <img src="{{ isset($passport) ? env('AZURE_STORAGE_FILESHARE_URL') . '/' . $passport->src : asset('assets/img/svg/profile_avatar.svg') }}" dynamic-src="source" class="mynti-icon profile-image img-circle img-responsive" alt="Avatar Profile" width="132" height="132">
                                                @else
                                                    <img src="{{ isset($passport) ? env('APP_URL') . str_replace('public/', '/storage/', $passport->src) : asset('assets/img/svg/profile_avatar.svg') }}" dynamic-src="source" class="mynti-icon profile-image img-circle img-responsive" alt="Avatar Profile" width="132" height="132">
                                                @endif
                                                    <input type="file" class="profile-picture-file hidden" name="photo" tabindex="-1">
                                                </span>
                                                <p class="btn-punched-up">
                                                    <a href="javascript:void(0);" tabindex="-1" class="btn mynti-button-calm pill upload-btn" upload-file-async="true" data-toggle="popover" data-content="You can put a face to your application. Simply Click this button to start" title="Upload A Profile Photo" data-trigger="focus"><b class="">Upload Photo</b></a>
                                                    <a href="javascript:void(0);" tabindex="-1" class="btn mynti-button-calm pill change-btn" upload-file-async="true"><b class="">Change Photo</b></a>
                                                </p>
                                            </div>
                                        </form>
                                        <ul class="mynti-application-menu text-left pill">
                                            <li class="mynti-application-menuitem"><a href="/applicants/personal-information" tabindex="9" class="nav-link {{ $page == 'biodata' ? 'active' : '' }}">Personal Information</a></li>
                                            @if(isset($certificates))
                                                <li class="mynti-application-menuitem" accordion="true"><a href="javascript:void(0);" rel="next" tabindex="10" class="nav-link {{ $page == 'certificate' ? 'active' : '' }}">Certificates Obtained</a>
                                                <ul class="mynti-application-submenu">

                                                    @foreach ($certificates as $certificate)
                                                        <li class="mynti-application-submenuitem"><a href="{{ url('/applicants/certificate/' . strtolower($certificate->name)) }}" class="nav-link" tabindex="11">{{ $certificate->name }}</a></li>
                                                    @endforeach
                                                 </ul></li>
                                            @else
                                                <li class="mynti-application-menuitem"><a href="/applicants/certificate" tabindex="10" class="nav-link {{ $page == 'certificate' ? 'active' : '' }}">Certificates Obtained</a>
                                            @endif

                                            <li class="mynti-application-menuitem active"><a href="{{ $applicant->application_state < 6 ? 'javascript:void(0);' : '/applicants/experience' }}" tabindex="15" class="nav-link {{ $page == 'experience' ? 'active' : '' }}">Work Experience</a></li>
                                            <li class="mynti-application-menuitem"><a href="{{ $applicant->application_state < 7 ? 'javascript:void(0);' : '/applicants/uploads' }}" tabindex="16" class="nav-link {{ $page == 'uploads' ? 'active' : '' }}">Documents Upload</a></li>
                                            <li class="mynti-application-menuitem"><a href="{{ $applicant->application_state < 8 ? 'javascript:void(0);' : '/applicants/review' }}" tabindex="17" class="nav-link {{ $page == 'review' ? 'active' : '' }}">Review &amp; Submit</a></li>
                                        </ul>
                                 </div>