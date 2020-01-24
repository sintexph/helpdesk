<template>
    <div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-green">
                    <td class="text-uppercase">Hit Catered</td>
                    <td>
                        <strong v-text="hit_catered"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-up"></i> {{ hit_catered_percentage }} %
                    </td>
                </tr>
                <tr class="text-red">
                    <td class="text-uppercase">Fail Catered</td>
                    <td>
                        <strong v-text="fail_catered"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-down"></i> {{ fail_catered_percentage }} %
                    </td>
                </tr>
                <tr class="text-green">
                    <td class="text-uppercase">Hit Processing</td>
                    <td>
                        <strong v-text="hit_processing"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-up"></i> {{ hit_processing_percentage }} %
                    </td>
                </tr>
                <tr class="text-red">
                    <td class="text-uppercase">Fail Processing</td>
                    <td>
                        <strong v-text="fail_processing"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-down"></i> {{ fail_processing_percentage }} %
                    </td>
                </tr>
                <tr class="text-green">
                    <td class="text-uppercase">Hit Solved</td>
                    <td>
                        <strong v-text="hit_solved"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-up"></i> {{ hit_solved_percentage }} %
                    </td>
                </tr>
                <tr class="text-red">
                    <td class="text-uppercase">Fail Solved</td>
                    <td>
                        <strong v-text="fail_solved"></strong>
                    </td>
                    <td>
                        <i class="fa fa-caret-down"></i> {{ fail_solved_percentage }} %
                    </td>
                </tr>

                <tr class="text-red">
                    <td class="text-uppercase">Total Tickets</td>
                    <td>
                        <strong v-text="total_tickets"></strong>
                    </td>
                    <td>

                    </td>
                </tr>

                <tr>
                    <td>
                        <h4>Effeciency Result</h4>
                    </td>
                    <td></td>
                    <td>
                        <h4>
                            <span class="label label-success">{{ efficiency }} %</span>
                        </h4>
                    </td>
                </tr>
            </tbody>
        </table>
         
    </div>
</template>
<script>
    export default {
        props: {
            support:{
                required:true,
            },
            date_from: {
                required: true,
            },
            date_to: {
                required: true,
            }
        },
        watch: {
            date_from: function () {
                this.load_data();
            },
            date_to: function () {
                this.load_data();
            },
           support: function () {
                this.load_data();
            },
        },
        data() {
            return {
                fail_catered: 0,
                fail_closed: 0,
                fail_processing: 0,
                fail_solved: 0,
                hit_catered: 0,
                hit_closed: 0,
                hit_processing: 0,
                hit_solved: 0,
                total_tickets: 0, 
            }
        },
        computed: {
            hit_catered_percentage() {
                return Math.round((this.hit_catered / this.total_tickets) * 100);
            },
            hit_processing_percentage() {
                return Math.round((this.hit_processing / this.total_tickets) * 100);
            },
            hit_solved_percentage() {
                return Math.round((this.hit_solved / this.total_tickets) * 100);
            },
            fail_catered_percentage() {
                return Math.round((this.fail_catered / this.total_tickets) * 100);
            },
            fail_processing_percentage() {
                return Math.round((this.fail_processing / this.total_tickets) * 100);
            },
            fail_solved_percentage() {
                return Math.round((this.fail_solved / this.total_tickets) * 100);
            },
            efficiency() {
                return Math.round((this.hit_catered_percentage + this.hit_processing_percentage + this
                    .hit_solved_percentage) / 3);
            }
        },
        methods: {
            load_data() {
                let vm = this;
                axios.post('/report/tickets/efficiency-breakdown', {
                    from: vm.date_from,
                    to: vm.date_to,
                    support: vm.support,
                }).then(response => {
                    var data = response.data;
                    if (data !== null) {
                        vm.fail_catered = data.fail_catered;
                        vm.fail_closed = data.fail_closed;
                        vm.fail_processing = data.fail_processing;
                        vm.fail_solved = data.fail_solved;
                        vm.hit_catered = data.hit_catered;
                        vm.hit_closed = data.hit_closed;
                        vm.hit_processing = data.hit_processing;
                        vm.hit_solved = data.hit_solved;
                        vm.total_tickets = data.total_tickets;
                    } else {
                        vm.fail_catered = 0;
                        vm.fail_closed = 0;
                        vm.fail_processing = 0;
                        vm.fail_solved = 0;
                        vm.hit_catered = 0;
                        vm.hit_closed = 0;
                        vm.hit_processing = 0;
                        vm.hit_solved = 0;
                        vm.total_tickets = 0;
                    }

                });
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.load_data();
            });
        }
    }

</script>
