  <table border="1" style="width:100%;">
      <thead>
          <tr>
              <th style="padding:5px;" colspan="3">
                  <center>Progress Update</center>
              </th>
          </tr>
          <tr>
              <th style="padding:5px;">Progress</th>
              <th style="padding:5px;">Description</th>
              <th style="padding:5px;">Date</th>
          </tr>
      </thead>
      <tbody>
          @foreach($ticket->state_progress()->orderBy('created_at','desc')->get() as $progress)
          <tr>
              <td style="padding:5px;">{{ $progress->state_text }}</td>
              <td style="padding:5px;">{!! nl2br($progress->content) !!}</td>
              <td style="padding:5px;">{{ $progress->created_at }}</td>
          </tr>
          @endforeach
      </tbody>
  </table>
