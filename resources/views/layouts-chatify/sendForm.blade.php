<div class="messenger-sendCard">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf
        <label><span class="fas fa-paperclip"></span><input disabled='disabled' type="file" class="upload-attachment" name="file" accept="image/*, .txt, .rar, .zip" /></label>
        <textarea readonly='readonly' name="message" class="m-send app-scroll" placeholder="Type a message.."></textarea>
        <input type="hidden" name="meeting_key" id="meeting_key" value="">
        <button class="add-meeting"><span class="fas fa-video"></span></button>
        <button disabled='disabled'><span class="fas fa-paper-plane"></span></button>
    </form>
</div>
