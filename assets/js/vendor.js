window.$ = window.jQuery = require('jquery');
require('jquery-ui');
require('jquery-jscroll');
require('jquery-modal');
require('spectrum-colorpicker');
require('jquery-hammerjs');
window.Handlebars = require('handlebars/dist/handlebars.min.js');
window.ClipboardJS = require('clipboard');

import { WOW } from 'wowjs';

import 'handlebars/dist/handlebars.min.js';

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();
