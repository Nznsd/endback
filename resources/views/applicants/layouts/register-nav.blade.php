<header class="mynti-main-header shadow-around clearfix">
            <div class="pull-left mynti-branding" role="branding">
                <span class="mynti-box branding-item">
                    <a href="/" class="mynti-link logo-link" tabindex="0">O</a>
                </span>
            </div>
            <nav class="mynti-desktop-navigation" role="navigation">
                <span class="mynti-box nav-item">
                    <a href="{{ isset($page) && $page == 'login' ? '#' : '/applicants/login' }}" class="mynti-link mynti-button-calm nav-btn pill {{ isset($page) && $page == 'login' ? 'active' : '' }}" tabindex="-1">Continue Application</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="/" class="mynti-link nav-link" tabindex="1">Home</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="{{ isset($page) && $page == 'register' ? '#' : '/applicants/apply' }}" class="mynti-link nav-link {{ isset($page) && $page == 'register' ? 'active' : '' }}" tabindex="2">New Application</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="{{ env('APP_URL') . '/application-guide' }}" class="mynti-link nav-link" tabindex="3">How to Apply</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="{{ env('APP_URL') . '/support' }}" class="mynti-link nav-link" tabindex="4">Help &amp; Support</a>
                </span>
            </nav>
            <p class="pull-right mynti-mobile-navigation" role="button">
                <button class="btn mynti-button-nav-hamburger" tabindex="5">&#9776;</button>
            </p>
        </header>
        <div class="mynti-old-browser-header upgrade-browser-info">
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Internet Explorer</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Google Chrome</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Mozilla Firefox</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Opera</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
        </div>