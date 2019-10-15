<div class="box box-solid">

<div class="box-header">
    <h3 class="box-title"><i class="fa fa-paperclip" aria-hidden="true"></i> Attachments</h3>
</div>
    <div class="box-body">

        @if(count($project->attachments)!=0)
        <div class="table-responsive">
            <table class="table">
            
                <tbody>
                    @foreach($project->attachments as $attachment)
                    <tr>
                        <td><a href="{{ $attachment->file_upload->url }}">{{ $attachment->file_upload->file_name }}</a>
                        </td>
                        <td>{{ $attachment->file_upload->file_size }}</td>

                        <td class="fit"><a href="{{ $attachment->file_upload->url }}"><i aria-hidden="true"
                                    class="fa fa-download"></i> Download</a>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
       <p class="text-center">There are no attachments to be displayed</p>
        @endif


    </div>

</div>
