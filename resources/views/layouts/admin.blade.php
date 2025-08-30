<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="sayor" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/animation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('font/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('icon/style.css')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/favicon.ico')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}">
    @stack("styles")
    
    <style>
        :root {
            --admin-primary: #1e293b;
            --admin-secondary: #334155;
            --admin-accent: #3b82f6;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-info: #06b6d4;
            --admin-light: #f8fafc;
            --admin-dark: #0f172a;
            --admin-border: #e2e8f0;
            --admin-text: #1e293b;
            --admin-text-muted: #64748b;
            --admin-bg: #ffffff;
            --admin-bg-secondary: #f1f5f9;
            --admin-shadow: 0 1px 3px rgba(0,0,0,0.1);
            --admin-shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --admin-radius: 12px;
            --admin-transition: all 0.3s ease;
        }
        
        body.admin {
            background: var(--admin-bg-secondary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--admin-text);
            line-height: 1.6;
        }
        
        /* Traditional Admin Layout */
        #page {
            display: flex;
            min-height: 100vh;
        }
        
        .layout-wrap {
            display: flex;
            width: 100%;
        }
        
        /* Modern Clean Sidebar Design */
        .section-menu-left {
            width: 280px;
            background: #ffffff;
            color: #333333;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-right: 1px solid #f0f0f0;
            backdrop-filter: blur(10px);
        }
        
        .section-menu-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .section-menu-left.collapsed {
            width: 70px;
        }
        
        .section-menu-left.collapsed .text,
        .section-menu-left.collapsed .center-heading {
            display: none;
        }
        
        .section-menu-left.collapsed .sub-menu {
            display: none;
        }
        
        /* Animated Logo Section */
        .box-logo {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            background: #f8f9fa;
            margin: 0;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .box-logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .box-logo .logo {
            flex: 1;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .box-logo .logo a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333333;
        }
        
        .box-logo .logo__image {
            height: 40px;
            width: auto;
            display: block;
            opacity: 1 !important;
            visibility: visible !important;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .box-logo .logo__image:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .logo-text {
            margin-left: 0.75rem;
        }
        
        .logo-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333333;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .logo-subtitle {
            font-size: 0.875rem;
            color: #666666;
            margin: 0;
        }
        
        .logo-dropdown {
            color: #666666;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
            position: relative;
            z-index: 1;
        }
        
        .logo-dropdown:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Animated Navigation Menu */
        .center {
            padding: 1rem 0;
            position: relative;
            z-index: 1;
        }
        
        .center-heading {
            color: #666666;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 1.5rem 1.5rem 1rem 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
        }
        
        .center-heading::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
            animation: slideRight 2s ease-in-out infinite;
        }
        
        @keyframes slideRight {
            0%, 100% { width: 30px; }
            50% { width: 60px; }
        }
        
        .center-item {
            margin-bottom: 0.25rem;
        }
        
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-item {
            margin: 0;
            position: relative;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
            transition: width 0.3s ease;
            z-index: 0;
        }
        
        .menu-item:hover::before {
            width: 4px;
        }
        
        .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            text-decoration: none;
            color: #333333;
            transition: all 0.3s ease;
            background: transparent;
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
            z-index: 1;
            border-radius: 0 12px 12px 0;
            margin-right: 0.5rem;
            overflow: hidden;
        }
        
        .menu-item a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .menu-item a:hover::before {
            left: 100%;
        }
        
        .menu-item a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #333333;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .menu-item a.active {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
            transform: translateX(5px);
        }
        
        .menu-item a.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #ff6b6b, #4ecdc4);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .menu-item .icon {
            width: 20px;
            margin-right: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #666666;
            transition: all 0.3s ease;
        }
        
        .menu-item a:hover .icon,
        .menu-item a.active .icon {
            color: #667eea;
            transform: scale(1.2) rotate(5deg);
        }
        
        .menu-item .text {
            flex: 1;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .menu-item a:hover .text {
            transform: translateX(3px);
        }
        
        .menu-item .badge {
            margin-left: auto;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 12px;
            min-width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            padding: 0 6px;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.4);
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        
        /* Animated User Profile Section */
        .user-profile {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f0f0f0;
            margin-top: auto;
            background: #f8f9fa;
            border-radius: 0 0 16px 0;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }
        
        .user-profile::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.05) 0%, rgba(102, 126, 234, 0.02) 100%);
            border-radius: 0 0 16px 0;
            pointer-events: none;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .user-avatar:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .user-details {
            flex: 1;
            margin-left: 0.75rem;
        }
        
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333333;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .user-email {
            font-size: 0.8rem;
            color: #666666;
            margin: 0;
        }
        
        .user-settings {
            color: #666666;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .user-settings:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Main Content Styling */
        .main-content-wrap {
            padding: 2rem;
            margin-left: 280px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .section-menu-left.collapsed + .main-content .main-content-wrap {
            margin-left: 70px;
        }
        
        /* Main Content Area Styling */
        .section-content-right {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background: #f8f9fa;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .section-menu-left.collapsed + .section-content-right {
            margin-left: 70px;
        }
        
        /* Box Styling */
        .wg-box {
            background: var(--admin-bg);
            border-radius: var(--admin-radius);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            margin-bottom: 2rem;
        }
        
        .wg-box-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            background: var(--admin-bg);
            border-radius: var(--admin-radius) var(--admin-radius) 0 0;
        }
        
        .wg-box-body {
            padding: 1.5rem;
        }
        
        .mb-30 {
            margin-bottom: 2rem;
        }
        
        /* Main Content Area Styling */
        .section-content-right {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .section-menu-left.collapsed + .section-content-right {
            margin-left: 70px;
        }
        
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }
        
        /* Header Dashboard Styling */
        .header-dashboard {
            background: var(--admin-bg);
            border-bottom: 1px solid var(--admin-border);
            padding: 1rem 2rem;
            box-shadow: var(--admin-shadow);
        }
        
        .header-dashboard .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }
        
        .header-grid {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Form Search Styling */
        .form-search {
            display: flex;
            align-items: center;
            background: var(--admin-bg-secondary);
            border-radius: 8px;
            padding: 0.5rem;
            max-width: 400px;
            width: 100%;
        }
        
        .form-search input {
            border: none;
            background: transparent;
            outline: none;
            flex: 1;
            padding: 0.5rem;
        }
        
        .button-submit {
            background: var(--admin-accent);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
        }
        
        /* Bottom Page Styling */
        .bottom-page {
            text-align: center;
            padding: 2rem;
            color: var(--admin-text-muted);
            border-top: 1px solid var(--admin-border);
            margin-top: 2rem;
        }
        
        /* Simple Navigation Styling */
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-item {
            margin-bottom: 0.5rem;
        }
        
        .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            text-decoration: none;
            color: #64748b;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .menu-item a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .menu-item a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .menu-item .icon {
            width: 16px;
            margin-right: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .menu-item .text {
            flex: 1;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .menu-item .badge {
            margin-left: auto;
        }
        
        .center-heading {
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0.75rem 0 0.4rem 0.75rem;
        }
        
        .center-item {
            margin-bottom: 0.3rem;
        }
        
        /* Chart container styling */
        .main-content {
            padding: 2rem;
        }
        
        /* Modern Card System */
        .wg-box {
            background: var(--admin-bg);
            border-radius: var(--admin-radius);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            overflow: hidden;
            transition: var(--admin-transition);
            margin-bottom: 1.5rem;
        }
        
        .wg-box:hover {
            box-shadow: var(--admin-shadow-lg);
        }
        
        .wg-box-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            background: var(--admin-bg-secondary);
        }
        
        .wg-box-body {
            padding: 1.5rem;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            background: var(--admin-bg);
            border-radius: var(--admin-radius);
            padding: 1.5rem;
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            transition: var(--admin-transition);
            text-align: center;
        }
        
        .stat-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--admin-shadow-lg);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
        }
        
        .stat-item.products .stat-icon { background: var(--admin-info); }
        .stat-item.categories .stat-icon { background: var(--admin-success); }
        .stat-item.brands .stat-icon { background: var(--admin-warning); }
        .stat-item.users .stat-icon { background: var(--admin-danger); }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-text);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--admin-text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .section-menu-left {
                transform: translateX(-100%);
            }
            
            .section-menu-left.mobile-open {
                transform: translateX(0);
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        /* Toggle Button for Mobile */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--admin-primary);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
        
        /* Sidebar collapsed state */
        .section-menu-left.collapsed {
            width: 70px;
        }
        
        .section-menu-left.collapsed .box-logo img {
            height: 30px;
        }
        
        .section-menu-left.collapsed .center-item {
            display: none;
        }
        
        .section-menu-left.collapsed .button-show-hide {
            padding: 0.25rem;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Smooth transitions */
        .section-menu-left,
        .main-content {
            transition: all 0.3s ease;
        }
        
        /* Responsive optimizations */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content-wrap {
                padding: 1rem;
                margin-left: 0;
            }
            
            .section-menu-left {
                transform: translateX(-100%);
            }
            
            .section-menu-left.mobile-open {
                transform: translateX(0);
            }
        }
        
        /* Performance optimizations */
        .wg-box {
            will-change: transform;
        }
        
        .stat-item {
            will-change: transform;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Focus states for accessibility */
        .button-show-hide:focus,
        .btn:focus {
            outline: 2px solid var(--admin-accent);
            outline-offset: 2px;
        }
        
        /* Custom Scrollbar */
        .section-menu-left::-webkit-scrollbar {
            width: 6px;
        }
        
        .section-menu-left::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 3px;
        }
        
        .section-menu-left::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea, #764ba2);
            border-radius: 3px;
        }
        
        .section-menu-left::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2, #667eea);
        }
    </style>
