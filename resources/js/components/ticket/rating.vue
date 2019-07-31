<template>
    <div>
        <template v-if="rating!==null">
            <i v-for="index in rating" :key="index+'yellow'" :style="size_style" class="fa fa-star text-yellow"
                aria-hidden="true"></i>
            <i v-for="index in (5-rating)" :key="index+'gray'" :style="size_style" class="fa fa-star text-gray"
                aria-hidden="true"></i>
        </template>
        <template v-else>
            <i :style="size_style" class="fa fa-star text-gray" aria-hidden="true"></i>
            <i :style="size_style" class="fa fa-star text-gray" aria-hidden="true"></i>
            <i :style="size_style" class="fa fa-star text-gray" aria-hidden="true"></i>
            <i :style="size_style" class="fa fa-star text-gray" aria-hidden="true"></i>
            <i :style="size_style" class="fa fa-star text-gray" aria-hidden="true"></i>
        </template>
        <p v-if="feedback!==null">{{ feedback }}</p>
        <p v-else>No feedback</p>
    </div>
</template>
<script>
    export default {
        props: {
            size: {
                default () {
                    return '35px';
                }
            },
            rating: {
                default () {
                    return null;
                }
            },
            feedback: {
                required: true,
                default () {
                    return null;
                }
            }
        },
        computed: {
            size_style() {
                return 'font-size:' + this.size + ';';
            }
        }
    }

</script>
