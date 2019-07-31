<template>
    <modal name="edit-setting" ref="modal">
        <template slot="header">Create Setting</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Setting</label>
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
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Setting</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                id: null,
                name: '',
                value: '',
                 remark:'',
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.load_setting();
                this.$refs.modal.show();
            },
            load_setting() {
                var vm = this;
                axios.post('/maintain/setting/info/' + vm.id).then(response => {
                    vm.name = response.data.name;
                    vm.value = response.data.value;
                    vm.remark = response.data.remark;
                });
            },
            update() {
                var vm = this;
                axios.patch('/maintain/setting/update/' + vm.id, {
                    name: vm.name,
                    value: vm.value,
                     remark: vm.remark,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.name = '';
                    vm.value = '';
                      vm.remark = '';
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
