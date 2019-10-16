<template>
    <div class="box box-solid">
    
        <div class="box-header">
                <h3 class="box-title">Login Account</h3>
            </div>
            <div class="login-box-body">
                <p>Sign in to start your session</p>
                <form action="/login" method="post" @submit.prevent="login">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" v-model="username" placeholder="Username">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <validation :errors="errors" field="username"></validation>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" v-model="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <validation :errors="errors" field="password"></validation>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <label class="control-label">
                                <input class="form-check-input" type="checkbox" v-model="remember">
                                Remember Me
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Sign In</button>
                        </div>
                    </div>
                    <a href="#" @click.prevent="$router.push('/reset')">I forgot my password</a><br>
                    <a href="#" @click.prevent="$router.push('/register')" class="text-center">Register a new membership</a>
                </form>
            </div>

    </div>
</template>
<script>
    export default {
        
        data() {
            return {
               
                username: '',
                password: '',
                remember: '',
                errors: [],
                submitted: false,
            }
        },
        methods: {
            login() {
                var vm = this;

                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Logging in please wait....");
                    axios.post('/login', {
                        username: vm.username,
                        password: vm.password,
                    }).then(function (response) {
                        setTimeout(function () {
                            window.location = response.data.redirect;
                        }, 3000);
                    }).catch(error => {
                        vm.hide_wait();
                        vm.submitted = false;
                        vm.errors = error.response.data.errors;
                    });
                }


            }
        }
    }

</script>
