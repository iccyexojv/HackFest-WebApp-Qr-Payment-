@push('fonts')
    <!--Fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
@endpush

<x-layouts.website title="Home">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="hackathon">KBC</span>
                        <span class="and">Hack</span>
                        <span class="fest">Fest</span>
                        <span class="year">2025</span>
                    </h1>
                </div>
                <div class="hero-graphics">
                    <div class="graphic-element graphic-1">
                        <img src="/assets/casual-life-3d-close-up-of-white-vr-glasses 1.png" alt="3D Graphics"
                            class="graphic-img">
                    </div>
                    <!-- <div class="graphic-element graphic-2">
                        <img src="/placeholder.svg?height=100&width=100" alt="Cube" class="graphic-img">
                    </div> -->
                </div>
            </div>
        </div>
    </section>

    <!-- About Hackathon Section -->
    <section class="about-hackathon">
        <div class="container">
            <div class="section-content">
                <div class="text-content1">
                    <h2 class="section-title">About Hackathon</h2>
                    <p class="section-description">
                        A hackathon is a time-bound event where individuals or teams come together to develop innovative
                        solutions, usually in the form of software or hardware projects. It typically lasts between 24
                        to 72 hours and encourages rapid prototyping, creativity, and collaboration. Participants from
                        diverse backgrounds such as healthcare, education, or AI—and may be organized by tech companies,
                        educational institutions, communities, or nonprofits. The goal is to foster innovation, solve
                        real-world problems, and build ideas often starting from a simple concept. In a typical, with
                        the best ideas often winning prizes or opportunities for further platform for learning,
                        networking, and creative problem-solving.
                    </p>
                    <button class="view-more-btn" onclick="window.location.href='/fest';">View More →</button>
                </div>
                <div class="visual-content">
                    <div class="hackathon-visual">
                        <img src="/assets/3d-modern-gaming-computer-with-monitor-keyboard_1155620-2103.png"
                            alt="Hackathon Illustration" class="main-illustration photo1">
                        <div class="floating-cards">
                            <div class="card card-1">
                                <img src="/assets/arduino.gif" alt="Code" class="card-img">
                            </div>
                            <div class="card card-2">
                                <img src="/assets/original-c40e21a7649688e3935f5cc8e42501de.gif" alt="Arduino"
                                    class="card-img">
                            </div>
                            <div class="card card-3">
                                <img src="/assets/original-d51d4e5640e4c9f47c680fb8da83378c.gif" alt="Terminal"
                                    class="card-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Fest Section -->
    <section class="about-fest">
        <div class="container">
            <div class="section-content reverse">
                <div class="visual-content">
                    <div class="fest-visual">
                        <img src="/assets/xyz.png" alt="Food Stall" class="main-illustration">
                    </div>
                </div>
                <div class="text-content2">
                    <h2 class="section-title">About Fest</h2>
                    <p class="section-description">
                        A fest is a celebration or festival that brings together people for entertainment and festive
                        fun! One that focuses with fun-fill themed decoration, where the organizer wants to create a
                        fun, bustling street ambiance and atmosphere for the participants.
                    </p>
                    <p class="section-description">
                        Step outside the code, join the the Fest Zone, packed with thrilling games like laser tag, golf,
                        and exciting food challenges to test your skills. Whether you're looking for adrenaline-pumping
                        activities or just want to unwind, enjoy the Fest and a refreshing cup of Chiya to keep the vibe
                        going strong. Come to the event with fun musical and cultural competition too, it's the perfect
                        way to recharge!
                    </p>
                    <button class="learn-more-btn" onclick="window.location.href='/fest';">Learn More
                        →</button>
                </div>
            </div>
        </div>
    </section>

    <section class="sponsors">
        <div class="container">
            <h2 class="section-title">Our Sponsors</h2>

            <!-- Title Sponsor -->
            <div class="sponsor-group title-sponsor">
                <img src="../assets/bfuso.png" alt="Title Sponsor Logo" class="sponsor-img" />
            </div>

            <!-- Other Sponsors -->
            <div class="sponsor-group other-sponsors">
                <img src="../assets/bello.svg" alt="Sponsor Logo" class="sponsor-img" />
                <img src="../assets/metlife.svg" alt="Sponsor Logo" class="sponsor-img" />
                <img src="../assets/7star.svg" alt="Sponsor Logo" class="sponsor-img" />
            </div>
        </div>
    </section>


    <!-- Contact Section -->
    <section class="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2 class="contact-title">Contact Information</h2>
                    <div class="contact-details">
                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-phone-alt"></i></span>
                            <span class="contact-text">01-5922965, 970-853-9992</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon"><i class="fa-solid fa-envelope"></i></span>
                            <span class="contact-text"><a
                                    href="mailto:info@kbcampus.edu.np">info@kbcampus.edu.np</a></span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="contact-text">Balaju Bypass, Kathmandu, Nepal</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon"><i class="fa-brands fa-instagram"></i></span>
                            <span class="contact-text"><a href="https://www.instagram.com/kathmandu_b_campus"
                                    target="_blank">@kathmandu_b_campus</a></span>
                        </div>
                    </div>
                    <!-- <div class="contact-decoration">
                        <div class="circle white-circle"></div>
                        <div class="circle blue-circle"></div>
                    </div> -->
                </div>
                <div class="contact-map">
                    <img src="/assets/Untitled design.jpg" alt="Map" class="map-img">
                </div>
            </div>
        </div>
    </section>

</x-layouts.website>
