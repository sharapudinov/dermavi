<div class="login" id="signin">
    <div class="login__left">
        <div class="login__img-wrap"><img src="/img/login.jpg" alt="" class="login__img"></div>
        <div class="login__logo-img-wrap"><img src="/img/logo_footer.svg" alt="" class="login__logo-img"></div>
    </div>
    <div class="login__right">
        <div class="login-logo__mobile">
            <a href="#" class="login-btn__back mobile-none"><i class="icon icon-arrow_left"></i></a>
            <div class="login-logo__mobile-img-wrap">
                <img src="img/Logo-log_mob.svg" alt="" class="login-logo__mobile-img"></div>
        </div>
        <div class="login__top">
            <a href="#" class="login__top-item active">Вход</a>
            <div class="login__top-logo-wrap"><img src="img/logo_footer.svg" alt="" class="login__logo-img"></div>
            <a href="#" class="login__top-item">Регистрация</a>
        </div>
        <div class="login__content">
            <div class="login__title mobile-none">Вход в аккаунт</div>
            <form v-on:submit.prevent="submit">
                <div class="login__form-group">
                    <div class="login__form">
                        <label for="email" class="label label-login">Адрес эл. почты</label>
                        <input id="email" type="text"
                               class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                               placeholder="Email" value="ibragimov2233@gmail.com">
                    </div>
                    <div class="login__form">
                        <label for="password" class="label label-login">Пароль</label>
                        <input id="password" :type="password_input_type"
                               class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                               placeholder="Email" value="ibragimov2233@gmail.com">
                        <div class="form__btn form__btn-password">
                            <div v-on:mousedown="show_password"
                                 v-on:mouseup="hide_password"
                                 class="form__icon">
                                <i class="icon icon-eye_off"></i></div>
                        </div>
                    </div>
                </div>
                <div class="login__btn">
                    <button class="btn btn-gold_fill2 btn-normal btn_full-width btn_center">Войти
                    </button>
                </div>
            </form>
            <div class="login__links">
                <a href="#" class="login__forget"><u>Забыли пароль?</u></a>
                <a href="#" class="login__new-user">Нет аккаунта? <u>Зарегистрируйтесь</u></a>
            </div>
        </div>
    </div>
</div>
