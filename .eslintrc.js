module.exports = {
    root: true,
    env: {
        browser: true,
        es2021: true,
        node: true
    },
    extends: [
        'eslint:recommended'
    ],
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module'
    },
    rules: {
        // Erreurs
        'no-console': 'warn',
        'no-debugger': 'error',
        'no-alert': 'warn',
        'no-unused-vars': ['warn', { 
            argsIgnorePattern: '^_',
            varsIgnorePattern: '^_'
        }],
        
        // Style
        'indent': ['error', 4],
        'linebreak-style': ['error', 'unix'],
        'quotes': ['error', 'single'],
        'semi': ['error', 'always'],
        
        // Bonnes pratiques
        'eqeqeq': ['error', 'always'],
        'curly': ['error', 'all'],
        'brace-style': ['error', '1tbs'],
        'no-var': 'error',
        'prefer-const': 'warn',
        'arrow-spacing': 'error',
        'no-duplicate-imports': 'error',
        
        // Espacement
        'space-before-function-paren': ['error', {
            anonymous: 'always',
            named: 'never',
            asyncArrow: 'always'
        }],
        'comma-spacing': ['error', { before: false, after: true }],
        'keyword-spacing': 'error',
        'space-infix-ops': 'error'
    },
    globals: {
        // Variables globales Laravel/Livewire
        Livewire: 'readonly',
        Alpine: 'readonly',
        axios: 'readonly',
        $: 'readonly',
        jQuery: 'readonly',
        bootstrap: 'readonly',
        ApexCharts: 'readonly'
    }
};
