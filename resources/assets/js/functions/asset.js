/**
 * Generate a complete url
 *
 * @return string Url
 */
export default function(relativeUrl) {

	// If matches a domain, return a domain
	let domains = {
		'localhost': 'localhost/wysiwyg/public/',
	};

	if(typeof domains[window.location.hostname] !== 'undefined') {
		return window.location.protocol+'//'+domains[window.location.hostname]+'/'+relativeUrl;
	}
	alert('Your domain has not been set.\nYou tried to connect with: '+window.location.hostname+'.\nPlease add your domain in asset.js');
}
