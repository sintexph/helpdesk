<template>
    <modal :prevent="true" name="create-project" :extended_width="true" ref="modal">
        <template slot="header">Create Project</template>
        <template slot="body">
            <form-project v-model="project"></form-project>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Project</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    import formProject from "./form";
    export default {
        components: {
            'form-project': formProject,
        },
        data() {
            return {
                project: new Project,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },

            create() {
                var vm = this;
                axios.put('/projects/save', {
                    name: vm.project.name,
                    description: vm.project.description,
                    requested_by: vm.project.requested_by,
                    start_date: vm.project.start_date,
                    due_date: vm.project.due_date,
                    followers: vm.project.followers,
                    tags: vm.project.tags,
                    state: vm.project.state,
                }).then(
                    response => {
                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        vm.$emit('created');
                    }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
