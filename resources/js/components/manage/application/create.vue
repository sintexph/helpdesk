<template>
    <modal name="create-application" ref="modal" :extended_width="true">
        <template slot="header">Create Application</template>
        <template slot="body">
            <application-form v-model="application"></application-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Application</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    import appForm from "./form";
    export default {
        components: {
            'application-form': appForm,
        },
        data() {
            return {
                application: new Application
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            create() {
                var vm = this;
             
                axios.put('/maintain/application/save', {
                    name: vm.application.name,
                    applications: vm.application.applications,
                    fields: vm.application.fields,
                    field_sender_id_number:vm.application.field_sender_id_number,
                    field_sender_name: vm.application.field_sender_name,
                    field_sender_email: vm.application.field_sender_email,
                    field_sender_factory: vm.application.field_sender_factory,
                    field_sender_phone: vm.application.field_sender_phone,
                }).then(
                    response => {
                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.application=new Application;
                        vm.$emit('created');
                    }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
