<template>
    <modal :prevent="true" name="edit-factory" ref="modal" >
        <template slot="header">Update Factory</template>
        <template slot="body">
      <div class="form-group">
                <label class="control-label">Factory</label>
                <input type="text" v-model="name" class="form-control">
            </div>

        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Factory</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                id: null,
                name: ''
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.load_factory();
                this.$refs.modal.show();
            },
            load_factory() {
                var vm = this;
                axios.post('/maintain/factory/info/' + vm.id).then(response => {
                    vm.name = response.data.name;
                });
            },
            update() {
                var vm = this;
                axios.patch('/maintain/factory/update/' + vm.id, {
                    name: vm.name,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.name='';
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
