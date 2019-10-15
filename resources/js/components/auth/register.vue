<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Register Account</h3>
        </div>
        <div class="login-box-body">
            <form @submit.prevent="register" method="post">
                <div class="form-group has-feedback">
                    <label class="control-label">ID Number</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-uppercase" v-model="user.id_number" required>
                        <span class="input-group-addon"><i class="fa fa-hashtag" aria-hidden="true"></i></span>
                    </div>
                    <validation :errors="errors" field="id_number"></validation>
                </div>
                <div class="form-group has-feedback">
                    <label class="control-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" class="form-control" v-model="user.email" required>
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    </div>
                    <validation :errors="errors" field="email"></validation>
                </div>
                <div class="form-group has-feedback">
                    <label class="control-label">Password</label>

                    <div class="input-group">
                        <input type="password" class="form-control" v-model="user.password" required>
                        <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    </div>

                    <validation :errors="errors" field="password"></validation>
                </div>
                <div class="form-group has-feedback">
                    <label class="control-label">Confirm Password</label>

                    <div class="input-group">
                        <input type="password" class="form-control" v-model="user.password_confirmation " required>
                        <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    </div>
                </div>

                <p>Registration was made easy for you, just provide your <strong>company id number</strong> and email
                    and let us take care the rest.</p>

                <button class="btn btn-sm btn-success pull-right" type="submit">Register Account</button>
            </form>
            <div class="clearfix"></div>
            <modal :prevent="true" name="modal-continue" ref="modalContinue">
                <template slot="header">Provide More Details</template>
                <template slot="body">
                    <p><strong>We apologize</strong> but it seems we cannot find you from our databases. Instead, you
                        can
                        fill out the form below to continue the registration process.</p>
                    <div class="form-group">
                        <label class="control-label">Name</label>

                        <div class="input-group">
                            <input type="text" class="form-control text-uppercase" v-model="user.name" required>
                            <span class="input-group-addon"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                        </div>
                        <validation :errors="errors" field="name"></validation>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Factory</label>
                        <div class="input-group">
                            <select class="form-control text-uppercase" v-model="user.factory" required>
                                <option value="">-- SELECT FACTORY --</option>
                                <option v-for="(value,key) in factories" :key="key" :value="value.name">{{ value.name }}
                                </option>
                            </select>
                            <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>
                        </div>
                        <validation :errors="errors" field="factory"></validation>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Position</label>

                        <div class="input-group">
                            <input type="text" class="form-control text-uppercase" v-model="user.position" required>
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                        </div>
                        <validation :errors="errors" field="position"></validation>
                    </div>
                </template>
                <template slot="footer">
                    <button class="btn btn-sm btn-primary" @click.prevent="register">Register</button>
                </template>
            </modal>
            <a href="#" @click.prevent="$router.push('/')" class="text-center">Login to helpdesk</a>
        </div>
    </div>
</template>
<script>
    export default {
        props: {

            id_number: {
                default () {
                    return null;
                }
            },
            password: {
                default () {
                    return null;
                }
            },
        },
        data: function () {
            return {
                factories: [],
                submitted: false,
                user: new User,
                errors: [],
                continue_form: false,
            }
        },
        mounted: function () {
            this.load_factory();
            this.$nextTick(function () {
                if (this.id_number !== null)
                    this.user.id_number = this.id_number;

                if (this.password !== null) {
                    this.user.password = this.password;
                    this.user.password_confirmation = this.password;
                }


            });

        },
        methods: {
            load_factory() {
                let vm = this;
                axios.post('/utility/factories').then(response => {
                    vm.factories = response.data;
                });
            },
            populate_information(val) {
                this.user.name = val.full_name;
                this.user.position = val.position;
                this.user.factory = val.factory;
                this.user.id_number = val.id_number;
            },
            register() {
                var vm = this;
                if (vm.submitted == false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while we are processing your request....");
                    axios.post('/register', {
                        id_number: vm.user.id_number,
                        email: vm.user.email,
                        password: vm.user.password,
                        password_confirmation: vm.user.password_confirmation,
                        continue: vm.continue_form,

                        name: vm.user.name,
                        factory: vm.user.factory,
                        position: vm.user.position,

                    }).then(response => {
                        vm.hide_wait();
                        vm.show_wait_success(response.data.message);

                        setTimeout(function () {

                            window.location = "/";

                        }, 3000);


                    }).catch(error => {

                        vm.hide_wait();

                        if (error.response.data.continue !== undefined && error.response.data.continue !== null)
                            vm.continue_form = error.response.data.continue;

                        if (vm.continue_form === true) {
                            vm.$refs.modalContinue.show();
                        }
                        vm.submitted = false;

                        vm.errors = error.response.data.errors;


                    });

                }

            }
        }
    }

</script>
