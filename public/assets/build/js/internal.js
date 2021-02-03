$(window).on('load', function(){
    $('html').addClass('loaded');

});

$(function(){

    galleryInit()

    // resize()
    // $(window).on('resize', function () {
    //     resize()
    // });
    //
    $('.js-drop-menu-close').on('click', function () {
        $('.js-drop-menu').removeClass('active');
        $('body').removeClass('hidden');
    });

    $('.js-menu-open').on('click', function () {
        $('.js-drop-menu').addClass('active');
        $('body').addClass('hidden');
    });

    $('.js-drop-item-close').on('click', function () {
        $('.js-item-list').removeClass('active');
        $(this).removeClass('hide');
    });

    $('.js-menu-item-open').on('click', function () {
        $(this).siblings('.js-item-list').addClass('active');
        // $('.js-item-list').addClass('active');
    });

    let sliderSideDots = $("div.js-main-slide-side-item").length;
    $('.js-main-slide-side').slick({
        arrows: false,
        dots: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 2000,
    });

    $('.js-slider-side__next').on('click', function () {
        $('.js-main-slide-side').slick('slickNext')
    });

    $('.js-slider-side__prev').on('click', function () {
        $('.js-main-slide-side').slick('slickPrev')
    });


    $('.js-slider-side__count-all').text('/'+sliderSideDots);
    $('.js-main-slide-side').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        $('.js-slider-side__count').text( nextSlide+1);
    });


    $('.js-about-open').on('click', function () {
        $('.js-about-open').removeClass('active');
        $('.js-about-content').removeClass('active');
        $(this).addClass('active');
        dataAbout = $(this).data('index');
        $('.js-about-content[data-index='+dataAbout+']').addClass('active')
    });

    $('.js-filter-open').on('click', function () {
        if ($('.filter-btn__open').hasClass('active') ) {
            $('.filter-btn__open').removeClass('active');
            $('.filter-btn__open').html('<i class="icon icon-filter">').append('<span>Показать фильтры</span>').append('<span class="mobile">Фильтры</span>');
        } else {
            $('.filter-btn__open').addClass('active');
            $('.filter-btn__open').html('<i class="icon icon-filter_fill">').append('<span>Скрыть фильтры</span>').append('<span class="mobile">Фильтры</span>')
        }

        $(this).parents('.js-catalog-wrap').find('.js-filter').toggleClass('active');
        $('.js-goods-item').toggleClass('goods-item_tablet-33');
    });

    $('button').click(function(e){
        var button_classes, value = +$(this).siblings('.counter__value').val();
        button_classes = $(e.currentTarget).prop('class');
        if(button_classes.indexOf('up_count') !== -1){
            value = (value) + 1;
        } else {
            value = (value) - 1;
        }
        value = value < 1 ? 1 : value;
        $(this).siblings('.counter__value').val(value);
        return false;
    });

    $('.counter').click(function(){
        $(this).focus().select();
        return false;
    });

    $('.js-history-item-mobile').on('click', function () {
        // $(this).toggleClass('open')
        openWrap = $(this).parent('.js-history-item-mobile-wrap');
        openRaw = $(this).siblings('.js-history-item__drop-mobile');
        if ($(this).hasClass('active') ) {
            $(this).find("span").text('Детали заказа');
            $(this).removeClass('active');

        } else {
            $(this).find("span").text('Скрыть детали');
            $(this).addClass('active');

        }
        if ($(openRaw).hasClass('active') ) {
            $(openRaw).removeClass('active');

        } else {
            $(openRaw).addClass('active');

        }
        if ($(openWrap).hasClass('active') ) {
            $(openWrap).removeClass('active');

        } else {
            $(openWrap).addClass('active');

        }
    });

    $('.js-history-item').on('click', function () {
        // $(this).toggleClass('open')
        openRaw = $(this).siblings('.js-history-item__drop')
        if ($(this).hasClass('open') ) {
            $(this).removeClass('open');
            openRaw.slideUp();
        } else {
            $(this).addClass('open');
            openRaw.slideDown();
        }
    });

    $('.js-filter-item-open').on('click', function () {
        let siblings = $(this).siblings('.js-filter-item-list');
        // $(siblings).toggleClass('active')
        if ($(this).hasClass('active') ) {
            $(this).removeClass('active');
            siblings.slideUp();
        } else {
            $(this).addClass('active');
            siblings.slideDown();
        }
    });


    let dropBtn = $('.js-open'),
        drop = null,
        dropwrap = null;

    dropBtn.on('click', function() {
        drop = $(this).siblings('.js-drop');
        // alert(drop);
        dropwrap = $(this).parent('.js-open-wrap');
        if ($(this).hasClass('active') ) {
            $(this).removeClass('active');
            drop.slideUp();
        } else {
            $(this).addClass('active');
            drop.slideDown();
        }
    });

    $(document).click(function (e) {
        if(drop !== null) {
            if (!dropBtn.is(e.target) && !drop.is(e.target) && !dropwrap.is(e.target) && dropwrap.has(e.target).length === 0 && drop.has(e.target).length === 0) {
                // alert('мимо');
                $('.js-drop').slideUp();
                dropBtn.removeClass('active');
            }
            ;
        }
    });


    $('.js-select-item').on('click', function () {
        var value = $(this).attr('data-value');
        var name = $(this).attr('data-name');
        // $(this).siblings('.drop-flat__link-input').val(valueD);
        $(this).siblings("[name='drop-city__link-input']").val(value);
        $(this).parents('.js-open-wrap').find('.js-select').text(name).append('<span class="icon icon-arrow_down">');
        drop.slideUp();
        dropBtn.removeClass('active');
    });

    let sliderMainDots = $("a.js-slider-main__item").length;
    if (sliderMainDots == 0){
        sliderMainDots = $("div.js-slider-main__item").length;
    }

    $('.js-slider-main').slick({
        arrows: false,
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        // centerMode: true,
        variableWidth: true,
        autoplay: true,
        autoplaySpeed: 2000,
    });


    $('.js-slider-main__next').on('click', function () {
        $('.js-slider-main').slick('slickNext')
    });

    $('.js-slider-main__prev').on('click', function () {
        $('.js-slider-main').slick('slickPrev')
    });

    $('.js-slider-main__count-all').text('/'+sliderMainDots);
    $('.js-slider-main').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        $('.js-slider-main__count').text( nextSlide+1);
    });


    let sliderMiniDots = $("a.js-slider-mini__item").length;
    $('.js-slider-mini').slick({
        arrows: false,
        dots: false,
        // infinite: true,
        // speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        // centerMode: true,
        // variableWidth: true,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 1367,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 700,
                settings: {
                    variableWidth: true,
                    slidesToShow: 2
                }
            }
        ]
    });

    $('.js-slider-mini__next').on('click', function () {
        $('.js-slider-mini').slick('slickNext')
    });

    $('.js-slider-mini__prev').on('click', function () {
        $('.js-slider-mini').slick('slickPrev')
    });


    $('.js-slider-mini__count-all').text('/'+sliderMiniDots);
    $('.js-slider-mini').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        $('.js-slider-mini__count').text( nextSlide+1);
    });

    $(".js-scroll-to").click(function() {
        $(".js-scroll-to").removeClass('active');
        $(this).addClass('active');
        let scrollId = $(this).data('id');
        $(".js-scroll-item").removeClass('active');
        $('.js-scroll-item[data-id='+scrollId+']').addClass('active')
        $('html, body').animate({
            scrollTop: $('.js-scroll-item[data-id='+scrollId+']').offset().top - 30  // класс объекта к которому приезжаем
        }, 500); // Скорость прокрутки
    });

    $('.video-gallery').lightGallery();

})

