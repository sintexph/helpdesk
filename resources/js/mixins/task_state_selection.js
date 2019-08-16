 export default {
     data() {
         return {
             select2_task_states: [{
                     id: '',
                     text: '-- CHOOSE --',
                 },
                 {
                     id: State.PENDING,
                     text: 'PENDING',
                 },

                 {
                     id: State.PROCESSING,
                     text: 'PROCESSING',
                 },
                 {
                     id: State.HOLD,
                     text: 'HOLD',
                 },
                 {
                     id: State.COMPLETED,
                     text: 'COMPLETED',
                 },
                 {
                     id: State.CANCELLED,
                     text: 'CANCELLED',
                 }
             ],
             select2_task_urgencies: [

                 {
                     id: '',
                     text: '-- CHOOSE --',
                 },

                 {
                     id: Urgency.LOW,
                     text: 'LOW',
                 },

                 {
                     id: Urgency.NORMAL,
                     text: 'NORMAL',
                 },

                 {
                     id: Urgency.HIGH,
                     text: 'HIGH',
                 },


             ]
         }
     } 
 }
