@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
    <style>
        td .fa-check{
            color:green;
        }
        td .fa-times{
            color:red;
        }
    </style>
@endpush
<style>
div.gallery {
  border: 1px solid #ccc;
}

div.gallery:hover {
  border: 1px solid #777;
}

div.gallery img {
  width: 100%;
  height: auto;
}

div.desc {
  padding: 15px;
  text-align: center;
}

* {
  box-sizing: border-box;
}

.responsive {
  padding: 0 6px;
  float: left;
  width: 24.99999%;
}

@media only screen and (max-width: 700px) {
  .responsive {
    width: 49.99999%;
    margin: 6px 0;
  }
}

@media only screen and (max-width: 500px) {
  .responsive {
    width: 100%;
  }
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}
</style>
@section('filter-section')

    <x-filters.filter-box>
        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex py-1 pr-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                        placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

    </x-filters.filter-box>

@endsection

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-flex">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <x-forms.link-primary :link="route('superadmin.softwares.create')" class="mr-3 float-left openRightModal"
                    icon="plus">
                    @lang('app.add')
                    @lang('app.new')
                    @lang('superadmin.softwareCategory')
                </x-forms.link-primary>
            </div>
        </div>

        <!-- Add Task Export Buttons End -->
        <!-- Task Box Start -->
        @foreach($categories as $category)
        <h6>{{ $category->name }}</h6>
        @if ($category->softwares)
            @foreach($category->softwares as $software)
            <div class="responsive">
                <div class="gallery">
                    <a target="_blank" href="http://localhost:8000/user-uploads/app-logo/2e7768b71d3f83f22c878f1683b1d641.png">
                        <img src="http://localhost:8000/user-uploads/app-logo/2e7768b71d3f83f22c878f1683b1d641.png" alt="Cinque Terre" width="600" height="400">
                    </a>
                </div>
                <div class="desc">
                    <div id="table-actions" class="flex-grow-1 align-items-center">
                        <a href  class="btn btn-primary">Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
@endforeach

        <!-- Task Box End -->
    <!-- CONTENT WRAPPER END -->

@endsection