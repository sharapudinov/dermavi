$(document).ready(function() {
    var signin = new Vue({
        el: '#signin',
        data: {
            password_input_type: 'password',
        },
        methods: {
            signin: function() {
                alert(this.message);
            },
            show_password: function() {
                this.password_input_type = 'text';
            },
            hide_password: function() {
                this.password_input_type = 'password';
            },
            submit: function() {
                alert(1)
                e.preventDefault()
                $.ajax({
                    url: '/api/internal/user/auth/signin/',
                    method: 'get',
                    data: $(this).serializeArray(),
                    success: function() {
                        setTimeout(function() {
                            window.location.href = window.location.origin + window.location.pathname + ('' !== t ? t + '&' : '?') + 'auth=true';
                        }, 3e3);
                    }
                    ,
                    error: function() {
                    },
                });
            },
        },
    });
});


