import webpack from 'webpack';
import colors from 'vuetify/es5/util/colors';

export default {
  mode: 'universal',
  /*
   ** Headers of the page
   */
  head: {
    titleTemplate: '%s | ' + 'King Boosting',
    title: 'Buy LOL ELO Boost & Premium LOL Boosting' || '',
    meta: [{
        charset: 'utf-8'
      },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
      },
      {
        hid: 'description',
        name: 'description',
        content: 'Buy high quality ELO Boosting and LoL Boost services. 100% Safety and Privacy. The best ELO &amp; League Boost experience. Available on all servers.'
      },
      {
        name: 'keywords',
        content: 'elo boost, lol elo boost, lol boost, lol elo boosting, elo boost lol, cheap elo boosting, elo boosting'
      }
    ],
    link: [{
      rel: 'icon',
      type: 'image/x-icon',
      href: '/favicon.ico'
    }]
  },
  /*
   ** Customize the progress-bar color
   */
  loading: {
    color: '#673ab7'
  },
  /*
   ** Global CSS
   */
  css: [
    '~/css/main.css'
  ],
  render: {
    bundleRenderer: {
      shouldPreload: (file, type) => {
        return ['script', 'style', 'font'].includes(type)
      }
    }
  },
  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    '~plugins/globals',
    '~plugins/storage',
    '~plugins/axios',
    '~plugins/CountryFlag.js',
    {
      src: '~plugins/vuex',
      ssr: false
    }
  ],
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    '@nuxtjs/vuetify'
  ],
  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    [
      'nuxt-i18n',
      {
        /* module options */
      }
    ],
    // Doc: https://github.com/nuxt-community/dotenv-module
    '@nuxtjs/dotenv'
  ],
  i18n: {
    locales: ['en', 'fr', 'de', 'at', 'swiss', 'es', 'nl', 'it', 'se', 'no', 'dk'],
    defaultLocale: 'en',
    vueI18n: {
      fallbackLocale: 'en'
    },
    vueI18nLoader: true,
    strategy: 'no_prefix',
  },
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {
    // proxyHeaders: false,
    debug: true
  },
  /*
   ** vuetify module configuration
   ** https://github.com/nuxt-community/vuetify-module
   */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: '#673ab7',
          secondary: '#9c27b0',
          accent: '#673ab7',
          error: '#f44336',
          warning: '#ffeb3b',
          info: '#3f51b5',
          success: '#4caf50'
        },
      },
    }
  },
  /*
   ** Build configuration
   */
  build: {
    /*
     ** You can extend webpack config here
     */
    plugins: [
      // Expose lodash globally
      new webpack.ProvidePlugin({
        // global modules
        '_': 'lodash'
      })
    ]
  }
}
