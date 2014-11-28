<div class="page-header">
    <h1>Playlist Seeds</h1>
</div>


<div class="row">
    <div class="col-md-6">

        <table class="table table-bordered table-hover">
            <tr class="bg-info">
                <td>PlayList Seed</td>
                <td width="20%" class="text-center">Actions</td>
            </tr>

            @foreach ($seeds as $seed)

            <tr>
                <td><a href="/playlist_seed_generate/{{ $seed->id }}" class="btn btn-success btn-block">{{ $seed->name }}</a></td>
                <td class="text-center"><a href="/playlist_seed_edit/{{ $seed->id }}" class="btn btn-warning btn-block">Edit</a></td>
            </tr>

            @endforeach

        </table>

    </div>
</div>
