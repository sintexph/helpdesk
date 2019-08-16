<template>
    <div class="progress-group">
        <span class="progress-text">Task Progress</span>
        <span class="progress-number"><b>{{ complete }}</b>/{{ total }}</span>
        <div class="progress progress-sm active">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100" :style="'width: '+percentage+'%'">
                <span class="sr-only">{{ percentage }}% Complete</span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['project_id'],
        data() {
            return {
                incomplete: 0,
                complete: 0,
                total: 0,
            }
        },
        computed: {
            percentage() {
                return (this.complete / this.total) * 100;
            }
        },
        methods: {
            load_progress() {
                var vm = this;
                axios.post('/tasks/progress', {
                    project_id: vm.project_id,
                }).then(response => {
                    var data = response.data;
                    vm.incomplete = data.incomplete;
                    vm.complete = data.complete;
                    vm.total = data.total;

                })
            }
        },
        mounted() {
            var vm = this;
            vm.load_progress();
            vm.$nextTick(function () {
                GlobalEvent.$on('task-updated', function () {
                    vm.load_progress();
                });
            });

        }
    }

</script>
