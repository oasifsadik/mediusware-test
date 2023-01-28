@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" value="{{ request('title') }}" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-pre pend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                    <tr>
                        <td>{{  ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td>{{ $product->title }}t <br> Created at : 25-Aug-2020</td>
                        <td>@php($pDesc = strip_tags(htmlspecialchars_decode($product->description)))
                            <p>
                                    {!! (strlen($pDesc) > 380) ? substr($pDesc,0,50).'...' : $pDesc; !!}
                            </p></td>
                        <td>
                            @if(!empty($product->variantPrice))
                                @foreach ($product->variantPrice as $price)
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                    <dt class="col-sm-3 pb-0">
                                        @if($price->variantOne)
                                            {{ $price->variantOne->variant }}
                                        @endif
                                        @if($price->variantTwo)
                                            /{{ $price->variantTwo->variant }}
                                        @endif
                                        @if($price->variantThree)
                                            /{{ $price->variantThree->variant }}/
                                        @endif
                                    </dt>
                                    <dd class="col-sm-9">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 pb-0">Price : {{ number_format($price->price,2) }}</dt>
                                            <dd class="col-sm-8 pb-0">InStock : {{ number_format($price->stock,2) }}</dd>
                                        </dl>
                                    </dd>
                                </dl>
                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing 1 to 10 out of {{ $products->total() }}</p>
                    {{ $products->links() }}
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </div>
    </div>

@endsection
