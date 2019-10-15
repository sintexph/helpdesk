<template>
    <modal :prevent="true" name="calendar-project" :extended_width="true" ref="modal">
        <template slot="header">Project Calendar</template>
        <template slot="body">
           <div style="min-height:350px;height:550px;">
               <project-calendar ref="projectCalendar"></project-calendar>
           </div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    export default {
      
        methods: {
            show: function () {
                this.$refs.projectCalendar.load_list();
                this.$refs.modal.show();
            },

 
        }, 
    }

</script>
 