Vue = BX.Vue;

Vue.component('bx-catalog-element',
        {
            props: {
                serialized_data: String,
            },
            data() {
                let data = JSON.parse(this.serialized_data);
                let selected = data.is_sku ? Object.values(data.offers)[0] : data;
                let color_helper = [];
                for (offer in data.offers) {
                    color_helper[data.offers[offer].color.id] = data.offers[offer].color;
                }
                return {
                    id: selected.id,
                    name: selected.name,
                    articul: selected.articul,
                    product: selected.product,
                    photo: selected.photo,
                    new_product: data.new_product,
                    hit: data.hit,
                    video: data.video,
                    description: data.description,
                    application: data.application,
                    structure: data.structure,
                    offers: data.offers,
                    is_sku: data.is_sku,
                    selected: selected,
                    selected_color: selected.color,
                    selected_volume: selected.volume,
                    color_helper: color_helper,
                };
            },
            computed: {
                photos: function() {
                    return [this.selected.photo, ...this.selected.more_photo];
                },

                color_facet: function() {
                    let values = Object.values(this.offers);
                    res = [...new Set(values.filter(offer => offer.volume === this.selected_volume).
                            map(offer => this.color_helper[offer.color.id]))];
                    return res;
                },
                volume_facet: function() {
                    return [...new Set(Object.values(this.offers).map(offer => offer.volume))];
                },

                selected_offer: function() {
                    return {
                        color: this.selected_color,
                        volume: this.selected_volume,
                    };
                },
                main_photo: function() {

                },

            },
            watch: {
                color_facet: function(newFacet, oldFacet) {
                    this.selected_color = newFacet[0];
                },
                selected_offer: function(newSelected, oldSelected) {
                    this.selected = Object.values(this.offers).
                            find(offer => offer.color.id === newSelected.color.id && offer.volume === newSelected.volume);
                    this.photo = this.selected.photo;
                },
            }
            ,
            model: {}
            ,

            methods: {
                clickPhoto(event) {
                    debugger
                    this.photo = event.target.src;
                }
                ,
                selectColor: function(event) {
                    this.selected_color = this.color_helper[event.target.dataset.index];
                },
                selectVolume: function(event) {
                    this.selected_volume = event.target.dataset.value;
                },
                addToCart: function() {
                    var url = '/api/internal/sale/cart/add/?productId=' + this.selected.id + '&quantity=1';

                    body = {
                        productId: this.selected.id,
                        quantity: 1,
                        properties: []
                    };
                    var options = {
                        method: 'POST',
                        mode: 'cors',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(body),
                    };

                    fetch(url, options).
                            then(response => response.json()).
                            then(alert('Товар успешно добавлен')).
                            catch(error => console.log('error', error));
                },

            }
            ,
            template: `<div> 
                            <div class="item-open">
                               <div class="item-open-wrap">
                                   <div class="item-open__left">
                                       <div class="item-gallery">
                                           <div class="item-gallery__thumbs js-gallery-views">
                                                <div v-for="(mphoto,index) in photos" 
                                                     :data-index="index" 
                                                     :data-data-index="index" 
                                                     class="item-gallery__thumbs-item">
                                                      <div class="item-gallery__thumbs-img-wrap">
                                                         <img :src="mphoto" 
                                                              v-on:click="clickPhoto"
                                                              alt=""
                                                              class="item-gallery__thumbs-img">
                                                      </div>
                                                </div>
                                           </div>
                                           <div class="item-gallery__big-image">
                                               <div class="item-gallery__big-img-wrap js-gallery-main">
                                                    <img :src="photo">
                                               </div>
                                           </div>
                                           <div class="item-gallery__stickers">
                                               <div v-if="hit" 
                                                    class="item-gallery__sticker">
                                                    <div class="sticker-hit">HIT</div>
                                               </div>
                                               <div v-if="new_product"
                                                    class="item-gallery__sticker">
                                                    <div class="sticker-new">new</div>
                                               </div>
                                           </div>
                                           <div class="item-slider-wrap">
                                               <div class="item-slider-mobile js-gallery-views-mobile js-slick-mobile"></div>
                                               <div class="item-slider__nav">
                                                   <button class="slider-side__prev js-slick-mobile__prev btn"><i class="icon icon-arrow_left"></i></button>
                                                   <div class="slider-side__count">
                                                       <div class="slider-side__count-current js-slick-mobile__count">1</div>
                                                       <div class="slider-side__count-all js-slick-mobile__count-all">8</div>
                                                   </div>
                                                   <button class="slider-side__next js-slick-mobile__next btn"><i class="icon icon-arrow_right"></i></button>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="goods-advantages">
                                           <div class="advantages">
                                               <div class="advantages-item">
                                                   <div class="advantages-item__title"><b>Доставка</b> по рф</div>
                                                   <div class="advantages-item__icon"><i class="icon icon-box_fill"></i></div>
                                               </div>
                                               <div class="advantages-item">
                                                   <div class="advantages-item__title"><b>оригинальная продукция</b></div>
                                                   <div class="advantages-item__icon"><i class="icon icon-thumbs_up"></i></div>
                                               </div>
                                               <div class="advantages-item">
                                                   <div class="advantages-item__title"><b>гарантия</b> качества</div>
                                                   <div class="advantages-item__icon"><i class="icon icon-star"></i></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="item-open__middle">
                                       <div class="item-open__top">
                                           <div class="item-open__title1">{{selected.name}}</div>
                                           <div class="item-open__title2"><b>{{selected.name}}</b><br>{{selected.name}}</div>
                                           <div class="item-open__article">Артикул: {{selected.articul}}</div>
                                       </div>
                                       <div class="item-open__attr">
                                           <div class="item-open__size">
                                               <div class="item-open__size-title">Объем (мл)</div>
                                               <div class="item-open__size-list js-gallery-size">
                                                        <div v-for="volume in volume_facet" 
                                                             v-on:click="selectVolume"
                                                             :data-value="volume"
                                                             class="item-open__size-item"
                                                             :class="{active: volume==selected_volume}">
                                                                {{volume}}                                                             
                                                        </div>
                                                </div>
                                           </div>
                                           <div class="item-open__color">
                                                <div class="item-open__name">
                                                    <div class="item-open__name-list js-gallery-name">
                                                         <div class="item-open__name-item active">
                                                              {{selected_color.name}}
                                                         </div>
                                                     </div>
                                                </div>
                                                <div class="item-open__color-list js-gallery-color">
                                                     <div v-for="color in color_facet"  
                                                          class="item-open__color-item"
                                                          :class="{active: color.name==selected_color.name}"> 
                                                         <div class="item-open__color-img-wrap">
                                                             <img :src="color.file" 
                                                                   v-on:click="selectColor"
                                                                   :data-index="color.id"
                                                                  alt="" 
                                                                  class="item-open__color-img">
                                                         </div>
                                                     </div>
                                                </div>
                                           </div>
                                       </div>
                                       <div class="item-open__bottom">
                                           <div class="item-open__discont">Скидка - {{selected.discount}}%</div>
                                           <div class="item-open__price">
                                               <div class="item-open__price-value">{{selected.discount_price}} ₽</div>
                                               <div class="item-open__price-old">{{selected.price}} ₽</div>
                                           </div>
                                           <div class="item-open__buttons">
                                               <div class="item-open__btn">
                                                    <a  v-on:click.prevent="addToCart"
                                                        href="#"
                                                        class="btn btn-black btn-normal btn_full-width btn_center btn-small-padding">
                                                         <i class="icon icon-cart"></i>Добавить в корзину
                                                    </a>
                                               </div>
                                               <div class="item-open__btn">
                                                    <a href="#" class="btn btn-white btn-normal btn_full-width btn_center btn-small-padding">
                                                    <i class="icon icon-whatsapp"></i>Заказать в WhatsApp
                                                    </a>
                                               </div>
                                               <a href="#" class="item-open__btn-fav">
                                                    <div class="btn btn-white btn-mini"><i class="icon icon-heart1"></i></div></a>
                                           </div>
                                           <div class="item-open__bottom-wrap">
                                               <div class="item-open__delivery"><i class="icon icon-box"></i><span>Доставим этот товар 7 октября в</span> <span class="mobile">Доставим 7 октября в</span> &nbsp;<a href="#">Махачкалу</a></div>
                                               <div class="item-open__share"><i class="icon icon-share"></i>поделиться</div>
                                           </div>
                                       </div>
                                   </div>
                                 </div>
                           </div>
                            <div class="item-about padding-80">
                               <div class="item-about__left">
                                   <div class="item-about__left-video-wrap">
                                       <div id="video-gallery"  class="video-gallery">
                                           <iframe width="560"
                                                   height="315" 
                                                   :src="'//'+video" 
                                                   title="YouTube video player" 
                                                   frameborder="0" 
                                                   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                   allowfullscreen></iframe>
                                       </div>
                                   </div>
                               </div>
                               <div class="item-about__right">
                                   <div class="item-about__nav">
                                       <div data-index="1" class="item-about__nav-item js-about-open active">Описание</div>
                                       <div data-index="2" class="item-about__nav-item js-about-open">применение</div>
                                       <div data-index="3" class="item-about__nav-item js-about-open">состав</div>
                                   </div>
                                   <div class="item-about__drop js-select-wrap">
                                       <div class="item-about-wrap">
                                           <div class="select-city__value-wrap js-open-wrap">
                                               <div class="item-about__nav-item js-select js-open">Описание<span class="icon icon-arrow_down"></span></div>
                                               <!--<div class="select-city__value js-select js-open">Описание<span class="icon icon-arrow_down"></span></div>-->
                                               <div class="select-city-drop about js-drop">
                                                   <div class="drop-city__content">
                                                       <div data-index="1" data-name="Описание" class="item-about__nav-item js-select-item js-about-open active">Описание</div>
                                                       <div data-index="2" data-name="применение" class="item-about__nav-item js-select-item js-about-open">применение</div>
                                                       <div data-index="3" data-name="состав" class="item-about__nav-item js-select-item js-about-open">состав</div>
                                                       <input type="hidden" name="drop-city__link-input" value="1">
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="item-about__content">
                                       <div v-html="description"
                                            data-index="1" 
                                            class="item-about__content-item js-about-content active">
                                        </div>
                                       <div v-html="application"
                                            data-index="2" 
                                            class="item-about__content-item js-about-content">
                                          </div>
                                       <div v-html="structure"
                                            data-index="3" 
                                            class="item-about__content-item js-about-content">
                                       </div>
                                   </div>
                               </div>
                           </div>
                      </div>`,

        })
;

BX.ready(function() {
    Vue.create({
        el: document.getElementById('product_detail'),
        data: {},

        methods: {},


    });
});
