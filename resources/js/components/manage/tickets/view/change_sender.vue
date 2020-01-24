<template>
    <modal :prevent="true" name="modal-change-user" ref="modal">
        <template slot="header">Change Sender</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Sender</label>
                <select2 placeholder="Search..." style="width:100%;" v-model="sender"
                    url="/utility/suggestions/find-user">
                </select2>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="change_sender">Update</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data() {
            return {
                sender: '',
                submitted: false,
            }
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
            change_sender() {
                let vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is processing your request....");
                    axios.post('/tickets/change-sender/' + vm.ticket_id, {
                        sender: vm.sender,
                    }).then(response => {
                        vm.alert_success(response);
                        vm.hide_wait();
                        location.reload();
                    }).catch(error => {
                        vm.hide_wait();
                        vm.submitted = false;
                        vm.alert_failed(error);
                    });
                }

            }
        }
    }

</script>
