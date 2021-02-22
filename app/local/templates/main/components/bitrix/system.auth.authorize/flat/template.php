<div id="auth">
    <div class="login">
        <div class="login__left">
            <div class="login__img-wrap">
                <img src="/img/login.jpg" alt="" class="login__img">
            </div>
            <div class="login__logo-img-wrap">
                <img src="/img/logo_footer.svg" alt="" class="login__logo-img">
            </div>
        </div>
        <bx-sign-in v-show="step=='signin'"
                    v-on:signup="signup"
                    v-on:forgot_password="forgot_password"></bx-sign-in>
        <bx-sign-up v-show="step=='signup'"
                    v-on:signin="signin"></bx-sign-up>
        <bx-forgot-password v-show="step=='forgot_password'"
                            v-on:signin="signin"
                            v-on:signup="signup"></bx-forgot-password>
    </div>
</div>

