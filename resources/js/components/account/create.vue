<template>
    <modal :name="'create-account'+_uid" ref="modal">
        <template slot="header">Create Account</template>
        <template slot="body">
            <account-form v-model="account"></account-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>
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
                account: new User,
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.put('/accounts/save', {

                        email: par.account.email,
                        name: par.account.name,
                        factory: par.account.username,
                        id_number: par.account.username,
                        position: par.account.position,
                        username: par.account.username,
                        password: par.account.password,
                        password_confirmation: par.account.password_confirmation,
                        active: par.account.active,
                        role: par.account.role


                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');


                        par.account.email = '';
                        par.account.name = '';
                        par.account.position = '';
                        par.account.username = '';
                        par.account.password = '';
                        par.account.password_confirmation = '';
                        par.account.perm_administrator = false;

                        par.account.perm_approver = false;
                        par.account.perm_reviewer = false;


                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }


            }
        }
    }

</script>
