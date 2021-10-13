// $("#hero_slider").slick({
//   slidesToShow: 1,
//   slidesToScroll: 1,
//   draggable: true,
//   arrows: true,
//   prevArrow: "#prev_slide",
//   nextArrow: "#next_slide",
//   dots: true,
//   speed: 900,
//   infinite: true,
//   autoplay: false,
//   fade: true,
//   //   responsive: [
//   //     {
//   //       breakpoint: 1800,
//   //       settings: {
//   //         slidesToShow: 4,
//   //       },
//   //     },
//   //   ],
// });
// console.log(document.querySelector("#hero_slider"));

jQuery(document).ready(function(){
    function createSlick() {
        $("#hero_slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            draggable: true,
            arrows: true,
            prevArrow: "#prev_slide",
            nextArrow: "#next_slide",
            dots: true,
            speed: 900,
            infinite: true,
            autoplay: false,
            fade: true,
            //   responsive: [
            //     {
            //       breakpoint: 1800,
            //       settings: {
            //         slidesToShow: 4,
            //       },
            //     },
            //   ],
        });
    }
    createSlick()
    // $(window).on( 'resize', createSlick);
});


