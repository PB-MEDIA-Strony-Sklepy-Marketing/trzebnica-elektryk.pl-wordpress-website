import globals from "globals";

export default [
  {
    files: ["**/*.js"],
    ignores: ["dist/wp-content/uploads/**", "dist/wp-content/cache/**"],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: "module",
      globals: {
        ...globals.browser,
        ...globals.jquery,
        wp: "readonly"
      }
    },
    rules: {
      "no-unused-vars": ["warn", { "argsIgnorePattern": "^_" }],
      "no-console": "warn",
      "semi": ["error", "always"],
      "quotes": ["error", "single"]
    }
  }
];