require("particles.js");

export default (context, inject) => {
	if (!context.isDev) {
		particlesJS.load("particles-js", "/particlesjs-config.json");
	}
}
