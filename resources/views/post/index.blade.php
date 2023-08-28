@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <caption>Table of all uploaded posts</caption>
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <th scope="row">{{ $post->id }}</th>
                            <td>{{ $post->title }}</td>
                            <td>{{ trim_characters($post->content) }}</td>
                            <td><a href="#">Edit</a> <a href="#">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
