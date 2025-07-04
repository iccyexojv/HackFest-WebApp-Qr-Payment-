@push('styles')
    <link rel="stylesheet" href="/css/website/contact-us.css">
@endpush

@push('fonts')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
@endpush

<x-layouts.website title="Contact Us">

    <!-- Contact Section -->
    <section class="contact-page">
        <div class="contact">
            <h1>Contact Us</h1>
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p><i class="fas fa-phone-alt"></i> 01-5922965, 970-853-9992</p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:info@kbcampus.edu.np">info@kbcampus.edu.np</a></p>
                <p><i class="fas fa-map-marker-alt"></i> Balaju Bypass, Kathmandu, Nepal</p>
                <p><i class="fab fa-instagram"></i> <a href="https://www.instagram.com/kathmandu_b_campus"
                        target="_blank">@kathmandu_b_campus</a></p>
            </div>

            <div class="map-image">
                <img src="/assets/Untitled design.jpg" alt="Map of Kathmandu" />
            </div>
        </div>
    </section>

</x-layouts.website>
