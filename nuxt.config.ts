// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/tailwindcss',
    'nuxt-icon'
  ],
  typescript: {
    strict: true
  },
  runtimeConfig: {
    public: {
      'api': process.env.NUXT_API
    }
  },
  app: {
    head: {
      title: "Rust Custom Map Link Generator",
      link: [
        {
          rel: "shortcut icon",
          href: "https://files.facepunch.com/lewis/1b2911b1/rust-marque.svg"
        }
      ],
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      meta: [
        {
          name: "description",
          content: "Upload your custom rust map and generate .map link for free without login "
        }
      ]
    }
  },
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {}
    }
  }
})
