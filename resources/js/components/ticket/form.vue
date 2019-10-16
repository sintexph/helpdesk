<template>

    <div>


        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Phone/Extension <i><small class="text-red">*</small></i></label>
                    <input type="text" class="form-control" v-model="ticket.sender_phone" required>
                    <validation :errors="validation" field="sender_phone"></validation>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">IP Address <i><small class="text-red">*</small></i></label>
                    <input type="text" class="form-control" v-model="ticket.sender_internet_protocol_address" required>
                    <validation :errors="validation" field="sender_internet_protocol_address"></validation>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label text-grey">Carbon Copies <small><i>(optional)</i></small></label>
            <input-tag v-model="ticket.sender_carbon_copies"></input-tag>
        </div>

        <div class="form-group">
            <label class="control-label">Title <i><small class="text-red">*</small></i></label>
            <input type="text" class="form-control" v-model="ticket.title" :maxlength="maxLength" required>
            <validation :errors="validation" field="title"></validation>
            <span class="text-yellow">Characters remaning {{title_remaining}} *</span>
        </div>
        <div class="form-group">
            <label class="control-label">Urgency <i><small class="text-red">*</small></i></label>

            <br>
            <label class="control-label">
                <icheck-radio skin="grey" name="urgency" v-model="urgency.low"></icheck-radio> Low
            </label>
            <label class="control-label">
                <icheck-radio skin="green" name="urgency" v-model="urgency.normal"></icheck-radio>
                Normal
            </label>
            <label class="control-label">
                <icheck-radio name="urgency" v-model="urgency.high"></icheck-radio> High
            </label>
            <br>
            <validation :errors="validation" field="urgency"></validation>
        </div>

        <div class="form-group">
            <label class="control-label">Content <i><small class="text-red">*</small></i></label>
            <p>
                <small>You can paste a picture in below text area but if you have multiple picture to be reference, much
                    better
                    to use the attachments area below.</small>
            </p>
            <tiny-editor v-model="ticket.content" :paste_image="true"></tiny-editor>
            <validation :errors="validation" field="content"></validation>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Attachments</label>
                    <input-file :multiple="true" v-model="ticket.attachments"></input-file>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            validation: {
                required: true,
                default () {
                    return [];
                }
            },
            value: {
                required: true,
                default () {
                    return new Ticket;
                }
            }
        },

        data: function () {
            return {
                ticket: new Ticket,
                maxLength: 50,
                urgency: {
                    low: true,
                    normal: false,
                    high: false,
                }
            }
        },
        computed: {
            title_remaining: function () {
                return (this.maxLength - this.ticket.title.length);
            }
        },
        watch: {
            urgency: {
                deep: true,
                handler(urgency) {

                    if (urgency.low === true)
                        this.ticket.urgency = '1';
                    if (urgency.normal === true)
                        this.ticket.urgency = '2';
                    if (urgency.high === true)
                        this.ticket.urgency = '3';
                }
            },
            value: {
                deep: true,
                handler(value) {
                    this.ticket = value;
                }
            },
            ticket: {
                deep: true,
                handler(ticket) {
                    this.$emit('input', ticket);
                }
            }
        },
        mounted() {
            this.$nextTick(function () {
                if (this.value !== null)
                    this.ticket = this.value;
            });
        }

    }

</script>
