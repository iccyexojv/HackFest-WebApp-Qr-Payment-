<header class="header">
    <div class="container">
        <div class="nav-brand">
            <img src="/assets/kbc new.png" alt="Kathmandu University" class="logo">
        </div>
        <nav class="nav-menu">
            <a href="/" @class(['nav-link', 'active' => request()->path() == ''])>HOME</a>
            <a href="/fest" @class(['nav-link', 'active' => request()->path() == 'fest'])>FEST</a>
            <a href="/about-us" @class(['nav-link', 'active' => request()->path() == 'about-us'])>ABOUT US</a>
            <a href="/contact-us" @class(['nav-link', 'active' => request()->path() == 'contact-us'])>CONTACT US</a>
            <a class="register-btn" href="/visitor/register">Register</a>
        </nav>
        <div class="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
