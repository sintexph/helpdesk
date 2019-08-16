<template>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Inbox</h3>
            <div class="box-tools pull-right">
                <div class="has-feedback">
                    <input type="text" @keydown.enter="filter_list" v-model="filters.find" class="form-control input-sm"
                        placeholder="Search Ticket">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="btn-group btn-group-xs">
                <button type="button" title="Reload list" class="btn btn-default"
                    @click.prevent="$refs.datatables.reload()">
                    <i class="fa fa-refresh" aria-hidden="true"></i> Reload
                </button>
            </div>

            <datatable :parameters="filters" @reloaded="load_images" ref="datatables" :columns="columns"
                url="/user/list" :buttons="false">
            </datatable>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            systems: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
            users: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
            filter_urgency: {
                Type: [Object, Array],
                default () {
                    return {
                        LOW: false,
                        HIGH: false,
                        NORMAL: false,
                    }
                }
            },
            filter_state: {
                Type: [Object, Array],
                default () {
                    return {
                        PENDING: false,
                        CATERED: false,
                        PROCESSING: false,
                        SOLVED: false,
                        CLOSED: false,
                        HOLD: false,
                        CANCELLED: false,
                    }
                }
            }
        },
        data: function () {
            return {
                user_options: [],
                filters: {
                    find: '',
                    state: {
                        PENDING: false,
                        CATERED: false,
                        PROCESSING: false,
                        SOLVED: false,
                        CLOSED: false,
                        HOLD: false,
                        CANCELLED: false,
                    },
                    urgency: {
                        LOW: false,
                        HIGH: false,
                        NORMAL: false,
                    },
                },
                find: '',
                columns: [

                    {
                        label: 'Ticket #',
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
                        label: 'Title',
                        name: 'title',
                        data: 'title',
                        sortable: false,
                        render: function (data, meta, row) {
                            return `<a class="sender-ticket-title" href="/user/view/` +
                                row.id + `">` + data + `</a>`;
                        }
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
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                        className: "fit",
                        sortable: false,
                    },

                    {
                        label: 'View',
                        name: 'id',
                        data: 'id',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            return `<center><a href="/user/view/` +
                                data + `" target="_blank">View Ticket</a></center>`;
                        }
                    },



                ],
                system_data: [],
            }
        },
        watch: {
            filter_urgency: {
                deep: true,
                handler(value) {
                    this.filters.urgency = value;
                    this.filter_list();
                }
            },
            filter_state: {
                deep: true,
                handler(value) {
                    this.filters.state = value;
                    this.filter_list();
                }
            },

        },
        methods: {
            load_images() {
                execPicCircle();
            },
            filter_list: function () {
                this.$refs.datatables.reload();
            }
        },
        mounted() {
            let par = this;

            par.filters.urgency = par.filter_urgency;
            par.filters.state = par.filter_state;

            par.systems.forEach(function (system) {
                par.system_data.push({
                    id: system.code,
                    text: system.name
                });
            });


            // Refresh list on idle
            $(document).idle({
                onIdle: function () {
                    par.$refs.datatables.reload();
                },
                idle: 120000
            });

            // Populate the users selection filter
            par.users.forEach(function (user) {
                par.user_options.push({
                    id: user[1],
                    text: user[0],
                });
            });


        }

    }

</script>
<style>
    .sender-ticket-title {
        color: #585858;
        font-weight: 600;
    }

</style>
