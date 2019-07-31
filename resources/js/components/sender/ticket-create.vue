<template>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Create Ticket</h3>
        </div>
        <div class="box-body">

            <form @submit.prevent="submit">
                <create-form :validation="validation_errors" v-model="ticket"></create-form>
                <button type="submit" class="btn btn-danger pull-right"><i class="fa fa-paper-plane-o"
                        aria-hidden="true"></i>
                    Submit Request</button>
            </form>

        </div>
    </div>
</template>


<script>
 
    export default { 
        data: function () {
            return {
                ticket: new Ticket,
                submitted: false,
                validation_errors: [],
            }
        },
        methods: {
            submit: function () {
                var vm = this;

                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is submitting your request.....");

                    let form = new FormData();

                    for (var i = 0; i < vm.ticket.attachments.length; i++) {
                        let file = vm.ticket.attachments[i];
                        form.append('attachments[' + i + ']', file);
                    }


                    form.append('sender_carbon_copies', vm.ticket.sender_carbon_copies);
                    form.append('sender_internet_protocol_address', vm.ticket.sender_internet_protocol_address);
                    form.append('sender_phone', vm.ticket.sender_phone);
                    form.append('title', vm.ticket.title);
                    form.append('content', vm.ticket.content);
                    form.append('urgency', vm.ticket.urgency);


                    form.append('_method', 'PUT');

                    axios.post('/user/ticket/save', form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        var data = response.data;
                        vm.hide_wait();
                        vm.show_wait_success(data.message);


                        setTimeout(function () {
                              vm.hide_wait();
                            vm.$router.push('/');
                        }, 3000);

                    }).catch(function (error) {
                        vm.submitted = false;
                        vm.hide_wait();
                        vm.validation_errors = error.response.data.errors;
                    });
                }


            }
        }

    }

</script>
