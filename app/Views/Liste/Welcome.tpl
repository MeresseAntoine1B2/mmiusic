<div class="page-header">
    <h1>{{ $title }}</h1>
</div>


<p>{{ $welcomeMessage }}</p>

<?php

?>

<a class="btn btn-md btn-success" href="{{ site_url('subpage') }}">
    {{ __('Open subpage') }}
</a>

<div class="playlist">
    <h2>Mes playlists</h2>
    {{ $mesPlaylists }}
</div>
