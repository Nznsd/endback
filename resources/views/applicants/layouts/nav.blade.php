<header class="mynti-main-header shadow-around clearfix" >
    <div class="" style="width:98% !important; padding-top:4px;">
            <div class="pull-left mynti-branding" role="branding">
                <span class="mynti-box branding-item">
                    <a href="/" class="mynti-link logo-link" tabindex="0">O</a>
                </span>
            </div>
            <nav class="mynti-desktop-navigation mynti-toolsbar-nav clearfix" role="navigation">
                <div class="dropdown mynti-toolsbar-item relative">
                            <a href="#" class="mynti-toolsbar-item-btn nav-link dropdown-toggle active" id="mynti-notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="1">
                                <i class="icon-btn-notification">0</i>
                            </a>
                            <div class="dropdown-menu mynti-toolsbar-menu mynti-dropdown-menu-right mynti-dropdown-menu-container borderless" aria-labelledby="mynti-notification">
                                <h5 class="mynti-dropdown-menu-heading top-half-pill">
                                    Notifications <span class="badge pill background-danger">0</span>
                                </h5>
                                <div class="mynti-dropdown-menu-notification-list">
                                    <div class="mynti-dropdown-menu-notification-item persona-container clearfix">
                                        <span class="persona-subject mynti-box">
                                            <img src="{{ asset('assets/img/photo-face.jpg') }}" alt="" class="img-responsive">
                                        </span>
                                        <div class="persona-context flexible-width">
                                           
                                        </div>  
                                    </div>
                                </div>
                                <div class="mynti-dropdown-menu-footing bottom-half-pill">
                                    
                                </div>
                            </div>
                     </div>
                     <div class="dropdown mynti-toolsbar-item relative">
                            <a href="#" class="mynti-toolsbar-item-btn nav-link dropdown-toggle active" id="mynti-messages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="2">
                                <i class="icon-btn-messages">0</i>
                            </a>
                            <div class="dropdown-menu mynti-toolsbar-menu mynti-dropdown-menu-right mynti-dropdown-menu-container borderless" aria-labelledby="mynti-messages">
                                <h5 class="mynti-dropdown-menu-heading top-half-pill">
                                    Inbox<span class="badge pill background-danger">0</span>
                                </h5>
                                <div class="mynti-dropdown-menu-messages-list clearfix">
                                    <div class="mynti-dropdown-menu-messages-item persona-container clearfix">
                                        <span class="persona-subject mynti-box">
                                            <img src="{{ asset('assets/img/photo-face.jpg') }}" alt="" class="img-responsive"  tabindex="-1">
                                        </span>
                                        <div class="persona-context flexible-width">
                                            
                                        </div>  
                                    </div>
                                </div>
                                <div class="mynti-dropdown-menu-footing bottom-half-pill">
                                    
                                </div>
                            </div>
                     </div>
                     <div class="dropdown mynti-toolsbar-item relative user-board">
                            <a href="javascript:void(0);" class="mynti-toolsbar-item-btn nav-link dropdown-toggle" id="mynti-user-board" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="3">
                                <i class="icon-btn-user">0</i>
                            </a>
                            <div class="dropdown-menu mynti-toolsbar-menu mynti-dropdown-menu-right" aria-labelledby="mynti-user-board">
                                <a class="dropdown-item" href="#" tabindex="6">Help</a>
                                <!--<div class="dropdown-divider"></div>-->
                                @if(Auth::check())
                                    <a class="dropdown-item" href="/applicants/logout" tabindex="7">Logout</a>
                                @else
                                    <a class="dropdown-item" href="/applicants/login" tabindex="7">Login</a>
                                @endif
                            </div>
                    </div>
            </nav>
            <p class="pull-right mynti-mobile-navigation" role="button">
                <button class="btn mynti-button-nav-hamburger" tabindex="8">&#9776;</button>
            </p>
            </div>
        </header>
        <div class="mynti-old-browser-header upgrade-browser-info">
            <p class=""><span class="">You are using an extremely old version of the </span><a href="" tabindex="-1"><b>Internet Explorer</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="" tabindex="-1"><b>Google Chrome</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="" tabindex="-1"><b>Mozilla Firefox</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Opera</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
        </div>