<template>
    <modal name="modal-custom-progress" ref="modal">
        <template slot="header">Custom Progress</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Progress</label>
                <select class="form-control" v-model="state">
                    <option value="">-- SELECT --</option>
                    <option value="FOR_CANVASSING">FOR CANVASSING</option>
                    <option value="CREATED_PURCHASE_REQUISITION">CREATED PURCHASE REQUISITION</option>
                    <option value="PROCESSING_PURCHASE_ORDER">PROCESSING PURCHASE ORDER</option>
                    <option value="DELIVERED">DELIVERED</option>
                    <option value="READY_FOR_RELEASE">READY FOR RELEASE</option>
                </select>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="update_progress">Update
                Progress</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data: function () {
            return {
                submitted: false,
                state: '',
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            update_progress() {
                var par = this;

                if (par.submitted === false) {

                    if (confirm("Do you want to update the ticket progress?")) {

                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request....");

                        axios.patch('/tickets/custom_progress/' + par.ticket_id, {
                            state: par.state,
                        }).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                            par.hide_wait();
                        });
                    }

                }
            }
        }
    }

</script>
