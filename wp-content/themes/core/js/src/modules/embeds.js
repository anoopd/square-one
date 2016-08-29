/**
 * @module
 * @description JavaScript specific to the_content embeds,
 * specifically YouTube & Vimeo oembeds.
 */

import delegate from 'delegate';
import * as tools from '../utils/tools';
import { on } from '../utils/events';

/* global TweenMax */

const gs = TweenMax;
const el = {
	container: tools.getNodes('site-wrap')[0],
};

/**
 * @function setupOembeds
 * @description Setup oembeds.
 */

const setupOembeds = () => {
	el.embeds.forEach((embed) => {
		const pStray = embed.querySelector('p');

		// Remove errant WP induced P tag
		if (pStray !== null) {
			embed.removeChild(pStray);
		}

		// Set display mode of embeds for small vs. regular
		if (embed.offsetWidth >= 500) {
			embed.classList.remove('wp-embed-lazy--small');
		} else {
			embed.classList.add('wp-embed-lazy--small');
		}
	});
};

/**
 * @function resetEmbed
 * @description Reset embed.
 */

const resetEmbed = () => {
	const embed = document.getElementsByClassName('wp-embed-lazy--is-playing')[0];
	const trigger = embed.querySelector('.wp-embed-lazy__trigger');
	const iframe = embed.querySelector('iframe');

	// Remove embed
	embed.removeChild(iframe);
	embed.classList.remove('wp-embed-lazy--is-playing');

	// Fade in image/caption
	trigger.classList.remove('u-hidden');
	gs.set(trigger, { opacity: 1 });
};

/**
 * @function playEmbed
 * @description Play embed.
 */

const playEmbed = (e) => {
	e.preventDefault();

	// Reset embed if another is playing
	if (document.getElementsByClassName('wp-embed-lazy--is-playing').length) {
		resetEmbed();
	}

	const target = e.delegateTarget;
	const parent = tools.closest(target, '.wp-embed-lazy');
	const videoId = target.getAttribute('data-embed-id');
	const iframeUrl = (parent.getAttribute('data-embed-provider') === 'youtube') ? `https://www.youtube.com/embed/${videoId}?autoplay=1&autohide=1&fs=1&modestbranding=1&showinfo=0&controls=2&autoplay=1&rel=0&theme=light&vq=hd720` : `//player.vimeo.com/video/${videoId}?autoplay=1`;
	const iframe = document.createElement('iframe');
	iframe.id = videoId;
	iframe.frameBorder = 0;
	iframe.src = iframeUrl;
	iframe.width = 1280;
	iframe.height = 720;
	iframe.tabIndex = 0;

	// Add & kickoff embed
	parent.classList.add('wp-embed-lazy--is-playing');
	tools.insertBefore(iframe, target);
	iframe.focus();

	// Fade out image/caption, avoid fouc
	gs.to(target, 0.25, {
		opacity: 0,
		onComplete: () => {
			target.classList.add('u-hidden');
		},
	});
};

/**
 * @function executeResize
 * @description Bind the events for this module that react to resize events here.
 */

const executeResize = () => {
	setupOembeds();
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.embeds = tools.getNodes('lazyload-embed', true, el.container);
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(el.container, '[data-js="lazyload-trigger"]', 'click', (e) => playEmbed(e));

	on(document, 'modern_tribe/resize_executed', (e) => executeResize(e));
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const embeds = () => {
	if (!el.container) {
		return;
	}

	cacheElements();

	if (!el.embeds.length) {
		return;
	}

	bindEvents();
	setupOembeds();

	console.info('Initialized embeds scripts.');
};

export default embeds;
