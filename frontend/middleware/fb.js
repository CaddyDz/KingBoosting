import https from 'https';
import axios from 'axios'

export default function({
	route
}) {
	// At request level
	const agent = new https.Agent({
		rejectUnauthorized: false
	});

	return axios.get(process.env.API_URL + '/checkFB', {
		httpsAgent: agent
	})
}
