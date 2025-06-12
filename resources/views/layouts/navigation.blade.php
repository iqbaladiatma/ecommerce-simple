<!-- Modern Navigation -->
<nav x-data="{ open: false, userMenu: false }" class="modern-nav">
    <div class="nav-container">
        <div class="nav-wrapper">
            <!-- Logo -->
            <div class="logo-container">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="logo">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="logo-text">ShopHub</div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Products
                </a>
                @auth
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> My Dashboard
                </a>
                @endif
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="nav-link admin-link">
                    <i class="fas fa-crown"></i> Admin Panel
                </a>
                @endif
            </div>

            <!-- Right Section -->
            <div class="flex items-center gap-4">
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="cart-icon relative">
                    <i class="fas fa-shopping-cart text-xl text-red-500"></i>
                    @php
                    $cartCount = app(App\Services\CartService::class)->count();
                    @endphp
                    @if($cartCount > 0)
                    <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                </a>

                @guest
                <!-- Guest Buttons -->
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                </div>
                @else
                <!-- User Menu -->
                <div class="user-menu" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </button>
                    <div class="user-dropdown"
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        <div class="dropdown-header">
                            <div class="dropdown-name">{{ auth()->user()->name }}</div>
                            <div class="dropdown-email">{{ auth()->user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn w-full text-left">
                                <i class="fas fa-sign-out-alt"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
                @endguest

                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" @click="open = !open">
                    <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" x-show="open" x-transition>
        <div class="mobile-links">
            <a href="{{ route('dashboard') }}" class="mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="{{ route('products.index') }}" class="mobile-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
            @if(auth()->check())
            <a href="{{ route('user.dashboard') }}" class="mobile-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> My Dashboard
            </a>
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="mobile-link">
                <i class="fas fa-crown"></i> Admin Panel
            </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="mobile-link">
                <i class="fas fa-user"></i> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="mobile-link w-full text-left">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
            @endif
        </div>
        @guest
        <div class="mobile-auth">
            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        </div>
        @endguest
    </div>
</nav>

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --secondary: #f43f5e;
        --secondary-dark: #e11d48;
        --dark: #0f172a;
        --light: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-700: #334155;
        --radius: 8px;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-nav {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        position: relative;
        z-index: 50;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .nav-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    /* Logo */
    .logo-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 10px;
        color: white;
        font-size: 20px;
        font-weight: 700;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
    }

    .logo-text {
        font-size: 1.4rem;
        font-weight: 700;
        background: linear-gradient(90deg, var(--primary), var(--dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Navigation Links */
    .nav-links {
        display: flex;
        gap: 0.5rem;
        margin-left: 2rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius);
        font-weight: 500;
        color: var(--gray-700);
        text-decoration: none;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover {
        color: var(--primary);
        background-color: rgba(37, 99, 235, 0.05);
    }

    .nav-link.active {
        color: var(--primary);
        background-color: rgba(37, 99, 235, 0.1);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background-color: var(--primary);
        border-radius: 10px 10px 0 0;
    }

    .nav-link i {
        margin-right: 8px;
        font-size: 0.9em;
    }

    .admin-link {
        color: var(--secondary);
        font-weight: 600;
    }

    .admin-link:hover {
        background-color: rgba(244, 63, 94, 0.05);
        color: var(--secondary-dark);
    }

    /* Cart */
    .cart-icon {
        position: relative;
        padding: 0.5rem;
        color: var(--gray-700);
        transition: var(--transition);
    }

    .cart-icon:hover {
        color: var(--primary);
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--secondary);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    /* Auth Buttons */
    .auth-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.6rem 1.25rem;
        border-radius: var(--radius);
        font-weight: 500;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        outline: none;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-login {
        background: transparent;
        color: var(--gray-700);
    }

    .btn-login:hover {
        color: var(--primary);
        background-color: rgba(37, 99, 235, 0.05);
    }

    .btn-register {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(37, 99, 235, 0.3);
    }

    /* User Menu */
    .user-menu {
        position: relative;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        padding: 0;
    }

    .user-avatar:hover {
        transform: scale(1.05);
    }

    .user-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background-color: white;
        border-radius: var(--radius);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        min-width: 200px;
        z-index: 50;
    }

    [x-cloak] {
        display: none !important;
    }

    .dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .dropdown-name {
        font-weight: 600;
        color: var(--dark);
    }

    .dropdown-email {
        font-size: 0.85rem;
        color: var(--gray-700);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--gray-700);
        text-decoration: none;
        transition: var(--transition);
    }

    .dropdown-item:hover {
        background-color: var(--gray-100);
        color: var(--primary);
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
        color: var(--gray-300);
    }

    .dropdown-item:hover i {
        color: var(--primary);
    }

    .logout-btn {
        border-top: 1px solid var(--gray-200);
        background-color: var(--gray-100);
        color: var(--secondary);
        width: 100%;
        text-align: left;
        border: none;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: rgba(244, 63, 94, 0.05);
    }

    /* Mobile Menu */
    .mobile-toggle {
        display: none;
        background: transparent;
        border: none;
        outline: none;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        justify-content: center;
        align-items: center;
        transition: var(--transition);
    }

    .mobile-toggle:hover {
        background-color: var(--gray-100);
    }

    .mobile-toggle i {
        font-size: 1.5rem;
        color: var(--gray-700);
    }

    .mobile-menu {
        position: absolute;
        top: 70px;
        left: 0;
        right: 0;
        background-color: white;
        border-radius: 0 0 var(--radius) var(--radius);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.5s ease;
    }

    .mobile-menu.active {
        max-height: 500px;
    }

    .mobile-links {
        padding: 1rem 0;
    }

    .mobile-link {
        display: block;
        padding: 0.9rem 1.5rem;
        color: var(--gray-700);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mobile-link i {
        width: 24px;
        text-align: center;
        color: var(--gray-300);
    }

    .mobile-link:hover {
        background-color: var(--gray-100);
        color: var(--primary);
    }

    .mobile-link:hover i {
        color: var(--primary);
    }

    .mobile-link.active {
        color: var(--primary);
        background-color: rgba(37, 99, 235, 0.1);
    }

    .mobile-auth {
        padding: 1.5rem;
        border-top: 1px solid var(--gray-200);
        display: flex;
        gap: 1rem;
    }

    .mobile-auth .btn {
        flex: 1;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .nav-links {
            display: none;
        }

        .auth-buttons {
            display: none;
        }

        .mobile-toggle {
            display: flex;
        }

        .cart-icon {
            margin-right: 0.5rem;
        }
    }

    @media (max-width: 640px) {
        .nav-container {
            padding: 0 1rem;
        }
    }
</style>