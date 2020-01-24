<template>
    <datatable ref="datatable" :parameters="parameters" url="/report/tickets/list" :columns="columns"></datatable>
</template>

<script>
    export default {
        props: {
            date_from: {
                required: true,
            },
            date_to: {
                required: true,
            },
            support: {
                required: true,
            },
        },

        watch: {
            date_from: function () {
                this.parameters.from = this.date_from;
                this.parameters.to = this.date_to;
                this.parameters.support = this.support;

                this.$refs.datatable.reload();
            },
            date_to: function () {
                this.parameters.from = this.date_from;
                this.parameters.to = this.date_to;
                this.parameters.support = this.support;

                this.$refs.datatable.reload();
            },
            support: function () {
                this.parameters.from = this.date_from;
                this.parameters.to = this.date_to;
                this.parameters.support = this.support;

                this.$refs.datatable.reload();
            },
        },

        data() {
            return {
                columns: [

                    {
                        label: '#',
                        name: 'control_number',
                        data: 'control_number',
                        className: "fit",
                    },

                  {
                        label: 'Status',
                        name: 'control_number',
                        data: 'control_number',
                        className: "fit",
                        sortable: false,
                        render: function (data, meta, row) {
                            var state = row.state_text;
                            return `<div title="Ticket Status" class="status ` +
                                state.toLowerCase() + `">` + state + `</div>`;
                        }
                    },



                    {
                        label: 'Catered By',
                        name: 'caterer.name',
                        data: 'caterer.name',
                        className: "fit",
                    },


                    {
                        label: 'Hit Catered',
                        name: 'ht_catered',
                        data: 'ht_catered',
                        className: "fit",
                    },


                    {
                        label: 'Hit Processing',
                        name: 'ht_processing',
                        data: 'ht_processing',
                        className: "fit",
                    },


                    {
                        label: 'Hit Solved',
                        name: 'ht_solved',
                        data: 'ht_solved',
                        className: "fit",
                    },


                
                        {
                        label: 'User Rating',
                        name: 'user_rating',
                        data: 'user_rating',
                        className: "fit",
                    },




                ],
                parameters: {
                    from: '',
                    to: '',
                    support: '',
                }
            }
        },
        mounted() {

            this.parameters.from = this.date_from;
            this.parameters.to = this.date_to;
            this.parameters.support = this.support;

        }
    }

</script>
