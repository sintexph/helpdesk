<template>
    <modal :prevent="true" name="modal-escalate" ref="modal">
        <template slot="header">Escalate Ticket</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Escalate to Support Staff</label>
                <select2 placeholder="Search..." style="width:100%;" v-model="user_id" url="/utility/suggestions/supports"></select2>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-danger btn-sm" @click.prevent="escalate">Escalate Ticket</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data() {
            return {
                user_id: '',
                submitted: false,
            }
        },
        methods: {

            show() {
                this.$refs.modal.show();
            },
            escalate() {
                let vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is processing your request....");
                    axios.patch('/tickets/escalate/' + vm.ticket_id, {
                        user_id: vm.user_id,
                    }).then(response => {
                        vm.hide_wait();
                        vm.alert_success(response);
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
