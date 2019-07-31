<template>
    <ul>
        <li v-for="(field,key) in fields" style="font-weight:800;" :key="key">
            <a href="#" v-if="field.type!=='Set'" @click.prevent="insert_field(field.id)">{{ field.data.label }}</a>
            <template v-else>
                <a href="#" @click.prevent="generate_loop(field.id)">{{ field.data.label }}</a>
                <template v-for="(item,keyItem) in field.data.items">
                    <click-dn-fields :key="keyItem" @generate="insert_set" :parent_id="field.id" :fields="item.fields">
                    </click-dn-fields>
                </template>
            </template>
        </li>
        <li v-if="parent_id!==null"><a href="#" class="text-yellow" @click.prevent="insert_field('index_key')">INDEX KEY</a></li>
    </ul>
</template>
<script>
    export default {
        props: {
            fields: {
                require: true,
                default () {
                    return [];
                }
            },
            parent_id: {
                default () {
                    return null;
                }
            },

        },
        methods: {
            insert_field(id) {
                if (this.parent_id !== null) {
                    this.$emit('generate', this.start_tag + '{' + this.parent_id + '}{' + id + '}' + this.end_tag);
                } else
                    this.$emit('generate', this.start_tag + id + this.end_tag);
            },
            insert_set(id) {
                this.$emit('generate', id);
            },
            generate_loop(id) {

                var start_loop = this.start_tag + 'loop_' + id + this.end_tag;
                var end_loop = this.start_tag + 'end_loop_' + id + this.end_tag;

                this.$emit('generate', start_loop + '\n' + end_loop);
            },
        }
    }

</script>
