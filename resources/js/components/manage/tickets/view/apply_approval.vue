<template>
    <modal :prevent="true" name="modal-initiate-approval" ref="modal">
        <template slot="header">Initiate Approval</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Approver Name</label>
                <input type="text" v-model="approver_name" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Approver Email</label>
                <input type="email" v-model="approver_email" class="form-control">
            </div>

        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default btn-sm" @click.prevent="initiatte_approval">Initiate
                Approval</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data() {
            return {
                approver_email: '',
                approver_name: '',
                submitted: false,
            }
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
            initiatte_approval() {
                let vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is processing your request....");
                    axios.put('/tickets/apply_approval/' + vm.ticket_id, {
                        approver_email: vm.approver_email,
                        approver_name: vm.approver_name,
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
