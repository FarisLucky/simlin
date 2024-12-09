const { defineConfig } = require("@vue/cli-service")

module.exports = defineConfig({
  pluginOptions: {
    transpileDependencies: true,
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableInSFC: false
    }
  },
  // publicPath: '/',
  publicPath: '/simlin',
})
