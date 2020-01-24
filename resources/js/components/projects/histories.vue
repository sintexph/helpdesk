<template>
    <div class="timeline-container">
        <ul class="timeline">

            <template v-for="(history,key) in histories">
                <li v-if="key===0" class="time-label" :key="key+'-label'">
                    <span class="bg-red" v-text="history.created_date"></span>
                </li>
                <li v-else-if="histories[key-1].created_date!==history.created_date" class="time-label"
                    :key="key+'-label'">
                    <span class="bg-red" v-text="history.created_date"></span>
                </li>

                <li :key="key+'-item'">
                    <i :class="history.icon"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{ history.created_time }}</span>
                        <h3 class="timeline-header">{{ history.type_text }} </h3>
                        <div class="timeline-body" v-text="history.description"></div>


                        <div class="timeline-footer">
                            <small v-text="history.creator"></small>
                        </div>
                    </div>
                </li>
            </template>

        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            project_id: {
                type: [String, Number],
                required: true,
            }
        },
        data() {
            return {
                histories: [],
            }
        },
        methods: {
            list() {
                let vm = this;
                axios.post('/utility/project-histories/' + vm.project_id).then(response => {
                    vm.histories = response.data;
                });
            }
        },
        mounted() {
            this.list();
        }
    }

</script>

<style>
    .timeline>.time-label>span {
        font-weight: 600;
        font-size: 12px;
    }

    .timeline>li>.timeline-item>.timeline-header {
        font-size: 14px;
        color: #1c688b;
        font-weight: 600;
    }

    .timeline>li>.timeline-item>.timeline-footer {
        color: #727272;
    }

    .timeline-container {
        max-height: 720px;
        overflow-y: auto;
    }

</style>
