<template>
    <modal :prevent="true" name="edit-category" ref="modal">
        <template slot="header">Update Category</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Category</label>
                <input type="text" v-model="name" class="form-control">
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Category</button>
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
                this.load_category();
                this.$refs.modal.show();
            },
            load_category() {
                var vm = this;
                axios.post('/maintain/category/info/' + vm.id).then(response => {
                    vm.name = response.data.name;
                });
            },
            update() {
                var vm = this;
                axios.patch('/maintain/category/update/' + vm.id, {
                    name: vm.name,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.name = '';
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
