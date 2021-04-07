"use strict";

/**
 * LionHead Animation
 */
var lionhead = document.getElementById('lionhead');
var productMenuBtn = document.querySelector('#product-menu-btn');
var productMenuOpen = false;

function moveLion(evt) {
  evt.preventDefault();
  console.log('clicktest Löwe');

  if (!productMenuOpen) {
    lionhead.classList.add('p_open');
    productMenuOpen = true;
    console.log("closed");
  } else {
    lionhead.classList.remove('p_open');
    productMenuOpen = false;
    console.log("open");
  }
}

productMenuBtn.addEventListener('click', moveLion);
productMenuBtn.addEventListener('touchstart', moveLion);
productMenuBtn.addEventListener('touchstart', function () {
  console.log('clicktest Löwe');

  if (!productMenuOpen) {
    lionhead.classList.add('p_open');
    productMenuOpen = true;
    console.log("closed");
  } else {
    lionhead.classList.remove('p_open');
    productMenuOpen = false;
    console.log("open");
  }
});
/*Menu Button*/

var menuBtn = document.querySelector('.menu-btn');
var BtnWrapper = document.querySelector('.ast-header-html-3');
console.log(menuBtn);
var menuOpen = false;

BtnWrapper.onclick = function () {
  console.log('clicktest Blatt');

  if (!menuOpen) {
    menuBtn.classList.add('open');
    menuOpen = true;
    console.log(menuOpen);
  } else {
    menuBtn.classList.remove('open');
    menuOpen = false;
    console.log(menuOpen);
  }
};

window.addEventListener('resize', function () {
  console.log(lionhead);
});
//# sourceMappingURL=all.js.map
