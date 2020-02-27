export default function ({
  $axios,
  redirect
}, inject) {
  // Create a custom axios instance
  const api = $axios.create({
    headers: {
      common: {
        Accept: 'text/plain, */*'
      }
    }
  })

  // Set baseURL to something different
  api.setBaseURL('https://kingboosting.dev/api/')

  // Inject to context as $api
  inject('api', api)
}
