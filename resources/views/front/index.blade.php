@extends('front.layout')

@section('main')
    <section id="bricks" class="front-content">
        <div class="row masonry">
            <div class="content-header">
                <p class="fs-28 font-bold inline-block">Ongoing repairs</p>
                <input type = "checkbox" id="filter_status" style="width: 2.5rem;" onclick = "filter_status()">Closed Repairs
                <button class="button-primary button-yellow media-float-right" data-toggle="modal" data-target="#create_repair_modal">New Repair</button>
            </div>
            <div class="bricks-wrapper">
                <table class="table">
                    <thead>
                        <th style = "width: 10%;">Repair ID</th>
                        <th style = "width: 15%;">Product</th>
                        <th style = "width: 15%;">Serial IMEI</th>
                        <th style = "width: 40%;">Problem</th>
                        <th style = "width: 20%;">Status</th>
                    </thead>
                    <tbody>
                    @if (strlen($posts) == 2)
                        <tr><td colspan = "5" style="text-align:center;border-radius:6px;">No Data</td></tr>
                    @endif
                    @foreach($posts as $post)
                        <tr>
                            <td class="table_status" style="display: none">{{$post->status}}</td>
                            <td class="text-light-yellow">{{$post->repair_id}}</td>
                            <td>{{$post->title}}</td>
                            <td class="text-gray">{{$post->imei}}</td>
                            <td class="text-gray text-problem">
                                @foreach(explode(",",$post->problem) as $item)
                                    <span>{{$item}}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($post->status == 0)
                                    <div class="point-div status-to-received"></div>
                                    To be received
                                @elseif ($post->status == 1)
                                    <div class="point-div status-received"></div>
                                    Received
                                @elseif ($post->status == 2)
                                    <div class="point-div status-waiting"></div>
                                    Waiting for parts
                                @elseif ($post->status == 3)
                                    <div class="point-div status-progress"></div>
                                    In Progress
                                @elseif ($post->status == 4)
                                    <div class="point-div status-done"></div>
                                    Done
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> <!-- end row -->
    </section> <!-- end bricks -->

    <div class="modal fade" id="create_repair_modal" role="dialog">
        <div class="modal-dialog repair-modal">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="repair-form-div">
                        <label class="margin-0 dark-color">Model</label>
                        <input id="title" type="text" class="full-width" name="title" required autofocus>
                    </div>
                    <div class="repair-form-div full-width-on-mobile">
                        <label class="margin-0 dark-color">Serial Number</label>
                        <input id="imei" type="text" class="full-width" name="imei" required>
                    </div>
                    <div class="full-width-on-mobile">
                        <label class="margin-0 dark-color">Problem</label>
                        <input id="problem" type="text" class="full-width" name="problem" value="Batterij,LCD,Speakers,Wi-Fi,Simkaart,Simlock,FRP,iCloud" data-role="tagsinput"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="button button-submit" onclick="create_repair()">Submit</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="success_repair_modal" role="dialog">
        <div class="modal-dialog repair-modal">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="text-center fs-18 font-bold">Repair Submitted</p>
                    <p class="text-center fs-13 font-bold">Repair ID</p>
                    <p id="repair_id" class="text-center fs-32 font-bold text-light-yellow"></p>
                    <p class="success-p">Model</p>
                    <p id="success_title" class="success-result"></p>
                    <p class="success-p">Serial Number</p>
                    <p id="success_imei" class="success-result"></p>
                    <p class="success-p">Problem</p>
                    <div id="success_problem"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-submit" onclick = "pdf_download()">Print label</button>
                <button type="button" class="button button-dark" data-dismiss="modal">Go to Dashboard</button>
            </div>
        </div>
    </div>
    <form target="_blank" action="{{ route('post.download') }}" method="post" id="pdf_download_form">
        <textarea id="content" name="content" style="display: none;"></textarea>
        <input type = "hidden" name="_token" value = "{{ csrf_token() }}">
    </form>

    <script>
        var repair_id = "";
        function create_repair(){
            var data = {};
            data.title = $("#title").val();
            data.imei = $("#imei").val();
            data.problem = $("#problem").val();
            data._token = "{{ csrf_token() }}";
            $.ajax({
                method: 'post',
                url: "{{ route('post.create') }}",
                data: data,
            })
            .done(function (data) {
                repair_id = data.repair_id;
                $("#repair_id").html(data.repair_id);
                $("#success_title").html($("#title").val());
                $("#success_imei").html($("#imei").val());
                var problem_array = $("#problem").tagsinput('items');
                var html = "";
                for (var i=0;i<problem_array.length;i++){
                    html += "<span>"+problem_array[i]+"</span>";
                }
                $("#success_problem").html(html);
                $("#create_repair_modal").modal("hide");
                $("#success_repair_modal").modal();
            })
            .fail(function(data) {
                var errors = data.responseJSON
                alert("Fail");
            });
        }
        
        function pdf_download(){
            var pdf_html = '<html><head>';
            pdf_html   +=  '<style>';
            // pdf_html   +=  '.tableWithOuterBorder{border: 0px solid black;border-collapse: separate;border-spacing: 0;}.repair_submitted{text-align:center;font-size:18px;font-weight:bold;}.repair_id_p{text-align:center;font-size:13px;font-weight:bold;}.repair_id{text-align:center;font-size:32px;font-weight:bold;color: #fb9c0e;}.success-p{padding-top: 1rem;font-size: 13px;font-weight: bold;}.success-result{font-size: 13px;line-height: 13px;padding-left: 1.5rem;}</style>';
            pdf_html   +=  '</style>';
            pdf_html   +=  '</head><body>';
            // pdf_html   +=  '<table class="tableWithOuterBorder"><tr style = "padding:0px;"><td class="img-td"><img src = "/images/serial1.jpg" width = "54" height = "7"></td></tr><tr style = "padding:0px;"><td class="img-td" style = "padding-right:20px;"><span style = "font-size:5px;">reparatie #'+repair_id+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr><tr style = "padding:0px;"><td class="img-td"><img src = "/images/serial2.jpg" width = "54" height = "18"></td></tr><tr><td class = "repair_submitted">Repair Submitted</td></tr><tr><td class = "repair_id_p">Repair ID</td></tr><tr><td class = "repair_id">'+repair_id+'</td></tr><tr><td class = "success-p">Model</td></tr><tr><td class = "success-result">'+$("#title").val()+'</td></tr><tr><td class = "success-p">Serial Number</td></tr><tr><td class = "success-result">'+$("#imei").val()+'</td></tr><tr><td class = "success-p">Problem</td></tr><tr><td class = "success-result">'+$("#problem").val()+'</td></tr></table>';
            pdf_html   +=  '<table class="tableWithOuterBorder"><tr style = "padding:0px;"><td class="img-td"><img src = "/images/serial1.jpg" width = "70" height = "10"></td></tr><tr><td class="img-td"><span style = "font-size:2px;">reparatie #'+repair_id+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr><tr><td class="img-td"><img src = "/images/serial2.jpg" width = "70" height = "10"></td></tr></table>';
            pdf_html   +=  '</body></html>';
            $("#content").val(repair_id);
            $("#pdf_download_form").submit();
            $("#success_repair_modal").modal("hide");
        }

        function filter_status(){
            if ($('#filter_status').prop("checked") == true){
                $('.table > tbody > tr > .table_status').each(function(index, value){
                    console.log($(this).html());
                    if ($(this).html() != '4'){
                        $(this).closest("tr").hide();
                    }
                });
            }else{
                $(".table > tbody > tr").show();
            }
        }
    </script>
@endsection
