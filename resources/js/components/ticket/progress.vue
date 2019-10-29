<template>
    <div class="activity-list">
        <div class="activity-list-item" v-for="(value,key) in progress_ticket" :key="key">
            <div :class="'activity-icon circle-'+value.state.toLowerCase().replace(' ','-')">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
            </div>
            <div>
                <strong>{{ value.state }}</strong><br>
                <span v-html="value.content"></span>
                <br>
                <span class="text-muted" :title="value.created_at"><small v-text="value.time_ago"></small></span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            ticket_id: [String, Number],
        },
        data()
        {
            return {
                progress_ticket:[]
            }
        },
        methods: {
            load_data() {
                let vm = this;
                axios.post('/utility/ticket-progress/' + vm.ticket_id).then(response => {
                    vm.progress_ticket = response.data;
                });
            }
        },
        mounted() {
            this.load_data();
        }
    }

</script>

<style lang="scss">
    @import '../../.././sass/_variables.scss';

    .activity-list-item {
        padding: 10px 0;
        display: grid;
        grid-template-columns: -webkit-min-content 1fr;
        grid-template-columns: min-content 1fr;
        grid-column-gap: 16px;
        color: #888;
        fill: #888;
        font-size: 0.9em;
    }

    .activity-icon {
        font-size: 25px;
    }



    .circle-pending {
        color: $font_pending;
    }

    .circle-catered {
        color: $font_catered;
    }

    .circle-processing {
        color: $font_processing;
    }

    .circle-escalated {
        color: $font_escalated;
    }


    .circle-cancelled {
        color: $font_cancelled;
    }

    .circle-closed {
        color: $font_closed;
    }

    .circle-hold {
        color: $font_hold;
    }

    .circle-un-hold {
        color: $font_un_hold;
    }

    .circle-solved {
        color: $font_solved;
    }

    .circle-applied-approval {
        color: $font_applied_approval;
    }

    .circle-approval-cancelled {
        color: $font_approval_cancelled;
    }


    .circle-approved {
        color: $font_approved;
    }

</style>
