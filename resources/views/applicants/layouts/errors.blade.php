@if($errors->any())
      <div class="alert alert-warning alert-dismissible show" role="alert">
            <ul>
            @foreach($errors->all() as $error)
            <li><button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
            </button>
            {{ $error }}</li>
            @endforeach
            </ul>
      </div>       
@endif