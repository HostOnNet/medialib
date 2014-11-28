<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('title')</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script language="JavaScript" type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="/css/style.css" />
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/bootstrap-theme.min.css" rel="stylesheet">

</head>
<body>

<div class="container-fluid">

    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="row">
                <ul class="nav navbar-nav">
                    <li><a href="/">HOME</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">MEDIA <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/media?sort_by=id">New Medias</a></li>
                            <li><a href="/media?sort_by=view_time">View Time</a></li>
                            <li><a href="/media?sort_by=views">Views</a></li>
                            <li><a href="/media?sort_by=likes">Likes</a></li>
                            <li><a href="/settings">Settings</a></li>
                        </ul>
                    </li>
                    <li><a href="/tags">TAGS</a></li>
                    <li><a href="/playlists">PLAYLISTS</a></li>
                    <li class="menu_right"><a href="/add">ADD</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="content" class="span12">
        @yield('content')
    </div>


</div>

</body>
</html>
