@extends('adminlte::page')

@section('title','Register Courses')
    
@section('content_header')
    <h1 class="m-0 text-dark">Register Courses for this semester</h1>
@endsection
@section('content')
    <table class="table dataTable text-center">
        <thead>
            <th>#</th>
            <th>Course code</th>
            <th>Course Name</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach ($courses as $index=>$course)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $course->code }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->department->name }}</td>
                    <td>{{ ($course->semester == 1)? "First" : "Second" }}</td>
                    <td>
                        <form action=""></form>
                        <a href="#" class="btn-primary btn-sm btn fa">Register</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection