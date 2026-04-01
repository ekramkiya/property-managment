<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MMS | سیستم مدیریت مارکیت</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=vazirmatn:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Vazirmatn', system-ui, -apple-system, sans-serif;
                background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2c3e50 100%);
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
            }

          
            @keyframes float {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-20px) rotate(5deg);
                }
            }

            /* Main container - improved responsive padding */
            .container {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            /* Card styles - fully responsive */
            .card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                border-radius: 1.5rem;
                padding: 1.5rem;
                max-width: 100%;
                width: 100%;
                text-align: center;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                transform: translateY(0);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                animation: fadeInUp 0.8s ease-out;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Logo styles - responsive */
            .logo {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #3498db 0%, #2ecc71 50%, #f1c40f 100%);
                border-radius: 1.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
                animation: pulse 2s infinite;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }

            .logo svg {
                width: 45px;
                height: 45px;
                color: white;
            }

            /* Typography - responsive font sizes */
            h1 {
                font-size: 2rem;
                font-weight: 800;
                background: linear-gradient(135deg, #3498db 0%, #2ecc71 50%, #f1c40f 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 0.5rem;
                letter-spacing: -0.02em;
            }

            .badge {
                display: inline-block;
                background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
                color: white;
                padding: 0.25rem 1rem;
                border-radius: 2rem;
                font-size: 0.75rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }

            .subtitle {
                font-size: 1.1rem;
                color: #2c3e50;
                margin-bottom: 1rem;
                font-weight: 700;
            }

            .description {
                color: #5a6c7e;
                margin-bottom: 1.5rem;
                line-height: 1.7;
                font-size: 0.9rem;
                text-align: justify;
            }

            /* Button styles - responsive */
            .btn-login {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 2.5rem;
                font-size: 0.95rem;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
                border: none;
                cursor: pointer;
                width: auto;
                min-width: 180px;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(52, 152, 219, 0.5);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .btn-login svg {
                width: 18px;
                height: 18px;
            }

            /* Features grid - fully responsive */
            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 1rem;
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid #e2e8f0;
            }

            .feature-item {
                text-align: center;
                padding: 0.75rem;
                transition: transform 0.3s ease;
            }

            .feature-item:hover {
                transform: translateY(-3px);
            }

            .feature-icon {
                width: 55px;
                height: 55px;
                background: linear-gradient(135deg, #3498db20 0%, #2ecc7120 100%);
                border-radius: 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.75rem;
                transition: all 0.3s ease;
            }

            .feature-item:hover .feature-icon {
                transform: scale(1.05);
                background: linear-gradient(135deg, #3498db30 0%, #2ecc7130 100%);
            }

            .feature-icon svg {
                width: 28px;
                height: 28px;
                color: #3498db;
            }

            .feature-title {
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .feature-desc {
                font-size: 0.75rem;
                color: #718096;
                line-height: 1.5;
            }

            /* Stats section - responsive */
            .stats {
                display: flex;
                justify-content: space-around;
                margin-top: 1.5rem;
                padding: 1rem;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 1rem;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .stat-item {
                text-align: center;
                flex: 1;
                min-width: 80px;
            }

            .stat-number {
                font-size: 1.2rem;
                font-weight: 800;
                color: #3498db;
            }

            .stat-label {
                font-size: 0.7rem;
                color: #718096;
                margin-top: 0.25rem;
            }

            /* Footer - responsive */
            .footer {
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid #e2e8f0;
                font-size: 0.7rem;
                color: #a0aec0;
            }

            /* Tablet Styles (768px and up) */
            @media (min-width: 768px) {
                .container {
                    padding: 1.5rem;
                }

                .card {
                    padding: 2rem;
                    border-radius: 1.75rem;
                }

                .logo {
                    width: 100px;
                    height: 100px;
                }

                .logo svg {
                    width: 55px;
                    height: 55px;
                }

                h1 {
                    font-size: 2.5rem;
                }

                .badge {
                    padding: 0.3rem 1.2rem;
                    font-size: 0.85rem;
                }

                .subtitle {
                    font-size: 1.2rem;
                }

                .description {
                    font-size: 0.95rem;
                }

                .btn-login {
                    padding: 0.85rem 2rem;
                    font-size: 1rem;
                }

                .features {
                    gap: 1.5rem;
                    grid-template-columns: repeat(2, 1fr);
                }

                .feature-icon {
                    width: 65px;
                    height: 65px;
                }

                .feature-icon svg {
                    width: 32px;
                    height: 32px;
                }

                .stat-number {
                    font-size: 1.3rem;
                }
            }

            /* Desktop Styles (1024px and up) */
            @media (min-width: 1024px) {
                .container {
                    padding: 2rem;
                }

                .card {
                    max-width: 1000px;
                    padding: 3rem;
                    border-radius: 2rem;
                }

                .logo {
                    width: 120px;
                    height: 120px;
                }

                .logo svg {
                    width: 70px;
                    height: 70px;
                }

                h1 {
                    font-size: 3.5rem;
                }

                .badge {
                    padding: 0.35rem 1.5rem;
                    font-size: 0.9rem;
                }

                .subtitle {
                    font-size: 1.4rem;
                }

                .description {
                    font-size: 1rem;
                }

                .btn-login {
                    padding: 1rem 2.8rem;
                    font-size: 1.1rem;
                }

                .features {
                    gap: 2rem;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                }

                .feature-icon {
                    width: 75px;
                    height: 75px;
                }

                .feature-icon svg {
                    width: 40px;
                    height: 40px;
                }

                .feature-title {
                    font-size: 1.1rem;
                }

                .feature-desc {
                    font-size: 0.875rem;
                }

                .stat-number {
                    font-size: 1.5rem;
                }
            }

            /* Large Desktop Styles (1440px and up) */
            @media (min-width: 1440px) {
                .card {
                    max-width: 1200px;
                }

                h1 {
                    font-size: 4rem;
                }

                .subtitle {
                    font-size: 1.6rem;
                }

                .features {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            /* Touch-friendly improvements for mobile */
            @media (hover: none) and (pointer: coarse) {
                .btn-login, .feature-item {
                    cursor: default;
                }
                
                .btn-login:active {
                    transform: scale(0.98);
                }
            }

            /* Landscape mode optimization */
            @media (max-width: 768px) and (orientation: landscape) {
                .container {
                    padding: 0.5rem;
                }

                .card {
                    padding: 1rem;
                }

                .logo {
                    width: 60px;
                    height: 60px;
                    margin-bottom: 0.75rem;
                }

                h1 {
                    font-size: 1.5rem;
                }

                .features {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.75rem;
                    margin-top: 1rem;
                }

                .stats {
                    margin-top: 1rem;
                }
            }
        </style>
    </head>
    <body>


        <div class="container">
            <div class="card">
                <!-- Logo -->
                <div class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3h8" />
                    </svg>
                </div>

                <!-- Badge -->
                {{-- <div class="badge">
                    مدیریت هوشمند مجتمع‌های تجاری
                </div> --}}

                <!-- Title and Description -->
                <h1>MMS</h1>
                <div class="subtitle">
                    سیستم مدیریت مارکیت
                </div>

                <!-- Login Button -->
                <div>
                    @auth
                        <a href="{{ url('/admin') }}" class="btn-login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            ورود به پنل مدیریت
                        </a>
                    @else
                        <a href="{{ url('/admin/login') }}" class="btn-login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            ورود به سیستم
                        </a>
                    @endauth
                </div>



                <!-- Footer -->
                <div class="footer">
                   
                    <div style="margin-top: 0.5rem;">© ۲۰۲۵ MMS - سیستم مدیریت مارکیت | مدیریت هوشمند مجتمع‌های تجاری</div>
                </div>
            </div>
        </div>
    </body>
</html>