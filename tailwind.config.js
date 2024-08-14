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
                "ocean-dark": "#092ebc",
                coral: "#ff3047",
                dark: {
                    100: '#242424',
                    200: '#1e1e1e',
                    300: '#171717',
                },
            },
            fontFamily: {
                heading: ["Arya", ...defaultTheme.fontFamily.sans],
            }
        }
    },
    plugins: [],
}
