@if(app('request')->input('view')=='kanban' || !app('request')->input('view'))
<kanban-tasks ref="kanbanTask" project_id="{{ $project->id }}"></kanban-tasks>
@elseif(app('request')->input('view')=='table')
<task-list ref="taskList" project_id="{{ $project->id }}"></task-list>
@endif
