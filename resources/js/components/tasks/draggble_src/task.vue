<template>
    <div class="box box-solid" style="cursor:pointer;">
        <div class="box-header">
            <h3 @click.prevent="$emit('view')" class="box-title">
                {{task.name}}
                <br>
                <small v-if="task.project!==null && task.project!==undefined" v-text="task.project.name"></small>
                <small v-else class="text-gray">--- none ---</small>
            </h3>
        </div>
        <div class="box-body task-desc-ell">
            <div :title="task.description" v-html="task.description_html"></div>
            <small class="text-yellow"><i class="fa fa-calendar-minus-o" aria-hidden="true"></i> {{ task.start_date }}</small>
            <span>&nbsp;&nbsp;</span>
            <small class="text-green"><i class="fa fa-calendar" aria-hidden="true"></i> {{ task.due_date }}</small>
        </div>
        <div class="box-footer">
            <i v-if="task.priority===1" class="fa fa-flag priority-1" title="Low"></i>
            <i v-else-if="task.priority===2" class="fa fa-flag priority-2" title="Normal"></i>
            <i v-else-if="task.priority===3" class="fa fa-flag priority-3" title="High"></i>
            &nbsp;{{ task.label }}
            <div class="pull-right">
                <a class="text-blue" title="View" href="#" @click.prevent="$emit('view')"><i class="fa fa-eye"
                        aria-hidden="true"></i></a>
                <span>&nbsp;&nbsp;</span>

                <a class="text-yellow" title="Edit" href="#" @click.prevent="$emit('edit')"><i class="fa fa-pencil"
                        aria-hidden="true"></i></a>
                <span>&nbsp;&nbsp;</span>
                <a class="text-red" title="Delete" @click.prevent="delete_task" href="#"><i class="fa fa-trash"
                        aria-hidden="true"></i></a>
                <span>&nbsp;&nbsp;</span>
                <a class="text-red" title="Cancel" @click.prevent="cancel_task" href="#"><i class="fa fa-ban"
                        aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                required: true,
                default () {
                    return new Task;
                }
            }
        },
        data() {
            return {
                task: new Task,
                submitted: false,
            }
        },
        watch: {
            task: {
                deep: true,
                handler(value) {
                    this.$emit('input', value);
                }
            },
            value: {
                deep: true,
                handler(value) {
                    this.task = value;
                }
            }
        },
        methods: {
            delete_task() {
                var vm = this;
                if (vm.submitted === false) {
                    if (confirm("Are you sure you want to delete the task?")) {
                        vm.submitted = true;
                        axios.delete('/tasks/delete/' + vm.task.id).then(response => {
                            vm.submitted = false;
                            GlobalEvent.$emit('task-reload');
                            GlobalEvent.$emit('task-updated');
                        }).catch(error => {
                            vm.submitted = false;
                            vm.alert_failed(error);
                        });
                    }
                }
            },
            cancel_task() {
                var vm = this;
                if (vm.submitted === false) {
                    if (confirm("Are you sure you want to cancel the task?")) {
                        vm.submitted = true;
                        axios.patch('/tasks/cancel/' + vm.task.id).then(response => {
                            vm.submitted = false;
                            GlobalEvent.$emit('task-reload');
                            GlobalEvent.$emit('task-updated');
                        }).catch(error => {
                            vm.submitted = false;
                            vm.alert_failed(error);
                        });
                    }
                }
            }
        },
        mounted() {
            this.$nextTick(function () {
                if (this.value !== null && this.value !== undefined)
                    this.task = this.value;
            });
        }
    }

</script>

<style>
    .task-desc-ell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>
