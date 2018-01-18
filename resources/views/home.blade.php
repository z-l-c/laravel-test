@extends('layouts.app')

@section('css')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <div class="row">
        <div class="col-xs-12">

            @if (session('status') == 1)
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">
                    &times;
                </a>
                Success!
            </div>
            @elseif (session('status'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">
                    &times;
                </a>
                {{ session('status') }}
            </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    My Shelf
                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#createModal">+ New</button>
                </div>

                <div class="panel-body shelf-body">

                    @forelse ($data as $key => $d)
                        @if ($key % 4 == 0)
                        <div class="row shelf-row">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-2 text-center">
                                <a href="{!! route('openBook', $d->id) !!}" class="book">
                                    <img src="{!! asset($d->cover) !!}"
                                         onerror="this.src='{{asset('images/noimg.png')}}'" />
                                </a>
                            </div>
                        @else
                            <div class="col-xs-2 text-center">
                                <a href="{!! route('openBook', $d->id) !!}" class="book" bookid="{{ $d->id }}">
                                    <img src="{!! asset($d->cover) !!}"
                                    onerror="this.src='{{asset('images/noimg.png')}}'" />
                                </a>
                            </div>
                        @endif

                        @if ($key % 4 == 3 || ($key + 1) == count($data))
                            <div class="col-xs-2"></div>
                            <div class="shelf-img"></div>
                        </div>
                        @endif
                    @empty
                        <p>Sorry, you don't have books for now.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="createModalLabel">New Book</h4>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="{{ route('createBook') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="book-name" class="control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="book-name" value="" />
                        </div>
                        <div class="form-group">
                            <div><label for="book-cover" class="control-label">Cover:</label></div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <!-- croppa -->
                                    <croppa v-model="croppa"
                                            :width="150"
                                            :height="194"
                                            :quality="1"
                                            :file-size-limit="102400"
                                            :zoom-speed="5"
                                            accept=".jpeg,.png,.jpg"
                                            remove-button-color="black"
                                            placeholder="choose an image"
                                            :placeholder-font-size="14">
                                    </croppa>
                                </div>
                                <div class="col-xs-4 img-generate">
                                    <button type="button"
                                            class="btn btn-info"
                                            @click="dataUrl = croppa.generateDataUrl()">
                                            Generate
                                    </button>
                                </div>
                                <div class="col-xs-4 img-preview">
                                    <img :src="dataUrl" onerror="this.src='{{asset('images/noimg.png')}}'" />
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="cover" :value="dataUrl" />
                        </div>
                        <div class="form-group">
                            <label for="book-brief" class="control-label">Brief:</label>
                            <textarea class="form-control" id="book-brief" name="brief" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBook">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
$(function(){
    $('.book').colorbox({
        innerWidth: 1045 + 10,
        innerHeight: 600 + 10,
        href: $(this).attr('href'),
        iframe: true,
        scrolling: false,
    });

    $('#saveBook').click(function(){
        var name = $('#book-name').val();
        if($.trim(name) == ''){
            $('#book-name').focus();
            return false;
        }
        $(this).attr('disabled','disabled');
        $("#createForm").submit();
        $('#createModal').modal('hide');
    })

    $('#createModal').on('hidden.bs.modal', function(e){
        document.getElementById("createForm").reset();
    });
    $('#createModal').on('show.bs.modal', function(e){
        $('#saveBook').removeAttr('disabled');
    });
})

function callback(data)
{
    if(data == 1){
        $.colorbox.close();
        alertify.success('Success!');
        window.location.reload();
    }else{
        alertify.error('Failure!');
    }
}
</script>
@endsection
