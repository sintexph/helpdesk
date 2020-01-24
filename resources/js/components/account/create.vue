<template>
    <modal :prevent="true" :name="'create-account'+_uid" ref="modal">
        <template slot="header">Create Account</template>
        <template slot="body">
            <account-form v-model="account"></account-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="save">Save</button>
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
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.put('/accounts/save', {

                        email: par.account.email,
                        name: par.account.name,
                        factory: par.account.factory,
                        id_number: par.account.id_number,
                        position: par.account.position,
                        username: par.account.username,
                        contact: par.account.contact,
                        password: par.account.password,
                        password_confirmation: par.account.password_confirmation,
                        active: par.account.active,
                        role: par.account.role,

                        shift_start: par.account.shift_start,
                        shift_end: par.account.shift_end,
                        break_time: par.account.break_time


                    }).then(function (response) {
                        par.alert_success(response);
                        par.hide_wait();
                        par.submitted = false;
                        par.$emit('created');
                        par.account = new User;


                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.hide_wait();
                        par.alert_failed(error);
                    });
                }


            }
        }
    }

</script>
