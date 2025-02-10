/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/js/**/*.vue",
        "./resources/js/**/*.ts",
        "./resources/js/**/*.jsx",
        "./resources/js/**/*.tsx",
        "./resources/css/**/*.css",
    ],
    safelist: ["text-orange-600", "bg-orange-100", "border-orange-500"],
    theme: {
        extend: {},
    },
    plugins: [],
};
