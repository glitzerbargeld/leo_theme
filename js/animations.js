/**
 * LionHead Animation
 */


const lionhead = document.querySelector('#lionhead');
const productMenuBtn = document.querySelector('#product-menu-btn')
let productMenuOpen = false;

productMenuBtn.addEventListener('click', () => {
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

})



/*Menu Button*/

const menuBtn = document.querySelector('.menu-btn');
console.log(menuBtn);
let menuOpen = false;
menuBtn.addEventListener('click', () => {
    if(!menuOpen){
        menuBtn.classList.add('open');
        menuOpen = true;
        console.log(menuOpen);

    }else{
        menuBtn.classList.remove('open');
        menuOpen = false;
        console.log(menuOpen);

    }
});