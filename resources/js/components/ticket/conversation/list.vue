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

            <template v-if="value.attachments.length!==0">
            <p><strong>Attachments</strong></p>
            <table>
                <tbody>
                    <tr v-for="(attachment,attkey) in value.attachments" :key="attkey">
                        <td style="padding-right:5px;">
                            <a :href="attachment.file_upload.url" v-text="attachment.file_upload.file_name"></a>
                        </td>
                        <td style="padding-left:5px;" v-text="attachment.file_upload.file_size"></td>
                    </tr>
                </tbody>
            </table>
            </template>
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
