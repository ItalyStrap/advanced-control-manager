<?php

/**
 * This is the style for the video Lazy Load
 */

?>.youtube {
    background-color: #000;
    margin-bottom: 32px;
    margin-bottom: 2rem;
    position: relative;
    padding-top: 56.25%;
    overflow: hidden;
    cursor: pointer;
}
.youtube::after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #000;
    opacity: 0.3;
}
.youtube img {
    width: 100%;
    top: -16.82%;
    left: 0;
}
.youtube .play-button {
    width: 90px;
    height: 60px;
    background-color: #333;
    box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
    z-index: 1;
    opacity: 0.8;
    border-radius: 6px;
}
.youtube .play-button:before {
    content: "";
    border-style: solid;
    border-width: 15px 0 15px 26.0px;
    border-color: transparent transparent transparent #fff;
}
.youtube img,
.youtube .play-button {
    cursor: pointer;
}
.youtube img,
.youtube iframe,
.youtube .play-button,
.youtube .play-button:before {
    position: absolute;
}
.youtube .play-button,
.youtube .play-button:before {
    top: 50%;
    left: 50%;
    transform: translate3d( -50%, -50%, 0 );
}
.youtube iframe {
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
}
