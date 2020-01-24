<template>
    <modal :prevent="true" name="modal-create-ticket" :extended_width="true" ref="modal">
        <template slot="header">Create Ticket</template>
        <template slot="body">
            <form ref="submitForm" @submit.prevent="submit">
                <div class="form-group">
                    <label class="control-label">Sender</label>
                    <select2 placeholder="Search..." style="width:100%;" v-model="sender"
                        url="/utility/suggestions/find-user">
                    </select2>
                    <validation :errors="validation_errors" field="sender"></validation>
                </div>
                <ticket-form :validation="validation_errors" v-model="ticket"></ticket-form>

                <button type="submit" class="btn btn-primary btn-sm pull-right">Create Ticket</button>
                <div class="clearfix"></div>
            </form>
        </template>


    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data: function () {
            return {
                submitted: false,
                ticket: new Ticket,
                validation_errors: [],
                sender: '',
            }
        },

        methods: {

            show: function () {
                this.$refs.modal.show();
            },
            submit: function () {

                if (this.$refs.submitForm.checkValidity()) {
                    //form.submit();

                    var vm = this;

                    if (vm.submitted === false) {
                        vm.submitted = true;
                        vm.show_wait("Please wait while the system is submitting your request.....");

                        let form = new FormData();

                        for (var i = 0; i < vm.ticket.attachments.length; i++) {
                            let file = vm.ticket.attachments[i];
                            form.append('attachments[' + i + ']', file);
                        }

                        form.append('sender', vm.sender);
                        form.append('sender_carbon_copies', vm.ticket.sender_carbon_copies);
                        form.append('sender_internet_protocol_address', vm.ticket.sender_internet_protocol_address);
                        form.append('sender_phone', vm.ticket.sender_phone);
                        form.append('title', vm.ticket.title);
                        form.append('content', vm.ticket.content);
                        form.append('urgency', vm.ticket.urgency);

                        form.append('_method', 'PUT');

                        axios.post('/tickets/save', form, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(function (response) {
                            var data = response.data;
                            vm.hide_wait();
                            vm.show_wait_success(data.message);
                            setTimeout(function () {
                                vm.hide_wait();
                                vm.$emit('submitted');
                                vm.$refs.modal.dismiss();
                                vm.ticket = new Ticket;

                            }, 3000);

                        }).catch(function (error) {
                            vm.submitted = false;
                            vm.hide_wait();

                            vm.validation_errors = error.response.data.errors;
                            vm.alert_failed(error);
                        });
                    }


                }
            }

        }
    }

</script>
