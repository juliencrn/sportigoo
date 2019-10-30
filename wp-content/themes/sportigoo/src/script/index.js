import {navbar} from './navbar.js'
import {cookie} from './cookie.js'
import {smoothScroll} from './smooth-scroll.js'
import {customSelect} from './custom-select.js'
import {svgMap} from './svg-map'
import {carousel} from './carousel.js'
import {activities} from './activities.js'
import {modal} from './modal.js'
import {lastPosts} from './last-posts.js'
import {respNavigation} from './resp-navigation.js'
import {faq} from './faq.js'
import {searchActivity} from './search-activity'
import {deleteCookie, getCookie, setCookie} from "./cookie";
import {activitiesPreview} from './activities-preview'
import {forceMap} from './force-map'
import {modalNewsletter} from './modal-newsletter'
import {woocommerce} from './woocommerce'

// Document ready
jQuery($ => {

	// let whereIam = getCookie('whereIam');
	// console.log(whereIam ?
	// 	"Region " + whereIam + " selectionnée" :
	// 	'Pas de région selectionnée'
	// );

	// setCookie('whereIam', 0);

	customSelect($);
	cookie($);
	navbar($);
	activitiesPreview($);
	smoothScroll($);
	carousel($);
	forceMap($);
	activities($);
	modal($);
	lastPosts($);
	respNavigation($);
	faq($)
	modalNewsletter($);
	svgMap($);
	searchActivity($);
	woocommerce($)

});

