<template>
    <modal :prevent="true" name="create-task" :extended_width="true" ref="modal">
        <template slot="header">Create Task</template>
        <template slot="body">
            <form-task v-model="task"></form-task>

        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Task</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['project_id'],
        data() {
            return {
                task: new Task,
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },


            create() {
                var vm = this;


                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is saving your task....");

                    let form = new FormData();

                    if (vm.task.attachments_input !== null) {
                        for (var i = 0; i < vm.task.attachments_input.length; i++) {
                            let file = vm.task.attachments_input[i];
                            form.append('attachments_input[' + i + ']', file);
                        }
                    }
                    
                    form.append('project_id', vm.task.project_id);
                    form.append('name', vm.task.name);
                    form.append('description', vm.task.description);

                    form.append('assigned_to', vm.task.assigned_to);
                    form.append('start_date', vm.task.start_date);
                    form.append('due_date', vm.task.due_date);
                    form.append('label', vm.task.label);
                    form.append('priority', vm.task.priority);
                    form.append('state', vm.task.state);
                    form.append('remarks', vm.task.remarks);




                    form.append('_method', 'PUT');

                    axios.post('/tasks/save', form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {

                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';

                        GlobalEvent.$emit('task-reload');
                        GlobalEvent.$emit('task-updated');

                        vm.task = new Task;
                        vm.task.project_id = vm.project_id;

                        vm.submitted = false;
                        vm.hide_wait();

                    }).catch(function (error) {
                        vm.submitted = false;
                        vm.hide_wait();
                        vm.validation_errors = error.response.data.errors;
                        vm.alert_failed(error);
                    });
                }



            }
        },
        mounted() {
            this.$nextTick(function () {
                this.task.project_id = this.project_id;
            })
        }
    }

</script>
