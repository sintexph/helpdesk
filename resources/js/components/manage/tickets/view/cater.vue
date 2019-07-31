<template>
    <modal name="modal-cater" ref="modal">
        <template slot="header">Cater Ticket</template>
        <template slot="body">
            Do you want to cater and at the same time process the ticket?
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="cater(false)">Cater Only</button>
            <button type="button" class="btn btn-success btn-sm" @click.prevent="cater(true)">Cater & Process</button>
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
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            cater(processed) {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request....");

                    axios.patch('/tickets/cater/' + par.ticket_id, {
                        processed: processed,
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

</script>
