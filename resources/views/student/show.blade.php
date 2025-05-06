@if(!is_null($student->content))
    {!! $student->content !!}
@else
    <b>The content is not parsed yet.</b>
@endif

