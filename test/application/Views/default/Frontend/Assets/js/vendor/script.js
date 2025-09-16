$(document).ready(function() {

    // Click menu con để chạy xuống vị trí theo id (Product - About us)
    $('.scrollspy a').click(function(event) {
    event.preventDefault();

    part = $(this).attr('href'); 

    position = $(part).offset().top; 

    $('html, body').animate({scrollTop: position},1000);
    });
    

    // Thả accordion (About us)
    $('.accordion-item.active .accordion-body').slideDown();
    $('.accordion-header').click(function(){
        $(this).parent().parent().toggleClass('active');
        $(this).parent().parent().children('.accordion-body').slideToggle(); // Từ header tìm về cha (item) rồi đưa về con (body)
    })


    // Click logo to top up
    $('.back-to-top').click(function(event) {
        $('html,body').animate({scrollTop: 0},800);
    }); // Button go to top


    
});