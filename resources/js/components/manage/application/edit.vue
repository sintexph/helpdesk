<template>
    <modal :prevent="true" name="edit-application" ref="modal" :extended_width="true">
        <template slot="header">Update Application</template>
        <template slot="body">
            <application-form v-model="application"></application-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Application</button>
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
                id: null,
                application: new Application
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.load_application();
                this.$refs.modal.show();
            },
            load_application() {
                var vm = this;
                axios.post('/maintain/application/info/' + vm.id).then(response => {
                    vm.application = vm.convert_application(response.data);
                });
            },
            update() {
                var vm = this;
            
                axios.patch('/maintain/application/update/' + vm.id, {
                    name: vm.application.name,
                    applications: vm.application.applications,
                    fields: vm.application.fields,
                    field_sender_id_number:vm.application.field_sender_id_number,
                    field_sender_name: vm.application.field_sender_name,
                    field_sender_email: vm.application.field_sender_email,
                    field_sender_factory: vm.application.field_sender_factory,
                    field_sender_phone: vm.application.field_sender_phone,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            },
            field_added(field) {
                this.application.fields.push(field);
            }
        }
    }

</script>
