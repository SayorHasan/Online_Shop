<style>
    /* Professional Account Navigation Styling */
    .account-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .account-nav li {
        border-bottom: 1px solid var(--border-light);
    }
    
    .account-nav li:last-child {
        border-bottom: none;
    }
    
    .account-nav .menu-link {
        display: block;
        padding: 1.25rem 1.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        position: relative;
        font-size: 0.95rem;
    }
    
    .account-nav .menu-link:hover {
        color: var(--primary-color);
        background: var(--bg-secondary);
        text-decoration: none;
    }
    
    .account-nav .menu-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .account-nav .menu-link:hover::before {
        transform: scaleX(1);
    }
    
    /* Active state for current page */
    .account-nav .menu-link.active {
        color: var(--primary-color);
        background: var(--bg-secondary);
        font-weight: 600;
    }
    
    .account-nav .menu-link.active::before {
        transform: scaleX(1);
    }
    
    /* Logout button special styling */
    .account-nav li:last-child .menu-link {
        color: #dc3545;
        font-weight: 600;
    }
    
    .account-nav li:last-child .menu-link:hover {
        background: #fef2f2;
        color: #dc3545;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .account-nav {
            margin-bottom: 2rem;
        }
        
        .account-nav .menu-link {
            padding: 1rem 1.25rem;
        }
    }
</style>

<ul class="account-nav">
    <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.index') ? 'active' : '' }}">Dashboard</a></li>
    <li><a href="{{route('user.shop')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.shop') ? 'active' : '' }}">Shop</a></li>
    <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.orders*') ? 'active' : '' }}">Orders</a></li>
    <li><a href="{{route('user.wishlist')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.wishlist') ? 'active' : '' }}">Wishlist</a></li>
    <li><a href="{{route('user.addresses')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.addresses*') ? 'active' : '' }}">Addresses</a></li>
    <li><a href="{{route('user.account.details')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.account.details') ? 'active' : '' }}">Account Details</a></li>
    <li><a href="{{ route('user.coupons') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.coupons*') ? 'active' : '' }}">Coupons</a></li>
    <li>
        <form method="POST" action="{{route('logout')}}" id="logout-form">
            @csrf
            <a href="{{route('logout')}}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logout-form').submit()">Logout</a>
        </form>
    </li>
</ul>