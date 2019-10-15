<template>
    <vue-cal class="vuecal--green-theme" :todayButton="true" style="height:100%;" :time="false" :events="events">
    </vue-cal>
</template>
<script>
    import VueCal from 'vue-cal'
    import 'vue-cal/dist/vuecal.css'

    export default {
        props: {
            mount_load: {
                type: Boolean,
                default () {
                    return false;
                }
            }
        },
        components: {
            VueCal
        },
        methods: {
            load_list() {
                var vm = this;
                axios.post('/utility/calendar/tasks').then(response => {
                    response.data.forEach(element => { 
                        vm.events.push({
                            start: element.start_date,
                            end: element.due_date ? element.due_date : element.start_date,
                            title: element.name,
                            content: element.description + `<br><span class="status pending">` +
                                element.state_text + `</span>`,
                            class: element.state_text.toLowerCase()
                        });
                    });
                });
            }
        },
        data() {
            return {
                events: []
            }
        },
        mounted() {
            if (this.mount_load === true)
                this.load_list();
        }
    }

</script>
