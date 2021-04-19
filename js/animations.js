/**
 * Product Menu Animation
 */


const lionhead = document.getElementById('lionhead');
const productMenuBtn = document.querySelector('#product-menu-btn');
const productMenu = document.querySelector('#product-menu');

let productMenuOpen = false;

console.log(productMenu.style.display);

function toggleProductMenu() {
    if (productMenu.style.display == "none") {
      productMenu.style.display = "block";
    } else {
      productMenu.style.display = "none";
    }
  }



/** Toggle Function */

  // Show an element
var show = function (elem) {
	elem.style.display = 'block';
    elem.style.height =  '145px';

};

// Hide an element
var hide = function (elem) {
	elem.style.display = 'none';
    elem.style.height =  '0px';
};

// Toggle element visibility
var toggle = function (elem) {

	// If the element is visible, hide it
	if (window.getComputedStyle(elem).display === 'block') {
		hide(elem);
		return;
	}

	// Otherwise, show it
	show(elem);

};


function productMenuToggler(evt){

evt.preventDefault();
    console.log('clicktest Löwe')

    toggle(productMenu);

    if(!productMenuOpen){
        lionhead.classList.add('p_open');
        productMenuOpen = true;
        console.log("closed");
    }
    else{
        lionhead.classList.remove('p_open')
        productMenuOpen = false;
        console.log("open");
    }

}

productMenuBtn.addEventListener('click', productMenuToggler);
productMenuBtn.addEventListener('touchstart', productMenuToggler);
productMenuBtn.addEventListener('mouseover', productMenuToggler);






/*productMenuBtn.addEventListener('touchstart', () => {

    console.log('clicktest Löwe')

    if(!productMenuOpen){
        lionhead.classList.add('p_open');
        productMenu.classList.add('toggle-product-menu');
        console.log(productMenu);
        productMenuOpen = true;
        console.log("closed");
    }
    else{
        lionhead.classList.remove('p_open')
        productMenu.classList.remove('toggle-product-menu');
        console.log(productMenu);
        productMenuOpen = false;
        console.log("open");
    }

})*/


/**End Product Menu Toggle */


/*Menu Button*/

const menuBtn = document.querySelector('.menu-btn');
const BtnWrapper = document.querySelector('.ast-header-html-3');


console.log(menuBtn);
let menuOpen = false;
BtnWrapper.onclick = () => {

    console.log('clicktest Blatt');

    if(!menuOpen){
        menuBtn.classList.add('open');
        menuOpen = true;
        console.log(menuOpen);

    }else{
        menuBtn.classList.remove('open');
        menuOpen = false;
        console.log(menuOpen);

    }
};


window.addEventListener('resize', () =>{
    console.log(lionhead);

})