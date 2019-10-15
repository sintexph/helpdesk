<template>
    <modal :prevent="true" name="create-note" ref="modal">
        <template slot="header">Add Note</template>
        <template slot="body">

            <div class="form-group"> 
                <textarea v-model="note" placeholder="Place your note here..." class="form-control" rows="5" required></textarea>
            </div>

        </template>
        <template slot="footer">

            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-default btn-sm" @click.prevent="save()">Add</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: {
            project_id: {
                type: [String, Number],
                required: true,
            }
        },
        data() {
            return {
                note: '',
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.note = '';
                this.$refs.modal.show();
            },
            save() {

                let vm = this;

                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is saving the note....");
                    axios.put('/projects/note/save/' + vm.project_id, {
                        note: vm.note
                    }).then(function (response) {

                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        vm.$emit('added');
                        vm.hide_wait();
                        vm.submitted = false;

                    }).catch(function (error) {
                        vm.alert_failed(error);
                        vm.submitted = false;
                        vm.hide_wait();
                    });

                }


            }


        },
    }

</script>
