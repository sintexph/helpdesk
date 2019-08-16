<template>
    <modal :prevent="true" name="modal-add-reference" ref="modal">
        <template slot="header">Add Reference Ticket</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Ticket #</label>
                <input type="text" class="form-control" v-model="control_number" required>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="add_reference">Add Reference</button>
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
                control_number: '',
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            add_reference() {
                var par = this;
                if(par.control_number==='')
                {
                    alert('Ticket number is required!');
                    return;
                }
                if (par.submitted === false) {
                    par.submitted = true;
                    axios.put('/tickets/add_reference/' + par.ticket_id, {
                        control_number: par.control_number,
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

</script>
