<template>
    <div>

        <transition name="fade">
            <template v-if="post_conversation">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Send Message</h3>
                        <div class="pull-right">
                            <button type="button" class="btn btn-default btn-xs" @click.prevent="post_conversation=false">
                                <i class="fa fa-times text-red"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <create-conversation @submitted="$refs.listConversation.list()" :ticket_id="ticket_id"
                            :token="token"></create-conversation>
                    </div>
                </div>
                <br>
            </template>
        </transition>

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-inbox text-yellow" aria-hidden="true"></i> Messages</h3>
                <transition name="fade">
                    <a href="#" class="btn btn-default btn-xs pull-right"
                            v-show="post_conversation===false" @click.prevent="post_conversation=true">
                            <i class="fa fa-inbox text-yellow" aria-hidden="true"></i>
                            Send Message
                        </a>
                </transition>
            </div>
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
