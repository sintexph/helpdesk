<template>
    <modal :prevent="true" name="calendar-task" :extended_width="true" ref="modal">
        <template slot="header">Task Calendar</template>
        <template slot="body">
           <div style="min-height:350px;height:550px;">
               <task-calendar ref="taskCalendar"></task-calendar>
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
                this.$refs.taskCalendar.load_list();
                this.$refs.modal.show();
            },

 
        }, 
    }

</script>
 