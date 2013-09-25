@extends('ProductCatalog::layouts.interface-single')

@section('title')
    Manage Categories
@stop

@section('content')
    <h1>Category Management</h1>

    <!-- Our Messaging Partial -->
    @include('ProductCatalog::partials.messaging')

    <a class="add-new-object-button btn btn-primary pull-right" href="{{ url('manage/categories/new') }}"><span class="icon-plus icon-white"></span> New Category</a>

    <!-- If we have data then lets create a table and loop through that data to build it -->
    @if( !$categories->isEmpty() )
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Products</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                <!-- Start That Loopin' -->
                @foreach($categories as $category)
                    <tr class='highlighted'>
                        <td>
                            @if( $category->getThumbnailImage() )
                                <a href="{{ url('manage/categories/edit/'.$category->id) }}">
                                    <img src="{{ $category->getThumbnailImage()->sizeImg( 100 , 60 , false ) }}" />
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('manage/categories/edit/'.$category->id) }}">{{ $category->name }}</a>
                        </td>
                        <td>
                            <a href="{{ url('manage/categories/edit/'.$category->id) }}">{{ $category->url }}</a>
                        </td>
                        <td>
                            <a href="{{ url('manage/categories/edit/'.$category->id) }}">{{ count( $category->getAllProductsIncludingChildren() ) }}</a>
                        </td>
                        <td>
                            <a href="{{ url('manage/categories/edit/'.$category->id) }}">{{ $category->enabled }}</a>
                        </td>
                    </tr>
                    @if ( $category->children()->count() > 0 )
                        @foreach( $category->children()->get() as $child )
                            <tr>
                                <td>
                                    @if( $child->getThumbnailImage() )
                                        <a href="{{ url('manage/categories/edit/'.$child->id) }}">
                                            <img src="{{ $child->getThumbnailImage()->sizeImg( 100 , 60 , false ) }}" />
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('manage/categories/edit/'.$child->id) }}">{{ $child->name }}</a>
                                </td>
                                <td>
                                    <a href="{{ url('manage/categories/edit/'.$child->id) }}">{{ $child->url }}</a>
                                </td>
                                <td>
                                    <a href="{{ url('manage/categories/edit/'.$child->id) }}">{{ count( $child->getAllProductsIncludingChildren() ) }}</a>
                                </td>
                                <td>
                                    <a href="{{ url('manage/categories/edit/'.$child->id) }}">{{ $child->enabled }}</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                
            </tbody>
        </table>
    @endif
@stop