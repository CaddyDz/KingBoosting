module.exports = {
    testRegex: 'resources/js/__tests__/.*.spec.js$',
    moduleFileExtensions: [
        'js',
        'json',
        'vue'
    ],
    modulePathIgnorePatterns: ["nova"],
    'transform': {
        '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest'
    },
    setupFilesAfterEnv: ['<rootDir>/resources/js/__tests__/vueSetup.js'],
    "collectCoverage": true,
    "collectCoverageFrom": ["**/*.{js,vue}", "!**/node_modules/**", "!**/nova/**", "!**/vendor/**"],
    "coverageReporters": ["html", "text-summary"]
}
