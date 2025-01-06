<footer class="bg-gray-800 text-blue-100 py-4">
    <div class="container">
        <div class="row ml-4 ">
            <div class="col-md-4 mb-3">
                <h5 class="font-bold">About Us</h5>
                <p>We are a leading company in providing streaming services. Our mission is to deliver high-quality
                    content to our users.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="font-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <router-link to="/" exact v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }">
                            <a :href="href" @click="navigate">Home</a>
                        </li>
                    </router-link>
                    <li>
                        <about-us-button></about-us-button>
                    </li>
                    <li>
                        <services-button></services-button>
                    </li>
                    <li>
                        <contact-button></contact-button>
                    </li>
                    <li>
                        <support-button></support-button>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="font-bold">Contact Us</h5>
                <p>Email: <a href="mailto:info@streamingservice.com" class="text-info">info@streamingservice.com</a></p>
                <p>Phone: <a href="tel:+1234567890" class="text-info">+123 456 7890</a></p>
                <p>Address: 123 Streaming St, Media City, Country</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Streaming Service. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
