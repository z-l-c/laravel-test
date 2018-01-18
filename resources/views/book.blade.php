<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/book.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        <book></book>

        <div class="modal fade" tabindex="-1" role="dialog" id="dropModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Operation confirm</h4>
                    </div>
                    <div class="modal-body">
                        <form id="deleteForm" action="{{ route('deleteBook') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="bookid" value="1" />
                            </div>
                            <div class="form-group">
                                <p class="alert alert-danger">Delete this book and it can not be recoverd, are you sure? </p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" id="deleteBook">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/turn.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script>
        //get page data
        function loadPage(page, element) {
        	var div = $('<div />',{
        		'class': 'every-page'
        	});

        	div.html('1234cdsv jhejvnjejnvjdn jscdsnvjnewvjnvdnnvnejknvkwnvnwnknw');

        	element.append(div);
        	element.find('.loader').remove();
        }
        //Add a new page
        function newPage() {
            console.log('new page');
        }
        //delete a book
        function dropBook() {
            $('#dropModal').modal('show');
            $('#deleteBook').click(function(){
                $(this).attr('disabled','disabled');
                $('#deleteForm').ajaxSubmit(function(data){
                    window.parent.callback(data);
                });
                $('#dropModal').modal('hide');
            });
        }

        $('#dropModal').on('show.bs.modal', function(e){
            $('#deleteBook').removeAttr('disabled');
        });
    </script>
</body>
</html>
