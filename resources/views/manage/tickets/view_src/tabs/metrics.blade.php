<div class="box box-solid">
    <div class=" box-header with-border">
        <h3 class="box-title">Metrics</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Target</th>
                    <th>Completed Date</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->targets as $target)
                <tr>
                    <td>
                        <span class="status {{ strtolower($target->target_text) }}">{{ $target->target_text }}</span>
                    </td>

                    <td>{{ $target->completed_date }}</td>
                    <td>
                        @if($target->assignee!=null)
                        {{ $target->assignee->name }}
                        @endif
                    </td>
                    <td>{{ $target->due_date }}</td>
                    <td>
                        <span class="ht-{{ strtolower($target->hit_target) }}">{{ $target->hit_target }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
