<template>
    <modal :prevent="true" name="modal-escalate" ref="modal">
        <template slot="header">Escalate Ticket</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Escalate to Support Staff</label>
                <select class="form-control" v-model="user_id">
                    <option value="">-- SELECT --</option>
                    <option v-for="(value,key) in users" :value="value.id" :key="key">{{ value.name }}</option>
                </select>
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
                users: [],
                user_id: '',
                submitted: false,
            }
        },
        methods: {
            get_users() {
                let vm = this;
                axios.post('/utility/get-users').then(response => {
                    vm.users = response.data;
                });
            },
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
        },
        mounted() {
            this.get_users();
        }

    }

</script>
