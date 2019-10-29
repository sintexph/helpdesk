<template>
    <div class="clearfix">
        <form @submit.prevent="submit">
            <div class="form-group"> 
                <tiny-editor v-model="conversation" :paste_image="true"></tiny-editor>
            </div>
            <div class="form-group pull-left">
                <label class="control-label">Attachments</label>
                <input-file :multiple="true" v-model="attachments"></input-file>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">
                    <i class="fa fa-inbox text-yellow" aria-hidden="true"></i>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {

        props: {
            token: String,
            ticket_id: String,

        },
        data: function () {
            return {
                submitted: false,
                attachments: [],
                conversation: '',
            }
        },
        methods: {
            submit: function () {
                var vm = this;
                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is sending the message....");
                    let form = new FormData();
                    for (var i = 0; i < vm.attachments.length; i++) {
                        let file = vm.attachments[i];
                        form.append('attachments[' + i + ']', file);
                    }
                    form.append('conversation', vm.conversation);
                    form.append('_method', 'PUT');
                    axios.post('/conversation/save/' + vm.ticket_id, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        vm.hide_wait();
                        vm.conversation = '';
                        vm.attachments = [];
                        vm.submitted = false;
                        vm.$emit('submitted');
                    }).catch(error => {
                        vm.hide_wait();
                        vm.submitted = false;
                        this.alert_failed(error);

                    });

                }

            }
        },
    }

</script>
