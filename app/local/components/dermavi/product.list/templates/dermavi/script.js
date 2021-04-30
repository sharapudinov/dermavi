Vue = BX.Vue;

Vue.component('bx-sorter',
        {
            props: {
                options: {
                    default: function() {
                        return {
                            PRICE: 'Цене',
                            NAME: 'Названию',
                        };
                    },
                },
                selected_value: {
                    default: null,
                },
            },
            data() {
                return {
                    value: this.selected_value,
                };
            },

            model: {},

            methods: {
                click(event) {
                    this.$emit('sort', event.currentTarget.dataset.value);
                },
            },
            template: `<div class="cart-list__office-sort">
                                <div class="select-sort-wrap">
                                    <div class="select-sort__label">
                                        <i class="icon icon-list"></i>
                                        Сортировать по
                                    </div>
                                    <div class="select-sort__value-wrap js-open-wrap">
                                        <div class="select-sort__value js-select js-open">{{value?options[value]:''}}<span
                                                class="icon icon-arrow_down"></span></div>
                                        <div class="select-sort-drop js-drop">
                                            <div class="drop-city__content">
                                                <div v-for="(name, code) in options" 
                                                     v-on:click="click"
                                                     :data-value="code" 
                                                     :data-name="name"
                                                     class="drop-city__link js-select-item">{{name}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`,
        });

Vue.component('bx-pagination',
        {
            props: {
                page: {
                    default: 1,
                },
                count: {
                    required: true,
                },
            },
            computed: {
                pages: function() {
                    return [...this.generatePagenSequence(this.count)];
                },
            },

            model: {},

            methods: {
                generatePagenSequence: function* (count) {
                    for (let i = 1; i <= count; i++)
                        yield {number: i, isActive: i == this.page};
                },
                click(event) {
                    this.$emit('page', event.currentTarget.dataset.page);
                },
            },
            template: `<div class="pagination mb200">
                            <div class="pagination-list">
                                <a v-for="page in pages" 
                                   v-on:click="click"
                                   :data-page="page.number"
                                   :class="{active: page.isActive}"
                                   class="pagination-item">{{page.number}}
                                </a>
                            </div>
                       </div>`,
        });

Vue.component('bx-filter',
        {
            props: {},
            data() {
                return {
                    pages: [...this.generatePagenSequence(this.count)],
                };
            },

            model: {},

            methods: {
                generatePagenSequence: function* (count) {
                    for (let i = 1; i <= count; i++)
                        yield {number: i, isActive: i == this.page};
                },
                click(event) {
                    this.$emit('page', event.currentTarget.dataset.page);
                },
            },
            template: `<div class="catalog-filter js-filter">
    <div class="catalog-filter-wrap">
        <div class="catalog-filter__top">
            <div class="catalog-filter__title">ФИЛЬТРЫ</div>
            <div class="catalog-filter__close js-filter-open"><i class="icon icon-x-circle"></i></div>
        </div>
        <div class="catalog-filter__applied">
            <div class="catalog-filter__applied-title">Примененные</div>
            <div class="catalog-filter__applied-list">
                <div class="catalog-filter__applied-item">
                    10 000 - 15 000 ₽ <div class="catalog-filter__applied-icon"><i class="icon icon-close"></i></div>
                </div>
                <div class="catalog-filter__applied-item">
                    Увлажнение <div class="catalog-filter__applied-icon"><i class="icon icon-close"></i></div>
                </div>
            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title">Категория</div>
            <div class="catalog-filter__category-form">
                <select class="input input-normal input-small_padding input-full_width input-border" name="" id="">
                    <option selected="" value="">Все категории</option>
                    <option value="">1</option>
                    <option value="">2</option>
                    <option value="">3</option>
                </select>
                <div class="form__btn form__btn-right"><div class="forms__icon"><i class="icon icon-arrow_down"></i></div></div>
            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open active">Цена <i class="icon icon-arrow_down"></i></div>
            <div class="catalog-filter__list js-filter-item-list active">
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="1000-5000" name="Bielita" value="Bielita">
                    <label for="1000-5000">1 500 - 5 000 ₽</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="5000-10000" name="Bielita" value="Bielita">
                    <label for="5000-10000">5 000 - 10 000 ₽</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="10000-15000" name="Bielita" value="Bielita">
                    <label for="10000-15000">10 000 - 15 000 ₽</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="15000+" name="Bielita" value="Bielita">
                    <label for="15000+">Дороже 15 000 ₽</label>
                </div>
            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open active">Бренд <i class="icon icon-arrow_down"></i></div>
            <div class="catalog-filter__list js-filter-item-list active">
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="all" name="Bielita" value="Bielita">
                    <label for="all">Все</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="1" name="Antonio" value="Antonio">
                    <label for="1">Forll’ed</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="2" name="Sardello" value="Sardello">
                    <label for="2">Uteki</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="3" name="Dove" value="Dove">
                    <label for="3">Menard</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="4" name="Murakoti" value="Murakoti" disabled="disabled">
                    <label for="4">Cellcosmet </label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="5" name="Bielita2" value="Bielita2">
                    <label for="5">Obagi medical</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="6" name="Mike" value="Mike">
                    <label for="6">MM system </label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="7" name="Sardello3" value="Sardello3">
                    <label for="7">Colorescience </label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="Dove8" name="Dove8" value="Dove8">
                    <label for="Dove8">Dove8</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="Murakoti55" name="Murakoti55" value="Murakoti55" disabled="disabled">
                    <label for="Murakoti55">Murakoti55</label>
                </div>
            </div>
        </div>

        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open">Страна производителя <i class="icon icon-arrow_down"></i></div>
            <div class="catalog-filter__list js-filter-item-list">
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="italy" name="Murakoti55" value="italy">
                    <label for="italy">Италия</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="france" name="Murakoti55" value="france">
                    <label for="france">Франция</label>
                </div>
                <div class="checkbox-black__wrap">
                    <input class="checkbox-black" type="checkbox" id="russia" name="russia" value="russia" disabled="disabled">
                    <label for="russia">Россия</label>
                </div>
            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open">Тип кожи <i class="icon icon-arrow_down"></i></div>
            <div class="js-catalog-filter__list catalog-filter__color-list">

            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open">Тип применения <i class="icon icon-arrow_down"></i></div>
            <div class="js-catalog-filter__list catalog-filter__color-list">

            </div>
        </div>
        <div class="catalog-filter__item">
            <div class="catalog-filter__item-title js-filter-item-open">Возраст <i class="icon icon-arrow_down"></i></div>
            <div class="js-catalog-filter__list catalog-filter__color-list">

            </div>
        </div>
    </div>

</div>`,
        });

