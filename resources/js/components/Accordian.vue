<template>
    <div class="container mx-auto py-8 px-4 bg-gray-900 text-white rounded-lg">
        <h1 class="text-3xl text-center font-semibold mb-6">Frequently Asked Questions</h1>

        <div class="mb-8 px-6">
            <div v-for="question in questions" :key="question.title" class="flex items-start mb-6">
                <button
                    class="border border-solid border-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs text-gray-300 font-bold leading-none cursor-pointer mr-4"
                    @click="question.isOpen = !question.isOpen">
                    <svg v-if="question.isOpen" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>

                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-plus">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>

                <div class="leading-loose flex-1">
                    <p v-text="question.title" class="mb-3 text-lg font-semibold text-gray-200 cursor-pointer"
                        @click="question.isOpen = !question.isOpen">
                    </p>
                    <div class="mb-3 text-gray-400" v-html="question.body" v-show="question.isOpen"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            questions: [
                {
                    title: 'What is Stream?',
                    body: 'Stream is a platform for building scalable news feeds and activity streams.',
                    isOpen: false
                },
                {
                    title: 'How to integrate Stream with Laravel?',
                    body: 'You can integrate Stream with Laravel using the Stream Laravel package.',
                    isOpen: false
                },
                {
                    title: 'What is the benefit of using Stream?',
                    body: 'Stream provides a scalable and reliable solution for building activity feeds and notifications.',
                    isOpen: false
                },
                {
                    title: 'How to get started with Stream?',
                    body: 'You can get started with Stream by signing up on the website and following the documentation.',
                    isOpen: false
                },
                {
                    title: 'What features does Stream offer?',
                    body: 'Stream offers features like real-time updates, personalization, and analytics for activity feeds.',
                    isOpen: false
                },
                {
                    title: 'How secure is Stream?',
                    body: 'Stream ensures data security with encryption and secure access controls.',
                    isOpen: false
                },
                {
                    title: 'Can Stream handle large-scale applications?',
                    body: 'Yes, Stream is designed to handle large-scale applications with millions of users.',
                    isOpen: false
                },
            ]
        };
    }
};
</script>
