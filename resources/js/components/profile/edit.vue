<template>
    <div class="modal fade" id="modal-edit-profile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Profile Settings</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="label-control">Name</label>
                        <input class="form-control  text-uppercase" v-model="account.name" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Id Number</label>
                        <input type="text" class="form-control" v-model="account.id_number" required>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Email</label>
                                <input type="email" class="form-control" v-model="account.email" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Contact</label>
                                <input type="text" class="form-control" v-model="account.contact" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Factory</label>
                                <select class="form-control" v-model="account.factory" required>
                                    <option value="">-- SELECT FACTORY --</option>
                                    <option v-for="(value,key) in factories" :key="'factory-'+key" :value="value.id">
                                        {{ value.text }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Position</label>
                                <input type="text" class="form-control" v-model="account.position" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Username</label>
                        <input type="text" class="form-control" v-model="account.username" required>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Password</label>
                                <input type="password" class="form-control" v-model="account.password" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="label-control">Password Confirmation</label>
                                <input type="password" class="form-control" v-model="account.password_confirmation"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="save">Save changes</button>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        data: function () {
            return {
                account: new User,
                factories: [],
                submitted: false,
            }
        },
        methods: {
            get_factories() {
                var par = this;
                axios.post('/utility/factories').then(response => {
                    response.data.forEach(element => {
                        par.factories.push({
                            id: element.name,
                            text: element.name
                        });

                    });

                    par.fetch();
                });
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request.....");
                    axios.patch('/profile/update', {

                        email: par.account.email,
                        name: par.account.name,
                        id_number: par.account.id_number,
                        factory: par.account.factory,
                        contact: par.account.contact,
                        position: par.account.position,
                        username: par.account.username,
                        password: par.account.password,
                        password_confirmation: par.account.password_confirmation,

                    }).then(function (response) {

                        par.submitted = false;
                        par.alert_success(response);
                        location.reload();

                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                        par.hide_wait();
                    });
                }


            },
            fetch: function () {
                var par = this;
                axios.post('/utility/get_auth_user').then(function (response) {
                    par.account.email = response.data.email;
                    par.account.name = response.data.name;
                    par.account.id_number = response.data.id_number;
                    par.account.factory = response.data.factory;
                    par.account.contact = response.data.contact;
                    par.account.position = response.data.position;
                    par.account.username = response.data.username;
                });
            }
        },
        mounted() {

            this.get_factories();

        }
    }

</script>
