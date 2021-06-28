var path = require('path')
module.exports = {
  // publicPath: process.env.NODE_ENV === 'production'
  // ? '/production-sub-path/'
  // : '/'
  devServer: {
    contentBase: path.join(__dirname, 'dist'),
    compress: true,
    port: 8090,
    // proxy: {
    //   "^/api": {
    //     target: "<url>",
    //     ws: true,
    //     changeOrigin: true
    //   },
    //   "^/foo": {
    //     target: "<other_url>"
    //   }
    // }
  },
  css: {
    requireModuleExtension: false,
    sourceMap: true,
    loaderOptions: {
      // pass options to sass-loader
      sass: {
        // @/ is an alias to src/
        // so this assumes you have a file named `src/variables.scss`
        // data: `@import "@/main.scss";`
      },
      // pass Less.js Options to less-loader
      less:{
        // http://lesscss.org/usage/#less-options-strict-units `Global Variables`
        // `primary` is global variables fields name
        globalVars: {
          primary: '#fff'
        }
      }
    }
  }
};
