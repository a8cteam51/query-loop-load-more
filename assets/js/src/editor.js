import { createHooks } from '@wordpress/hooks';
import domReady from '@wordpress/dom-ready';

window.wpcomsp_qllm = window.wpcomsp_qllm || {};
window.wpcomsp_qllm.hooks = createHooks();

domReady( () => {
	window.wpcomsp_qllm.hooks.doAction( 'editor.ready' );
} );
