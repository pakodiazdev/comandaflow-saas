<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentationTitle }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('swagger-ui/swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('swagger-ui/favicon-32x32.png') }}" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{ asset('swagger-ui/favicon-16x16.png') }}" sizes="16x16"/>
    <style>
    html
    {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
    }
    *,
    *:before,
    *:after
    {
        box-sizing: inherit;
    }

    body {
      margin:0;
      background: #fafafa;
      padding-top: 120px; /* Space for fixed header and login bar */
    }
    
    /* Fixed Header Styles */
    .swagger-ui .topbar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1000 !important;
    }
    
    /* Fixed Quick Login Bar */
    #quick-login-container {
        position: fixed !important;
        top: 60px !important; /* Below the header */
        left: 0 !important;
        right: 0 !important;
        z-index: 999 !important;
        background: #f8f9fa !important;
        border-bottom: 2px solid #e9ecef !important;
        padding: 15px !important;
    }
    </style>
    @if(config('l5-swagger.defaults.ui.display.dark_mode'))
        <style>
            body#dark-mode,
            #dark-mode .scheme-container {
                background: #1b1b1b;
            }
            #dark-mode .scheme-container,
            #dark-mode .opblock .opblock-section-header{
                box-shadow: 0 1px 2px 0 rgba(255, 255, 255, 0.15);
            }
            #dark-mode .operation-filter-input,
            #dark-mode .dialog-ux .modal-ux,
            #dark-mode input[type=email],
            #dark-mode input[type=file],
            #dark-mode input[type=password],
            #dark-mode input[type=search],
            #dark-mode input[type=text],
            #dark-mode textarea{
                background: #343434;
                color: #e7e7e7;
            }
            #dark-mode .title,
            #dark-mode li,
            #dark-mode p,
            #dark-mode table,
            #dark-mode label,
            #dark-mode .opblock-tag,
            #dark-mode .opblock .opblock-summary-operation-id,
            #dark-mode .opblock .opblock-summary-path,
            #dark-mode .opblock .opblock-summary-path__deprecated,
            #dark-mode h1,
            #dark-mode h2,
            #dark-mode h3,
            #dark-mode h4,
            #dark-mode h5,
            #dark-mode .btn,
            #dark-mode .tab li,
            #dark-mode .parameter__name,
            #dark-mode .parameter__type,
            #dark-mode .prop-format,
            #dark-mode .loading-container .loading:after{
                color: #e7e7e7;
            }
            #dark-mode .opblock-description-wrapper p,
            #dark-mode .opblock-external-docs-wrapper p,
            #dark-mode .opblock-title_normal p,
            #dark-mode .response-col_status,
            #dark-mode table thead tr td,
            #dark-mode table thead tr th,
            #dark-mode .response-col_links,
            #dark-mode .swagger-ui{
                color: wheat;
            }
            #dark-mode .parameter__extension,
            #dark-mode .parameter__in,
            #dark-mode .model-title{
                color: #949494;
            }
            #dark-mode table thead tr td,
            #dark-mode table thead tr th{
                border-color: rgba(120,120,120,.2);
            }
            #dark-mode .opblock .opblock-section-header{
                background: transparent;
            }
            #dark-mode .opblock.opblock-post{
                background: rgba(73,204,144,.25);
            }
            #dark-mode .opblock.opblock-get{
                background: rgba(97,175,254,.25);
            }
            #dark-mode .opblock.opblock-put{
                background: rgba(252,161,48,.25);
            }
            #dark-mode .opblock.opblock-delete{
                background: rgba(249,62,62,.25);
            }
            #dark-mode .loading-container .loading:before{
                border-color: rgba(255,255,255,10%);
                border-top-color: rgba(255,255,255,.6);
            }
            #dark-mode svg:not(:root){
                fill: #e7e7e7;
            }
            #dark-mode .opblock-summary-description {
                color: #fafafa;
            }
        </style>
    @endif
</head>

<body @if(config('l5-swagger.defaults.ui.display.dark_mode')) id="dark-mode" @endif>

<div id="swagger-ui"></div>

