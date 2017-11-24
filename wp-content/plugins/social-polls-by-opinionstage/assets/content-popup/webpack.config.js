var path = require('path')

module.exports = function(env={}) {
  return {
    entry: './index.js',

    output: {
      filename: 'content-popup.js',
      path: path.resolve(__dirname, '../../admin/js')
    },

    module: {
      rules: [
        {
          test: /\.js$/,
          loader: 'babel-loader',
          exclude: /node_modules/,
        },
        {
          test: /\.scss$/,
          use: [
            'style-loader',
            { loader: 'css-loader',
              options: {
                sourceMap: !env.production
              }
            },
            { loader: 'sass-loader',
              options: {
                sourceMap: !env.production
              }
            },
            { loader: 'postcss-loader',
              options: {
                sourceMap: !env.production
              }
            },
          ]
        }
      ]
    },

    resolve: {
      alias: {
        'vue$': 'vue/dist/vue.esm.js',
        'lodash$': 'lodash-es',
      }
    },

    watchOptions: {
      ignored: [
        /node_modules/
      ]
    }
  }
}
