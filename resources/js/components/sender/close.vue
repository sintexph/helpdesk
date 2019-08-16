<template>
    <modal :prevent="true" name="modal-close" ref="modal">
        <template slot="header">Close Ticket</template>
        <template slot="body">
            <div class="form-group">
                <textarea placeholder="Feedback" v-model="feedback" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label class="control-label">Rating</label>
                <div class="clearfix"></div>
                <fieldset class="rating">
                    <input type="radio" id="star5" v-model="rating" name="rating" value="5" /><label for="star5"
                        title="5 Star">5
                        stars</label>
                    <input type="radio" id="star4" v-model="rating" name="rating" value="4" /><label for="star4"
                        title="4 Star">4
                        stars</label>
                    <input type="radio" id="star3" v-model="rating" name="rating" value="3" /><label for="star3"
                        title="3 Star">3
                        stars</label>
                    <input type="radio" id="star2" v-model="rating" name="rating" value="2" /><label for="star2"
                        title="2 Star">2
                        stars</label>
                    <input type="radio" id="star1" v-model="rating" name="rating" value="1" /><label for="star1"
                        title="1 Star">1
                        star</label>
                </fieldset>
                <div class="clearfix"></div>
            </div>
            <p>
                Your <strong class="text-yellow">feedback</strong> is valuable and will only take 5 seconds.
                <strong>Click</strong> or tap the <strong class="text-yellow">rating</strong> which best represents your
                experience
            </p>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="close">Close Ticket</button>
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
                rating: null,
                feedback: null,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            close(processed) {
                var par = this;
                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is processing your request....");
                    axios.patch('/user/close/' + par.ticket_id, {
                        feedback: par.feedback,
                        rating: par.rating,
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

</script>
