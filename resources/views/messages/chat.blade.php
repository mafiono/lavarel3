



        <!-- Conversations are loaded here -->
        <div id = "messagebox" class="direct-chat-messages">
            <!-- Message. Default to the left -->
            @foreach($messages as $message)
                @if($message->sender_id == Auth::user()->id)
            <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{$message->username}}</span>
                    <span class="direct-chat-timestamp pull-right">{{$message->created_at}}</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="/assets/portal/img/usermessages.png" alt="Message User Image"><!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    @if($message->image)
                    {{header("Content-type: image")}}
                    <a href = {{$message->image}} target="blank"><img src="{{$message->image}}" width = "100px" height = "100px"/>
                    </a>
                    @endif

                    {{$message->text}}
                </div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->
            @else
            <!-- Message to the right -->
            <div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right">Staff</span>
                    <span class="direct-chat-timestamp pull-left">{{$message->created_at}}</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="/assets/portal/img/usermessages.png" alt="Message User Image"><!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    @if($message->image)
                    {{header("Content-type: application/json charset=UTF-8")}}
                    <img src="{{$message->image}}" width="100px" height = "100px"/>
                    @endif
                    {{$message->text}}
                </div>
                <!-- /.direct-chat-text -->
            </div>
            @endif
            @endforeach
            <!-- /.direct-chat-msg -->
        </div>

        <!-- /.direct-chat-pane -->

    <!-- /.box-body -->

    <!-- /.box-footer-->



