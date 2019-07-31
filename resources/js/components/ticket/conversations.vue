<template>
    <div>
        <transition name="fade">
            <div class="clearfix">
                <button type="button" class="btn btn-primary btn-sm pull-right" v-show="post_conversation===false"
                    @click.prevent="post_conversation=true">
                    <i class="fa fa-inbox" aria-hidden="true"></i>
                    Send Message
                </button>
            </div>
        </transition>
        <transition name="fade">
            <div class="box box-solid" v-show="post_conversation">
                <div class="box-header">
                    <h3 class="box-title">Send Message</h3>
                    <div class="pull-right">
                        <button type="button" class="btn btn-xs" @click.prevent="post_conversation=false">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <create-conversation @submitted="$refs.listConversation.list()" :ticket_id="ticket_id"
                        :token="token"></create-conversation>
                </div>

            </div>
        </transition>
        <br>
        <div class="box box-solid">
            <div class="box-body">
                <list-conversation ref="listConversation" :ticket_id="ticket_id" :token="token"></list-conversation>
            </div>
        </div>
    </div>
</template>

<script>
    import listConversation from './conversation/list';
    import createConversation from './conversation/create';

    export default {

        components: {
            'create-conversation': createConversation,
            'list-conversation': listConversation,
        },
        props: {
            token: String,
            ticket_id: String,

        },
        data: function () {
            return {
                post_conversation: false,
            }
        },
    }

</script>
