<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Account</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="find_account">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_account" class="btn btn-default btn-flat">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Role</label>
                    <select class="form-control input-sm" @change="$refs.datatables.reload()" v-model="filters.role"
                        required>
                        <option value="">-- SELECT ROLE --</option>

                        <option :value="ROLE.SENDER">SENDER</option>
                        <option :value="ROLE.SUPPORT">SUPPORT</option>
                        <option :value="ROLE.MODERATOR">MODERATOR</option>
                        <option :value="ROLE.ADMINISTRATOR">ADMINISTRATOR</option>

                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Active</label>
                    <select class="form-control input-sm" @change="$refs.datatables.reload()" v-model="filters.active"
                        required>
                        <option value="">-- SELECT ACTIVE --</option>

                        <option :value="0">NO</option>
                        <option :value="1">YES</option>

                    </select>
                </div>

            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Account List</h3>
            </div>
            <div class="box-body">
                <button class="btn btn-xs btn-default" @click.prevent="$refs.createAccount.show">Create Account</button>
                <datatable :buttons="false" :fixedRightColumns="1" ref="datatables" :parameters="filters"
                    :columns="columns" url="/accounts/list"></datatable>
                <create-account ref="createAccount" @created="$refs.datatables.reload()"></create-account>
                <edit-account ref="editAccount" @updated="$refs.datatables.reload()"></edit-account>
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
                    role: '',
                    active: '',
                },
                submitted: false,
                columns: [{
                        label: 'Name',
                        name: 'name',
                        data: 'name',
                        className: 'nowrap',
                    },

                    {
                        label: 'Id Number',
                        name: 'id_number',
                        data: 'id_number',
                    },

                    {
                        label: 'Factory',
                        name: 'factory',
                        data: 'factory',
                    },


                    {
                        label: 'Position',
                        name: 'position',
                        data: 'position',
                    },
                    {
                        label: 'Role',
                        name: 'role_text',
                        data: 'role_text',
                        sortable: false,
                    },





                    {
                        label: 'Username',
                        name: 'username',
                        data: 'username',
                    },



                    {
                        label: 'Email',
                        name: 'email',
                        data: 'email',
                        className: 'nowrap',

                    },

                    {
                        label: 'Shift',
                        className: 'nowrap',
                        render(data, meta, row) {
                            if (row.shift_start !== null && row.shift_end !== null)
                                return row.shift_start + ' - ' + row.shift_end;
                            else
                                return ``;
                        }
                    },

                    {
                        label: 'break time',
                        name: 'break_time',
                        data: 'break_time',
                        className: 'nowrap',

                    },

                    {
                        label: 'Active',
                        name: 'active',
                        data: 'active',
                        className: 'nowrap',
                        render: function (data) {
                            if (data === true)
                                return '<span class="label label-success">Active</span>';
                            else
                                return '<span class="label label-default">Not Active</span>';
                        }

                    },
                    {
                        label: 'Created By',
                        name: 'created_by',
                        data: 'created_by',
                        className: 'nowrap',
                    },
                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                        className: 'nowrap',
                    },
                    {
                        label: 'Updated By',
                        name: 'updated_by',
                        data: 'updated_by',
                        className: 'nowrap',
                    },

                    {
                        label: 'Updated At',
                        name: 'updated_at',
                        data: 'updated_at',
                        className: 'nowrap',
                    },


                    {
                        label: 'Actions',
                        name: 'id',
                        data: 'id',
                        className: 'nowrap',
                        export: false,
                        render: function (data, meta, row) {
                            var delbtn = `<a href="#" data-id="` + data +
                                `" class="btn-delete" ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>`;
                            var editbtn = `<a href="#" data-id="` + data +
                                `" class="btn-edit" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>`;

                            return editbtn + '&nbsp;&nbsp;' + delbtn;
                        }
                    }

                ]
            }
        },
        methods: {
            find_account: function () {
                this.$refs.datatables.reload();
            },
            delete: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to delete the account?");
                    if (r == true) {
                        par.submitted = true;
                        axios.delete('/accounts/delete/' + id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            par.$refs.datatables.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }


            }
        },
        mounted() {
            var parent = this;
            parent.$nextTick(function () {
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.delete(id);
                });
                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.$refs.editAccount.show(id);
                });

            })
        }
    }

</script>
