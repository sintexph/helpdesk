<template>
    <modal :prevent="true" name="modal-mod-cc" ref="modal">
        <template slot="header">Modify Carbon Copies</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label text-grey">Carbon Copies</label>
                <input-tag v-model="sender_carbon_copies"></input-tag>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="modify_carbon_copies">Update</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id', 'cc'],
        data() {
            return {
                sender_carbon_copies: '',
                submitted: false,
            }
        },
        methods: {
            show() {
                this.$refs.modal.show();
                 this.sender_carbon_copies = this.cc.join();
            this.$forceUpdate();
            },
            modify_carbon_copies() {
                let vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is processing your request....");
                    axios.post('/tickets/modify-carbon-copies/' + vm.ticket_id, {
                        sender_carbon_copies: vm.sender_carbon_copies,
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
