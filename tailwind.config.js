/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        './resources/scss/**/*.scss',
        './resources/js/Components/**/*.vue',
        './resources/js/Elements/**/*.vue',
        './www/index.html',
    ],
    theme: {
        extend: {
            "height": {
                "input": "2.75rem"
            },
            "width": {
                "70": "17.5rem",
            },
            "maxWidth": {
                "16": "4rem",
            },
            "maxHeight": {
                "64": "16rem",
            },
            "fontWeight": {
                "normal": "300",
                "semibold": "600",
                "bold": "900"
            },
            "fontSize": {
                "xxs": "0.7rem",
                "normal": "0.9rem",
                "xl": "1.2rem",
                "xxl": "1.6rem",
                "xxxl": "2.5rem",
            },
            "zIndex": {
                "max": "9999"
            },
            "colors": {
                "danger": {
                    "50": "fff1f2",
                    "100": "#ffe4e6",
                    "200": "#fecdd3",
                    "300": "#fda4af",
                    "400": "#fb7185",
                    "500": "#f43f5e",
                    "600": "#e11d48",
                    "700": "#be123c",
                    "800": "#9f1239",
                    "900": "#881337"
                },
                "warning": {
                    "50": "#fffbeb",
                    "100": "#fef3c7",
                    "200": "#fde68a",
                    "300": "#fcd34d",
                    "400": "#fbbf24",
                    "500": "#f59e0b",
                    "600": "#d97706",
                    "700": "#b45309",
                    "800": "#92400e",
                    "900": "#78350f"
                },
                "success": {
                    "50": "#ecfdf5",
                    "100": "#d1fae5",
                    "200": "#a7f3d0",
                    "300": "#6ee7b7",
                    "400": "#34d399",
                    "500": "#10b981",
                    "600": "#059669",
                    "700": "#047857",
                    "800": "#065f46",
                    "900": "#064e3b"
                },
                "primary": {
                    "50": "#eef2ff",
                    "100": "#e0e7ff",
                    "200": "#c7d2fe",
                    "300": "#a5b4fc",
                    "400": "#818cf8",
                    "500": "#6366f1",
                    "600": "#4f46e5",
                    "700": "#4338ca",
                    "800": "#3730a3",
                    "900": "#312e81"
                },
                "text": {
                    "lighten": "#d1d5db",
                    "lighter": "#9ca3af",
                    "light": "#6b7280",
                    "normal": "#374151",
                    "dark": "#374151"
                },
                gray: colors.slate,
            }
        },
    },
    plugins: [],
}
