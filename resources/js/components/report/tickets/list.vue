<template>
    <div>
        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Ticket</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="reload_list">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="reload_list" class="btn btn-default btn-flat">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Created From</label>
                    <date-mask class="input-sm" v-model="filters.to"></date-mask>
                </div>
                <div class="form-group">
                    <label class="control-label">Created To</label>
                    <date-mask class="input-sm" v-model="filters.from"></date-mask>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <rating-chart></rating-chart>
                    </div>
                    <div class="col-sm-6">
                        <category-chart></category-chart>
                    </div>
                    <div class="col-sm-12">
                        <target-chart></target-chart>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Ticket List</h3>

                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" @click.prevent="$refs.createAccount.show">Create Account</a>
                            </li>

                        </ul>
                    </div>

                </div>

            </div>
            <div class="box-body">
                <datatable :parameters="filters" :buttons="false" ref="datatables" :columns="columns"
                    url="/report/list">
                </datatable>
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

                ]
            }
        },
        methods: {
            reload_list: function () {
                this.$refs.datatables.reload();
            },
        },
    }

</script>
