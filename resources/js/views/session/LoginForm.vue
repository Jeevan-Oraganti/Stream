<template>
    <div class="container py-4 px-16 bg-gray-900 text-white rounded-lg">
        <h1 class="text-2xl text-center font-semibold mb-6 mt-4">Login</h1>
        <form @submit.prevent="login">
            <div class="mb-4">
                <label for="email" class="block text-lg text-gray-200 mb-2">Email</label>
                <input type="email" id="email" v-model="form.email" @input="failure = false"
                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Your Email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-lg text-gray-200 mb-2">Password</label>
                <input type="password" id="password" v-model="form.password" @input="failure = false"
                       class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Your Password" required>
            </div>
            <div class="flex justify-between mb-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Login
                </button>
                <div v-show="failure" class="text-sm text-red-500">
                    Log in failed. Please check your credentials.
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            failure: false,
        };
    },
    methods: {
        login() {
            axios.post('/login', this.form)
                .then(response => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Login failed:', error);
                    this.failure = true;
                });
        },
    }
};
</script>
