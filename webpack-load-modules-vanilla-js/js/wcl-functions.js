const modules = [];
const requireModules = require.context('./modules/', true, /\.js$/);

requireModules.keys().forEach((key) => {
	const module = requireModules(key).default;
	modules.push(module);
});


const ready = (callback) => {
	if (document.readyState != "loading") callback();
	else document.addEventListener("DOMContentLoaded", callback);
}

ready(() => {

	/* SCRIPTS GO HERE */

	Object.keys(modules).forEach(key => {
		if (modules[key]) {
			modules[key]();
		}
	});
});