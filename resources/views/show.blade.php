@extends('layouts.app')
@section('title', 'Laravel')

@section('content')
    <main class="flex-grow-1">
        <div class="alert alert-info" role="alert">
            @include('flash::message')
            <p>Url has been added</p>
        </div>

        <div class="container-lg">
            <h1 class="mt-5 mb-3"></h1>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap">
                    <tbody><tr>
                        <td>id</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td></td>
                    </tr>
                    </tbody></table>
            </div>

            <h2 class="mt-5 mb-3">Checks</h2>
            <form method="post" action="https://php-l3-page-analyzer.herokuapp.com/domains/285/checks">
                @csrf
                <input type="submit" class="btn btn-primary" value="Run check">
            </form>
            <table class="table table-bordered table-hover text-nowrap">
                <tbody><tr>
                    <th>Id</th>
                    <th>Status Code</th>
                    <th>h1</th>
                    <th>Keywords</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
                </tbody></table>
        </div>
    </main>
@endsection