<!-- Quick Login Form - Fixed position below header -->
<div id="quick-login-container">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-weight: bold; color: #495057;">🔐 Quick Login:</span>
            <input type="email" id="quick-email" placeholder="Email" 
                   style="padding: 8px 12px; border: 1px solid #ced4da; border-radius: 4px; width: 200px;">
            <input type="password" id="quick-password" placeholder="Password"
                   style="padding: 8px 12px; border: 1px solid #ced4da; border-radius: 4px; width: 150px;">
            <button id="quick-login-btn" onclick="doQuickLogin()"
                    style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Login
            </button>
        </div>
        <div id="login-status" style="color: #666; font-size: 14px;">
            Enter credentials to authenticate automatically
        </div>
    </div>
</div>

<!-- Logout Button - Floating in top right -->
<div id="logout-container" style="display: none; position: fixed; top: 70px; right: 20px; z-index: 998;">
    <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span id="logged-user-info" style="font-size: 12px; color: #495057; font-weight: bold;"></span>
            <button id="logout-btn" onclick="doLogout()"
                    style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
                Logout
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('swagger-ui/swagger-ui-bundle.js') }}"></script>
<script src="{{ asset('swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
<script>
    window.onload = function() {
        const urls = [];
        const isTenant = "{{ $documentation }}" === "tenant";
        const isCentral = "{{ $documentation }}" === "central";

        @foreach($urlsToDocs as $title => $url)
            urls.push({name: "{{ $title }}", url: "{{ $url }}"});
        @endforeach

        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',
            // Only show URLs selector in Central, hide in Tenant
            @if(count($urlsToDocs) > 1 && $documentation === 'central')
            urls: urls,
            "urls.primaryName": "{{ $documentationTitle }}",
            @else
            url: "{{ $urlsToDocs[$documentationTitle] ?? array_values($urlsToDocs)[0] }}",
            @endif
            operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
            configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
            validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
            oauth2RedirectUrl: "{{ url('api/oauth2-callback') }}",

            requestInterceptor: function(request) {
                request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: "StandaloneLayout",
            docExpansion : "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
            deepLinking: true,
            filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
            persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

        })

        window.ui = ui

        // Hide server selector for both Central and Tenant
        setTimeout(function() {
            const serverSelector = document.querySelector('.servers select');
            if (serverSelector) {
                serverSelector.style.display = 'none';
            }
        }, 500);

        @if(in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
        ui.initOAuth({
            usePkceWithAuthorizationCodeGrant: "{!! (bool)config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
        })
        @endif
    }

    // Quick Login Function
    async function doQuickLogin() {
        const email = document.getElementById('quick-email').value;
        const password = document.getElementById('quick-password').value;
        const loginBtn = document.getElementById('quick-login-btn');
        const statusDiv = document.getElementById('login-status');

        if (!email || !password) {
            updateStatus('Please enter both email and password', 'error');
            return;
        }

        // Show loading state
        loginBtn.disabled = true;
        loginBtn.textContent = 'Logging in...';
        loginBtn.style.background = '#6c757d';
        updateStatus('Authenticating...', 'loading');

        try {
            const response = await fetch('/api/v1/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            const token = data.data?.access_token;
            const user = data.data?.user;
            
            if (!token) {
                throw new Error('No access token received');
            }

            // Store user info for logout display
            localStorage.setItem('currentUser', JSON.stringify(user));
            localStorage.setItem('accessToken', token);

            // Authorize Swagger UI
            window.ui.authActions.authorize({
                bearerAuth: {
                    name: 'bearerAuth',
                    schema: {
                        type: 'http',
                        scheme: 'bearer'
                    },
                    value: token
                }
            });

            updateStatus('✅ Login successful! You are now authenticated.', 'success');
            
            // Clear form
            document.getElementById('quick-email').value = '';
            document.getElementById('quick-password').value = '';

            // Hide login form and show logout button
            toggleAuthUI(true);

        } catch (error) {
            updateStatus('❌ ' + error.message, 'error');
        } finally {
            // Reset button
            loginBtn.disabled = false;
            loginBtn.textContent = 'Login';
            loginBtn.style.background = '#28a745';
        }
    }

    // Logout Function
    async function doLogout() {
        try {
            const token = localStorage.getItem('accessToken');
            
            if (token) {
                // Call logout endpoint
                await fetch('/api/v1/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            }
        } catch (error) {
            console.warn('Logout request failed:', error.message);
        } finally {
            // Clear local storage
            localStorage.removeItem('currentUser');
            localStorage.removeItem('accessToken');
            
                        // Clear Swagger UI authorization properly
            if (window.ui && window.ui.authActions) {
                // Method 1: Use logout action
                window.ui.authActions.logout();
                
                // Method 2: Clear authorization manually
                window.ui.authActions.authorize({
                    bearerAuth: {
                        name: 'bearerAuth',
                        schema: {
                            type: 'http',
                            scheme: 'bearer'
                        },
                        value: ''
                    }
                });
                
                // Method 3: Force clear using backup method
                setTimeout(() => {
                    forceLogoutSwagger();
                }, 100);
            }
            
            // Show login form and hide logout button
            toggleAuthUI(false);
        }
    }

    // Toggle between login form and logout button
    function toggleAuthUI(isLoggedIn) {
        const loginContainer = document.getElementById('quick-login-container');
        const logoutContainer = document.getElementById('logout-container');
        const userInfo = document.getElementById('logged-user-info');
        
        if (isLoggedIn) {
            const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
            loginContainer.style.display = 'none';
            logoutContainer.style.display = 'block';
            userInfo.textContent = `👤 ${user.name || user.email || 'User'}`;
        } else {
            loginContainer.style.display = 'block';
            logoutContainer.style.display = 'none';
            updateStatus('Logged out successfully. Enter credentials to authenticate again.', 'success');
            
            // Clear form fields
            document.getElementById('quick-email').value = '';
            document.getElementById('quick-password').value = '';
        }
    }

    // Force clear Swagger UI authorization (backup method)
    function forceLogoutSwagger() {
        try {
            // Try multiple methods to ensure logout
            if (window.ui && window.ui.getSystem) {
                const system = window.ui.getSystem();
                
                // Clear authorization state
                if (system.authSelectors) {
                    const auths = system.authSelectors.authorized();
                    if (auths && auths.size > 0) {
                        auths.entrySeq().forEach(([key, value]) => {
                            window.ui.authActions.logout([key]);
                        });
                    }
                }
                
                // Force clear auth state
                if (system.authActions && system.authActions.logout) {
                    system.authActions.logout();
                }
            }
            
            // Visual verification - check if authorize button shows "Authorize" or "Logout"
            setTimeout(() => {
                const authorizeBtn = document.querySelector('.btn.authorize');
                if (authorizeBtn && authorizeBtn.textContent.includes('Logout')) {
                    console.log('Swagger still shows as authorized, attempting force clear...');
                    authorizeBtn.click();
                }
            }, 200);
            
        } catch (error) {
            console.warn('Force logout failed:', error.message);
        }
    }

    // Check authentication status on page load
    function checkAuthStatus() {
        const token = localStorage.getItem('accessToken');
        const user = localStorage.getItem('currentUser');
        
        if (token && user) {
            // Auto-authorize Swagger UI if token exists
            window.ui.authActions.authorize({
                bearerAuth: {
                    name: 'bearerAuth',
                    schema: {
                        type: 'http',
                        scheme: 'bearer'
                    },
                    value: token
                }
            });
            
            toggleAuthUI(true);
        } else {
            toggleAuthUI(false);
        }
    }

    function updateStatus(message, type) {
        const statusDiv = document.getElementById('login-status');
        statusDiv.textContent = message;
        
        if (type === 'error') {
            statusDiv.style.color = '#dc3545';
        } else if (type === 'success') {
            statusDiv.style.color = '#28a745';
        } else if (type === 'loading') {
            statusDiv.style.color = '#007bff';
        } else {
            statusDiv.style.color = '#666';
        }
    }

    // Handle Enter key in login form
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('quick-email');
        const passwordInput = document.getElementById('quick-password');
        
        function handleEnter(event) {
            if (event.key === 'Enter') {
                doQuickLogin();
            }
        }
        
        emailInput.addEventListener('keypress', handleEnter);
        passwordInput.addEventListener('keypress', handleEnter);

        // Check authentication status when page loads
        setTimeout(checkAuthStatus, 500);
    });
</script>
</body>
</html>
