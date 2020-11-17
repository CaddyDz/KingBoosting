import https from 'https';

export default function ({
  $axios,
  redirect
}, inject) {
  $axios.setBaseURL(process.env.API_URL);
  const agent = new https.Agent({
    rejectUnauthorized: false
  });
  $axios.onRequest(config => {
    config.httpsAgent = agent;
  });
}
