<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Categories</h3>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-default" @click.prevent="$refs.createCategory.show">Add
                    Category</button>
            </div>


        </div>
        <div class="box-body">
            <datatable ref="datatable" :buttons="false" url="/maintain/category/list" :columns="columns"></datatable>
            <create-category @created="$refs.datatable.reload()" ref="createCategory"></create-category>
            <edit-category @updated="$refs.datatable.reload()" ref="editCategory"></edit-category>
        </div>
    </div>
</template>
<script>
    import createCategory from './create';
    import editCategory from './edit';

    export default {
        components: {
            'create-category': createCategory,
            'edit-category': editCategory,
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
                    vm.$refs.editCategory.show($(this).data('id'));
                });
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();

                    if (confirm("Are you sure you want to delete the category?") === true) {
                        axios.delete('/maintain/category/delete/' + $(this).data('id')).then(
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
