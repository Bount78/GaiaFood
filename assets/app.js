/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
// import $ from 'jquery';
// import Popper from 'popper.js';

// Configure jQuery and Popper.js
window.jQuery = $;
window.Popper = Popper;
const $ = require('jquery');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');





import '../node_modules/bootstrap/dist/css/bootstrap.css';
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

var images = [
    'build/images/BG/bg_welcome01.jpg',
    'build/images/BG/bg_welcome02.jpg',
    'build/images/BG/bg_welcome03.jpg',
    'build/images/BG/bg_welcome04.jpg',
    'build/images/BG/bg_welcome05.jpg',

    // Ajoutez autant d'images que vous le souhaitez
];


var currentImageIndex = 0;
var slideshowElements = document.getElementsByClassName('background');
var opacity = [1, 0];

function changeImage() {
    slideshowElements[0].style.backgroundImage = 'url(' + images[currentImageIndex] + ')';
    slideshowElements[0].style.opacity = opacity[0];
    slideshowElements[1].style.backgroundImage = 'url(' + images[(currentImageIndex + 1) % images.length] + ')';
    slideshowElements[1].style.opacity = opacity[1];
    currentImageIndex = (currentImageIndex + 1) % images.length;
    opacity.reverse();
}

if (document.getElementById('slideshow')) {
    changeImage();
    setInterval(changeImage, 30000);
}
