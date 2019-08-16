<template>
    <div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label class="control-label">Search Ticket </label>
                        <div class="input-group input-group-sm">
                            <input type="text" placeholder="Search" class="form-control" @keydown.enter="filter_list"
                                v-model="filters.find">
                            <span class="input-group-btn">
                                <button type="button" class="btn" @click.prevent="filter_list">
                                    <i aria-hidden="true" class="fa fa-search"></i>
                                    <span>Search</span>
                                </button>
                            </span>
                        </div>
                    </div>
 
                    
                    <div class="form-group">
                        <label class="control-label">State</label>
                        <select class="form-control input-sm" v-model="filters.state" @change="filter_list">
                            <option value="">-- Filter State --</option>
                            <option :value="State.PENDING">Pending</option>
                            <option :value="State.CATERED">Catered</option>
                            <option :value="State.PROCESSING">Processing</option>
                            <option :value="State.SOLVED">Solved</option>
                            <option :value="State.CLOSED">Closed</option>
                            <option :value="State.HOLD">Hold</option>
                            <option :value="State.CANCELLED">Cancelled</option>
                            <option :value="-1">Show All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Ticket List</h3>
            </div>
            <div class="box-body">
                <div class="btn-group btn-group-xs">
                    <button type="button" title="Create Ticket" class="btn btn-default"
                        @click.prevent="$refs.createTicket.show"><i class="fa fa-ticket"
                            aria-hidden="true"></i></button>
                    <button type="button" title="Reload list" @click.prevent="filter_list" class="btn btn-default"><i
                            class="fa fa-refresh" aria-hidden="true"></i></button>
                </div>
                <datatable :parameters="filters" @reloaded="load_images" ref="datatables" :createdRow="createdRow"
                    :buttons="false" :columns="columns" url="/tickets/list"></datatable>
                <create-ticket ref="createTicket" @submitted="$refs.datatables.reload()"></create-ticket>
            </div>
        </div>
    </div>
</template>
<script>
    import createTicket from './create';

    export default {
        computed: {
            gen_rat() {
                return this.generate_rating(1);
            },
           
        },
        components: {
            'create-ticket': createTicket,
        },
        props: {
            systems: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
        },
        data: function () {
            return {
                filters: {
                    find: '',
                    state: '',
                },
                find: '',
                columns: [{
                        label: '#',
                        name: 'control_number',
                        data: 'control_number',
                        className: "fit",
                        sortable: false,
                        render(data, meta, row) {
                            var urgency = '';
                            switch (row.urgency) {
                                case 1:
                                    urgency = '<i class="fa fa-flag priority-1" title="Low"></i>';
                                    break;
                                case 2:
                                    urgency = '<i class="fa fa-flag priority-2"  title="Normal"></i>';
                                    break;
                                case 3:
                                    urgency = '<i class="fa fa-flag priority-3"  title="High"></i>';
                                    break;
                            }
                            return urgency + `&nbsp;&nbsp;` + data;
                        }
                    },
                    {
                        label: 'Sender',
                        name: 'sender_name',
                        data: 'sender_name',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            var display = `<strong>` + data + `</strong><br>`;

                            display +=
                                `<div class="detail"><a href="#">` +
                                row
                                .sender_email + `</a></div>`;


                            return display;
                        }
                    },
                    {
                        label: 'Title',
                        name: 'title',
                        data: 'title',
                        className: "fit",
                        sortable: false,
                        render: this.render_title,
                    },
                    {
                        label: 'Catered By',
                        name: 'catered_by',
                        data: 'catered_by',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            if (data !== null) {
                                return `
                                    <div class="caterer">
                        
                                            <div class="sintex-circle-image">
                                                    <img src="` + row.caterer.photo + `" alt="User Image">
                                                </div>

                                    <span class="caterer-name">` +
                                    row.caterer.name +
                                    `</span>
                                    <span class="caterer-position">` +
                                    row.caterer.email +
                                    `</span>
                                    </div>
                            
                            `;
                            } else
                                return '';
                        }
                    },
                    {
                        label: 'Status',
                        name: 'control_number',
                        data: 'control_number',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            var state = row.state_text;
                            return `<div class="status ` +
                                state.toLowerCase() + `">` + state + `</div>`;
                        }
                    },
                    {
                        label: 'View',
                        name: 'id',
                        data: 'id',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            return `<center><a href="/tickets/view/` + data +
                                `" target="_blank">View Ticket</a></center>`;
                        }
                    },
                ],
                system_data: [],
            }
        },
        methods: {
            createdRow: function (row, data, index) {
                if (data.catered_by === null)
                    $(row).addClass("row-un-catered");
            },
            filter_list: function () {

                this.$refs.datatables.reload();
            },
            load_images() {
                execPicCircle();
            },
            render_title(data, meta, row) {
                var display = `<a href="/tickets/view/` + row.id + `" target="_blank"><strong>` + data +
                    `</strong></a><br>`;

                if (row.state === State.PENDING)
                    display += row.sender_factory;
                else
                    display += this.generate_rating(row.user_rating);

                display += ` </div>`;
                return display;
            }
        },
        mounted() {
            let par = this;

            par.systems.forEach(function (system) {
                par.system_data.push({
                    id: system.code,
                    text: system.name
                });
            });

            $(document).idle({
                onIdle: function () {
                    par.$refs.datatables.reload();
                },
                idle: 120000
            });




        }

    }

</script>

<style>
    .table tr td .detail,
    .table tr td .detail a,
    .table tr td .detail p,
    .table tr td .detail span {
        color: #8d8d8d !important;
    }



    .table tr td .detail span {
        display: inline-block;
        padding-left: 10px;

    }

    .table tr td .detail i {
        display: inline-block;
        padding-right: 10px;
    }

    .row-un-catered {
        background-color: #fff9e4;
    }

</style>
