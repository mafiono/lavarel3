


<div class="box box-success direct-chat direct-chat-success">
    <div class="box-header with-border">
        <h3 class="box-title">Staff Messages</h3>


    </div>
    <!-- /.box-header -->
    <div    class="box-body">
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
                    {{header("Content-type: image/jpeg")}}
                    <img src="{{$message->image}}"/>
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
    </div>
    <!-- /.box-body -->
    <div class="box-footer" >
        <form action="/sendmessage" method="post" enctype="multipart/form-data">
            {!! Form::token() !!}
            <input type="hidden" name="id" value={{Auth::user()->id}}>
                <div class="input-group" style="width: 300px">
                        <input type="text" name="message" placeholder="Type Message ..." class="form-control" style="height: 34px">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-flat">Send</button>
                    </span>
                </div>
                <div class="input-group">
                    <input  type="file" class="filestyle" name="image" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText="Upload Image" style="height: 34px; margin-top: 10px; padding: 5px 20px;">
                </div>
        </form>
    </div>
    <!-- /.box-footer-->
</div>

<script>

</script>
