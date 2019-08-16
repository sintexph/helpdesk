<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Custom Application</h3>
            <div class="pull-right">

                                <div class="has-feedback">
                    <input type="text" v-model="filters.find" placeholder="Search..." @keydown.enter="$refs.datatable.reload()" class="form-control input-sm">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>

            
            </div>


        </div>
        <div class="box-body">
                <button type="button" class="btn btn-xs btn-default" @click.prevent="$refs.createApplication.show">Add
                    Application</button>

            <datatable ref="datatable" :parameters="filters"  :buttons="false" url="/maintain/application/list" :columns="columns"></datatable>
            <create-application @created="$refs.datatable.reload()" ref="createApplication"></create-application>
            <edit-application @updated="$refs.datatable.reload()" ref="editApplication"></edit-application>
            <rep-application @updated="$refs.datatable.reload()" ref="repApplication"></rep-application>
        </div>
    </div>
</template>
<script>
    import createApplication from './create';
    import editApplication from './edit'; 

    export default {
        components: {
            'create-application': createApplication,
            'edit-application': editApplication, 
        },
        data() {
            return {
                  filters: {
                    find: '',
                },

                columns: [ 
                    {
                        label: 'name',
                        data: 'name',
                    },
                    {
                        label: 'applications',
                        data: 'applications',
                    },
                    {
                        label: 'Action',
                        data: 'id',
                        render(data, meta, row) {

                            var btn_rep = `<a class="btn-rep text-green" href="#" data-id="` + data +
                                `">Representation</a>`;

                            var btn_edit = `<a class="btn-edit text-yellow" href="#" data-id="` + data +
                                `">Edit</a>`;
                            var btn_delete = `<a class="btn-delete text-red" href="#" data-id="` + data +
                                `">Delete</a>`;

                            return btn_rep + `&nbsp;&nbsp;&nbsp;` + btn_edit + `&nbsp;&nbsp;&nbsp;` + btn_delete;
                        }
                    },
                ]
            }
        },
        mounted() {
            this.$nextTick(function () {
                var vm = this;

                $(document).on('click', '.btn-rep', function (e) {
                    e.preventDefault();
                    vm.$refs.repApplication.show($(this).data('id'));
                });

                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    vm.$refs.editApplication.show($(this).data('id'));
                });
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();

                    if (confirm("Are you sure you want to delete the application?") === true) {
                        axios.delete('/maintain/application/delete/' + $(this).data('id')).then(
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
