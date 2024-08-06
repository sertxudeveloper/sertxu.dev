import defaultTheme from "tailwindcss/defaultTheme"

/** @type {import('tailwindcss').Config} */
export default {
    content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                ocean: "#0035ff",
                coral: "#ff3047",
            },
        }
    },
    plugins: [],
}
