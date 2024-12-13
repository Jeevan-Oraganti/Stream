<template>
    <div>
        <a class="text-blue-100 hover:text-white" @click="$modal.show('contact-officials-modal')">
            Contact Officials
        </a>

        <modal name="contact-officials-modal" height="auto" width="50%" :pivotY=".5" class="rounded-lg shadow-lg">
            <div class="container mx-auto py-4 px-16 bg-gray-900 text-white rounded-lg">
                <h1 class="text-2xl text-center font-semibold mb-6 mt-6">Contact Our Officials</h1>

                <div class="mb-6">
                    <div v-for="official in officials" :key="official.id" class="bg-gray-800 p-2 px-4 rounded-lg mb-4">
                        <h3 class="text-xl font-bold text-gray-200">{{ official.name }}</h3>
                        <p class="text-sm text-gray-400">{{ official.title }}</p>
                        <p class="mt-2"><strong>Email: </strong><a :href="'mailto:' + official.email"
                                class="text-blue-400">{{ official.email }}</a></p>
                        <p v-if="official.phone"><strong>Phone: </strong>{{ official.phone }}</p>
                    </div>
                </div>

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
            officials: [
                {
                    id: 1,
                    name: 'John Doe',
                    title: 'CEO',
                    email: 'johndoe@example.com',
                    phone: '123-456-7890'
                },
                {
                    id: 2,
                    name: 'Jane Smith',
                    title: 'Support Manager',
                    email: 'janesmith@example.com',
                    phone: '987-654-3210'
                },
                {
                    id: 3,
                    name: 'Mark Johnson',
                    title: 'Technical Lead',
                    email: 'markjohnson@example.com',
                    phone: '555-123-4567'
                }
            ]
        };
    },
    methods: {
        cancel() {
            this.$modal.hide('contact-officials-modal');
        }
    }
}
</script>