function galleryInit() {
    if(typeof gallery == 'undefined'){
        return
    }
    let data = gallery;
    renderColor(data)
    if($(window).width() < 700) {
        renderViewMobile(data[0], 0)
    }else{
        renderMidImg(data[0].views[0])
        renderView(data[0], 0)
    }
    renderColorName(data[0])
    renderSize(data[0])



    $('body').on('click',  '.js-gallery-color .item-open__color-item ', function () {
        $('.js-gallery-color .item-open__color-item').removeClass('active')
        $(this).addClass('active')

        let index = $(this).data('index')
        if($(window).width() < 700) {
            $('.js-slick-mobile').slick('unslick')
            renderViewMobile(data[index], index)
        }else{
            renderMidImg(data[index].views[0])
            renderView(data[index], index)
        }
        renderColorName(data[index])
        renderSize(data[index])



        let value = $(this).data('value')
        $('.js-color-field').val(value)

    })

    $('body').on('click',  '.js-gallery-views .item-gallery__thumbs-item ', function () {
        $('.js-gallery-views .item-gallery__thumbs-item ').removeClass('active')
        $(this).addClass('active')

        let index = $(this).data('index')
        let dataIndex = $(this).data('data-index')
        renderMidImg(data[dataIndex].views[index])
    })


    $('body').on('click',  '.js-gallery-size .item-open__size-item ', function () {
        $('.js-gallery-size .item-open__size-item ').removeClass('active')
        $(this).addClass('active')
        let value = $(this).data('value')

        $('.js-size-field').val(value)
    })




    function renderMidImg(item){
        $('.js-gallery-main').empty().html('<img src="'+item.middle+'" />')
    }

    function renderView(item, dataIndex){
        let template = ''
        let isActive = ''
        item.views.forEach((view, i) => {
            isActive = i === 0 ? 'active' : ''
            template += '<div data-index="'+i+'" data-data-index="'+dataIndex+'" class="item-gallery__thumbs-item '+isActive+'"> <div class="item-gallery__thumbs-img-wrap"><img src="'+view.mini+'" alt="" class="item-gallery__thumbs-img"></div> </div>'
        })

        $('.js-gallery-views').empty().html(template)
    }

    function renderViewMobile(item, dataIndex){
        let template = ''
        let isActive = ''

        item.views.forEach((view, i) => {
            isActive = i === 0 ? 'active' : ''
            template += '<div data-index="'+i+'" data-data-index="'+dataIndex+'" class="item-gallery__mobile-item '+isActive+' js-slick-mobile-item"> <div class="item-gallery__mobile-img-wrap"><img src="'+view.middle+'" alt="" class="item-gallery__mobile-img"></div> </div>'
        })

        $('.js-gallery-views-mobile').empty().html(template)

        $('.js-slick-mobile__count').text(1);
        let sliderDotsMobile = $("div.js-slick-mobile-item").length;
        $('.js-slick-mobile').slick({
            arrows: false,
            dots: false,
            // infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            // centerMode: true,
            // variableWidth: true,
            // adaptiveHeight: true,
            autoplay: true,
            autoplaySpeed: 2000,
        });

        $('.js-slick-mobile__next').on('click', function () {
            $('.js-slick-mobile').slick('slickNext')
        });

        $('.js-slick-mobile__prev').on('click', function () {
            $('.js-slick-mobile').slick('slickPrev')
        });


        $('.js-slick-mobile__count-all').text('/'+sliderDotsMobile);
        $('.js-slick-mobile').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $('.js-slick-mobile__count').text( nextSlide+1);
        });

    }

    function renderColor(item){
        let template = ''
        let isActive = ''

        item.forEach((color, i) => {
            isActive = i === 0 ? 'active' : ''
            template += '<div data-value="'+color.id+'" data-index="'+i+'" class="item-open__color-item '+isActive+'"> <div class="item-open__color-img-wrap"><img src="'+color.color+'" alt="" class="item-open__color-img"></div> </div>'

        })
        $('.js-gallery-color').empty().html(template)

        let value = $('.js-gallery-color  .item-open__color-item').eq(0).data('value')
        $('.js-color-field').val(value)
    }

    function renderColorName(item){
        let template = ''
        let isActive = ''
        item.colorname.forEach((colorname, i) => {
            isActive = i === 0 ? 'active' : ''
            template += '<div data-value="'+colorname+'" data-index="'+i+'" class="item-open__name-item '+isActive+'">'+colorname+'</div>'
        })


        $('.js-gallery-name').empty().html(template)


        let value = $('.js-gallery-name .item-open__name-item').eq(0).data('value')
        $('.js-size-field').val(value)
    }

    function renderSize(item){
        let template = ''
        let isActive = ''
        item.size.forEach((size, i) => {
            isActive = i === 0 ? 'active' : ''
            template += '<div data-value="'+size+'" data-index="'+i+'" class="item-open__size-item '+isActive+'">'+size+'</div>'
        })


        $('.js-gallery-size').empty().html(template)


        let value = $('.js-gallery-size .item-open__size-item').eq(0).data('value')
        $('.js-size-field').val(value)
    }
}
