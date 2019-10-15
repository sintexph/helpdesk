<template>

    <div class="box box-solid">

        <div class="box-header">
            <h3 class="box-title">Reset Password</h3>
        </div>
        <div class="login-box-body">
            <form method="POST" action="/password/email" @submit.prevent="send_reset">
                <div class="form-group has-feedback">
                    <label class="control-label">Email Address</label>
                    <div class="input-group">
                        <input id="email" type="email" class="form-control" v-model="email" required>
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                    </div>
                    <validation :errors="errors" field="email"></validation>
                </div>
                <button type="submit" class="pull-right btn btn-sm btn-primary">Send Password Reset Link</button>
            </form>
            <a href="#" @click.prevent="$router.push('/')" class="text-center">Login to helpdesk</a>
        </div>

    </div>


</template>
<script>
    export default {
        data() {
            return {
                register: false,
                email: '',
                errors: [],
                submitted: false,
            }
        },
        methods: {
            send_reset() {
                var vm = this;

                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is processing your request....");
                    axios.post('/password/email', {
                        email: vm.email,
                    }).then(function (response) {
                        vm.hide_wait();
                        vm.show_wait_success(response.data.message);

                        setTimeout(function () {
                            location.replace("/login");
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
