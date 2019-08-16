<template>
    <modal :prevent="true" name="create-canned-solution" ref="modal">
        <template slot="header">Create Canned Solution</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Name</label>
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
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Canned Solution</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                name: '',
                content: '',
                type: '',
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            create() {
                var vm = this;
                axios.put('/maintain/canned-solution/save', {
                    name: vm.name,
                    content: vm.content,
                    type: vm.type,
                }).then(
                    response => {
                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        vm.content = '';
                        vm.type = '';
                        vm.$emit('created');
                    }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
