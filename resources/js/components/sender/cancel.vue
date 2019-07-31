<template>
    <a href="#" class="text-red" @click.prevent="cancel_ticket">
        <i aria-hidden="true" class="fa fa-folder-open"></i>
        <span>CANCEL TICKET</span>
    </a>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data() {
            return {
                submitted: false,
            }
        },
        methods: {
            cancel_ticket() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to cancel the ticket?') === true) {
                        var cancellation_reason = prompt("Please put cancellation reason", "");
                        par.submitted = true;

                        par.show_wait("Please wait while the system is processing your request....");
                        axios.patch('/user/cancel/' + par.ticket_id, {
                            cancellation_reason: cancellation_reason
                        }).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.hide_wait();
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }
            }
        }
    }

</script>
