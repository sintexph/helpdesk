<template>
    <div>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-filter" aria-hidden="true"></i> Filters</h3>
            </div>

            <div class="box-body">
                <div class=" form-inline">
                    <div class="form-group">
                        <label class="control-label">Created From</label>
                        <date-picker class="input-sm" v-model="filters.from"></date-picker>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Created To</label>
                        <date-picker class="input-sm" v-model="filters.to"></date-picker>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Filter Support</label>
                    <select2 style="width:100%" v-model="filters.support" :options="supports">
                    </select2>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <rating-chart :support="filters.support" :date_from="filters.from" :date_to="filters.to"
                            style="width:420px;">
                        </rating-chart>
                    </div>
                    <div class="col-sm-6">
                        <category-chart :support="filters.support" :date_from="filters.from" :date_to="filters.to"
                            style="width:380px;">
                        </category-chart>
                    </div>

                </div>
            </div>
        </div>
        <div class="box box-solid" v-if="!filters.support">
            <div class="box-body">
                <user-efficiencies style="min-height:700px;" :date_from="filters.from" :date_to="filters.to"></user-efficiencies>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Efficiency Breakdown</h3>
            </div>
            <div class="box-body">
                <efficiency-breakdown :support="filters.support" :date_from="filters.from" :date_to="filters.to">
                </efficiency-breakdown>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Tickets</h3>
            </div>
            <div class="box-body">
                <ticket-list :support="filters.support" :date_from="filters.from" :date_to="filters.to"></ticket-list>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                filters: {
                    find: '',
                    to: '',
                    from: '',
                    support: '',
                },
                submitted: false,
                columns: [

                    {
                        label: 'Ticket#',
                        name: 'control_number',
                        data: 'control_number',
                    },

                    {
                        label: 'Sender',
                        name: 'sender_name',
                        data: 'sender_name',
                        className: 'fit',
                    },

                    {
                        label: 'Factory',
                        name: 'sender_factory',
                        data: 'sender_factory',
                    },
                    {
                        label: 'HIT CATERED',
                        name: 'ht_catered',
                        data: 'ht_catered',
                    },
                    {
                        label: 'HIT PROCESSING',
                        name: 'ht_processing',
                        data: 'ht_processing',
                    },
                    {
                        label: 'HIT SOLVED',
                        name: 'ht_solved',
                        data: 'ht_solved',
                    },

                    {
                        label: 'HIT CLOSED',
                        name: 'ht_closed',
                        data: 'ht_closed',
                    },

                ],
                supports: [],
            }
        },
        methods: {
            reload_list: function () {
                this.$refs.datatables.reload();
            },
            load_supports() {
                var vm = this;
                axios.post('/utility/supports').then(response => {

                    vm.supports.push({
                        id: '',
                        text: 'Select support'
                    });

                    response.data.forEach(element => {
                        vm.supports.push(element);
                    });;



                });
            }
        },
        mounted() {
            this.load_supports();
        }
    }

</script>
