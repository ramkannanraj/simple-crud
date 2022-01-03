@extends('layouts.app')
@push('scripts')
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@endpush

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
 <div class="container">
        <h3>Products</h3>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @foreach($errors->all() as $error)
            <div class="alert alert-success">
                <p>{{ $error }}</p>
            </div>
        @endforeach
        <button style="margin: 5px;" class="btn btn-danger btn-sm deleteAll" data-url="">Delete</button>
        <a class="btn btn-primary btn-sm float-end" href="{{ url('products/create')}}">Add Product</a>
        <table class="table table-bordered">
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>No</th>
                <th>Name</th>
                <th>UPC</th>
                <th>Price</th>
                <th width="100px">Action</th>
            </tr>
            @if($products->count())
                @foreach($products as $key => $product)
                <tr id="tr_{{$product->id}}">
                    <td><input type="checkbox" class="checkbox" data-id="{{$product->id}}"></td>
                    <td>{{ ++$key }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->upc }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                    <!-- <form  method="post"  action="{{ route('products.destroy', $product->id) }}" style="display:inline;" >
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm deleteAll" data-toggle="confirmation" type="submit">Delete</button>
                    </form> -->
                    <a href="{{ route('products.edit', $product->id) }}" class=" btn btn-primary btn-sm float-end" style="display:inline;">edit</a>
                     
                    </td>
                </tr>
                @endforeach
            @endif
        </table>
    </div>
<script type="text/javascript">
$(document).ready(function () {
    $('#checkAll').on('click', function(e) {
        if ($(this).is(':checked',true))  
        {
            $(".checkbox").prop('checked', true);
        } else {
            $(".checkbox").prop('checked',false);
        }
    });

    $('.checkbox').on('click',function() {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#checkAll').prop('checked',true);
        } else {
            $('#checkAll').prop('checked',false);
        }
    });

    $('.deleteAll').on('click', function(e) {
        var idsArr = [];  
        $(".checkbox:checked").each(function() {  
            idsArr.push($(this).attr('data-id'));
        });

        if (idsArr.length <=0)  
        {  
            alert("Please select atleast one record to delete.");  
        }  else {
            if(confirm("Are you sure, you want to delete the selected row?")){
                var strIds = idsArr.join(","); 
                $.ajax({
                    url: "{{ route('products.multiple-delete') }}",
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids='+strIds,
                    success: function (data) {
                        if (data['status']==true) {
                            $(".checkbox:checked").each(function() {  
                                $(this).parents("tr").remove();
                            });
                            alert(data['message']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });
            }  
        }  
    });

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function (event, element) {
            element.closest('form').submit();
        }
    });   
});
</script>
@endsection