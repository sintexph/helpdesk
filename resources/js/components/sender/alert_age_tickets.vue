<template>
    <modal :prevent="true" name="age-tickets" ref="modal" :extended_width="true">
        <template slot="header">Un closed tickets</template>
        <template slot="body">
            <p class="alert-custom alert-custom-danger"><strong>Reminder!</strong> You have completed tickets that needs
                to closed. Tickets will
                be automatically closed after 30 days if you are unable to do so.</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Title</th>
                             <th>Status</th>
                            <th>Aged</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value,key) in tickets" :key="key">
                            <td class=" fit">
                                <a target="_blank" :href="'user/view/'+value.id">{{ value.control_number }}</a>
                            </td>
                            <td v-text="value.title"></td>
                            <td class=" fit"> 
                                <div :class="'status ' + value.state_text.toLowerCase()" >{{ value.state_text}}</div>

                            </td>
                            <td class=" fit" v-text="value.time_ago"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                tickets: []
            }
        },
        methods: {
            load_aged_tickets() {
                var vm = this;
                axios.post('/utility/unclosed_tickets').then(response => {

                    vm.tickets = response.data;

                    if (vm.tickets.length !== 0)
                        vm.$refs.modal.show();
                });
            },
        },
        mounted() {
            this.$nextTick(function () {
                this.load_aged_tickets();
            });
        }
    }

</script>
