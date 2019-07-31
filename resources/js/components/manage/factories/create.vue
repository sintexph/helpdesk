<template>
    <modal name="create-factory" ref="modal"  >
        <template slot="header">Create Factory</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Factory</label>
                <input type="text" v-model="name" class="form-control">
            </div>
            
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Factory</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script> 
    export default { 
        data() {
            return {
               name:'',
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            create() {
                var vm = this;
                axios.put('/maintain/factory/save', {
                    name: vm.name, 
                }).then(
                    response => {
                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name='';
                        vm.$emit('created');
                    }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
