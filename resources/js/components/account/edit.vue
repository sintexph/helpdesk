<template>
    <modal :prevent="true" :name="'edit-account'+_uid" ref="modal">
        <template slot="header">Edit Account</template>
        <template slot="body">
            <account-form v-model="account"></account-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning" @click.prevent="save">Save</button>
        </template>
    </modal>
</template>

<script>
    import AccountForm from './form.vue';

    export default {
        components: {
            'account-form': AccountForm
        },
        data: function () {
            return {
                id: '',
                account: new User,
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.account = new User;
                this.fetch();
                this.$refs.modal.show();

            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.patch('/accounts/update/' + par.id, {

                        email: par.account.email,
                        name: par.account.name,

                        id_number: par.account.id_number,
                        factory: par.account.factory,
                        role: par.account.role,
                        contact: par.account.contact,

                        position: par.account.position,
                        username: par.account.username,
                        password: par.account.password,
                        password_confirmation: par.account.password_confirmation,
                        active: par.account.active,

                    }).then(function (response) {
                        par.alert_success(response);
                        par.hide_wait();
                        par.submitted = false;
                        par.$emit('updated');
                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.hide_wait();
                        par.alert_failed(error);
                    });
                }


            },
            fetch: function () {
                var par = this;
                axios.post('/accounts/fetch/' + par.id).then(function (response) {
                    par.account.email = response.data.email;
                    par.account.name = response.data.name;

                    par.account.id_number = response.data.id_number;
                    par.account.factory = response.data.factory;
                    par.account.role = response.data.role;
                    par.account.contact = response.data.contact;

                    par.account.position = response.data.position;
                    par.account.username = response.data.username;
                    par.account.active = response.data.active;
                });
            }
        }
    }

</script>
