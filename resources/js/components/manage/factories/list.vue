<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Factories</h3>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-default" @click.prevent="$refs.createFactory.show">Add
                    Factory</button>
            </div>


        </div>
        <div class="box-body">
            <datatable ref="datatable" :buttons="false" url="/maintain/factory/list" :columns="columns"></datatable>
            <create-factory @created="$refs.datatable.reload()" ref="createFactory"></create-factory>
            <edit-factory @updated="$refs.datatable.reload()" ref="editFactory"></edit-factory>
        </div>
    </div>
</template>
<script>
    import createFactory from './create';
    import editFactory from './edit';

    export default {
        components: {
            'create-factory': createFactory,
            'edit-factory': editFactory,
        },
        data() {
            return {
                columns: [{
                        label: 'id',
                        data: 'id',
                    },
                    {
                        label: 'name',
                        data: 'name',
                    },
                      {
                        label: 'Created By',
                        data: 'created_by',
                    },
                       {
                        label: 'Created At',
                        data: 'created_at',
                    },


                    {
                        label: 'Updated By',
                        data: 'updated_by',
                    },
                       {
                        label: 'Updated At',
                        data: 'updated_at',
                    },

                    {
                        label: 'Action',
                        data: 'id',
                        render(data, meta, row) {

                            var btn_edit = `<a class="btn-edit text-yellow" href="#" data-id="` + data +
                                `">Edit</a>`;
                            var btn_delete = `<a class="btn-delete text-red" href="#" data-id="` + data +
                                `">Delete</a>`;

                            return btn_edit + `&nbsp;&nbsp;&nbsp;` + btn_delete;
                        }
                    },
                ]
            }
        },
        mounted() {
            this.$nextTick(function () {
                var vm = this;


                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    vm.$refs.editFactory.show($(this).data('id'));
                });
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();

                    if (confirm("Are you sure you want to delete the factory?") === true) {
                        axios.delete('/maintain/factory/delete/' + $(this).data('id')).then(
                            response => {
                                vm.alert_success(response);
                                vm.$refs.datatable.reload();
                            }).catch(error => {
                            vm.alert_failed(error);

                        });
                    }


                });



            })
        }

    }

</script>
