@foreach($posts as $post)
    <tr>
        <td>{{ $post->name }}</td>
        <td>{{ $post->title }}</td>
        <td>{{ $post->imei }}</td>
        <td>{{ $post->problem }}</td>
        <td>{{ $post->note }}</td>
        <td>{{ $post->created_at->formatLocalized('%c') }}</td>
        <td>
            <select id="repair_status" class="form-control" onchange="change_status('{{$post->repair_id}}')">
                <option value="0" @if ($post->status == 0) selected @endif>To be received</option>
                <option value="1" @if ($post->status == 1) selected @endif>Received</option>
                <option value="2" @if ($post->status == 2) selected @endif>Waiting for parts</option>
                <option value="3" @if ($post->status == 3) selected @endif>In Progress</option>
                <option value="4" @if ($post->status == 4) selected @endif>Done</option>
            </select>
        </td>
        <td>{{ $post->repair_id }}</td>
        <td><a class="btn btn-success btn-xs btn-block" onclick = "show_modal('{{$post->repair_id}}')" role="button" title="@lang('Note')"><span class="fa fa-edit"></span></a></td>
        <!-- <td><a class="btn btn-warning btn-xs btn-block" href="{{ route('posts.edit', [$post->id]) }}" role="button" title="@lang('Edit')"><span class="fa fa-edit"></span></a></td> -->
        <td><a class="btn btn-danger btn-xs btn-block" href="{{ route('posts.destroy', [$post->id]) }}" role="button" title="@lang('Destroy')"><span class="fa fa-remove"></span></a></td>
    </tr>

    <div class="modal fade in" id="note_modal">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Please note.</h4>
            </div>
            <div class="modal-body">
                <textarea name="note" id="note" cols="70" rows="7"></textarea>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="write_note()">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script>
        var id = 0;
        function change_status(id){
            var data = {};
            data.id = id;
            data.status = $("#repair_status").val();
            data._token = "{{ csrf_token() }}";
            $.ajax({
                method: 'post',
                url: "{{ route('post.change_status') }}",
                data: data,
            })
            .done(function (data) {
                alert("Success");
            })
            .fail(function(data) {
                var errors = data.responseJSON
                alert("Fail");
            });
        }

        function show_modal(val){
            id = val;
            $("#note_modal").modal();
        }

        function write_note(){
            var data = {};
            data.id = id;
            data.note = $("#note").val();
            $.ajax({
                method: 'post',
                url: "{{ route('post.note') }}",
                data: data,
            })
            .done(function (data) {
                $("#note_modal").modal("hide");
            })
            .fail(function(data) {
                var errors = data.responseJSON
                alert("Fail");
            });
        }
    </script>
@endforeach

