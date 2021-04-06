/**
 * LionHead Animation
 */


const lionhead = document.getElementById('lionhead');
const productMenuBtn = document.querySelector('#product-menu-btn')
let productMenuOpen = false;


function moveLion(evt){

evt.preventDefault();
    console.log('clicktest Löwe')

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

productMenuBtn.addEventListener('click', moveLion)
productMenuBtn.addEventListener('touchstart', moveLion)




productMenuBtn.addEventListener('touchstart', () => {

    console.log('clicktest Löwe')

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
menuBtn.onclick = () => {

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