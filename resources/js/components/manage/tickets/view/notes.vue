<template>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Notes</h3>
            <div class="pull-right">
                <button @click="show_editor===true?show_editor=false:show_editor=true" class="btn btn-xs btn-default">
                    <i class="fa fa-file" aria-hidden="true"></i> Add Note
                </button>
            </div>
        </div>
        <div class="box-body">
            <form @submit.prevent="save_note" v-if="show_editor">
                <div class="form-group">
                    <label class="control-label">Note</label>
                    <textarea class="form-control" rows="5" v-model="note" required></textarea>
                </div>
                <div class="pull-right">
                    <button class="btn btn-sm btn-danger" @click.prevent="cancel_edit" type="button">Cancel</button>
                    <button class="btn btn-sm btn-default" type="submit">Save Note</button>
                </div>
                <div class="clearfix"></div>
                <br>
            </form>

            <center v-if="notes.length===0">No available <strong class="text-yellow">notes</strong> to be displayed.
            </center>
            <table v-else class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Note</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value,key) in notes" :key="key">
                        <td v-html="value.html_content"></td>
                        <td class="fit" v-html="value.created_by"></td>
                        <td class="fit" v-html="value.created_at"></td>
                        <td class="fit">
                            <a class="text-yellow" href="#" @click.prevent="edit(key)"><i class="fa fa-pencil"
                                    aria-hidden="true"></i> Edit</a>
                            <span>&nbsp;&nbsp;</span>
                            <a class="text-red" href="#" @click.prevent="remove(value.id)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Remove</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['ticket_id'],
        data() {
            return {
                notes: [],
                note: '',
                show_editor: false,
                submitted: false,
                update: false,
                update_id: null,
            }
        },
        methods: {
            get_data() {
                let vm = this;
                axios.post('/tickets/notes/' + vm.ticket_id).then(response => {
                    vm.notes = response.data;
                });
            },
            save_note() {
                let vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;

                    vm.show_wait("Please wait while the system is saving your note....");

                    var execaxios = '';
                    if (vm.update === true) {
                        // Update
                        execaxios = axios.patch('/tickets/update_note/' + vm.update_id, {
                            note: vm.note
                        });
                    } else {
                        // Save new
                        execaxios = axios.put('/tickets/add_note/' + vm.ticket_id, {
                            note: vm.note
                        });
                    }
                    execaxios.then(response => {
                        vm.submitted = false;
                        vm.alert_success(response);
                        vm.note = '';
                        vm.get_data();


                        vm.note = '';
                        vm.show_editor = false;
                        vm.update = false;
                        vm.update_id = null;

                        vm.hide_wait();


                    }).catch(error => {
                        vm.alert_failed(error);
                        vm.submitted = false;

                        vm.hide_wait();
                    });


                }

            },
            edit: function (index) {
                this.note = this.notes[index].content;
                this.update_id = this.notes[index].id;
                this.show_editor = true;
                this.update = true;

            },
            cancel_edit() {
                this.note = '';
                this.show_editor = false;
                this.update = false;
                this.update_id = null;
            },
            remove: function (id) {
                let vm = this;

                if (confirm("Are you sure you want to remove the note?") === true) {
                    if (vm.submitted === false) {
                        vm.submitted = true;
                        axios.delete('/tickets/remove_note/' + id).then(response => {

                            vm.alert_success(response);
                            vm.note = '';
                            vm.get_data();
                            vm.submitted = false;

                        }).catch(error => {
                            vm.alert_failed(error);
                            vm.submitted = true;
                        });
                    }
                }



            }
        },
        mounted() {
            this.get_data();
        }
    }

</script>
