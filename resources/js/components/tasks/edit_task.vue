<template>
    <modal :prevent="true" name="update-task" :extended_width="true" ref="modal">
        <template slot="header">Update Task</template>
        <template slot="body">
            <form-task v-model="task"></form-task>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Task</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                task: new Task,
                id: null,
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.task = new Task;
                this.info();
                this.$refs.modal.show();
            },
            update() {
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
                    form.append('to_be_deleted_attachments', vm.task.to_be_deleted_attachments);


                    form.append('_method', 'PATCH');

                    axios.post('/tasks/update/' + vm.id, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {


                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        GlobalEvent.$emit('task-updated');
                        GlobalEvent.$emit('task-reload');



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



            },
            info() {
                var vm = this;
                vm.show_wait("Please wait while the system is retrieving the information....");
                axios.post('/tasks/info/' + vm.id).then(response => {
                    var data = response.data;
                    vm.task.name = data.name;
                    vm.task.description = data.description;
                    vm.task.assigned_to = data.assigned_to;
                    if (data.start_date !== null)
                        vm.task.start_date = data.start_date;
                    if (data.due_date !== null)
                        vm.task.due_date = data.due_date;
                    vm.task.label = data.label;
                    vm.task.priority = data.priority;
                    vm.task.state = data.state;
                    vm.task.remarks = data.remarks;
                    vm.task.project_id = data.project_id;
                    vm.task.attachments = data.attachments;
                    vm.hide_wait();

                }).catch(error => {
                    vm.hide_wait();
                });
            }


        },

    }

</script>
