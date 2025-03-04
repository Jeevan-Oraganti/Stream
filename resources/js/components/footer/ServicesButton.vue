<template>
    <div>
        <a class="text-blue-100 hover:text-white" @click="$modal.show('services-modal')">
            Our Services
        </a>

        <modal name="services-modal" height="auto" width="50%" :pivotY="1" class="rounded-lg shadow-lg" :styles="{ backgroundColor: 'transparent' }">
            <div class="container py-4 px-16 bg-gray-900 text-white rounded-lg">
                <!-- Modal Header -->
                <h1 class="text-2xl text-center font-semibold mb-6 mt-6">Our Services</h1>

                <!-- Services List -->
                <div class="mb-6">
                    <!-- Loop through services and display their details -->
                    <div v-for="service in services" :key="service.id" class="bg-gray-800 p-3 rounded-lg mb-4">
                        <h3 class="text-xl font-bold text-gray-200">{{ service.name }}</h3>
                        <p class="text-sm text-gray-400">{{ service.description }}</p>
                        <p class="mt-2"><strong>Price: </strong>${{ service.price }}</p>
                    </div>
                </div>

                <!-- Close Button -->
                <div class="flex justify-end">
                    <a @click="cancel"
                        class="button text-white border-2 border-white hover:bg-white hover:text-gray-900 px-4 py-2 mb-6 rounded-md">
                        Close
                    </a>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            services: [
                {
                    id: 1,
                    name: 'Web Development',
                    description: 'Custom websites built with modern technologies.',
                    price: '999'
                },
                {
                    id: 2,
                    name: 'Mobile App Development',
                    description: 'Native apps for iOS and Android platforms.',
                    price: '1499'
                },
                {
                    id: 3,
                    name: 'SEO Services',
                    description: 'Optimize your website to rank higher in search engines.',
                    price: '499'
                },
                {
                    id: 4,
                    name: 'UI/UX Design',
                    description: 'Designing user-friendly and visually appealing interfaces.',
                    price: '799'
                }
            ]
        };
    },
    methods: {
        cancel() {
            this.$modal.hide('services-modal');
        }
    }
}
</script>
