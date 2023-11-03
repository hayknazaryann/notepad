@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard">
        <div class="row justify-content-center">
            <div class="col-lg-10 m-auto">
                <div class="card pt-2">
                    <div class="card-body">
                        <h5 class="card-title">Notes</h5>

                    </div>
                </div>
                <div class="card pt-4">
                    <div class="card-body">
                        <div class="table-responsive" id="data-list">
                            @include('admin.notes.partials.notes-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
