


/*Menu Button*/
jQuery( document ).ready(function() {
  const menuBtn = document.getElementsByClassName('menu-btn');
  console.log(menuBtn)
  menuBtn.onclick = () => {
    menuBtn.forEach(element => {
      console.log(element)
      element.classList.contains('open') ? element.classList.remove('open') : element.classList.add('open');
    });
};


});



/* Main Menu */

function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}


/*PRODUCT VARIATIONS SLIDER CBD ÖLE*/


jQuery( function() {
    var select = jQuery( "#anteil-cbd" );
    var slider = jQuery( "#variations-slider" ).slider({
      min: 1,
      max: 3,
      range: false,
      animate:"fast",
      value: select[ 0 ].selectedIndex + 1,
      slide: function( event, ui ) {
        select[ 0 ].selectedIndex = ui.value - 1;
        select.trigger("change");
      }
    });
    jQuery( "#anteil-cbd" ).on( "change", function() {
      slider.slider( "value", this.selectedIndex + 1 );
      console.log("Value Passed: " + this.selectedIndex + 1)
      
    });
  } );


  /* DECKEL CBD BLÜTEN*/

  function openPopUp(popupbutton, popupid) {


    var popup = document.getElementById(popupid);
    var popbtn = document.getElementById(popupbutton);
    var logo = popbtn.children[0];
    
    var pop_btn_array = document.getElementsByClassName("popup_btn");
    var otherpopups = document.getElementsByClassName("info-popup");
    

    Array.prototype.forEach.call(otherpopups, function(p) {
    if(p.id != popupid){
        p.classList.remove("show");
    }
    });

    Array.prototype.forEach.call(pop_btn_array, function(p) {
    console.log(p.id);
    if(p.id == popupbutton){
        p.classList.toggle("btn_active");

    }
    else{
        p.classList.toggle("hide_btn");
    }

    });

    logo.classList.toggle("infologo_active");
    popup.classList.toggle("show"); 
    document.getElementById("outer-circle").classList.toggle("outer-circle_spin");
    document.getElementById("inner-circle").classList.toggle("inner-circle_spin");
    document.getElementById("circle-shadow").classList.toggle("inner-circle_spin");
    document.getElementById("logogrid").classList.toggle("grid-transform");
    
}
  