</head>
<body class="body admin">
    <div id="page">
            <div class="layout-wrap">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="icon-menu-left"></i>
            </button>
            
            <!-- Sidebar -->
            <div class="section-menu-left" id="sidebar">
                <!-- Logo Section - Clean Header -->
                <div class="box-logo">
                    <div class="logo">
                        <a href="{{ route('home.index') }}" style="display: flex; align-items: center; text-decoration: none;">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="logo__image d-block" style="height: 40px; width: auto; opacity: 1; visibility: visible;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                            <span style="display: none; color: #333333; font-weight: bold; font-size: 18px;">LOGO</span>
                            <div class="logo-text">
                                <div class="logo-title">Online Shop</div>
                                <div class="logo-subtitle">E-commerce Admin</div>
                            </div>
                        </a>
                    </div>
                    <div class="logo-dropdown">
                        <i class="icon-arrow-down"></i>
                    </div>
                </div>
                
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Main Home</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.index')}}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.product.add')}}" class="{{ request()->routeIs('admin.product.add') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                                        <div class="text">Add Product</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.products')}}" class="{{ request()->routeIs('admin.products') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-package"></i></div>
                                        <div class="text">All Products</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.shop')}}" class="{{ request()->routeIs('admin.shop') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-shopping-bag"></i></div>
                                        <div class="text">Shop View</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.brand.add')}}" class="{{ request()->routeIs('admin.brand.add') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-award"></i></div>
                                        <div class="text">Add Brand</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.brands')}}" class="{{ request()->routeIs('admin.brands') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-tag"></i></div>
                                        <div class="text">All Brands</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.category.add')}}" class="{{ request()->routeIs('admin.category.add') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Add Category</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.categories')}}" class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-folder"></i></div>
                                        <div class="text">All Categories</div>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.coupon.add')}}" class="{{ request()->routeIs('admin.coupon.add') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-tag"></i></div>
                                        <div class="text">Add Coupon</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.coupons')}}" class="{{ request()->routeIs('admin.coupons') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-gift"></i></div>
                                        <div class="text">All Coupons</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.orders')}}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-plus-square"></i></div>
                                        <div class="text">Orders</div>
                                        <span class="badge" style="background: #ef4444; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;">{{ \App\Models\Order::where('status', 'ordered')->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.notifications')}}" class="{{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                                        <div class="icon"><i class="icon-bell"></i></div>
                                        <div class="text">Notifications</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="icon"><i class="icon-log-out"></i></div>
                                        <div class="text">Logout</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Quick Stats Section - REMOVED -->
                    </div>
                    
                    <!-- User Profile Section -->
                    <div class="user-profile">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-email">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="user-settings">
                                <i class="icon-settings"></i>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Main Content Area -->
            <div class="section-content-right" id="main-content">
                <!-- Header Dashboard -->
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="{{ route('home.index')}}" title="Click to go to Homepage">
                                <img class="" id="logo_header_mobile" alt="Logo - Click to go to Homepage" src="{{ asset('assets/images/logo.png') }}"
                                    data-light="{{ asset('assets/images/logo.png') }}" data-dark="{{ asset('assets/images/logo.png') }}"
                                    data-width="154px" data-height="52px" data-retina="{{ asset('assets/images/logo.png') }}">
                            </a>
                            <div class="button-show-hide" title="Toggle Sidebar" onclick="toggleSidebar()">
                                    <i class="icon-menu-left"></i>
                                </div>

                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search here..." class="show-search" name="name"
                                            tabindex="2" value="" aria-required="true" required="">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                </form>
                            </div>

                        <div class="header-grid">
                            <!-- Notifications -->
                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny" id="notifCount">{{ auth()->user()->unreadNotifications()->count() }}</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Notifications</h6>
                                            </li>
                                            <li>
                                                @php($notifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get())
                                                @forelse($notifications as $note)
                                                <div class="message-item item-1">
                                                    <div class="image"><i class="icon-noti-1"></i></div>
                                                    <div>
                                                        <div class="body-title-2">{{ $note->data['user_name'] ?? 'A customer' }} placed an order</div>
                                                        <div class="text-tiny">Order #{{ $note->data['order_number'] ?? '' }} — {{ $note->data['item_count'] ?? 0 }} items — ${{ number_format($note->data['total'] ?? 0,2) }}</div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="message-item item-1">
                                                    <div class="image"><i class="icon-noti-1"></i></div>
                                                    <div>
                                                        <div class="body-title-2">No new notifications</div>
                                                        <div class="text-tiny">You're all caught up.</div>
                                                    </div>
                                                </div>
                                                @endforelse
                                            </li>
                                            <li><a href="{{ route('admin.notifications') }}" class="tf-button w-full">View all</a></li>
                                        </ul>
                                    </div>
                                </div>

                            <!-- User Profile -->
                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="images/avatar/user-1.png" alt="">
                                                </span>
                                                <span class="flex flex-column">
                                                <span class="body-title mb-2">{{ auth()->user()->name }}</span>
                                                    <span class="text-tiny">Admin</span>
                                                </span>
                                                <span class="user-count">
                                                    <i class="icon-users"></i>
                                                    <span class="count-number">{{ \App\Models\User::count() }}</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Account</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}" class="user-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Log out</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- Main Content -->
                    <div class="main-content">
                        @yield('content')

                        <div class="bottom-page">
                            <div class="body-text">© 2025</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>   
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>    
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        });
        
        // Auto-hide sidebar on mobile
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('main-content').classList.remove('expanded');
            }
        }
        
        window.addEventListener('resize', checkScreenSize);
        checkScreenSize();
        
        // Auto-refresh notification and order counts
        setInterval(function() {
            // Refresh notification count
            fetch('{{ route("admin.notifications.count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        document.getElementById('notifCount').textContent = data.count;
                        document.getElementById('notifCount').style.display = 'inline';
                    } else {
                        document.getElementById('notifCount').style.display = 'none';
                    }
                });
            
            // Refresh order count
            fetch('{{ route("admin.orders.count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        document.getElementById('sidebarOrderCount').textContent = data.count;
                        document.getElementById('sidebarOrderCount').style.display = 'inline';
                    } else {
                        document.getElementById('sidebarOrderCount').style.display = 'none';
                    }
                });
        }, 30000); // Refresh every 30 seconds
        
        // Sidebar toggle functionality
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const icon = this.querySelector('i');
            
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                icon.className = 'icon-arrow-left';
            } else {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                icon.className = 'icon-arrow-right';
            }
        });
        
        // Logo visibility debugging - Simplified like home page
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.querySelector('.logo__image');
            const logoContainer = document.querySelector('.box-logo');
            
            console.log('Logo container found:', logoContainer);
            console.log('Logo image found:', logoImg);
            
            if (logoImg) {
                console.log('Logo src:', logoImg.src);
                console.log('Logo loaded successfully');
            }
            
            if (logoContainer) {
                console.log('Logo container ready');
            }
        });
        
        // Chart debugging and optimization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Checking ApexCharts availability...');
            console.log('ApexCharts available:', typeof ApexCharts !== 'undefined');
            
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not loaded!');
                // Try to load ApexCharts dynamically
                var script = document.createElement('script');
                script.src = '{{ asset("js/apexcharts/apexcharts.js") }}';
                script.onload = function() {
                    console.log('ApexCharts loaded dynamically');
                    setTimeout(function() {
                        if (typeof window.initializeChart === 'function') {
                            window.initializeChart();
                        }
                    }, 500);
                };
                script.onerror = function() {
                    console.error('Failed to load ApexCharts dynamically');
                };
                document.head.appendChild(script);
            }
        });
        
        // Performance optimization
        document.addEventListener('DOMContentLoaded', function() {
            // Preload critical resources
            const criticalImages = [
                '{{ asset("assets/images/logo.png") }}'
            ];
            
            criticalImages.forEach(src => {
                const img = new Image();
                img.src = src;
            });
            
            // Optimize sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.querySelector('.main-content');
                    const icon = this.querySelector('i');
                    
                    if (sidebar.classList.contains('collapsed')) {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                        icon.className = 'icon-arrow-left';
                    } else {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                        icon.className = 'icon-arrow-right';
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

