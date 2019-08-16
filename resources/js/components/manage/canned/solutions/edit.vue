<template>
    <modal :prevent="true" name="edit-canned-solution" ref="modal">
        <template slot="header">Update Canned Solution</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Canned Solution</label>
                <input type="text" v-model="name" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Content</label>
                <textarea v-model="content" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label class="control-label">Type</label>
                <select class="form-control" v-model="type">
                    <option value="">-- SELECT --</option>
                    <option :value="SOLUTION_TYPE.TEXT">TEXT</option>
                    <option :value="SOLUTION_TYPE.URL">URL</option>
                </select>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Canned Solution</button>
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
                content: '',
                type: '',
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
                axios.post('/maintain/canned-solution/info/' + vm.id).then(response => {
                    vm.name = response.data.name;
                    vm.content = response.data.content;
                    vm.type = response.data.type;
                });
            },
            update() {
                var vm = this;
                axios.patch('/maintain/canned-solution/update/' + vm.id, {
                    name: vm.name,
                    content: vm.content,
                    type: vm.type,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.name = '';
                    vm.content = '';
                    vm.type = '';
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