Vue.component('bx-product-list',
        {
            props: {
                products: {
                    default: function(){
                        return []
                    },
                },

            },
            data() {
                return {};
            },

            model: {},

            methods: {
                click(event) {
                    this.$emit('page', event.currentTarget.dataset.page);
                },
            },
            template: `<div class="goods-wrap">
                           <div class="goods ">
                               <a v-for="product in products" 
                                  :href="product['DETAIL_PAGE_URL']"
                                  class="goods-item goods-item_25 goods-item_tablet-50 goods-item_mobile-100 js-goods-item">
                                   <div class="goods-item__img-wrap">
                                       <img :src="product['PREVIEW_PICTURE']"
                                            alt=""
                                            class="goods-item__img">
                                   </div>
                                   <div class="goods-item__title">{{product.NAME}}</div>
                                   <div class="goods-item__price-wrap">
                                       <div class="goods-item__price">{{product['DISCOUNT_PRICE']}} ₽</div>
                                       <div class="goods-item__price-old">{{product['PRICE'] }}₽</div>
                                   </div>
                                   <div v-if="product['IBLOCK_ELEMENTS_ELEMENT_CATALOG_SALELEADER_VALUE']== 2"
                                        class="goods-item__stickers">
                                       <div class="goods-item__sticker">
                                           <div class="sticker-hit">hit</div>
                                       </div>
                                   </div>
                                   <div class="goods-item__in-favorite"><i class="icon icon-heart"></i></div>
                               </a>
                           </div>
                       </div>`,
        });

Vue.component('bx-catalog-section',
        {
            props: {
                serialized_data: String,
            },
            data() {
                let data = JSON.parse(this.serialized_data);
                return {
                    products: data.products,
                    page: data.page,
                    page_size: data.page_size,
                    page_count: data.page_count,
                    sort: 'PRICE',
                    order: 'asc'
                };
            },
            watch: {
                page: function(oldPage, newPage) {
                    this.refreshProducts();
                },
                sort: function(oldSort, newSort) {
                    this.refreshProducts();
                },
            },
            model: {},

            methods: {
                toPage: function(page) {
                    this.page = page;
                },
                sorting: function(sort) {
                    this.sort = sort;
                },
                refreshProducts: function() {
                    var url = '/api/internal/component/dermavi:product.list/json?p=' + this.page + '&sortBy=' + this.sort
                            + '&order='+this.order;

                    var options = {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    };
                    fetch(url, options).
                            then(response => response.json()).
                            then(this.refreshProductsResponseHandler).
                            catch(error => console.log('error', error));
                },
                refreshProductsResponseHandler(result) {
                    this.page = result.page;
                    this.page_count = result.page_count;
                    this.products = result.products;
                },
            },
            template: `<div> 
                            <div class="catalog-wrap js-catalog-wrap">
                                <bx-filter ></bx-filter>
                                <div class="catalog-goods">
                                    <div class="catalog-goods__top">
                                        <div class="filter-btn__open js-filter-open">
                                            <i class="icon icon-filter"></i>
                                            <span>Показать фильтры </span>
                                            <span class="mobile">Фильтры</span>
                                        </div>
                                        <bx-sorter v-on:sort="sorting"></bx-sorter>
                                    </div>
                                    <bx-product-list :products="products"></bx-product-list>
                                </div>
                            </div>
                            <bx-pagination :count="page_count"
                                           :page="page"
                                           v-on:page="toPage">
                            </bx-pagination>
                      </div>`,

        });

BX.ready(function() {
    Vue.create({
        el: document.getElementById('catalog_section'),
        data: {
            state: 'personal',
        },


    });
});
