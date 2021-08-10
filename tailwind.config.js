module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
        fontFamily: {
            sans: '"Montserrat", sans-serif',
        }
    },
    variants: {
        extend: {
            display: ['group-hover'],
        },
    },
    plugins: [],
}
