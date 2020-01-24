   <div class="box box-solid">
         <div class="box-header">
             <h3 class="box-title">{{ $task->name }}</h3>
             <div class="pull-right">
                 <div class="status {{ strtolower($task->state_text) }}">{{ $task->state_text }}</div>
             </div>
         </div>
         <div class="box-body">
             <small>{!! $task->description_html !!}</small>
         </div>
         @if(!empty($task->remarks))
         <div class="box-footer">
             <small><strong>Remarks:</strong> {!! $task->remarks_html !!}</small>
         </div>
         @endif
     </div>