<template>
    <div>
            <div class="container py-4 px-16 bg-gray-900 text-white rounded-lg">
                <!-- Modal Header -->
                <h1 class="text-2xl text-center font-semibold mb-6 mt-6">About Us</h1>

                <!-- Company Introduction -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-200">Our Mission</h3>
                    <p class="text-sm text-gray-400 mt-2">We strive to deliver high-quality digital solutions that help
                        businesses thrive in a fast-paced world. Our mission is to provide innovative and impactful web
                        and mobile services to empower our clients.</p>
                </div>

                <!-- Team Section -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-200">Meet the Team</h3>
                    <div class="flex space-x-6 mt-4">
                        <!-- Team Members -->
                        <div v-for="member in team" :key="member.id" class="bg-gray-800 p-3 rounded-lg w-1/3">
                            <h4 class="text-lg font-semibold text-gray-200">{{ member.name }}</h4>
                            <p class="text-sm text-gray-400">{{ member.role }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ member.bio }}</p>
                        </div>
                    </div>
                </div>

                <!-- Company Values -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-200">Our Values</h3>
                    <ul class="list-disc pl-6 mt-4">
                        <li class="text-sm text-gray-400">Innovation: We constantly strive to innovate and offer
                            creative solutions.</li>
                        <li class="text-sm text-gray-400">Integrity: We value honesty and transparency in all our
                            dealings.</li>
                        <li class="text-sm text-gray-400">Customer-Centric: We put our clients' needs at the heart of
                            everything we do.</li>
                    </ul>
                </div>
            </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            team: [
                {
                    id: 1,
                    name: 'John Doe',
                    role: 'Founder & CEO',
                    bio: 'John is the visionary behind the company and leads the team with a focus on innovation and growth.'
                },
                {
                    id: 2,
                    name: 'Jane Smith',
                    role: 'Chief Technology Officer',
                    bio: 'Jane leads the technical team and ensures the highest standards of technology are met.'
                },
                {
                    id: 3,
                    name: 'Mark Johnson',
                    role: 'Head of Marketing',
                    bio: 'Mark focuses on building the brand and reaching new clients through effective marketing strategies.'
                }
            ]
        };
    },
    methods: {
        cancel() {
            this.$modal.hide('about-us-modal');
        }
    }
}
</script>
