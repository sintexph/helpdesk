<template>
    <modal name="create-setting" ref="modal">
        <template slot="header">Create Setting</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" v-model="name" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Value</label>
                <input type="text" v-model="value" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Remark</label>
                <input type="text" v-model="remark" class="form-control">
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Setting</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                name: '',
                value: '',
                remark: '',
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            create() {
                var vm = this;
                axios.put('/maintain/setting/save', {
                    name: vm.name,
                    value: vm.value,
                    remark: vm.remark,
                }).then(
                    response => {
                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        vm.value = '';
                        vm.remark = '';
                        vm.$emit('created');
                    }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
