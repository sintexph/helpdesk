<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Settings</h3>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-default" @click.prevent="$refs.createSetting.show">Add
                    Setting</button>
            </div>


        </div>
        <div class="box-body">
            <datatable ref="datatable" :buttons="false" url="/maintain/setting/list" :columns="columns"></datatable>
            <create-setting @created="$refs.datatable.reload()" ref="createSetting"></create-setting>
            <edit-setting @updated="$refs.datatable.reload()" ref="editSetting"></edit-setting>
        </div>
    </div>
</template>
<script>
    import createSetting from './create';
    import editSetting from './edit';

    export default {
        components: {
            'create-setting': createSetting,
            'edit-setting': editSetting,
        },
        data() {
            return {
                columns: [{
                        label: 'Id',
                        data: 'id',
                    },
                    {
                        label: 'Name',
                        data: 'name',
                    },
                     {
                        label: 'Value',
                        data: 'value',
                    },
                     {
                        label: 'Remark',
                        data: 'remark',
                    },
                      {
                        label: 'Created By',
                        data: 'created_by',
                        className:'fit',
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
                    vm.$refs.editSetting.show($(this).data('id'));
                });
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();

                    if (confirm("Are you sure you want to delete the setting?") === true) {
                        axios.delete('/maintain/setting/delete/' + $(this).data('id')).then(
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
