const { defineConfig } = require("@vue/cli-service");

module.exports = defineConfig({
  pluginOptions: {
    transpileDependencies: true,
    i18n: {
      locale: "en",
      fallbackLocale: "en",
      localeDir: "locales",
      enableInSFC: false,
    },
  },
  // publicPath: '/',
  publicPath: "/simlin",
  // optimization: {
  //   splitChunks: {
  //     chunks: "all",
  //     minSize: 20000,
  //     maxSize: 700000,
  //     minChunks: 1,
  //     automaticNameDelimiter: "-",
  //     cacheGroups: {
  //       vendors: {
  //         test: /[\\/]node_modules[\\/]/,
  //         name: "chunk-vendors",
  //         priority: -10,
  //         chunks: "all",
  //       },
  //       common: {
  //         name: "chunk-common",
  //         minChunks: 2,
  //         priority: -20,
  //         chunks: "all",
  //         reuseExistingChunk: true,
  //       },
  //     },
  //   },
  //   runtimeChunk: "single",
  //   minimize: true,
  // },
  // parallel: true,
  // productionSourceMap: false,
});
