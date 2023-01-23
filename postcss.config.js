module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
        // ...(import.meta.env.APP_ENV === "production" ? { cssnano: {} } : {}),
    },
};
