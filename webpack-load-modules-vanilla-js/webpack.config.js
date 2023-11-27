const path = require('path');

module.exports = {
  entry: './js/wcl-functions.js',
  output: {
    path: path.resolve(__dirname, 'js'),
    filename: 'wcl-bundle.js'
  },
  plugins: [
    // new BrowserSyncPlugin({
    //   proxy: 'dev.site-one',
    //   files: ['css/wcl-style.min.css'],
    //   reloadDelay: 0,
    // }),
  ],
};