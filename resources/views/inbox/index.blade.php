@extends('layouts.top.app')

@section('menu-3','active')

@section('content')

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Conversations</h3>
        <div class="box-tools pull-right">
            <div class="has-feedback">
                <input type="text" class="form-control input-sm" placeholder="Search Conversation">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>
    </div>
    <div class="box-body no-padding" style="min-height:450px;">

        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <tbody>
                    @foreach($ticket_conversations as $conversation)
                    <tr>

                        <td class="fit"><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></td>
                        <td class="mailbox-name fit"><a href="{{ route('inbox.view',$conversation->id) }}"
                                target="_blank"># {{ $conversation->control_number }}</a></td>
                        <td class="mailbox-subject"><b>{{ $conversation->title }}</b>
                        <div class="text-primary"><small>{{ $conversation->sender_name }}</small></div>
                        </td>
                        <td class="mailbox-attachment"></td>
                        <td class="mailbox-date fit">{{ $conversation->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($ticket_conversations->count()==0)
        <h3 class="text-gray text-center">Sorry!
            <br>
            <small>There are no available message to be displayed.</small>
        </h3>
        @endif

    </div>
    <div class="box-footer no-padding">
        <div class="mailbox-controls">
            <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
            </div>
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            {{ $ticket_conversations->links() }}

            <div class="pull-right">
                {{ $ticket_conversations->currentPage() }}-{{ $ticket_conversations->count() }}/{{ $ticket_conversations->total()  }}
                <div class="btn-group">
                    <a type="button" href="{{ $ticket_conversations->previousPageUrl() }}"
                        class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a type="button" href="{{ $ticket_conversations->nextPageUrl() }}" class="btn btn-default btn-sm"><i
                            class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
