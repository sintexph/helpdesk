<template>
    <modal name="modal-solve" :prevent="true" ref="modal">
        <template slot="header">Solve Ticket</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Category</label>
                <select2 class="form-group" placeholder="Please select category" v-model="category" :options="cat_options" style="width:100%;"></select2>
                <validation :errors="errors" field="category"></validation>
            </div>
            <div class="form-group clearfix">
                <label class="control-label pull-left">Solution</label>
                <button type="button" class="pull-right btn btn-xs btn-primary" @click.prevent="$refs.findCanned.show()">GET
                        CANNED SOLUTION</button>
                <textarea v-if="solution_type==1" class="form-control" v-model="solution" rows="5"
                    placeholder="Write solution..."></textarea>
                <input v-else-if="solution_type==2" class="form-control" v-model="url_solution" placeholder="URL">
                <validation :errors="errors" field="solution"></validation>
                 <label class="control-label"><input type="radio" v-model="solution_type" value="1" name="solution" @click="clear_solution">
                    Write</label>
                <label class="control-label"><input type="radio" v-model="solution_type" value="2" name="solution" @click="clear_solution">
                    Link</label>
            </div>
            <center v-if="show_wait_url" class="text-gray">
                <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
            </center>
            <div v-if="solution_type==2" v-html="solution"></div>
            <div class="form-group">
                <label class="control-label"><input type="checkbox" v-model="message_to_sender"> Send Message to Sender</label>
                <template v-if="message_to_sender===true">
                    <tiny-editor v-model="message" :paste_image="true"></tiny-editor>
                    <validation :errors="errors" field="message"></validation>
                </template>
            </div>
            <modal name="modal-find-canned" :prevent="true" ref="findCanned">
                <template slot="header">Canned Solution</template>
                <template slot="body">
                    <select2 placeholder="Please select solution" class="form-group" v-model="canned_id" url="/utility/suggestions/canned-solutions"
                        style="width:100%;"></select2>
                </template>
                <template slot="footer">
                    <button type="button" class="btn btn-default btn-sm"
                        @click.prevent="set_canned_solution">Select</button>
                </template>
            </modal>

        </template>
        <template slot="footer">
            <button type="button" class="btn btn-success btn-sm" @click.prevent="solve">Solve Ticket</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data: function () {
            return {

                solution_type: 1,
                solution: '',
                category: '',
                submitted: false,
                cat_options: [],
                errors: [],
                url_solution: '',
                show_wait_url: false,
                message_to_sender: false,
                message: '',
                canned_id: '',
            }
        },
        methods: {
            show: function () {
                this.solution = '';
                this.$refs.modal.show();
            },
            set_canned_solution() {
                let par = this;
                // clearout first the solution
                par.clear_solution();

                axios.post('/utility/canned-solution/' + this.canned_id).then(response => {
                    var csdata = response.data;

                    par.solution_type = csdata.type;
                    
                    if (par.solution_type === 1)
                        par.solution = csdata.content;
                    else
                        par.url_solution = csdata.content;

                    par.$refs.findCanned.dismiss();


                });

            },
            clear_solution() {
                this.solution = '';
                this.url_solution = '';
            },
            load_categories() {
                let par = this;
                axios.post('/utility/categories').then(response => {
                    response.data.forEach(element => {
                        par.cat_options.push({
                            id: element.name,
                            text: element.name,
                        });
                    });

                });
            },
            validURL(str) {
                var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
                return !!pattern.test(str);
            },
            solve() {
                var par = this;


                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.patch('/tickets/solve/' + par.ticket_id, {
                        solution: par.solution,
                        category: par.category,
                        message_to_sender: par.message_to_sender,
                        message: par.message
                    }).then(function (response) {
                        par.hide_wait();
                        par.alert_success(response);
                        location.reload();
                    }).catch(function (error) {

                        par.errors = error.response.data.errors;
                        // Show alert if not a validation error
                        if (par.errors === undefined || par.errors === null || par.errors.length === 0)
                            par.alert_failed(error);

                        par.hide_wait();
                        par.submitted = false;
                    });

                }

            }
        },
        watch: {
            url_solution(val) {
                var vm = this;
                if (val !== "") {
                    vm.show_wait_url = true;
                    vm.solution = '';
                    axios.post('/preview-generate', {
                        url: val,
                    }).then(response => {
                        vm.solution = response.data;
                        vm.show_wait_url = false;
                    }).catch(error => {
                        vm.show_wait_url = false;
                    });
                }

            }
        },
        mounted() {
            this.load_categories();
        }
    }

</script>
