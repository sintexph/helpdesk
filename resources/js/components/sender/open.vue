<template>


    <a href="#" @click.prevent="open_ticket">
        <i aria-hidden="true" class="fa fa-folder-open"></i>
        <span>Open Ticket</span>
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
            open_ticket() {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to re-open the ticket?') === true) {
                        var reason = prompt("Reason for opening the ticket", "");
                        if (reason) {
                            par.submitted = true;
                            axios.patch('/user/open/' + par.ticket_id, {
                                reason: reason
                            }).then(function (response) {
                                par.alert_success(response);
                                location.reload();
                            }).catch(function (error) {
                                par.submitted = false;
                                par.alert_failed(error);
                            });
                        }

                    }
                }
            }
        }
    }

</script>
