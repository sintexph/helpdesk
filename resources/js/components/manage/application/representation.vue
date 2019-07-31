<template>
    <modal name="edit-application" ref="modal" :extended_width="true">
        <template slot="header">Representation Format</template>
        <template slot="body">
            <div class="row">
                <div class="col-sm-3" style="font-size:10px;">
                    <div class="form-group">

                        <label class="control-label">Application</label>
                        <br>
                        <a style="font-weight:800;" class="text-red" href="#"
                            @click.prevent="insert_application_name">Application Name</a>
                        <br>
                        <br>
                        <label class="control-label">Dynamic Fields</label>
                        <br>
                        <click-dn-fields @generate="insert_field" :fields="application.fields"></click-dn-fields>

                        <br>
                        <label class="control-label">Static Fields</label>
                        <div style="font-weight:800;">
                            <a href="#" @click.prevent="insert_field(start_tag+'created_at'+end_tag)">CREATED AT</a><br>
                            <a href="#" @click.prevent="insert_field(start_tag+'sender_id_number'+end_tag)">SENDER ID NUMBER</a><br>
                            <a href="#" @click.prevent="insert_field(start_tag+'sender_name'+end_tag)">SENDER NAME</a><br>
                            <a href="#" @click.prevent="insert_field(start_tag+'sender_email'+end_tag)">SENDER EMAIL</a><br>
                            <a href="#" @click.prevent="insert_field(start_tag+'sender_contact'+end_tag)">SENDER CONTACT</a><br>
                            <a href="#" @click.prevent="insert_field(start_tag+'sender_factory'+end_tag)">SENDER FACTORY</a><br>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label class="control-label">Raw Html</label>
                        <textarea ref="rawHtmlTextarea" v-model="application.format" class="form-control"
                            rows="20"></textarea>
                        <div class="ovFormat" v-html="application.format"></div>
                    </div>
                </div>
            </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Save Format</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    import appForm from "./form";
    export default {
        components: {
            'application-form': appForm,
        },
        data() {
            return {
                id: null,
                application: new Application
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.load_application();
                this.$refs.modal.show();
            },
            load_application() {
                var vm = this;
                axios.post('/maintain/application/info/' + vm.id).then(response => {
                    vm.application = vm.convert_application(response.data);
                });
            },
            insert_application_name() {
                this.text_area_selection(this.start_tag+'--application_name--'+this.end_tag);
            },
            insert_field(textToInsert) {
 
                this.text_area_selection(textToInsert);

            },
            text_area_selection(textToInsert) {
                var input = this.$refs.rawHtmlTextarea;

                // get current text of the input
                const value = input.value;

                // save selection start and end position
                const start = input.selectionStart;
                const end = input.selectionEnd;

                // update the value with our text inserted
                this.application.format = value.slice(0, start) + textToInsert + value.slice(end);
            },
            update() {
                var vm = this;
                axios.patch('/maintain/application/update/format/' + vm.id, {
                    format: vm.application.format,
                }).then(response => {
                    vm.alert_success(response);
                    vm.id = null;
                    vm.$refs.modal.dismiss();
                    vm.$emit('updated');
                }).catch(error => {
                    vm.alert_failed(error);
                });

            }
        }
    }

</script>
<style>
    .ovFormat {
        overflow-x: scroll;
    }

</style>
