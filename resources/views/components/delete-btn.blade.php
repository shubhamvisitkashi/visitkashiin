<form action="{{$route}}" method="POST" style="display:inline">
    @method('Delete')
    @csrf
    <button type="submit" class="btn btn-icon btn-danger btn-sm deleteBtn mb-1" title="Delete"><i data-feather="trash-2"></i></button>
</form>
