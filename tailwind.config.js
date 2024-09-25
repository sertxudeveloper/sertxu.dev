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
                heading: ["Arya", "Inter", ...defaultTheme.fontFamily.sans],
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            height: {
                screen: "calc(100vh - 3.75rem)",
            },
            minHeight: {
                screen: "calc(100vh - 3.75rem)",
            },
            animation: {
                "slide-fade-in": "slide-fade-in 1.5s cubic-bezier(0.25, 1, 0.5, 1) forwards",
            },
            keyframes: {
                "slide-fade-in": {
                    "0%": {
                        opacity: 0,
                        transform: "translateY(32px)",
                    },
                    "100%": {
                        opacity: 1,
                        transform: "translateY(0)",
                    },
                },
            }
        }
    },
    plugins: [],
}
