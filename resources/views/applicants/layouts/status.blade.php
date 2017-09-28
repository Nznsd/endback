@if (session('status'))
      <br>
      <div class="alert alert-info alert-dismissible show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
            </button>
            {{ session('status') }}
      </div>
@endif