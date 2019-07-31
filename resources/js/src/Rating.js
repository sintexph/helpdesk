export default {



    methods: {
        generate_rating: function (rating) {
            var display = ``;

            for (let index = 0; index < rating; index++) {
                display += `<i class="fa fa-star text-yellow" aria-hidden="true"></i>`;
            }

            for (let index = 0; index < (5 - rating); index++) {
                display += `<i class="fa fa-star text-gray" aria-hidden="true"></i>`;
            }

            return display;
        }

    },


}
