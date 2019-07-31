<template>
    <modal name="modal-solve" ref="modal">
        <template slot="header">Solve Ticket</template>
        <template slot="body">
            <div class="form-group">
                <label class="control-label">Category</label>
                <select3 class="form-group" v-model="category" :options="cat_options" style="width:100%;"></select3>

            </div>
            <div class="form-group">
                <label class="control-label">Solution</label>
                <textarea class="form-control" v-model="solution" rows="5"></textarea>
            </div>

            <div v-html="textTest"></div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-success btn-sm" @click.prevent="solve">Solve Ticket</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        props: ['ticket_id'],
        data: function () {
            return {

                solution: '',
                category: '',
                submitted: false,
                cat_options: [],

                textTest: '',
            }
        },
        methods: {
            show: function () {
                this.solution = '';
                this.$refs.modal.show();
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

                if (par.solution === '' || par.category === '') {
                    alert("Solution and category is required to close the ticket!");
                    return;
                }

                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.patch('/tickets/solve/' + par.ticket_id, {
                        solution: par.solution,
                        category: par.category,
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
        },
        watch: {
            solution(val) {
                var vm = this;
                axios.post('/preview-generate', {
                    url: val,
                }).then(response => {
                    vm.textTest = response.data;
                });
            }
        },
        mounted() {
            this.load_categories();
        }
    }

</script>
