import defaultTheme from "tailwindcss/defaultTheme";
import preset from "./vendor/filament/support/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./app/Livewire/**/*.php",
    ],

    theme: {
        screens: {
            sm: "480px",
            md: "768px",
            lg: "976px",
            xl: "1440px",
        },
        fontFamily: {
            sans: [
                "Calibri",
                "ui-sans-serif",
                "system-ui",
                "-apple-system",
                "BlinkMacSystemFont",
            ],
            serif: ["Merriweather", "serif"],
            Calibri: [
                "Calibri",
                "ui-sans-serif",
                "system-ui",
                "-apple-system",
                "BlinkMacSystemFont",
            ],
        },
        extend: {
            fontSize: {
                xxs: "0.68rem",
            },
            spacing: {
                128: "32rem",
                144: "36rem",
            },
            borderRadius: {
                "4xl": "2rem",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: 0 },
                    "100%": { opacity: 1 },
                },
                fadeOut: {
                    "0%": { opacity: 1 },
                    "100%": { opacity: 0 },
                },
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
                marquee2: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0%)' },
                },
            },
            animation: {
                fadeIn: "fadeIn 0.5s ease-in-out",
                fadeOut: "fadeOut 0.5s ease-in-out",
                marquee: 'marquee 25s linear infinite',
                marquee2: 'marquee2 25s linear infinite',
            },
            screens: {
                "max-sm": { max: "640px" },
                "max-md": { max: "768px" },
                "max-975": { max: "975px" },
                "max-lg": { max: "1024px" },
                "max-xl": { max: "1280px" },
            },
            fontFamily: {
                aeonik: "'Aeonik', sans-serif",
                editorial: "'Editorial', sans-serif",
            },
            colors: {
                blue: "#56B6E9",
                purple: "#7e5bef",
                pink: "#F2CCB7",
                orange: "#F15C3B",
                green: "#A9E46E",
                yellow: "#FFBC39",
                "gray-dark": "#273444",
                gray: "#8492a6",
                "gray-light": "#d3dce6",
                white: "#ffffff",
                black: "#000000",
                "panda-green": "#91b88f",
            },
        },
    },
};
