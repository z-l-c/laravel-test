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
    <link href="{{ asset('css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertify.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertify.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simditor.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        <book></book>

        <div class="modal fade bs-example-modal-lg" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="createModalLabel">New Page</h4>
                    </div>
                    <div class="modal-body">
                        <form id="createForm" action="{{ route('createPage') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="hidden" value="{{ $data->id }}" name="book_id" />
                                <input type="hidden" value="" name="id" />
                                <label for="page-date" class="control-label">Date:</label>
                                <input type="text" class="form-control" name="date" id="page-date" value="{{ date('Y-m-d') }}" readonly />
                            </div>
                            <div class="form-group">
                                <div><label for="page-weather" class="control-label">Weather:</label></div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="weather" value="sunny" checked>
                                        <img src="{{ asset('images/sunny.png') }}" width="25" />
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="weather" value="cloudy">
                                        <img src="{{ asset('images/cloudy.png') }}" width="25" />
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="weather" value="rain">
                                        <img src="{{ asset('images/rain.png') }}" width="25" />
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="weather" value="snow">
                                        <img src="{{ asset('images/snow.png') }}" width="25" />
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="page-content" class="control-label">Content:</label>
                                <textarea class="form-control" id="page-content" name="content" rows="4"></textarea>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="savePage">Save</button>
                    </div>
                </div>
            </div>
        </div>

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
                                <input type="hidden" class="form-control" name="bookid" value="{{ $data->id }}" />
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
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{ asset('js/module.js') }}"></script>
    <script src="{{ asset('js/hotkeys.js') }}"></script>
    <script src="{{ asset('js/uploader.js') }}"></script>
    <script src="{{ asset('js/simditor.js') }}"></script>
    <script>
        alertify.set('notifier','position', 'top-center');
        alertify.set('notifier','delay', 6);
        var maxPage = 100;

        $('#page-date').datetimepicker({
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format: 'yyyy-mm-dd',
        });
        var edit = new Simditor({
            textarea: $('#page-content'),
            imageButton: 'upload',
            toolbar: ['title','bold','italic','fontScale','color','ol'
                    ,'ul','blockquote','table','image','hr','indent','outdent','alignment'
            ],
            upload: {
                url: '{{ route('uploadImage') }}',
                params: { _token: '{{ csrf_token() }}' },
                fileKey: 'upload_file',
                connectionCount: 3,
                leaveConfirm: 'Uploading is in progress',
            },
            pasteImage: true,
        });

        //get page data
        function loadPage(page, element)
        {
        	var div = $('<div />',{
        		'class': 'every-page'
        	});

            if(page < maxPage){
                $.post("{{ route('bookPages') }}",{
                    'bookid' : '{{ $data->id }}',
                    'page' : page,
                    '_token' : '{{ csrf_token() }}'
                }, function(data){
                    if(data != 'null'){
                        div.html(generatePage(data));
                    }else{
                        maxPage = page;
                        div.html('');
                    }
                    element.append(div);
                    bindEdit(element, data);
                    element.find('.loader').remove();
                    element.find(".page-content").mCustomScrollbar({
    					scrollInertia:600,
    					autoDraggerLength:false
    				});
                });
            }else{
                element.find('.loader').remove();
            }
        }

        //generate page content
        function generatePage(data)
        {
            var week = new Array('Sun.', 'Mon.', 'Tue.', 'Wed.', 'Thur.', 'Fri.', 'Sat.');
            var rawData = eval('(' + data + ')');
            var date = new Date(rawData.date);
            var html = '<div class="page-datebar">';
                html += '<div class="page-date">' + rawData.date + ' ' + week[date.getDay()] + '</div>';
                html += "<div class='page-weather'><img src='{!! asset('images/" + rawData.weather + ".png') !!}' /></div>";
                html += '</div>';
                html += '<div class="page-edit">';
                html += '<a title="edit" class="pageEdit"><img src="{{ asset('images/edit.png') }}" /></a>';
                html += '</div>';
                html += '<div class="page-content content_3">';
                html += rawData.content;
                html += '</div>';

            return html;
        }

        //bind the edit btn of a page
        function bindEdit(pageObj, data)
        {
            var editbtn = pageObj.find('.pageEdit');
            editbtn.click(function(){
                $('#createModal').modal('show');
                var rawData = eval('(' + data + ')');
                console.log(rawData);
                $('#createForm').find('input[name=id]').val(rawData.id);
                $('#createForm').find('input[name=date]').val(rawData.date);
                $('#page-date').datetimepicker('update');
                $('#createForm').find('input[name=weather]').each(function(){
                    $(this).removeAttr('checked');
                    if($(this).val() == rawData.weather){
                        $(this).attr('checked',true);
                    }
                });
                edit.setValue(rawData.content);
            });
        }

        //Add a new page
        function newPage()
        {
            $('#createModal').modal('show');
        }

        $('#savePage').click(function(){
            var content = edit.getValue();
            if($.trim(content) == ''){
                alertify.notify('Input the content');
                edit.focus();
                return false;
            }
            $(this).attr('disabled','disabled');
            $("#createForm").ajaxSubmit({
                success: function(responseText, statusText){
                    if(responseText.success == 1){
                        alertify.success(responseText.msg);
                        $('#createModal').modal('hide');
                        window.location.reload();
                    }else{
                        alertify.error(responseText.msg);
                        setTimeout(function () {
                            $('#savePage').removeAttr('disabled');
                        }, 6000);
                    }
                },
                dataType: 'json',
            });
        })
        $('#createModal').on('hidden.bs.modal', function(e){
            document.getElementById("createForm").reset();
        });
        $('#createModal').on('show.bs.modal', function(e){
            $('#savePage').removeAttr('disabled');
        });

        //delete a book
        function dropBook()
        {
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
