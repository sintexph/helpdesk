<template>
    <div v-if="conversations.length!==0">

        <div class="post" v-for="(value,key) in conversations" :key="key">
            <div class="user-block">
                <a class="venobox" :href="value.creator.photo">
                    <img class="img-circle img-bordered-sm" :src="value.creator.photo" alt="User Image" />
                </a>
                <span class="username">{{ value.creator.name }}
                </span>
                <span class="description">{{ value.time_ago }}</span>
            </div>
            <p v-html="value.content_html"></p>
        </div>
    </div>
    <h3 v-else class="text-gray text-center">Sorry!
        <br>
        <small>There are no available message to be displayed.</small>
    </h3>

</template>
<script>
    export default {
        props: {
            token: String,
            ticket_id: String,
        },
        data: function () {
            return {
                conversations: [],
            }
        },
        methods: {
            list: function () {
                var vm = this;
                axios.post('/conversation/list/' + vm.ticket_id + '/' + vm.token).then(function (response) {
                    vm.conversations = response.data;
                });
            },
            delete_conversation: function (id) {
                var r = confirm("Do you want to delete the conversation?");
                if (r == true) {
                    var vm = this;
                    axios.delete('/conversation/delete/' + id).then(function (response) {
                        vm.list();
                    });
                }
            }
        },
        mounted() {
            this.list();
        }
    }

</script>

.<style>
    .post .username {
        font-size: 13px;
    }

</style>
