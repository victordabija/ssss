{{ $emoji }} {!! trans_choice('message.student', $count, ['value' => $count]) !!}
@if($count > 0)

@foreach($students as $student)
{{ $student->name }} - {{ $student->group }} | <a href="{{ route('students.show', $student) }}">View</a>
@endforeach
@endif
